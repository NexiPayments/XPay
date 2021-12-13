# Lightbox
Lightbox è la soluzione che Nexi mette a disposizione per integrare il sistema di pagamento XPay con il proprio portale Ecommerce, consente ai propri clienti di effettuare i pagamenti senza essere rediretti su pagine esterne.
Per consultare le specifiche tecniche visita il sito https://ecommerce.nexi.it/specifiche-tecniche/lightbox.html

La soluzione lightbox sfrutta un SDK javascript fornito da Nexi che una volta configurato, con i parametri relativi al pagamento, mostra un iframe che copre l'intera finestra del browser. In questa finestra verrà visualizzata la pagina di cassa Nexi dove il cliente effettuerà il pagamento. Una volta terminato il pagamento verrà restituito un evento javascript alla pagina del merchant che si occuperà di gestire l'esito della transazione.
