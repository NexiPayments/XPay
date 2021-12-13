                                            
<?php

// Warning

$connection = curl_init();

if ($connection) {

    $requestURL = "https://int-ecommerce.nexi.it/"; // URL
    $requestURI = "ecomm/api/bo/warning"; // URI
    
    // Parametri calcolo MAC
    $ALIAS = '<ALIAS>'; // Sostituire con il valore fornito da Nexi
    $CHIAVESEGRETA = '<CHIAVE SEGRETA PER CALCOLO MAC>'; // Sostituire con il valore fornito da Nexi
    $codiceTransazione = ""; // Codice della transazione da incassare 
    $dataTransazioneDal = "gg/mm/aaaa hh:mm:ss "; //Ricerca data transazione da
    $dataTransazioneAl = "gg/mm/aaaa hh:mm:ss "; //Ricerca data transazione da
    $timeStamp = (time()) * 1000;

    // Calcolo MAC
    $mac = sha1('apiKey=' . $apiKey . "timeStamp=" . $timeStamp . $chiaveSegreta);

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

        

            // Controllo esito
            if ($risposta['esito'] == 'OK') {
                print_r($risposta['warnings']);
            } else {
                echo 'Operazione n. ' . $risposta['idOperazione'] . ' non eseguita. esito ' . $risposta['esito'] . '<br><br>' . json_encode($risposta['errore']);
            }
        
        }
         else 
        {
        echo 'Errore nella lettura del JSON di risposta';
    }
    } else 
    {
    echo "Errore curl";
}
                    
                