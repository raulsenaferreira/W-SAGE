from joblib import Parallel, delayed  
import multiprocessing
import psycopg2
import sys
import numpy as np
import scipy.stats  # Para o kernel density estimation
import cgi, cgitb 
cgitb.enable()  # debug

data = cgi.FieldStorage()

latitude = []
longitude = []
arrayPDF = []

dataFromPHP = ""
try:
    dataFromPHP = sys.argv[1]
except:
    print "ERROR"
    sys.exit(1)

#substitua com os dados do seu banco
try:
	conn = psycopg2.connect("host='host'  dbname='banco'  user='usuario'  password='senha'")
except:
	print "Nao conectou!"

#database operations
cur = conn.cursor()

cur.execute(dataFromPHP)
linhas = cur.fetchall()

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
	ind = np.vstack([values[0][j], values[1][j]])
	kdepdf = kernel.evaluate(ind)
	return kdepdf * 500

def paralelo():
	return Parallel(n_jobs=numThreads, backend="threading")(delayed(recuperaArrayPDFParalelo)(j) for j in limite)

def json_list(list):
    lst = []
    for pn in list:
        d = {}
        d=pn[0]
        lst.append(round(d,2))
    return lst

resultado = paralelo()

print json_list(resultado)