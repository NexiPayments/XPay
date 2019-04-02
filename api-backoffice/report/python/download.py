# -*- coding: utf-8 -*-

# Report - Download

import sys
if sys.version_info >= (3,):
    from urllib.parse import urlencode
else:
    from urllib import urlencode
import hashlib
import datetime

requestUrl = "https://int-ecommerce.nexi.it/ecomm/ecomm/DispatcherServlet"

# Alias e chiave segreta
ALIAS = "<ALIAS>" # Sostituire con il valore fornito da Nexi
CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>" # Sostituire con il valore fornito da Nexi

timeStamp = (int(time.time())) * 1000
idReport = "123"

# Calcolo MAC
mac_str = 'apiKey=' + str(ALIAS) + 'timeStamp=' + str(timeStamp) + 'idReport=' + str(idReport) + str(CHIAVESEGRETA)
mac =  hashlib.sha1(mac_str.encode('utf8')).hexdigest()

# Parametri dati
requestParams = {
    'apiKey': ALIAS,
    'idReport': idReport,
    'timeStamp': timeStamp,
    'mac': mac,
}

redirectUrl = requestUrl + "?" + urlencode(requestParams)

print(redirectUrl)
