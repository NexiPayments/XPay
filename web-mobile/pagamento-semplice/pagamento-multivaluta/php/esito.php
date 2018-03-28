<?php

// Chiave segreta 
$CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

// Controllo che ci siano tutti i parametri di ritorno obbligatori per calcolare il MAC
$requiredParams = array('codTrans', 'esito', 'importo', 'divisa', 'data', 'orario', 'codAut', 'mac');
foreach ($requiredParams as $param) {
    if (!isset($_REQUEST[$param])) {
        echo 'Paramentro mancante ' . $field;
        exit;
    }
}

// Calcolo MAC con i parametri di ritorno
$macCalculated = sha1('codTrans=' . $_REQUEST['codTrans'] .
        'esito=' . $_REQUEST['esito'] .
        'importo=' . $_REQUEST['importo'] .
        'divisa=' . $_REQUEST['divisa'] .
        'data=' . $_REQUEST['data'] .
        'orario=' . $_REQUEST['orario'] .
        'codAut=' . $_REQUEST['codAut'] .
        $CHIAVESEGRETA
);

// Verifico corrispondenza tra MAC calcolato e parametro mac di ritorno
if ($macCalculated != $_REQUEST['mac']) {
    echo 'Errore MAC: ' . $macCalculated . ' non corrisponde a ' . $_REQUEST['mac'];
    exit;
}

// Nel caso in cui non ci siano errori gestisco il parametro esito
if($_REQUEST['esito'] == 'OK'){
    echo 'La transazione ' . $_REQUEST['codTrans'] . " è avvenuta con successo; codice autorizzazione: " . $_REQUEST['codAut'];
    echo    '<br><br><b>DDC</b>'
            . '<br>dccRate = ' . $_REQUEST['dccRate']
            . '<br>dccAmount = ' . $_REQUEST['dccAmount']
            . '<br>dccCurrency = ' . $_REQUEST['dccCurrency']
            . '<br>dccState = ' . $_REQUEST['dccState'];
} else {
    echo 'La transazione ' . $_REQUEST['codTrans'] . " è stata rifiutata; descrizione errore: " . $_REQUEST['messaggio'];
}

