                                            
<?php

// Incasso senza pensieri - Pagamento no Show

$connection = curl_init();

if ($connection) {

    $requestURL = "https://int-ecommerce.nexi.it/"; // URL
    $requestURI = "ecomm/api/recurring/pagamentoNoShow"; // URI

    // Parametri calcolo MAC
    $apiKey = "<ALIAS>"; // Alias fornito da Nexi
    $chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Chiave segreta fornita da Nexi  
    $numeroContratto = "contratto1";
    $codiceTransazione = "codice1";
    $importo = 1000;
    $divisa = 978;
    $scadenza = "";
    $codiceGruppo ="Gruppo1";
    $timeStamp = (time()) * 1000;

    // Calcolo MAC
    $mac = sha1('apiKey=' . $apiKey . 'numeroContratto=' . $numeroContratto . 'codiceTransazione=' . $codiceTransazione . 'importo=' . $importo . 'divisa=' . $divisa . 'scadenza=' . $scadenza .  "timeStamp=" . $timeStamp . $chiaveSegreta);

    // Parametri
    $parametri = array(
        'apiKey' => $apiKey,
        'codiceTransazione' => $codiceTransazione,
        'numeroContratto' => $numeroContratto,
        'importo' => $importo,
        'divisa' => $divisa,
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

    $json = curl_exec($connection);

    curl_close($connection);

    // Decodifico risposta
    $risposta = json_decode($json, true);

    // Controllo JSON di risposta
    if (json_last_error() === JSON_ERROR_NONE) {

        

            // Controllo esito
            if ($risposta['esito'] == 'OK') {
                echo 'Operazione n. ' . $risposta['idOperazione'] . ' eseguita';
            } else {
                echo 'Operazione n. ' . $risposta['idOperazione'] . ' non eseguita. esito ' . $risposta['esito'] . '<br><br>' . json_encode($risposta['errore']);
            }
    }
}
                    
                