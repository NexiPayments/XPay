# API di backoffice
XPay di Nexi mette a disposizione degli esercenti l’ambiente di backoffice per la gestione delle transazioni ricevute. Gli esercenti che dispongono di un proprio gestionale possono usufruire delle funzionalità tipiche del post-vendita (operatività e reportistica), mediante integrazione con API.
Sono disponibili i seguenti servizi:

## 1. [Incasso](/api-backoffice/incasso/)
Effettua una operazione di Contabilizzazione.

## 2. [Storno/rimborso](/api-backoffice/storno-rimborso/)
Effettua una operazione di annullamento o rimborso in base allo stato della transazione.

## 3. [Interrogazione dettaglio ordine](/api-backoffice/integrazione-dettaglio-ordine/)
Restituisce il dettaglio di un ordine con tutte le operazioni ad esso associate.

## 4. [Elenco ordini](/api-backoffice/elenco-ordini/)
Permette di ottenere l’elenco degli ordini che soddisfano i filtri impostati nella richiesta

## 5. [Richiesta link PayMail](/api-backoffice/richiesta-link-paymail/)
Il servizio consente di ottenere un link di pagamento che inviato per e-mail al cliente gli consente di essere rimandato sulle pagine di pagamento XPay e completare la transazione in sicurezza.

## 6. [Elenco ordini](/api-backoffice/report/)
Questa API richiede i dati necessari per effettuare il download di un report schedulato dal BO. Partendo dalla data di riferimento, ritorna l’elenco delle istanze di report elaborate più prossime alla data stessa. Se la data riferimento non viene specificata, viene usata la data corrente. Mediante i dati contenuti nel vettore listaReport sarà possibile effettuare il download del report stesso. Per poter scaricare il file, è necessario configuarare i report nel backoffice, nella sezione "Report" inserendo tipo, filtri, dati e formato del report che si vuole venga generato.

## 7. [Richiesta link PayMail](/api-backoffice/report-paymail/)
Effettua la ricerca dei link paymail restituendo lo stato del pagamento. Ogni ricerca restituirà un massimo di 100 link.