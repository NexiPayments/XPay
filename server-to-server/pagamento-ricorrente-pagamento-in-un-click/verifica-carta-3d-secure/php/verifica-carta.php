                                            
<?php

// Verifica carta 3D Secure - Verifica carta

// apiKey e chiave segreta
$apiKey = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

if ($_REQUEST['esito'] != "OK") {
    echo "Esito 3D-Secure:" . $_REQUEST['esito'] . "-" . $_REQUEST['messaggio'];
    exit;
}

// Controllo che si siano tutti i parametri obbligatori di ritorno
$requiredParams = array('esito', 'idOperazione', 'xpayNonce', 'timeStamp', 'mac');
foreach ($requiredParams as $param) {
    if (!isset($_REQUEST[$param])) {
        echo 'Paramentro mancante ' . $field;
        exit;
    }
}

// Calcolo MAC
$macCalculated = sha1('esito=' . $_REQUEST['esito'] .
        'idOperazione=' . $_REQUEST['idOperazione'] .
        'xpayNonce=' . $_REQUEST['xpayNonce'] .
        'timeStamp=' . $_REQUEST['timeStamp'] .
        $chiaveSegreta
);

// Verifico corrispondenza tra MAC calcolato e parametro mac di ritorno
if ($macCalculated != $_REQUEST['mac']) {
    echo '3DS errore MAC: ' . $macCalculated . ' NON CORRISPONDENTE A ' . $_REQUEST['mac'];
    exit;
}

// Dopo i controlli eseguo la prova di pagamento

$requestUrl = "https://int-ecommerce.nexi.it/ecomm/api/recurring/verificaCarta3DS";

$xpayNonce = $_REQUEST['xpayNonce']; // Nonce generato da Nexi da utilizzare per eseguire il pagamento
$timeStamp = (time()) * 1000;

// Calcolo MAC
$mac = sha1('apiKey=' . $apiKey . 'xpayNonce=' . $xpayNonce . 'timeStamp=' . $timeStamp . $chiaveSegreta);

$requestParams = array(
    'apiKey' => $apiKey,
    'xpayNonce' => $xpayNonce,
    'numeroContratto' => "TEST_" . date('YmdHis'),
    'timeStamp' => $timeStamp,
    'mac' => $mac,<informazioniSicurezza>
    'informazioniSicurezza' => array(
    )</informazioniSicurezza>
);

$json = json_encode($requestParams);

$connection = curl_init();
if ($connection == false) {
    echo "connessione fallita!";
    exit;
}
curl_setopt($connection, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($connection, CURLOPT_URL, $requestUrl);
curl_setopt($connection, CURLOPT_POST, 1);
curl_setopt($connection, CURLOPT_POSTFIELDS, $json);
curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($connection);
curl_close($connection);

$dataVerifica = json_decode($response, true);

if ($dataVerifica['esito'] == "OK") { // Transazione andata a buon fine
    // Calcolo MAC con i parametri di ritorno
    $macCalculated2 = sha1('esito=' . $dataVerifica['esito'] . 'idOperazione=' . $dataVerifica['idOperazione'] . 'timeStamp=' . $dataVerifica['timeStamp'] . $chiaveSegreta);
    if ($macCalculated2 != $dataVerifica['mac']) {
        echo 'Errore MAC: ' . $macCalculated2 . ' non corrisponde a ' . $dataVerifica['mac'];
        exit;
    }

    echo "La verifica è avvenuta con successo; codice operazione: " . $dataVerifica['idOperazione'];
} else { // Transazione rifiutata
    echo "La verifica è fallita; descrizione errore: " . $dataVerifica['errore']['messaggio'];
}
                    
                