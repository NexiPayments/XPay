                                            
<?php

// Pagamento DCC - Verifica

// apiKey e chiave segreta
$apiKey = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

$requestUrl = "https://int-ecommerce.nexi.it/" . "ecomm/api/etc/verificaDCC";

$importo = "5000"; // 5000 = 50,00 EURO (indicare la cifra in centesimi)
$pan = "4000000000000101"; // Pan della carta
$timeStamp = (time()) * 1000;

// Calcolo MAC
$mac = sha1('apiKey=' . $apiKey . 'pan=' . $pan . 'importo=' . $importo . "timeStamp=" . $timeStamp . $chiaveSegreta);

// Parametri
$requestParams = array(
    'apiKey' => $apiKey,
    'pan' => $pan,
    'importo' => $importo,
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
curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);

$response = curl_exec($connection);

curl_close($connection);

$risposta = json_decode($response, true);

// Controllo JSON di risposta
if (json_last_error() === JSON_ERROR_NONE) {

    $MACrisposta = sha1('esito=' . $risposta['esito'] . 'idOperazione=' . $risposta['idOperazione'] . 'timeStamp=' . $risposta['timeStamp'] . $chiaveSegreta);

    // Controllo MAC di risposta
    if ($risposta['mac'] == $MACrisposta) {

        // Controllo esito
        if ($risposta['esito'] == 'OK') {

            $valoriDCC = $risposta;

            unset($valoriDCC['esito']);
            unset($valoriDCC['idOperazione']);
            unset($valoriDCC['timeStamp']);
            unset($valoriDCC['mac']);

            // Salvo i dati per usarli nella fase di pagamento
            $file = fopen("dcc.txt", 'w+');
            if ($file) {
                fwrite($file, json_encode($valoriDCC));
                fclose($file);
            }

            // Includo il file per generare il nonce
            include 'nonce.php';
        } else {
            echo 'Operazione n. ' . $risposta['idOperazione'] . ' non eseguita. esito ' . $risposta['esito'] . '<br><br>' . json_encode($risposta['errore']);
        }
    } else {
        echo 'Errore nel calcolo del MAC di risposta';
    }
} else {
    echo 'Errore nella lettura del JSON di risposta';
}
                    
                