<?php

$APIKEY = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
$CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

$requestUrl = "https://int-ecommerce.nexi.it/ecomm/api/recurring/pagamentoRicorrente";

$connection = curl_init();

if ($connection) {

    // Parametri della richiesta
    $codTrans = "TESTPS_" . date('YmdHis');
    $importo = "5000";
    $divisa = "978";
    $scadenza = '202012';
    $timeStamp = (time()) * 1000;
    $numContratto = ""; // Inserire il numero contratto generato con il primo pagamento
    $codiceGruppo = ""; // Inserire il codice gruppo assegnato da Nexi

    // Calcolo MAC
    $mac = sha1('apiKey=' . $APIKEY . 'numeroContratto=' . $numContratto . 'codiceTransazione=' . $codTrans . 'importo=' . $importo . "divisa=" . $divisa . "scadenza=" . $scadenza . "timeStamp=" . $timeStamp . $CHIAVESEGRETA);

    $requestParams = array(
        'apiKey' => $APIKEY,
        'numeroContratto' => $numContratto,
        'codiceTransazione' => $codTrans,
        'importo' => $importo,
        'divisa' => $divisa,
        'scadenza' => $scadenza,
        'codiceGruppo' => $codiceGruppo,
        'timeStamp' => $timeStamp,
        'parametriAggiuntivi' => array(
        ),
        'mac' => $mac
    );

    $json = json_encode($requestParams);

    curl_setopt_array($connection, array(
        CURLOPT_URL => $requestUrl,
        CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_RETURNTRANSFER => 1
    ));

    // Eseguire chiamata
    $jsonResponse = curl_exec($connection);

    curl_close($connection);

    $response = json_decode($jsonResponse, true);

    // Calcolare il mac
    $macCalculated = sha1('esito=' . $response['esito'] . 'idOperazione=' . $response['idOperazione'] . 'timeStamp=' . $response['timeStamp'] . $CHIAVESEGRETA);

    // Verificare il mac della risposta
    if ($macCalculated == $response['mac']) {

        // Controllo esito dell'operazione
        if ($response['esito'] == "OK") {
            echo 'La transazione ' . $codTrans . " è avvenuta con successo; codice autorizzazione: " . $response['codiceAutorizzazione'];
        } else {
            echo 'La transazione ' . $codTrans . " è stata rifiutata; descrizione errore: " . $response['errore']['messaggio'];
        }
    } else {
        echo 'Errore MAC: ' . $macCalculated . ' non corrisponde a ' . $response['mac'];
    }
}
