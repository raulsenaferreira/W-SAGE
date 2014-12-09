from __future__ import division, print_function, absolute_import

# Standard library imports.
import warnings

# Scipy imports.
from scipy.lib.six import callable, string_types
from scipy import linalg, special

from numpy import atleast_2d, reshape, zeros, newaxis, dot, exp, pi, sqrt, \
     ravel, power, atleast_1d, squeeze, sum, transpose
import numpy as np
from numpy.random import randint, multivariate_normal

# Local imports.
from . import mvn
#meus imports
from joblib import Parallel, delayed  
import multiprocessing

from timeit import Timer
import psycopg2
import sys

import scipy.stats                  # Para o kernel density estimation
import cgi, cgitb 
cgitb.enable()  # debug

#the cgi library gets vars from html
data = cgi.FieldStorage()

latitude = []
longitude = []
arrayPDF = []



dataFromPHP = "SELECT ST_X(geom), ST_Y(geom) from alunos_rural WHERE latitude != 0;"


# Connect to an existing database

conn = psycopg2.connect("host='107.170.124.51' dbname='tccdb_cloud' user='postgres' password='raul$0128$raul'")

# Open a cursor to perform database operations
cur = conn.cursor()

cur.execute(dataFromPHP)
linhas = cur.fetchall()

# Close communication with the database
cur.close()
conn.close()

def pegaLatLon(linhas):
	for linha in linhas:
		latitude.append( float(linha[0]) )
		longitude.append( float(linha[1]) )
	return latitude, longitude

m1, m2 = pegaLatLon(linhas)

__all__ = ['gaussian_kde']


