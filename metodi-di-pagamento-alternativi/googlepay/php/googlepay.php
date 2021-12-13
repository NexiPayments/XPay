                                            
<?php

// Google Pay - Integrazione tramite API

$connection = curl_init();

if ($connection) {
    $apiKey = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
    $chiaveSegreta = "<CHIAVESEGRETA>"; // Sostituire con il valore fornito da Nexi

    $requestUrl = "https://int-ecommerce.nexi.it/";
    $requestUri = "ecomm/api/paga/googlePay";

    $codTrans = "TESTAP_" . date('YmdHis'); // Codice della transazione
    $importo = "5000"; // 5000 = 50,00 EURO (indicare la cifra in centesimi)
    $divisa = "978"; // divisa 978 indica EUR
    $timeStamp = (time()) * 1000;

    // Calcolo MAC
    $mac = sha1('apiKey=' . $apiKey . 'codiceTransazione=' . $codTrans . 'importo=' . $importo . "divisa=" . $divisa . "timeStamp=" . $timeStamp . $chiaveSegreta);

    $parametri = array(
        'apiKey' => $apiKey,
        'codiceTransazione' => $codTrans,
        'importo' => $importo,
        'divisa' => $divisa,
        'googlePay' => $jsonGoogle,
        'timeStamp' => (string) $timeStamp,
        'mac' => $mac
    );

    curl_setopt($connection, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($connection, CURLOPT_URL, $requestUrl . $requestUri);
    curl_setopt($connection, CURLOPT_POST, 1);
    curl_setopt($connection, CURLOPT_POSTFIELDS, json_encode($parametri));
    curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($connection, CURLINFO_HEADER_OUT, true);

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
                echo 'La transazione ' . $codTrans . " è avvenuta con successo; codice autorizzazione: " . $risposta['codiceAutorizzazione'];
            } else {
                echo 'La transazione ' . $codTrans . " è stata rifiutata; descrizione errore: " . $risposta['errore']['messaggio'];
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
                    
                