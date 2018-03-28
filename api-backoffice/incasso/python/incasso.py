                                            
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
requestUrl = "https://int-ecommerce.nexi.it/" + "ecomm/api/bo/contabilizza";

# Parametri per calcolo MAC
apiKey = "<ALIAS>" # Alias fornito da Nexi
chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>" # Chiave segreta fornita da Nexi
codTrans = "dib30v789b078" # Codice della transazione da incassare 
importo = "5000" # 5000 = 50,00 EURO (indicare la cifra in centesimi)
divisa = "978" # divisa 978 indica EUR
timeStamp = (int(time.time())) * 1000

# Calcolo MAC
mac_str = 'apiKey=' + apiKey + \
    'codiceTransazione=' + codTrans + \
    "divisa=" + divisa + \
    'importo=' + importo + \
    "timeStamp=" + str(timeStamp) + \
     chiaveSegreta
mac =  hashlib.sha1(mac_str.encode('utf8')).hexdigest()

# Parametri di invio
requestParams = {
    'apiKey': apiKey,
    'codiceTransazione': codTrans,
    'importo': importo,
    'divisa': divisa,
    'timeStamp': str(timeStamp),
    'mac': mac
}

# Chiamata API
response  = requests.post(requestUrl,json=requestParams,headers={'Content-Type':'application/json'})

# Parametri di ritorno
response_data = response.json()
