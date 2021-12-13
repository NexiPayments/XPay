                                            
<?php

// Aggiorna contratto - Controllo 3D Secure

// apiKey e chiave segreta
$apiKey = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

$requestUrl = "https://int-ecommerce.nexi.it/ecomm/api/recurring/creaNonceAggiornaContratto";
$merchantServerUrl = "https://" . $_SERVER['HTTP_HOST'] . "/xpay/S2S/payment/";

$numeroContratto = "";
$codTrans = "TESTPS_" . date('YmdHis');; // Codice della transazione
$importo = "5000"; // 5000 = 50,00 EURO (indicare la cifra in centesimi)
$divisa = "978"; // divisa 978 indica EUR
$codiceGruppo = "";
$timeStamp = (time()) * 1000;

// Calcolo MAC
$mac = sha1('apiKey=' . $apiKey . 'numeroContratto=' . $numeroContratto. 'codiceTransazione=' . $codTrans . 'importo=' . $importo . "divisa=" . $divisa . "codiceGruppo=" . $codiceGruppo . "timeStamp=" . $timeStamp . $chiaveSegreta);

$requestParams = array(
    'apiKey' => $apiKey,
    'numeroContratto' => $numeroContratto,
    'importo' => $importo,
    'divisa' => $divisa,
    'codiceTransazione' => $codTrans,
    'urlRisposta' => $merchantServerUrl . "payment.php",
    'timeStamp' => (string) $timeStamp,
    'mac' => $mac,
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
    echo $data3DS['html'];
} else {
    echo "Errore durante la verifica 3D-Secure. " . $data3DS['errore']['messaggio'];
    exit;
}
                    
                