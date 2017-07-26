                                            
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
requestUrl = "https://int-ecommerce.cartasi.it/" + "ecomm/api/cfpan/rimuovi";

# Parametri per calcolo MAC
apiKey = "<ALIAS>" # Alias fornito da CartaSi
chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>" # Chiave segreta fornita da CartaSi
codiceFiscale = "<CODICE FISCALE>" # Codice fiscale
hashPan = "<PAN>" # Pan della carta
codiceGruppo = "<codiceGruppo>" # Codice gruppo fornito da CartaSi
timeStamp = (int(time.time())) * 1000

# Calcolo MAC
mac_str = 'apiKey=' + apiKey + \
	'codiceFiscale=' + codiceFiscale + \
	'hashPan=' + hashPan + \
    "timeStamp=" + str(timeStamp) + \
     chiaveSegreta
mac =  hashlib.sha1(mac_str.encode('utf8')).hexdigest()

# Parametri di invio
requestParams = {
    'apiKey': apiKey,    
    'codiceFiscale': codiceFiscale,
    'hashPan': hashPan,
    'codiceGruppo': codiceGruppo,
    'timeStamp': str(timeStamp),
    'mac': mac
}

# Chiamata  API
response = requests.post(requestUrl,json=requestParams,headers={'Content-Type':'application/json'})

# Parametri di ritorno
response_data = response.json()   
                
