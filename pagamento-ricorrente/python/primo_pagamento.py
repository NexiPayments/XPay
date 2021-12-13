                                            
# -*- coding: utf-8 -*-

# Pagamento ricorrente - Primo pagamento - Avvio pagamento

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

# Calcolo MAC
mac_str = 'codTrans=' + str(codTrans) + 'divisa=' + str(divisa) + 'importo=' + str(importo) + str(CHIAVESEGRETA)
mac =  hashlib.sha1(mac_str.encode('utf8')).hexdigest()

# Viene creato il numero contratto e viene richiesto alla pagina di cassa un nuovo pagamento
numContratto = "NC_TEST_" + datetime.datetime.today().strftime('%Y%m%d%H%M%s')
tipoRichiesta = 'PP';

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
    'tipo_servizio': 'paga_multi',
    'tipo_richiesta': tipoRichiesta
}

# Parametri facoltativi
facoltativi = {
}

# Creare un form html con metodo post verso requestUrl con campi hidden contenenti requestParams
                    
                