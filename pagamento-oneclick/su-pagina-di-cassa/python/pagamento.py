                                            
# -*- coding: utf-8 -*-

#  Pagamento OneClik - Su pagina di cassa - Avvio pagamento

import sys
if sys.version_info >= (3,):
    from urllib.parse import urlencode
else:
    from urllib import urlencode
import hashlib
import datetime

HTTP_HOST = "my-server.example.tdl"

requestUrl = "https://int-ecommerce.nexi.it/ecomm/ecomm/DispatcherServlet"
merchantServerUrl = "https://" + HTTP_HOST + "/xpay/pagamento_semplice_python/one_click/"

# Alias e chiave segreta
ALIAS = "<ALIAS>" # Sostituire con il valore fornito da Nexi
CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>" # Sostituire con il valore fornito da Nexi

codTrans = "TESTPS_" + datetime.datetime.today().strftime('%Y%m%d%H%M%s')
divisa = "EUR"
importo = 5000

numContratto = "NC_TEST_" + datetime.datetime.today().strftime('%Y%m%d%H%M%s')
gruppo = "<GRUPPO>" # Sostituire con il valore fornito da Nexi

# Calcolo MAC
mac_str = 'codTrans=' + str(codTrans) + 'divisa=' + str(divisa) + 'importo=' + str(importo) + "gruppo=" + str(gruppo) + "num_contratto=" + str(numContratto) + str(CHIAVESEGRETA)
mac =  hashlib.sha1(mac_str.encode('utf8')).hexdigest()

# Parametri obbligatori
obbligatori = {
    'alias': ALIAS,
    'importo': importo,
    'divisa': divisa,
    'codTrans': codTrans,
    'url': merchantServerUrl + "esito.py",
    'url_back': merchantServerUrl + "annullo.py",
    'mac': mac,
    'num_contratto': numContratto,
    'tipo_servizio': 'paga_1click',
}

# Parametri facoltativi
facoltativi = { 
    }
 

# Creare un form html con metodo post verso requestUrl con campi hidden contenenti requestParams
                    
                