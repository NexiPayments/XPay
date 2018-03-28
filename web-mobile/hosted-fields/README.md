# Hosted fields

Hai a disposizione questa modalità per integrare XPay di Nexi permettendo una completa personalizzazione dell’esperienza di pagamento, con un limitato impatto sui requisiti PCI DSS.

## Come funziona?
Si descrivono di seguito l’architettura e il processo di pagamento che prevede l’utilizzo di un client SDK Javascript.

Il pagamento si compone dei seguenti elementi:
1. Pagina di cassa custom ospitata sul dominio del merchant dotata di certificato (https)
2. Libreria XPay Javascript non intrusiva ospitata nella pagina di cassa che, previa opportuna configurazione, è in grado di inserirsi nel processo di immissione dei dati
3. Back-end del merchant che riceve il nonce( codice casuale valido per la singola transazione) e lo utilizza per il pagamento server to server
4. API pagaNonce di XPay che esegue il pagamento server to server