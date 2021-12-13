                                            
# -*- coding: utf-8 -*-

# Elenco Ordini

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
requestUrl = "https://int-ecommerce.nexi.it/" + "ecomm/api/bo/reportOrdini";

# Parametri per calcolo MAC
apiKey = "<ALIAS>" # Alias fornito da Nexi
chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>" # Chiave segreta fornita da Nexi
codTrans = "<CODICE TRANSAZIONE>" # Vuoto per tutte le transazioni altrimenti cerca la transazione inserita
periodo = "01/01/2017 - 31/12/2017" # gg/mm/aaaa - gg/mm/aaaa 
canale = "All" # All | MyBank | CartaCredito | PayPal
stato = ["Autorizzato"] # Transazioni autorizzate
timeStamp = (int(time.time())) * 1000

# Calcolo MAC
mac_str = 'apiKey=' + apiKey + \
    "codiceTransazione=" + codTrans + \
    "periodo=" + periodo + \
    "canale=" + canale + \
    "timeStamp=" + str(timeStamp) + \
     chiaveSegreta
mac =  hashlib.sha1(mac_str.encode('utf8')).hexdigest()

# Parametri di invio
requestParams = {
    'apiKey': apiKey,
    'codiceTransazione': codTrans,
    'periodo': periodo,
    'canale': canale,
    'stato': stato,
    'timeStamp': str(timeStamp),
    'mac': mac
}
# Chiamata API
response  = requests.post(requestUrl,json=requestParams,headers={'Content-Type':'application/json'})

# Parametri di ritorno
response_data = response.json()
                    
                