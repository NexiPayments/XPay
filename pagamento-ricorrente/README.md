# Pagamento ricorrente

L'integrazione di questa soluzione consente all'esercente di tokenizzare i dati della carta del cliente, in modo da poter effettuare delle ricorrenze per servizi come abbonamenti.

Se si è invece interessati ad una soluzione che consente al cliente finale di memorizzare i dati della propria carta di credito, ed utilizzarli successivamente per effettuare acquisti più rapidamente, fare riferimento alla soluzione OneClick.

I pagamenti ricorrenti sono identificati anche con il termine "MIT" (Merchant Initiated Transaction). Le transazione MIT si dividono in:

- Scheduled: addebiti con cadenza definita (es. primo di ogni mese).
- Unscheduled: addebiti con cadenza non definita.

E' necessario comunicare al supporto XPay il tipo di addebiti che verranno eseguiti dal proprio negozio, in quanto è necessario configurare correttamente il profilo Nexi assegnato.

Non è consentito utilizzare numeri contratto creati tramite pagamenti MIT Scheduled per effettuare transazioni MIT Unscheduled e viceversa.

A livello tecnico, la gestione di questa soluzione si divide in 2 fasi:

- Primo pagamento
- Pagamenti successivi


## 1. Primo pagamento
Va generata una prima transazione, assegnando un token che consente a Nexi di salvare l'abbinamento tra l'utente e la carta di pagamento utilizzata.

Il primo pagamento è soggetto a Strong Customer Authentication (SCA), il cliente verrà dunque reindirizzato sul protocollo 3DS per l’autenticazione. 

## 2. Pagamenti successivi
Per i pagamenti successivi è necessario utilizzare un'API fornita da Nexi. Questa API richiede come parametri il token generato con il primo pagamento e altri parametri relativi alla transazione da effettuare. 