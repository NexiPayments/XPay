<?php

// apiKey e chiave segreta
$apiKey = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

$requestUrl = "https://int-ecommerce.nexi.it/ecomm/api/recurring/creaNoncePrimo3DS";
$merchantServerUrl = "https://" . $_SERVER['HTTP_HOST'] . "/xpay/php/S2S/recurring/pagamento_3DS/";

$codTrans = "TESTPS_" . date('YmdHis'); // Codice della tranzione
$importo = "5000"; // 5000 = 50,00 EURO (indicare la cifra in centesimi)
$divisa = "978"; // divisa 978 indica EUR
$pan = "4000000000000002"; // Pan della carta
$scadenza = '202012'; // Scadenza della carta (formato aaaamm)
$cvv = "123"; // CVV della carta
$timeStamp = (time()) * 1000;

// Calcolo MAC
$mac = sha1('apiKey=' . $apiKey . 'codiceTransazione=' . $codTrans . "divisa=" . $divisa . 'importo=' . $importo . "timeStamp=" . $timeStamp . $chiaveSegreta);

// Parametri
$requestParams = array(
    'apiKey' => $apiKey,
    'pan' => $pan,
    'scadenza' => $scadenza,
    'cvv' => $cvv,
    'importo' => $importo,
    'divisa' => $divisa,
    'codiceTransazione' => $codTrans,
    'urlRisposta' => $merchantServerUrl . "payment.php",
    'timeStamp' => (string) $timeStamp,
    'mac' => $mac
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

$data3DS = json_decode($response, true);

if ($data3DS['esito'] == "OK") {
    // Calcolo MAC con i parametri di ritorno
    $macCalculated2 = sha1('esito=' . $data3DS['esito'] . 'idOperazione=' . $data3DS['idOperazione'] . 'timeStamp=' . $data3DS['timeStamp'] . $chiaveSegreta);
    if ($macCalculated2 != $data3DS['mac']) {
        echo 'Errore MAC: ' . $macCalculated2 . ' non corrisponde a' . $data3DS['mac'];
        exit;
    } else {
        echo $data3DS['html'];
    }
} else {
    echo "Errore durante la verifica 3D-Secure. " . $data3DS['errore']['messaggio'];
    exit;
}


