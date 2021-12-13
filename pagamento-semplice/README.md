# Pagamento semplice

Questa soluzione è la più semplice per abilitare un e-commerce a ricevere pagamenti, senza doversi preoccupare di gestire i dati sensibili del cliente.

Il cliente resta sull'e-commerce dell'esercente fino al momento del checkout. Viene reindirizzato in ambiente sicuro Nexi per effettuare il pagamento per poi tornare sul sito dell'esercente al termine della transazione. 

Questa soluzione si divide in due fasi:

1. Reindirizzare l'utente verso l'ambiente di pagamento di Nexi
2. Gestire la risposta al termine della transazione. È eventualmente possibile ricevere una notifica del pagamento in modalità "server to server" per un'ulteriore conferma dell'esito della transazione.