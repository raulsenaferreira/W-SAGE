import psycopg2
import sys, json
import requests
import simplejson, urllib           # trabalhar com json e webservices
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
    dataFromPHP = sys.argv[1]
except:
    print "ERROR"
    sys.exit(1)

# Connect to an existing database
try:
	conn = psycopg2.connect("host='host' dbname='banco' user='usuario' password='senha'")
except:
	print "Nao conectou!"
# Open a cursor to perform database operations
cur = conn.cursor()

# Query the database and obtain data as Python objects
#cur.execute("SELECT ST_X(geom), ST_Y(geom) from alunos_rural where situacao != %s and latitude != 0;",(dataFromPHP))
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

#X, Y = np.mgrid[-180:180:100j, -90:90:100j]

#positions = np.vstack([X.ravel(), Y.ravel()])
values = np.vstack([m1, m2])
kernel = scipy.stats.kde.gaussian_kde(values)

def recuperaArrayPDF(kernel, values):
	for j in range(len(values[0])):
		ind = np.vstack([values[0][j], values[1][j]])
		kdepdf = kernel.evaluate(ind)
		arrayPDF.append(kdepdf * 500) #grandeza do pdf, equivale ao parametro do matlab, se la vale 3 entao aqui vale 3000
	return arrayPDF

def json_list(list):
    lst = []
    for pn in list:
        d = {}
        d=pn[0]
        lst.append(round(d,2))
    return lst

print json_list(recuperaArrayPDF(kernel, values))