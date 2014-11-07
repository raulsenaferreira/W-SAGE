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

"""
resp = requests.get('http://dadosabertos.rio.rj.gov.br/apiTransporte/apresentacao/rest/index.cfm/obterPosicoesDaLinha/474')
#print resp.json()['DATA']
coordsJson = np.asarray( resp.json()['DATA'] )


def pegaLatLon(coordsJson):
	for i in range(len(coordsJson)):
		latitude.append( float(unicode(coordsJson[i][3])) )
		longitude.append( float(unicode(coordsJson[i][4])) )
	return latitude, longitude
	
m1, m2 = pegaLatLon(coordsJson)
#print coords
"""
#print dataFromPHP
dataFromPHP = json.loads(dataFromPHP)

#print dataFromPHP[0]['st_x']
#print dataFromPHP[0]['st_y']

def pegaLatLon(dataFromPHP):
	for i in range(len(dataFromPHP)):
		#print dataFromPHP[i]
		latitude.append( float(dataFromPHP[i]['st_x']) )
		longitude.append( float(dataFromPHP[i]['st_y']) )
	return latitude, longitude

m1, m2 = pegaLatLon(dataFromPHP)

X, Y = np.mgrid[-180:180:100j, -90:90:100j]

values = np.vstack([X.ravel(), Y.ravel()]) #positions
#values = np.vstack([m1, m2])
kernel = scipy.stats.kde.gaussian_kde(values)

def recuperaArrayPDF(kernel, values):
	for j in range(len(values[0])):
		ind = np.vstack([values[0][j], values[1][j]])
		kdepdf = kernel.evaluate(ind)
		arrayPDF.append(kdepdf) #grandeza do pdf, equivale ao parametro do matlab, se la vale 3 entao aqui vale 3000
	return arrayPDF

arrayPDF = recuperaArrayPDF(kernel, values)
jsonResult = []
jsonResult = np.asarray(arrayPDF)
#print jsonResult

def json_list(list):
    lst = []
    for pn in list:
        d = {}
        d['pdf']=pn[0]
        lst.append(d)
    return lst

print json_list(recuperaArrayPDF(kernel, values))

#print len(arrayPDF)
#print m1[0]
#print m2[0]
