                                            
# -*- coding: utf-8 -*-

# Pagamento OneClik - Pagamenti successivi - Tramite redirezione - Avvio pagamento

import sys
if sys.version_info >= (3,):
    from urllib.parse import urlencode
else:
    from urllib import urlencode
import hashlib
import datetime
import time
import requests

# Script Pagamenti successivi - tramite redirezione (.py)
requestUrl = "https://int-ecommerce.nexi.it/" + "ecomm/ecomm/DispatcherServlet";
merchantServerUrl = "http://localhost/fileesito.py"

# PARAMETRI PER CALCOLO MAC
alias = "<ALIAS>" # Alias fornito da Nexi
codTrans = "23cfsc23" # Codice Transazione
importo = "5000" # 5000 = 50,00 Euro (espresso in centesimi)
divisa = "978" # 978 = EUR
url = "esito.py" # Url di redirect in caso di esito positivo
url_back = "annullo.py" # Url di redirect in caso di esito negativo
urlpost = "notifica.py" # Url di notifica server
numeroContratto = "" # Numero del contratto
tipo_servizio = "paga_oc3d"
tipo_richiesta = "PR"
gruppo = "<CODICE GRUPPO>" # CodiceGruppo fornito da Nexi
timeStamp = (int(time.time())) * 1000
chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>" # Chiave segreta fornita da Nexi

# CALCOLO MAC 
mac_str = 'codTrans=' + codTrans + \
    "divisa=" + divisa + \
    'importo=' + importo + \
     chiaveSegreta
mac =  hashlib.sha1(mac_str.encode('utf8')).hexdigest()

# PARAMETRI DI INVIO
requestParams = {
    'alias': alias,
    'importo': importo,
    'divisa': divisa,
    'codTrans': codTrans,
    'url': url,
    'url_back': url_back,
    'num_contratto': num_contratto,
    'tipo_servizio': tipo_servizio,
    'tipo_richiesta': tipo_richiesta,
    'timeStamp': str(timeStamp),
    'mac': mac
}

# Parametri facoltativi
facoltativi = [
]

# Creare un form html con metodo post verso requestUrl con campi hidden contenenti requestParams
                    
                