# -*- coding: utf-8 -*-

# Stato contratti

import sys
if sys.version_info >= (3,):
    from urllib.parse import urlencode
else:
    from urllib import urlencode
import hashlib
import datetime
import time
import requests
import json

# URL + URI
requestUrl = "https://int-ecommerce.nexi.it/" + "ecomm/api/contratti/statoContratti";

# Parametri per calcolo MAC
apiKey = "<ALIAS>" # Alias fornito da Nexi
chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>" # Chiave segreta fornita da Nexi
numeroContratto = "" # Numero del contratto (vuoto per elencarli tutti)
codiceFiscale = "" # vuoto per elencarli tutti
dataRegistrazioneDa = "00/00/0000 00:00:00" # formato gg/mm/aaaa hh/mm/ss (giorno/mese/anno ora:minuti:secondi)
dataRegistrazioneA = "00/00/0000 00:00:00" # formato gg/mm/aaaa hh/mm/ss (giorno/mese/anno ora:minuti:secondi)
dataAggiornamentoDa = "00/00/0000 00:00:00" # formato gg/mm/aaaa hh/mm/ss (giorno/mese/anno ora:minuti:secondi)
dataAggiornamentoA = "00/00/0000 00:00:00" # formato gg/mm/aaaa hh/mm/ss (giorno/mese/anno ora:minuti:secondi)
statoAggiornamento = ""
timeStamp = (int(time.time())) * 1000

# Calcolo MAC
mac_str = "apiKey=" + apiKey + \
    "numeroContratto=" + numeroContratto + \
    "codiceFiscale=" + codiceFiscale + \
    "dataRegistrazioneDa=" + dataRegistrazioneDa + \
    "dataRegistrazioneA=" + dataRegistrazioneA + \
    "timeStamp=" + str(timeStamp) + \
     chiaveSegreta
mac = hashlib.sha1(mac_str.encode('utf8')).hexdigest()

# Parametri di invio
requestParams = {
    'apiKey': apiKey,
    'numeroContratto': numeroContratto,
    'codiceFiscale': codiceFiscale,
    'dataRegistrazioneDa': dataRegistrazioneDa,
    'dataRegistrazioneA': dataRegistrazioneA,
    'dataAggiornamentoDa': dataAggiornamentoDa,
    'dataAggiornamentoA': dataAggiornamentoA,
    'statoAggiornamento': statoAggiornamento,
    'timeStamp': str(timeStamp),
    'mac': mac
}

# Chiamata  API
response = requests.post(requestUrl,json=requestParams,headers={'Content-Type':'application/json'})

# Parametri di ritorno
response_data = response.json()
                    
                