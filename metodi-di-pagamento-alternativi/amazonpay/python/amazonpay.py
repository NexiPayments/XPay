# -*- coding: utf-8 -*-

# Amazon Pay - Pagamenti integrati

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

requestUrl = "https://int-ecommerce.nexi.it/ecomm/api/paga/amazonpay"

# Alias e chiave segreta
APIKEY = "<ALIAS>" # Sostituire con il valore fornito da Nexi
CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>" # Sostituire con il valore fornito da Nexi

# Parametri per calcolo MAC
codTrans = "TESTAP_" + datetime.datetime.today().strftime('%Y%m%d%H%M%s')
importo = 5000
divisa = "978"
timeStamp = int(time.time()*1000)

# Calcolo MAC
mac_str = 'apiKey=' + str(APIKEY) + 'codiceTransazione=' + str(codTrans)  + 'importo=' + str(importo) + 'divisa=' + str(divisa) + "timeStamp=" + str(timeStamp) + str(CHIAVESEGRETA)
mac =  hashlib.sha1(mac_str.encode('utf8')).hexdigest()

# Parametri dati
dati = {
    'apiKey': APIKEY,
    'codiceTransazione': codTrans,
    'importo': importo,
    'divisa': divisa,
    'amazonpay': {
        'amazonReferenceId': '',
        'accessToken': '',
        'softDecline': '',
        'creaContratto': ''
    },
    'parametriAggiuntivi': {
        'nome': 'Mario',
        'cognome': 'Rossi',
        'mail': "cardHolder@mail.it",
        'descrizione': "descrizione",
        'Note1': "note",
    },
    'timeStamp': str(timeStamp),
    'mac': mac,
}

response = requests.post(requestUrl, json=dati)

dataVerifica = response.json()

if dataVerifica['esito'] == "OK":
    macCalculated_str = 'esito=' + dataVerifica['esito'] + 'idOperazione=' + dataVerifica['idOperazione'] + 'timeStamp=' + dataVerifica['timeStamp'] + CHIAVESEGRETA
    macCalculated =  hashlib.sha1(macCalculated_str.encode('utf8')).hexdigest()
    if macCalculated != dataVerifica['mac']:
        print('Errore MAC: ' + macCalculated + ' non corrisponde a ' + dataVerifica['mac'])
    else:
        print('La transazione ' + codTrans + " è avvenuta con successo; codice autorizzazione: " + dataVerifica['codiceAutorizzazione'])
else:
    print('La transazione ' + codTrans + " è stata rifiutata; descrizione errore: " + dataVerifica['errore']['messaggio'])
                    
                