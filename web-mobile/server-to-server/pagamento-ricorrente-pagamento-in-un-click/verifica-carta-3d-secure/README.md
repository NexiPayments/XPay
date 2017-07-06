# Verifica carta 3D Secure
Effettua una transazione di verifica carta, senza nessun addebito al cliente, in modalità con 3D-Secure, questo servizio prevede una doppia API una di verifica 3D-Secure e una di pagamento.

## 1. Verifica 3D Secure
L'API risponde con un JSON contenente il codice html fornito da X-Pay per l'inserimento dei dati utili al 3D-Secure, è compito del ricevente stampare sul browser dell'utente l'html ricevuto.

## 2. Pagamento
Successivamente, dopo l'autenticazione da parte dell'utente l'API comunica il risultato.