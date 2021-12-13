# Pagamento OneClick tramite pagina di cassa
Con questa modalità la chiamata al gateway sarà identica sia per i primi pagamenti che per quelli successivi: sarà XPay a gestirli. Nel caso di primo pagamento XPay mostrerà il form per l'inserimento dei dati della carta mentre nel caso di pagamenti successivi mostrerà i dati della carta precedentemente inseriti oppure la possibilità di inserire i dati di una nuova carta.

In caso di primo pagamento sulla pagina di cassa XPay verrà data la possibilità al cardholder di salvare i dati della propria carta per effettuare i pagamenti One Click.

Il primo pagamento è soggetto a Strong Customer Authentication (SCA), il cliente verrà dunque reindirizzato sul protocollo 3DS per l’autenticazione.

L'unico dato che dovrà essere gestito dall'esercente è il parametro "num_contratto" che andrà valorizzato con un identificativo univoco per ogni cliente (ad esempio l'id cliente del proprio sito).