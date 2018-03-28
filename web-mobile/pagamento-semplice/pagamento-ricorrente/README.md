# Pagamento ricorrente
Questa soluzione si divide in due fasi:

## 1. Primo pagamento
Durante la fase di primo pagamento è necessario creare un codice identificativo che verrà poi utilizzato per i pagamenti successivi. Questa fase si divide in tre passaggi:
1. Reindirizzare l'utente verso l'ambiente di pagamento di Nexi
2. Gestire la chiamata Server To Server fatta da Nexi per registrare l'esito del pagamento
3. Gestire il rientro dell'utente sul proprio sito

## 2. Pagamenti successivi
&Egrave; necessario fare delle chiamate alle API di Nexi utilizzando il codice identificativo, precedentemente generato, per effettuare i pagamenti successivi