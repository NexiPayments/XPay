                                            
<?php

// XPay Build - Primo pagamento ricorrente

$requestUrl = "https://int-ecommerce.nexi.it/ecomm/api/hostedPayments/pagaNonceCreazioneContratto";

$APIKEY = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
$CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

// Verificare il MAC che arriva dalla chiamata CreaNonce dell'SDK di Nexi
$macCreaNonce = sha1('esito=' . $_REQUEST['xpayEsito'] . 'idOperazione=' . $_REQUEST['xpayIdOperazione'] . 'xpayNonce=' . $_REQUEST['xpayNonce'] . 'timeStamp=' . $_REQUEST['xpayTimeStamp'] . $CHIAVESEGRETA);

// Se i mac sono uguali continuo con la chiamata
if ($macCreaNonce == $_REQUEST['xpayMac']) {

    // Fare la chiamata per eseguire il pagamento
    $connection = curl_init();

    if ($connection) {
        $timeStamp = ((time()) * 1000);

        // Calcolare il mac
        $mac = sha1('apiKey=' . $APIKEY . 'codiceTransazione=' . $_REQUEST['codiceTransazione'] . 'importo=' . $_REQUEST['importo'] . 'divisa=' . $_REQUEST['divisa'] . 'xpayNonce=' . $_REQUEST['xpayNonce'] . 'timeStamp=' . $timeStamp . $CHIAVESEGRETA);

        $numeroContratto = 'NUM_' . time(); // Numero del contratto con cui poi si faranno i pagamenti successivi
        $codiceGruppo = ''; // Inserire il codice gruppo assegnato da Nexi        
        
        // Parametri della chiamata        
        $requestParams = array(
            'apiKey' => $APIKEY,
            'codiceTransazione' => $_REQUEST['codiceTransazione'],
            'importo' => $_REQUEST['importo'],
            'divisa' => $_REQUEST['divisa'],
            'xpayNonce' => $_REQUEST['xpayNonce'],
            'timeStamp' => $timeStamp,
            'numeroContratto' => $numeroContratto,
            'mac' => $mac
        );

        $json = json_encode($requestParams);

        curl_setopt_array($connection, array(
            CURLOPT_URL => $requestUrl,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_RETURNTRANSFER => 1
        ));

        // Eseguire chiamata
        $jsonResponse = curl_exec($connection);

        curl_close($connection);

        $response = json_decode($jsonResponse, true);

        // Calcolare il mac
        $macCalculated = sha1('esito=' . $response['esito'] . 'idOperazione=' . $response['idOperazione'] . 'timeStamp=' . $response['timeStamp'] . $CHIAVESEGRETA);

        // Verificare il mac della risposta
        if ($macCalculated == $response['mac']) {

            // Controllo esito dell'operazione
            if ($response['esito'] == "OK") {
                echo 'La transazione ' . $codTrans . " è avvenuta con successo; codice autorizzazione: " . $response['codiceAutorizzazione'] . ' numeroContratto: ' . $numeroContratto;
            } else {
                echo 'La transazione ' . $codTrans . " è stata rifiutata; descrizione errore: " . $response['errore']['messaggio'];
            }
        } else {
            echo 'Errore MAC: ' . $macCalculated . ' non corrisponde a ' . $response['mac'];
        }
    } else {
        echo 'Connessione fallita';
    }
} else {
    echo 'Mac chiamata CreaNonce non valido';
}
                    
                