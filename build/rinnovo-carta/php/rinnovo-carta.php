<?php

//XPay Build - Rinnovo carta 3D Secure

// apiKey e chiave segreta
$apiKey = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

if ($_REQUEST['xpayEsito'] != "OK") {
    echo "Esito 3D-Secure:" . $_REQUEST['esito'] . "-" . $_REQUEST['messaggio'];
    exit;
}

// Calcolo MAC con i parametri di ritorno
$macCalculated = sha1('esito=' . $_REQUEST['xpayEsito'] .
        'idOperazione=' . $_REQUEST['xpayIdOperazione'] .
        'xpayNonce=' . $_REQUEST['xpayNonce'] .
        'timeStamp=' . $_REQUEST['xpayTimeStamp'] .
        $chiaveSegreta
);

// Verifico corrispondenza tra MAC calcolato e parametro mac di ritorno
if ($macCalculated != $_REQUEST['xpayMac']) {
    echo 'Errore MAC: ' . $macCalculated . ' non corrisponde a ' . $_REQUEST['mac'];
    exit;
}

// Dopo i controlli inizio il pagamento effettivo

$requestUrl = "https://int-ecommerce.nexi.it/ecomm/api/recurring/rinnovoCarta3DS";

//$codTrans = "TESTPS_" . date('YmdHis'); // Codice della transazione
$importo = "5000"; // 5000 = 50,00 EURO (indicare la cifra in centesimi)
$divisa = "978"; // divisa 978 indica EUR
$numeroContratto = ""; // Numero del contratto
$timeStamp = (time()) * 1000;

// Calcolo MAC
$mac = sha1('apiKey=' . $apiKey . 'numeroContratto=' . $numeroContratto . "codiceTransazione=" . $_REQUEST['codiceTransazione'] . "importo=" . $importo . 'divisa=' . $divisa . "xpayNonce=" . $_REQUEST['xpayNonce'] . "timeStamp=" . $timeStamp . $chiaveSegreta);

$requestParams = array(
    'apiKey' => $apiKey,
    'numeroContratto' => $numeroContratto,
    'codiceTransazione' => $_REQUEST['codiceTransazione'],
    'importo' => $importo,
    'divisa' => $divisa,
    'xpayNonce' => $_REQUEST['xpayNonce'],
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

$dataEsito = json_decode($response, true);

if ($dataEsito['esito'] == "OK") { // Transazione andata a buon fine
    // Calcolo MAC con i parametri di ritorno
    $macCalculated2 = sha1('esito=' . $dataEsito['esito'] . 'idOperazione=' . $dataEsito['idOperazione'] . 'timeStamp=' . $dataEsito['timeStamp'] . $chiaveSegreta);
    if ($macCalculated2 != $dataEsito['mac']) {
        echo 'Errore MAC: ' . $macCalculated2 . ' non corrisponde a ' . $dataEsito['mac'];
        exit;
    }
    echo 'La transazione e rinnovo carta' . $codTrans . " sono avvenuti con successo; codice autorizzazione: " . $dataEsito['codiceAutorizzazione'];
} else { // Transazione rifiutata
    echo 'La transazione ' . $codTrans . " è stata rifiutata; descrizione errore: " . $dataEsito['errore']['messaggio'];
}
                    
                