# Pagamento 3D Secure
Questo servizio prevede una doppia API una di verifica 3D-Secure e una di pagamento.

## 1. Verifica 3D Secure
In questo step l'API risponde con un JSON contenete il codice html fornito dall'MPI per l'inserimento dei dati utili al 3DSecure, è compito del ricevente stampare sul browser dell'utente l'html ricevuto.

## 2. Pagamento
Dopo l'autenticazione da parte dell'utente l'API comunica il risultato all'indirizzo di risposta indicato nella richiesta. Con il Nonce ricevuto in risposta si procede a richiamare la seconda API per l'esecuzione del pagamento vero e proprio.
