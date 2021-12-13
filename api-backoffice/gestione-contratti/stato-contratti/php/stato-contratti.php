<?php

// Stato contratti

$connection = curl_init();

if ($connection) {

    $requestURL = "https://int-ecommerce.nexi.it/"; // URL
    $requestURI = "ecomm/api/contratti/statoContratti"; // URI
    
    // Parametri calcolo MAC
    $apiKey = "<ALIAS>"; // Alias fornito da Nexi
    $chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Chiave segreta fornita da Nexi
    $numeroContratto = 'scf23fc23'; // Numero del contratto da cercare (vuoto per elencarli tutti)
    $codiceFiscale = "";
    $dataRegistrazioneDa = "00/00/0000 00:00:00"; // formato gg/mm/aaaa hh/mm/ss (giorno/mese/anno ora:minuti:secondi)
    $dataRegistrazioneA = "00/00/0000 00:00:00"; // formato gg/mm/aaaa hh/mm/ss (giorno/mese/anno ora:minuti:secondi)
    $dataAggiornamentoDa = "00/00/0000 00:00:00"; // formato gg/mm/aaaa hh/mm/ss (giorno/mese/anno ora:minuti:secondi)
    $dataAggiornamentoA = "00/00/0000 00:00:00"; // formato gg/mm/aaaa hh/mm/ss (giorno/mese/anno ora:minuti:secondi)
    $statoAggiornamento = "";
    $timeStamp = (time()) * 1000;

    // Calcolo MAC
    $mac = sha1('apiKey=' . $apiKey . 'numeroContratto=' . $numeroContratto . 'codiceFiscale=' . $codiceFiscale . 'dataRegistrazioneDa=' . $dataRegistrazioneDa . 'dataRegistrazioneA=' . $dataRegistrazioneA . "timeStamp=" . $timeStamp . $chiaveSegreta);

    // Parametri
    $parametri = array(
        'apiKey' => $apiKey,
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

        $MACrisposta = sha1('esito=' . $risposta['esito'] . 'idOperazione=' . $risposta['idOperazione'] . 'timeStamp=' . $risposta['timeStamp'] . $chiaveSegreta);

        // Controllo MAC di risposta
        if ($risposta['mac'] == $MACrisposta) {

            // Controllo esito
            if ($risposta['esito'] == 'OK') {
                echo 'Operazione n. ' . $risposta['idOperazione'] . ' eseguita<br>';

                echo '<pre>';
                print_r($risposta['contratti']);
                echo '</pre>';
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
                    
                