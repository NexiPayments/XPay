<?php

// apiKey e chiave segreta
$apiKey = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

$requestUrl = "https://int-ecommerce.nexi.it/" . "ecomm/api/hostedPayments/creaNonce";

$divisa = "EUR"; // Divisa
$pan = "4000000000000101"; // Pan della carta
$scadenza = "12/30"; // Scadenza carta mm/aa
$cvv = "123"; // CVV della carta
$importo = "5000"; // 5000 = 50,00 EURO (indicare la cifra in centesimi)
$codiceTransazione = "sdb3vre" . date('YmdHi'); // Codice della transazione
$urlRisposta = "https://" . $_SERVER['HTTP_HOST'] . "/pagamento.php"; // Dopo il controllo 3DS viene fatto un redirect verso questo link che si deve occupare di eseguire il pagamento
$timeStamp = (time()) * 1000;

// Calcolo MAC
$mac = sha1('apiKey=' . $apiKey . 'codiceTransazione=' . $codiceTransazione . "divisa=" . $divisa . "importo=" . $importo . "timeStamp=" . $timeStamp . $chiaveSegreta);

// Parametri
$requestParams = array(
    'apiKey' => $apiKey,
    'pan' => $pan,
    'scadenza' => $scadenza,
    'cvv' => $cvv,
    'importo' => $importo,
    'divisa' => $divisa,
    'codiceTransazione' => $codiceTransazione,
    'urlRisposta' => $urlRisposta,
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

    if ($risposta['xpayNonce'] != null) { // Se è settato il nonce allora procedo con il pagamento
        $MACrisposta = sha1('esito=' . $risposta['esito'] . 'idOperazione=' . $risposta['idOperazione'] . 'xpayNonce=' . $risposta['xpayNonce'] . 'timeStamp=' . $risposta['timeStamp'] . $chiaveSegreta);

        // Controllo MAC di risposta
        if ($risposta['mac'] == $MACrisposta) {

            // Controllo esito
            if ($risposta['esito'] == 'OK') {

                // Salvo il nonce per poi utilizzarlo nella fase di pagamento
                $file = fopen("dcc.txt", 'w+');
                if ($file) {                    
                    $valoriDCC = json_decode(fread($file, filesize("dcc.txt")), true);                    
                    $valoriDCC['xpayNonce'] = $risposta['xpayNonce'];                    
                    fwrite($file, json_encode($valoriDCC));
                    fclose($file);
                }       

                // Includo il file per eseguire il pagamento
                include 'pagamento.php';
            } else {
                echo 'Operazione n. ' . $risposta['idOperazione'] . ' non eseguita. esito ' . $risposta['esito'] . '<br><br>' . json_encode($risposta['errore']);
            }
        } else {
            echo 'Errore nel calcolo del MAC di risposta';
        }
    } else { // Altrimenti mostro l'html per eseguire la verifica del nonce
        $MACrisposta = sha1('esito=' . $risposta['esito'] . 'idOperazione=' . $risposta['idOperazione'] . 'timeStamp=' . $risposta['timeStamp'] . $chiaveSegreta);

        // Controllo MAC di risposta
        if ($risposta['mac'] == $MACrisposta) {

            // Controllo esito
            if ($risposta['esito'] == 'OK') {
                // Mostro l'html per la verifica del 3DS
                echo $risposta['html'];
            } else {
                echo 'Operazione n. ' . $risposta['idOperazione'] . ' non eseguita. esito ' . $risposta['esito'] . '<br><br>' . json_encode($risposta['errore']);
            }
        } else {
            echo 'Errore nel calcolo del MAC di risposta';
        }
    }
} else {
    echo 'Errore nella lettura del JSON di risposta';
}