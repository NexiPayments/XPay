# Pagamento ricorrente M.O.T.O
Questa soluzione si divide in due step.

## 1. Primo pagamento
Effettua una transazione di pagamento M.O.T.O. Server to Server contestualmente registra il contratto per l'utilizzo nei successivi pagamenti.

## 2. Pagamenti successivi
Ogni volta che l'utente registrato effettua un acquisto successivo, l'e-commerce deve inviare, a CartaSi, una chiamata API con i dati del contratto registrato in fase di primo pagamento.
