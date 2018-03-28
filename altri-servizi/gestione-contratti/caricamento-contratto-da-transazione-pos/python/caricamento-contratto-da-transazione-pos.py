                                            
# -*- coding: utf-8 -*-
import sys
if sys.version_info >= (3,):
    from urllib.parse import urlencode
else:
    from urllib import urlencode
import hashlib
import datetime
import time
import requests
import json

# URL + URI
requestUrl = "https://int-ecommerce.nexi.it/" + "ecomm/api/contratti/creazioneDaPosFisico";

# Parametri per calcolo MAC
apiKey = "<ALIAS>" # Alias fornito da Nexi
chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>" # Chiave segreta fornita da Nexi
timeStamp = (int(time.time())) * 1000
numeroContratto = "scf23scf23" # Numero del contratto
idPOSFisico = "<ID>" # ID del POS fisico
codiceAutorizzazione = "<CODICE_AUTORIZZAZIONE>" # Codice dia autorizzazione della transazione
importo = "5000" # 5000 = 50,00 EURO (indicare la cifra in centesimi)
stan = ""
descrizione = ""
mail = ""

# Calcolo MAC
mac_str = 'apiKey=' + apiKey + \
	'numeroContratto=' + numeroContratto + \
	'idPOSFisico=' + idPOSFisico + \
	'codiceAutorizzazione=' + codiceAutorizzazione + \
	'stan=' + stan + \
	'importo=' + importo + \
	'descrizione=' + descrizione + \
	'mail=' + mail + \
    "timeStamp=" + str(timeStamp) + \
     chiaveSegreta
mac =  hashlib.sha1(mac_str.encode('utf8')).hexdigest()

contratto = {
	'numeroContratto': numeroContratto,
	'idPOSFisico': idPOSFisico,
	'importo': importo
}

# Parametri di invio
requestParams = {
    'apiKey': apiKey,
    'contratto': contratto,
    'timeStamp': str(timeStamp),
    'mac': mac
}

# Chiamata  API
response = requests.post(requestUrl,json=requestParams,headers={'Content-Type':'application/json'})

# Parametri di ritorno
response_data = response.json() 
 
                
