                                            
<?php

// Incasso senza Pensieri - Caricamento Termini e condizioni

$connection = curl_init();

if ($connection) {

    $requestURL = "https://int-ecommerce.nexi.it/"; // URL
    $requestURI = "ecomm/api/vas/ig/caricaTermCond"; // URI
    
    // Parametri calcolo MAC
    $apiKey = "<ALIAS>"; // Alias fornito da Nexi
    $chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Chiave segreta fornita da Nexi
    $tipo = "GARANTITA";
    $codice = "Termini1";
    $descrizione = "Termini 1";
    $testi = "Nuovi termini";
    $timeStamp = (time()) * 1000;

    // Calcolo MAC
    $mac = sha1('apiKey=' . $apiKey . "timeStamp=" . $timeStamp . $chiaveSegreta);

    // Parametri
    $parametri = array(
        'apiKey' => $apiKey,
        'tipo' => $tipo,
        'codice' => $codice,
        'descrizione' => $descrizione,
        'testi' => array(
            'ITA' => "tetso in italiano",
            'ENG' => "testo in inglese",
            'SPA' => "testo in spagnolo",
            'FRA' => "testo in francese",
            'GER' => "testo in germanico",
            'JPN' => "testo in giapponese",
            'CHI' => "testo in chievo",
            'ARA' => "testo in aramaico",
            'RUS' => "testo in russo",
            'POR' => "testo in portoghese",
        ),        
        'timeStamp' => $timeStamp,
        'mac' => $mac
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
                    
                