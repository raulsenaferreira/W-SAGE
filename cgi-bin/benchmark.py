from joblib import Parallel, delayed  
import multiprocessing
import numpy as np
from timeit import Timer
import psycopg2
import sys
import numpy as np
import scipy.stats                  # Para o kernel density estimation
import cgi, cgitb 
cgitb.enable()  # debug

#the cgi library gets vars from html
data = cgi.FieldStorage()

latitude = []
longitude = []
arrayPDF = []

dataFromPHP = ""
try:
    dataFromPHP = "SELECT ST_X(geom), ST_Y(geom) from alunos_rural WHERE latitude != 0;"
except:
    print "ERROR"
    sys.exit(1)

# Connect to an existing database
try:
	conn = psycopg2.connect("host='107.170.124.51' dbname='tccdb_cloud' user='postgres' password='raul$0128$raul'")
except:
	print "Nao conectou!"
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

values = np.vstack([m1, m2])
kernel = scipy.stats.kde.gaussian_kde(values)

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
	return Parallel(n_jobs=numThreads, backend="threading")(delayed(recuperaArrayPDFParalelo)(j) for j in limite)

#print len(values[0])
#results = paralelo()
#print results
#sequencial
#t = Timer(lambda: recuperaArrayPDF(kernel, values))

#print t.timeit(number=1)

#paralelo
t = Timer(lambda: paralelo())

print t.timeit(number=1)
