<?php

// apiKey e chiave segreta
$apiKey = "<ALIAS>"; // Sostituire con il valore fornito da CartaSi
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da CartaSi

$requestUrl = "https://int-ecommerce.cartasi.it/ecomm/api/recurring/creaNonceVerificaCarta";
$merchantServerUrl = "https://" . $_SERVER['HTTP_HOST'] . "/xpay/php/S2S/recurring/verifica_carta_3DS/";

$pan = "4000000000000002"; // Pan della carta
$scadenza = '202012'; // Scadenza della carta (formato aaaamm)
$cvv = "123"; // CVV della carta
$timeStamp = (time()) * 1000;

// Calcolo MAC
$mac = sha1('apiKey=' . $apiKey . 'pan=' . $pan . "scadenza=" . $scadenza . 'cvv=' . $cvv . "timeStamp=" . $timeStamp . $chiaveSegreta);

$requestParams = array(
    'apiKey' => $apiKey,
    'pan' => $pan,
    'scadenza' => $scadenza,
    'cvv' => $cvv,
    'urlRisposta' => $merchantServerUrl . "verifica_auth.php",
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
curl_close($connection);

$data3DS = json_decode($response, true);

if ($data3DS['esito'] == "OK") {
    // Calcolo MAC con i parametri di ritorno
    $macCalculated2 = sha1('esito=' . $data3DS['esito'] . 'idOperazione=' . $data3DS['idOperazione'] . 'timeStamp=' . $data3DS['timeStamp'] . $chiaveSegreta);
    if ($macCalculated2 != $data3DS['mac']) {
        echo 'Errore MAC: ' . $macCalculated2 . ' non corrisponde a ' . $dataVerifica['mac'];
        exit;
    } else {
        echo $data3DS['html'];
    }
} else {
    echo "Errore durante la verifica 3D-Secure. " . $data3DS['errore']['messaggio'];
    exit;
}

