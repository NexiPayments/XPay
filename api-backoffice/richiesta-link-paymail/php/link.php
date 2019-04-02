<?php

$connection = curl_init();

if ($connection) {

    $requestURL = "https://int-ecommerce.nexi.it/"; // URL
    $requestURI = "ecomm/api/bo/richiestaPayMail"; // URI
    
    // Parametri per calcolo MAC
    $apiKey = "<ALIAS>"; // Alias fornito da Nexi
    $chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Chiave segreta fornita da Nexi
    $codiceTransazione = "APIBO_" . date('YmdHis'); // Codice della transazione
    $importo = 5000; // 5000 = 50,00 EURO (indicare la cifra in centesimi)
    $timeout = 4; // Durata in ore del link di pagamento che verrà generato 
    $url = "https://" . filter_input(INPUT_SERVER, 'HTTP_HOST') . "/esito.php"; // URL dove viene rimandato il cliente al termine del pagamento (prefisso necessario http:// oppure https://)
    $timeStamp = (time()) * 1000;

    // Calcolo MAC
    $mac = sha1("apiKey=" . $apiKey . "codiceTransazione=" . $codiceTransazione . "importo=" . $importo . "timeStamp=" . $timeStamp . $chiaveSegreta);

    // Parametri
    $parametri = array(
        // Obbligatori
        'apiKey' => $apiKey,
        'importo' => $importo,
        'timeout' => $timeout,
        'codiceTransazione' => $codiceTransazione,
        'url' => $url,
        'mac' => $mac,
        'timeStamp' => $timeStamp
            // Facoltativi
            /* 'parametriAggiuntivi' => array(
              'mail' => "mail@cliente.it",
              'languageId' => "ITA",
              'descrizione' => "Prova di pagamento",
              'session_id' => session_id(),
              'Note1' => "NOTA 1",
              'Note2' => "NOTA 2",
              'Note3' => "NOTA 3",
              'OPTION_CF' => "RSSMRA74D22A001Q",
              'selectedcard' => "VISA",
              'TCONTAB' => "D",
              'infoc' => "Info su pagamento per compagnia",
              'infob' => "Info su pagamento per banca",
              'modo_gestione_consegna' => "completo"
              ) */
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
                echo 'Operazione n. ' . $risposta['idOperazione'] . ' eseguita<br>';
                echo "Link generato correttamente: " . $risposta['payMailUrl'] . "<br>";
                echo "<a href ='" . $risposta['payMailUrl'] . "'>VAI AL LINK</a>";
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