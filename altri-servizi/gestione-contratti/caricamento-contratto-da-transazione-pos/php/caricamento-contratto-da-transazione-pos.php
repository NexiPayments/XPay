<?php

$connection = curl_init();

if ($connection) {

    $requestURL = "https://int-ecommerce.cartasi.it/"; // URL
    $requestURI = "ecomm/api/contratti/creazioneDaPosFisico"; // URI
    
    // Parametri calcolo MAC
    $apiKey = "<ALIAS>"; // Alias fornito da CartaSi
    $chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Chiave segreta fornita da CartaSi

    $numeroContratto = 'scf23fc23'; // Numero del contratto da abilitare
    $idPOSFisico = "<ID>"; // ID del POS fisico
    $codiceAutorizzazione = "<CODICE_AUTORIZZAZIONE>"; // Codice dia autorizzazione della transazione
    $importo = 5000; // 5000 = 50,00 EURO (indicare la cifra in centesimi)

    $stan = "";
    $descrizione = "";
    $mail = "";

    $timeStamp = (time()) * 1000;

    // Calcolo MAC
    $mac = sha1("apiKey=" . $apiKey . "numeroContratto=" . $numeroContratto . "idPOSFisico=" . $idPOSFisico . "codiceAutorizzazione=" . $codiceAutorizzazione . "stan=" . $stan . "importo=" . $importo . "descrizione=" . $descrizione . "mail=" . $mail . "timeStamp=" . $timeStamp . $chiaveSegreta);

    // Oggetto contratto
    $contratto = array(
        'numeroContratto' => $numeroContratto,
        'idPOSFisico' => $idPOSFisico,
        'codiceAutorizzazione' => $codiceAutorizzazione,
        'importo' => $importo,
        'stan' => $stan,
        'descrizione' => $descrizione,
        'mail' => $mail
    );

    // Parametri
    $parametri = array(
        'apiKey' => $apiKey,
        'timeStamp' => (string) $timeStamp,
        'contratto' => $contratto,
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