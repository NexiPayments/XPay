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

## 5. [Richiesta link Pay-by-Link](/api-backoffice/richiesta-link-pay-by-link/)
Il servizio consente di ottenere un link di pagamento che inviato per e-mail al cliente gli consente di essere rimandato sulle pagine di pagamento XPay e completare la transazione in sicurezza.

## 6. [Rigenera link Pay-by-Link](/api-backoffice/richiesta-link-pay-by-link/)
L’api permette di rigenerare, con gli stessi parametri del link originale, un link precedentemente scaduto o pagato con esito negativo. L’api può essere anche utilizzata per estendere il periodo di vita di un link ancora attivo non utilizzato. La nuova scadenza del link generato sarà la data di richiesta incrementata del timeout specifica.

In caso il link associato al codice transazione non sia ancora stato utilizzato, l’api aggiorna la sola scadenza del link. In questo caso il link paymail restituito sarà identico al precedente.

In caso il link sia già stato utilizzato con esito del pagamento negativo, sarà creato un nuovo link con il solo codice transazione differente. Gli altri parametri sono invariati rispetto al link originale.

Il nuovo codice transazione sarà il valore del campo nuovoCodiceTransazione, se non specificato XPay genererà un codice posponendo all’originale un contatore. (Per non superare i 30 caratteri di dimensione massima del codice transazione potrebbero essere sostituiti gli ultimi due caratteri). Il codice transazione associato al nuovo link sarà restituito in risposta all’api e dovrà essere riutilizzato per eventuali nuove rigenerazioni

## 7. [Report](/api-backoffice/report/)
Questa API richiede i dati necessari per effettuare il download di un report schedulato dal BO. Partendo dalla data di riferimento, ritorna l’elenco delle istanze di report elaborate più prossime alla data stessa. Se la data riferimento non viene specificata, viene usata la data corrente. Mediante i dati contenuti nel vettore listaReport sarà possibile effettuare il download del report stesso. Per poter scaricare il file, è necessario configuarare i report nel backoffice, nella sezione "Report" inserendo tipo, filtri, dati e formato del report che si vuole venga generato.

## 8. [Report Pay-by-Link](/api-backoffice/report-pay-by-link/)
Effettua la ricerca dei link paymail restituendo lo stato del pagamento. Ogni ricerca restituirà un massimo di 100 link.

## 9. [Gestione contratti](/api-backoffice/gestione-contratti/)
API dedicate alle operazioni sui contratti (token) associati a carte di pagamento.

## 10. [Gestione controlli](/api-backoffice/gestione-controlli/)
API dedicate alle operazioni di controllo/verifica/blacklist.