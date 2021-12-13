                                            
<?php

// Pagamento OneClik - Pagamenti successivi - Tramite redirezione - Esito

// Chiave segreta
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

//CONTROLLO CHE CI SIANO TUTTI I PARAMETRI DI RITORNO OBBLIGATORI PER CALCOLARE IL MAC
$requiredParams = array('codTrans', 'esito', 'importo', 'divisa', 'data', 'orario', 'codAut', 'mac');
foreach ($requiredParams as $param) {
    if (!isset($_REQUEST[$param])) {
        echo 'Paramentro mancante ' . $field;
        exit;
    }
}


//CALCOLO MAC CON I PARAMETRI DI RITORNO
$macCalculated = sha1('codTrans=' . $_REQUEST['codTrans'] .
        'esito=' . $_REQUEST['esito'] .
        'importo=' . $_REQUEST['importo'] .
        'divisa=' . $_REQUEST['divisa'] .
        'data=' . $_REQUEST['data'] .
        'orario=' . $_REQUEST['orario'] .
        'codAut=' . $_REQUEST['codAut'] .
        $chiaveSegreta
);


//VERIFICO CORRISPONDENZA TRA MAC CALCOLATO E MAC DI RITORNO
if ($macCalculated != $_REQUEST['mac']) {
    echo 'S2S errore MAC: ' . $macCalculated . ' NON CORRISPONDENTE A ' . $_REQUEST['mac'];
    exit;
}

//NEL CASO IN CUI NON CI SIANO ERRORI GESTISCO IL PARAMETRO esito
if($_REQUEST['esito'] == 'OK'){
    echo 'La transazione ' . $_REQUEST['codTrans'] . " è avvenuta con successo; codice autorizzazione: " . $_REQUEST['codAut']. "<br>Codice Contratto: " . $_REQUEST['num_contratto'];
} else {
    echo 'La transazione ' . $_REQUEST['codTrans'] . " è stata rifiutata; descrizione errore: " . $_REQUEST['messaggio'];
}
                    
                