# -*- coding: utf-8 -*-
import sys
if sys.version_info >= (3,):
    from urllib.parse import urlencode
else:
    from urllib import urlencode
import hashlib
import datetime

param_from_request = {
"codTrans":"",
"esito":"OK",
"importo":"",
"divisa":"",
"data":"",
"orario":"",
"codAut":"",
"mac":"58815356e9052f7d6a355a399d1d8edfc4a58bd7"
}

# Chiave segreta
CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>" # Sostituire con il valore fornito da CartaSi

# Controllo che ci siano tutti i parametri di ritorno obbligatori per calcolare il MAC
requiredParams = ['codTrans', 'esito', 'importo', 'divisa', 'data', 'orario', 'codAut', 'mac']
for param in requiredParams:
    if param not in param_from_request:
        raise ValueError("Parametro {} mancante".format(param))

# Calcolo MAC con i parametri di ritorno
mac_str = 'codTrans=' + param_from_request['codTrans'] + \
        'esito=' + param_from_request['esito'] + \
        'importo=' + param_from_request['importo'] + \
        'divisa=' + param_from_request['divisa'] + \
        'data=' + param_from_request['data'] + \
        'orario=' + param_from_request['orario'] + \
        'codAut=' + param_from_request['codAut'] + \
        CHIAVESEGRETA
macCalculated =  hashlib.sha1(mac_str.encode('utf8')).hexdigest()

# Verifico corrispondeza tra MAC calcolato e parametro mac di ritorno
macCalculated != param_from_request['mac']:
    raise ValueError('Errore MAC: ' + macCalculated +' non corrisponde a ' + param_from_request['mac'])

# Nel caso in cui non ci siano errori gestisco il parametro esito
if param_from_request['esito'] == 'OK':
    print('La transazione ' + param_from_request['codTrans'] + " è avvenuta con successo; codice autorizzazione: " + param_from_request['codAut'])
    print('dccRate = ' + param_from_request['dccRate'])
    print('dccAmount = ' + param_from_request['dccAmount'])
    print('dccCurrency = ' + param_from_request['dccCurrency'])
    print('dccState = ' + param_from_request['dccState'])
else:
    print('La transazione ' + param_from_request['codTrans'] + " è stata rifiutata")
    if 'messaggio' in param_from_request:
        print("descrizione errore: " + param_from_request['messaggio'])



