                                            
# -*- coding: utf-8 -*-

# Pagamento OneClik - Pagamenti successivi - Tramite redirezione - Esito

import sys
if sys.version_info >= (3,):
    from urllib.parse import urlencode
else:
    from urllib import urlencode
import hashlib
import datetime
import time
import requests

# Script Pagamenti Successivi - Esito (Tramite Redirezione) (.py)
requestUrl = "https://int-ecommerce.nexi.it/" + "ecomm/api/bo/contabilizza";

# PARAMETRI PER CALCOLO MAC
apiKey = "<ALIAS>" # Alias fornito da Nexi
codTrans = "NOW_20170706104334"
importo = "5000" # 5000 = 50,00 EURO (indicare la cifra in centesimi)
divisa = "978" # 978 = EUR
timeStamp = (int(time.time())) * 1000
chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>" # Chiave segreta fornita da Nexi

# CALCOLO MAC
mac_str = 'apiKey=' + apiKey + \
    'codiceTransazione=' + codTrans + \
    "divisa=" + divisa + \
    'importo=' + importo + \
    "timeStamp=" + str(timeStamp) + \
     chiaveSegreta
mac =  hashlib.sha1(mac_str.encode('utf8')).hexdigest()

# PARAMETRI DI INVIO
requestParams = {
    'apiKey': apiKey,
    'codiceTransazione': codTrans,
    'importo': importo,
    'divisa': divisa,
    'timeStamp': str(timeStamp),
    'mac': mac
}
import json

# CHIAMATA API
response = requests.post(requestUrl,json=requestParams,headers={'Content-Type':'application/json'})

# PARAMETRI DI RITORNO
response_data = response.json()
                    
                