class gaussian_kde(object):
    
    def __init__(self, dataset, bw_method=None):
        self.dataset = atleast_2d(dataset)
        if not self.dataset.size > 1:
            raise ValueError("`dataset` input should have multiple elements.")

        self.d, self.n = self.dataset.shape
        self.set_bandwidth(bw_method=bw_method)

    def evaluate(self, points):
        
        points = atleast_2d(points)

        d, m = points.shape
        if d != self.d:
            if d == 1 and m == self.d:
                # points was passed in as a row vector
                points = reshape(points, (self.d, 1))
                m = 1
            else:
                msg = "points have dimension %s, dataset has dimension %s" % (d,
                    self.d)
                raise ValueError(msg)

        result = zeros((m,), dtype=np.float)

        if m >= self.n:
            # there are more points than data, so loop over data
            for i in range(self.n):
                diff = self.dataset[:, i, newaxis] - points
                tdiff = dot(self.inv_cov, diff)
                energy = sum(diff*tdiff,axis=0) / 2.0
                result = result + exp(-energy)
        else:
            # loop over points
            for i in range(m):
                diff = self.dataset - points[:, i, newaxis]
                tdiff = dot(self.inv_cov, diff)
                energy = sum(diff * tdiff, axis=0) / 2.0
                result[i] = sum(exp(-energy), axis=0)

        result = result / self._norm_factor

        return result

    __call__ = evaluate

    def integrate_gaussian(self, mean, cov):
       
        mean = atleast_1d(squeeze(mean))
        cov = atleast_2d(cov)

        if mean.shape != (self.d,):
            raise ValueError("mean does not have dimension %s" % self.d)
        if cov.shape != (self.d, self.d):
            raise ValueError("covariance does not have dimension %s" % self.d)

        # make mean a column vector
        mean = mean[:, newaxis]

        sum_cov = self.covariance + cov

        diff = self.dataset - mean
        tdiff = dot(linalg.inv(sum_cov), diff)

        energies = sum(diff * tdiff, axis=0) / 2.0
        result = sum(exp(-energies), axis=0) / sqrt(linalg.det(2 * pi *
                                                        sum_cov)) / self.n

        return result

    def integrate_box_1d(self, low, high):
        
        if self.d != 1:
            raise ValueError("integrate_box_1d() only handles 1D pdfs")

        stdev = ravel(sqrt(self.covariance))[0]

        normalized_low = ravel((low - self.dataset) / stdev)
        normalized_high = ravel((high - self.dataset) / stdev)

        value = np.mean(special.ndtr(normalized_high) -
                        special.ndtr(normalized_low))
        return value

    def integrate_box(self, low_bounds, high_bounds, maxpts=None):
        
        if maxpts is not None:
            extra_kwds = {'maxpts': maxpts}
        else:
            extra_kwds = {}

        value, inform = mvn.mvnun(low_bounds, high_bounds, self.dataset,
                                  self.covariance, **extra_kwds)
        if inform:
            msg = ('An integral in mvn.mvnun requires more points than %s' %
                   (self.d * 1000))
            warnings.warn(msg)

        return value

    def integrate_kde(self, other):
        
        if other.d != self.d:
            raise ValueError("KDEs are not the same dimensionality")

        # we want to iterate over the smallest number of points
        if other.n < self.n:
            small = other
            large = self
        else:
            small = self
            large = other

        sum_cov = small.covariance + large.covariance
        result = 0.0
        for i in range(small.n):
            mean = small.dataset[:, i, newaxis]
            diff = large.dataset - mean
            tdiff = dot(linalg.inv(sum_cov), diff)

            energies = sum(diff * tdiff, axis=0) / 2.0
            result += sum(exp(-energies), axis=0)

        result /= sqrt(linalg.det(2 * pi * sum_cov)) * large.n * small.n

        return result

    def resample(self, size=None):
        
        if size is None:
            size = self.n

        norm = transpose(multivariate_normal(zeros((self.d,), float),
                         self.covariance, size=size))
        indices = randint(0, self.n, size=size)
        means = self.dataset[:, indices]

        return means + norm

    def scotts_factor(self):
        return power(self.n, -1./(self.d+4))

    def silverman_factor(self):
        return power(self.n*(self.d+2.0)/4.0, -1./(self.d+4))

    #  Default method to calculate bandwidth, can be overwritten by subclass
    covariance_factor = scotts_factor

    def set_bandwidth(self, bw_method=None):
        
        if bw_method is None:
            pass
        elif bw_method == 'scott':
            self.covariance_factor = self.scotts_factor
        elif bw_method == 'silverman':
            self.covariance_factor = self.silverman_factor
        elif np.isscalar(bw_method) and not isinstance(bw_method, string_types):
            self._bw_method = 'use constant'
            self.covariance_factor = lambda: bw_method
        elif callable(bw_method):
            self._bw_method = bw_method
            self.covariance_factor = lambda: self._bw_method(self)
        else:
            msg = "`bw_method` should be 'scott', 'silverman', a scalar " \
                  "or a callable."
            raise ValueError(msg)

        self._compute_covariance()

    def _compute_covariance(self):
        """Computes the covariance matrix for each Gaussian kernel using
        covariance_factor().
        """
        self.factor = self.covariance_factor()
        # Cache covariance and inverse covariance of the data
        if not hasattr(self, '_data_inv_cov'):
            self._data_covariance = atleast_2d(np.cov(self.dataset, rowvar=1,
                                               bias=False))
            self._data_inv_cov = linalg.inv(self._data_covariance)

        self.covariance = self._data_covariance * self.factor**2
        self.inv_cov = self._data_inv_cov / self.factor**2
        self._norm_factor = sqrt(linalg.det(2*pi*self.covariance)) * self.n


values = np.vstack([m1, m2])
kernel = gaussian_kde(values)

limite = range(len(values[0]))
tam = len(values[0])

if tam < 1000:
	numThreads = 1
else:
	numThreads = int(len(values[0])/1000)

def recuperaArrayPDFParalelo(j):
	#for j in range(len(values[0])):
	ind = np.vstack([values[0][j], values[1][j]])
	kdepdf = kernel.evaluate(ind)
	#arrayPDF.append(kdepdf * 500) #grandeza do pdf, equivale ao parametro do matlab, se la vale 3 entao aqui vale 3000
	return kdepdf * 500

def json_list(list):
    lst = []
    for pn in list:
        d = {}
        d=pn[0]
        lst.append(round(d,2))
    return lst

def recuperaArrayPDF(kernel, values):
	for j in range(len(values[0])):
		ind = np.vstack([values[0][j], values[1][j]])
		kdepdf = kernel.evaluate(ind)
		arrayPDF.append(kdepdf * 500) #grandeza do pdf, equivale ao parametro do matlab, se la vale 3 entao aqui vale 3000
	return arrayPDF

def paralelo():
	return Parallel(n_jobs=numThreads)(delayed(recuperaArrayPDFParalelo)(j) for j in limite)

#print len(values[0])
#results = paralelo()
#print results
#sequencial

