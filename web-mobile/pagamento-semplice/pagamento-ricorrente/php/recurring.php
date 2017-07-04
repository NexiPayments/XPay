<?php

// apikey e chiave segreta - sostituire con i valori forniti da CartaSi
$APIKEY = "<ALIAS>";
$CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>";

$requestUrl = "https://int-ecommerce.cartasi.it/ecomm/api/recurring/pagamentoRicorrente";

// Parametri dalla richiesta
$numContratto = $_REQUEST['numContratto'];
if (!$numContratto) {
    echo "Inviare numContratto!";
    exit;
}

$codTrans = "TESTPS_" . date('YmdHis');
$importo = "5000";
$divisa = "978";
$scadenza = '202012';
$timeStamp = (time()) * 1000;

// Calcolo MAC
$mac = sha1('apiKey=' . $APIKEY . 'numeroContratto=' . $numContratto . 'codiceTransazione=' . $codTrans . 'importo=' . $importo . "divisa=" . $divisa . "scadenza=" . $scadenza . "timeStamp=" . $timeStamp . $CHIAVESEGRETA);

$requestParams = array(
    'apiKey' => $APIKEY,
    'numeroContratto' => $numContratto,
    'codiceTransazione' => $codTrans,
    'importo' => $importo,
    'divisa' => $divisa,
    'scadenza' => $scadenza,
    'codiceGruppo' => 'GRUPPOTEST',
    'timeStamp' => (string) $timeStamp,
    'mac' => $mac,
    'mail' => "cardHolder@mail.it",
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
curl_setopt($connection, CURLINFO_HEADER_OUT, true);

$response = curl_exec($connection);
curl_close($connection);

$dataVerifica = json_decode($response, true);

if ($dataVerifica['esito'] == "OK") { // Transazione andata a buon fine
    // Calcolo MAC con i parametri di ritorno
    $macCalculated = sha1('esito=' . $dataVerifica['esito'] . 'idOperazione=' . $dataVerifica['idOperazione'] . 'timeStamp=' . $dataVerifica['timeStamp'] . $CHIAVESEGRETA);
    if ($macCalculated != $dataVerifica['mac']) {
        echo 'Errore MAC: ' . $macCalculated . ' non corrisponde a ' . $dataVerifica['mac'];
        exit;
    }
    
    echo 'La transazione ' . $codTrans . " è avvenuta con successo; codice autorizzazione: " . $dataVerifica['codiceAutorizzazione'];
} else { // Transazione rifiutata
    echo 'La transazione ' . $codTrans . " è stata rifiutata; descrizione errore: " . $dataVerifica['errore']['messaggio'];
}