                                            
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

# URL + URI
requestUrl = "https://int-ecommerce.nexi.it/" + "ecomm/api/bo/richiestaPayMail";

# Parametri per calcolo MAC
apiKey = "<ALIAS>" # Alias fornito da Nexi
codTrans = "APIBO_" + datetime.datetime.today().strftime('%Y%m%d%H%M%S')
importo = "5000" # 5000 = 50,00 EURO (indicare la cifra in centesimi)
timeout = "4" # Durata in ore del link di pagamento che verrà generato 
url = "https://my.server/esito.py" # URL dove viene rimandato il cliente al termine del pagamento
timeStamp = (int(time.time())) * 1000
chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>" # Chiave segreta fornita da Nexi

# Calcolo MAC
mac_str = 'apiKey=' + apiKey + \
    'codiceTransazione=' + codTrans + \
    'importo=' + importo + \
    "timeStamp=" + str(timeStamp) + \
     chiaveSegreta
mac =  hashlib.sha1(mac_str.encode('utf8')).hexdigest()

# Parametri di invio
requestParams = {
    'apiKey': apiKey,
    'codiceTransazione': codTrans,
    'importo': importo,
    'timeout': timeout,
    'url': url,
    'timeStamp': str(timeStamp),
    'mac': mac
}
import json

# Chiamata API
response  = requests.post(requestUrl,json=requestParams,headers={'Content-Type':'application/json'})

# Parametri di ritorno
response_data = response.json()
