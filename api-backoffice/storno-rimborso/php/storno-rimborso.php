<?php

$connection = curl_init();

if ($connection) {
    
    $requestURL = "https://int-ecommerce.nexi.it/"; // URL
    $requestURI = "ecomm/api/bo/storna"; // URI
    
    // Parametri per calcolo MAC
    $apiKey = "<ALIAS>"; // Alias fornito da Nexi
    $chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Chiave segreta fornita da Nexi
    $codiceTransazione = "dib3187bv8734"; // Codice della transazione da stornare
    $importo = 5000; // 5000 = 50,00 EURO (indicare la cifra in centesimi)
    $divisa = "978"; // divisa 978 indica EUR
    $timeStamp = (time()) * 1000;

    // Calcolo MAC
    $mac = sha1('apiKey=' . $apiKey . 'codiceTransazione=' . $codiceTransazione . 'divisa=' . $divisa . 'importo=' . $importo . 'timeStamp=' . $timeStamp . $chiaveSegreta);

    // Parametri
    $parametri = array(
        // Obbligatori
        'apiKey' => $apiKey,
        'codiceTransazione' => $codiceTransazione,
        'importo' => $importo,
        'divisa' => $divisa,
        'timeStamp' => (string) $timeStamp,
        'mac' => $mac,
        // Facoltativi
        //'idContabParzialePayPal' => $idContabParzialePayPal
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

    $json = curl_exec($connection);

    curl_close($connection);

    // Decodifico risposta
    $risposta = json_decode($json, true);

    // Controllo JSON di risposta
    if (json_last_error() === JSON_ERROR_NONE) {

        $MACrisposta = sha1('esito=' . $risposta['esito'] . 'idOperazione=' . $risposta['idOperazione'] . 'timeStamp=' . $risposta['timeStamp'] . $chiaveSegreta);

        // Controllo MAC di risposta
        if ($risposta['mac'] == $MACrisposta) {

            // Controllo esito
            if ($risposta['esito'] == 'OK') {
                echo 'Operazione n. ' . $risposta['idOperazione'] . ' eseguita';
            } else {
                echo 'Operazione n. ' . $risposta['idOperazione'] . ' non eseguita. esito ' . $risposta['esito'] . '<br><br>' . json_encode($risposta['errore']);
            }
        } else {
            echo 'Errore nel calcolo del MAC di risposta';
        }
    } else {
        echo 'Errore nella lettura del JSON di risposta';
    }
} else {
    echo "Impossibile connettersi!";
}

