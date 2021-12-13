<?php

// Chiave segreta
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

// Controllo che si siano tutti i parametri di ritorno obbligatori per il calcolo del MAC
$requiredParams = array('codTrans', 'esito', 'importo', 'divisa', 'data', 'orario', 'codAut', 'mac');
foreach ($requiredParams as $param) {
    if (!isset($_REQUEST[$param])) {
        echo 'Paramentro mancante ' . $field;
        header('500 Internal Server Error', true, 500);
        exit;
    }
}

// Calcolo MAC con parametri di ritorno
$macCalculated = sha1('codTrans=' . $_REQUEST['codTrans'] .
        'esito=' . $_REQUEST['esito'] .
        'importo=' . $_REQUEST['importo'] .
        'divisa=' . $_REQUEST['divisa'] .
        'data=' . $_REQUEST['data'] .
        'orario=' . $_REQUEST['orario'] .
        'codAut=' . $_REQUEST['codAut'] .
        $chiaveSegreta
);


// Verifico corrispondeza tra MAC calcolato e parametro mac di ritorno
if ($macCalculated != $_REQUEST['mac']) {
    echo 'Errore MAC: ' . $macCalculated . ' non corrisponde a ' . $_REQUEST['mac'];
    header('500 Internal Server Error', true, 500);
    exit;
}

// nel caso in cui non ci siano errori gestisco il parametro esito
if($_REQUEST['esito'] == 'OK'){
    header('OK, pagamento avvenuto, preso riscontro', true, 200);
} else {
    header('KO, pagamento non avvenuto, preso riscontro', true, 200);
}
