                                            
<?php

// Pagamento successivo 3D Secure - Pagamento

$apiKey = "<ALIAS>"; // Alias fornito da Nexi
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Chiave segreta fornita da Nexi

$requestMac = sha1('esito=' . $_REQUEST['esito']
        . 'idOperazione=' . $_REQUEST['idOperazione']
        . 'xpayNonce=' . $_REQUEST['xpayNonce']
        . 'timeStamp=' . $_REQUEST['timeStamp']
        . $chiaveSegreta);

if ($requestMac == $_REQUEST['mac']) {
    $connection = curl_init();

    if ($connection) {

        $requestURL = "https://int-ecommerce.nexi.it/"; // URL
        $requestURI = "ecomm/api/recurring/pagamentoRicorrente3DS"; // URI

        $timeStamp = (time()) * 1000;
        $codiceTransazione = "XPAY_" . time();
        $importo = 5000;
        $divisa = 978;

        // Calcolo MAC
        $mac = sha1('apiKey=' . $apiKey
                . 'codiceTransazione=' . $codiceTransazione
                . 'importo=' . $importo
                . 'divisa=' . $divisa
                . 'xpayNonce=' . $_REQUEST['xpayNonce']
                . 'timeStamp=' . $timeStamp
                . $chiaveSegreta);

        // Parametri
        $parametri = array(
            'apiKey' => $apiKey,
            'codiceTransazione' => $codiceTransazione,
            'importo' => $importo,
            'divisa' => $divisa,
            'xpayNonce' => $_REQUEST['xpayNonce'],
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
                    echo 'Operazione n. ' . $json['idOperazione'] . ' eseguita.';
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
} else {
    echo 'Errore verifica MAC';
}
                    
                