                                            
# -*- coding: utf-8 -*-

# Pagamento ricorrente/Pagamento in un click - Pagamento successivo SSL

import sys
if sys.version_info >= (3,):
    from urllib.parse import urlencode
else:
    from urllib import urlencode
import hashlib
import datetime
import time
import requests

HTTP_HOST = "my-server.example.tdl"

requestUrl = "https://int-ecommerce.nexi.it/ecomm/api/paga/pagamentoRicorrente";

# Alias e chiave segreta
APIKEY = "<ALIAS>" # Sostituire con il valore fornito da Nexi
CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>" # Sostituire con il valore fornito da Nexi



codTrans = "TESTAP_" + datetime.datetime.today().strftime('%Y%m%d%H%M%s')
importo = "5000"; # 5000 = 50,00 EURO (indicare la cifra in centesimi)
divisa = "978"; # divisa 978 indica EUR
scadenza = '203012'; # Scadenza della carta (Formato aaaamm)
timeStamp = int(time.time()*1000)

# Calcolo MAC
mac_str = 'apiKey=' + apiKey + 'numeroContratto=' + numContratto + 'codiceTransazione=' + codTrans + 'importo=' + importo + "divisa=" + divisa + "scadenza=" + scadenza + "timeStamp=" + timeStamp + chiaveSegreta
mac =  hashlib.sha1(mac_str.encode('utf8')).hexdigest()

dati = {
    'apiKey': apiKey,
    'numeroContratto': numContratto,
    'codiceTransazione': codTrans,
    'importo': importo,
    'divisa': divisa,
    'timeStamp':  timeStamp,
    'mac': mac,
}

response = requests.post(requestUrl, json=dati)

dataVerifica = response.json()

if dataVerifica['esito'] == "OK":
    macCalculated_str = 'esito=' + dataVerifica['esito'] + 'idOperazione=' + dataVerifica['idOperazione'] . 'timeStamp=' + dataVerifica['timeStamp'] + CHIAVESEGRETA;
    macCalculated =  hashlib.sha1(macCalculated_str.encode('utf8')).hexdigest()
    if macCalculated != dataVerifica['mac']:
        print('Errore MAC: ' + macCalculated + ' non corrisponde a ' + dataVerifica['mac'])
    else:
        print('La transazione ' + codTrans + " è avvenuta con successo; codice autorizzazione: " + dataVerifica['codiceAutorizzazione'])
else:
    print('La transazione ' + codTrans + " è stata rifiutata; descrizione errore: " + dataVerifica['errore']['messaggio'])
                    
                