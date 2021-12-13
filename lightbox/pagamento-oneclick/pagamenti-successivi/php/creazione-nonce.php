                                            
<?php

// Pagamento successivo 3D Secure - Autenticazione

$connection = curl_init();

if ($connection) {

    $requestURL = "https://int-ecommerce.nexi.it/"; // URL
    $requestURI = "ecomm/api/recurring/creaNonceRico3DS"; // URI

    $apiKey = "<ALIAS>"; // Alias fornito da Nexi
    $chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Chiave segreta fornita da Nexi
    $codiceGruppo = "<GRUPPO>"; // Gruppo fornito da Nexi

    $timeStamp = (time()) * 1000;
    $numeroContratto = "123";
    $codiceTransazione = "XPAY_" . time();
    $importo = 5000;
    $divisa = 978;
    $urlRisposta = "https://" . $_SERVER['HTTP_HOST'] . "/pagamento.php";
    $scadenza = date('Y') . '12';
    
    // Calcolo MAC
    $mac = sha1('apiKey=' . $apiKey
            . 'numeroContratto=' . $numeroContratto
            . 'codiceTransazione=' . $codiceTransazione
            . 'importo=' . $importo
            . 'divisa=' . $divisa
            . 'codiceGruppo=' . $codiceGruppo
            . 'timeStamp=' . $timeStamp
            . $chiaveSegreta);

    // Parametri
    $parametri = array(
        'apiKey' => $apiKey,
        'numeroContratto' => $numeroContratto,
        'codiceTransazione' => $codiceTransazione,
        'importo' => $importo,
        'divisa' => $divisa,
        'urlRisposta' => $urlRisposta,
        'timeStamp' => $timeStamp,
        'mac' => $mac,
    );

    curl_setopt_array($connection, array(
        CURLOPT_URL => $requestURL . $requestURI,
        CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => json_encode($parametri),
        CURLOPT_RETURNTRANSFER => 1,
        CURLINFO_HEADER_OUT => true,
        CURLOPT_SSL_VERIFYPEER => 0
    ));

    $risposta = curl_exec($connection);

    curl_close($connection);

    // Decodifico risposta
    $json = json_decode($risposta, true);

    // Controllo JSON di risposta
    if (json_last_error() === JSON_ERROR_NONE) {

        $MACrisposta = sha1('esito=' . $json['esito'] . 'idOperazione=' . $json['idOperazione'] . 'timeStamp=' . $json['timeStamp'] . $chiaveSegreta);

        // Controllo MAC di risposta
        if ($json['mac'] == $MACrisposta) {

            // Controllo esito
            if ($json['esito'] == 'OK') {
                echo $json['html'];
            } else {
                echo 'Operazione n. ' . $json['idOperazione'] . ' non eseguita. esito ' . $json['esito'] . '<br><br>' . json_encode($json['errore']);
            }
        } else {
            echo 'Errore nel calcolo del MAC di risposta';
        }
    } else {
        echo 'Errore nella lettura del JSON di risposta';
    }
} else {
    echo "Errore curl";
}
                    
                