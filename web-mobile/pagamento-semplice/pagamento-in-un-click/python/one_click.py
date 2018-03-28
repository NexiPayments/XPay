# -*- coding: utf-8 -*-
import sys
if sys.version_info >= (3,):
    from urllib.parse import urlencode
else:
    from urllib import urlencode
import hashlib
import datetime

HTTP_HOST = "my-server.example.tdl"
session_id = "12345"

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

param_from_request = {}

# Se nella query string compare il parametro numContratto allora viene preso come valido e viene richiesto alla pagina di cassa un pagamento ricorrente
# altrimenti viene creato il numero contratto e viene richiesto alla pagina di cassa un nuovo pagamento
if 'numContratto' in param_from_request:
    numContratto = param_from_request['numContratto']
    tipoRichiesta = 'PR'
else:
    numContratto = "NC_TEST_" + datetime.datetime.today().strftime('%Y%m%d%H%M%s')
    tipoRichiesta = 'PP'

# Parametri obbligatori
obbligatori = [
    ('alias', ALIAS),
    ('importo', importo),
    ('divisa', divisa),
    ('codTrans', codTrans),
    ('url', merchantServerUrl + "esito.py"),
    ('url_back', merchantServerUrl + "annullo.py"),
    ('mac', mac),
    ('urlpost', merchantServerUrl + "notifica.py"),
    ('num_contratto', numContratto),
    ('tipo_servizio', 'paga_multi'),
    ('tipo_richiesta', tipoRichiesta),
    ('gruppo', 'GRUPPOTEST')
]

# Parametri facoltativi
facoltativi = [
    ('mail', "mail@cliente.it"),
    ('languageId', "ITA"),
    ('descrizione', "Prova di pagamento"),
    ('session_id', session_id),
    ('Note1', "NOTA 1"),
    ('Note2', "NOTA 2"),
    ('Note3', "NOTA 3"),
    ('OPTION_CF', "RSSMRA74D22A001Q"),
    ('selectedcard', "VISA"),
    ('TCONTAB', "D"),
    ('infoc', "Info su pagamento per compagnia"),
    ('infob', "Info su pagamento per banca"),
    ('modo_gestione_consegna', "completo")
]

requestParams = obbligatori + facoltativi

redirectUrl = requestUrl + "?" + urlencode(requestParams)

print(redirectUrl)
