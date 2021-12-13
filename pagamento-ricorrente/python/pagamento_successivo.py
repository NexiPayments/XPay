                                            
# -*- coding: utf-8 -*-

# Pagamento ricorrente - Pagamento successivo - Chiamata sincrona

import sys
if sys.version_info >= (3,):
    from urllib.parse import urlencode
else:
    from urllib import urlencode
import hashlib
import datetime
import time
import requests

requestUrl = "https://int-ecommerce.nexi.it/ecomm/api/recurring/pagamentoRicorrente";

# Alias e chiave segreta
APIKEY = "<ALIAS>" # Sostituire con il valore fornito da Nexi
CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>" # Sostituire con il valore fornito da Nexi

# Parametri della richiesta
numContratto = "TESTPS_" + datetime.datetime.today().strftime('%Y%m%d%H%M%s')

codTrans = "TESTPS_" + datetime.datetime.today().strftime('%Y%m%d%H%M%s')
importo = "5000"
divisa = "978"
scadenza = '202012'
timeStamp = (int(time.time())) * 1000

# Calcolo MAC
mac_str = 'apiKey=' + APIKEY + \
    'numeroContratto=' + numContratto + \
    'codiceTransazione=' + codTrans + \
    'importo=' + importo + \
    "divisa=" + divisa + \
    "scadenza=" + scadenza + \
    "timeStamp=" + str(timeStamp) + \
     CHIAVESEGRETA
mac =  hashlib.sha1(mac_str.encode('utf8')).hexdigest()

requestParams = {
    'apiKey': APIKEY,
    'numeroContratto': numContratto,
    'codiceTransazione': codTrans,
    'importo': importo,
    'divisa': divisa,
    'scadenza': scadenza,
    'timeStamp': str(timeStamp),
    'mac': mac
}
import json
print(json.dumps(requestParams, sort_keys=True, indent=4, separators=(',', ': ')))

response  = requests.post(requestUrl,json=requestParams,headers={'Content-Type':'application/json'})
try:
    response_data = response.json()

    if response_data['esito'] == "OK": # Transazione andata a buon fine
        # calcolo MAC con i parametri di ritorno
        macResponse = 'esito=' + response_data['esito'] + 'idOperazione=' + response_data['idOperazione'] + 'timeStamp=' + response_data['timeStamp'] + CHIAVESEGRETA
        macCalculated =  hashlib.sha1(macResponse.encode('utf8')).hexdigest()

        if macCalculated != response_data['mac']:
            raise ValueError('Errore MAC: ' + macCalculated + ' non corrisponde a ' + response_data['mac'])

        print('La transazione ' + codTrans + " è avvenuta con successo; codice autorizzazione: " + response_data['codiceAutorizzazione'])
    else: # Transazione rifiutata
        print('La transazione ' + codTrans + " è stata rifiutata; descrizione errore: " + response_data['errore']['messaggio'])

except Exception as e:
    print(response)
    print(response.content)
                    
                