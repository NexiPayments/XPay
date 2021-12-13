# Primo pagamento 3D Secure
Effettua una transazione di pagamento con 3D-Secure contestualmente registra il contratto per l'utilizzo nei successivi pagamenti recurring o OneClickPay, questo servizio prevede una doppia API una di verifica 3D-Secure e una di pagamento.

## 1. Verifica 3D Secure
L'API risponde con un JSON contenete il codice html fornito da XPay per l'inserimento dei dati utili al 3DSecure, è compito del ricevente stampare sul browser dell'utente l'html ricevuto. 

## 2. Pagamento
Successivamente, dopo l'autenticazione da parte dell'utente l'API comunica il risultato.