# -*- coding: utf-8 -*-

# Report - Elenco

import sys
if sys.version_info >= (3,):
    from urllib.parse import urlencode
else:
    from urllib import urlencode
import hashlib
import datetime
import time
import requests

# URL + URI
requestUrl = "https://int-ecommerce.nexi.it/" + "ecomm/api/bo/elencoReport";

# Parametri per calcolo MAC
apiKey = "<ALIAS>" # Alias fornito da Nexi
timeStamp = (int(time.time())) * 1000
chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>" # Chiave segreta fornita da Nexi

# Calcolo MAC
mac_str = 'apiKey=' + apiKey + \
    "timeStamp=" + str(timeStamp) + \
     chiaveSegreta
mac =  hashlib.sha1(mac_str.encode('utf8')).hexdigest()

# Parametri di invio
requestParams = {
    'apiKey': apiKey,
    'refDate': datetime.datetime.today().strftime('%d/%m/%Y'),
    'timeStamp': str(timeStamp),
    'mac': mac
}
import json

# Chiamata API
response  = requests.post(requestUrl,json=requestParams,headers={'Content-Type':'application/json'})

# Parametri di ritorno
response_data = response.json()

