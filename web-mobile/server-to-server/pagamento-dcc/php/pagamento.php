<?php

// apiKey e chiave segreta
$apiKey = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

$requestUrl = "https://int-ecommerce.nexi.it/" . "ecomm/api/etc/pagaDCC";

// Recupero i dati salvati nelle fasi precedenti
$file = fopen("dcc.txt", "r");
if ($file) {
    $datiSalvati = json_decode(fread($file, filesize("dcc.txt")), true);
    fclose($file);
}

// Se è settato un nonce allora lo imposto altrimenti lo prende dalla seconda fase
if ($_REQUEST['xpayNonce']) {
    $nonce = $_REQUEST['xpayNonce'];
} else {
    $nonce = $datiSalvati['xpayNonce'];
}

$importo = "5000"; // 5000 = 50,00 EURO (indicare la cifra in centesimi)
$divisa = "EUR";

$codiceTransazione = "sdb3vre" . date('YmdHi'); // Codice della transazione

$xpayNonce = $nonce; // Nonce per effettuare il pagamento
// I seguenti valori vengono recuperati nella prima fase del DCC
$ticket = $datiSalvati['ticket'];
$importoDCC = $datiSalvati['importoDCC'];
$divisaDCC = $datiSalvati['divisaDCC'];

$tassoDiCambioAccettato = 'SI'; // Valorizzare SI o No se si accetta o meno il cambio proposto
$pan = "4000000000000101"; // Pan della carta
$scadenza = "203012"; // Scadenza carta mm/aa
$cvv = "123"; // CVV della carta

$timeStamp = (time()) * 1000;

// Calcolo MAC
$mac = sha1('apiKey=' . $apiKey . 'codiceTransazione=' . $codiceTransazione . "ticket=" . $ticket . "tassoDiCambioAccettato=" . $tassoDiCambioAccettato . "timeStamp=" . $timeStamp . $chiaveSegreta);

// Parametri
$requestParams = array(
    'apiKey' => $apiKey,
    'ticket' => $ticket,
    //'pan' => $pan,
    'scadenza' => $scadenza,
    'cvv' => $cvv,
    'xpayNonce' => $xpayNonce,
    'importo' => $importo,
    'divisa' => $divisa,
    'codiceTransazione' => $codiceTransazione,
    'importoDCC' => $importoDCC,
    'divisaDCC' => $divisaDCC,
    'tassoDiCambioAccettato' => $tassoDiCambioAccettato,
    'timeStamp' => (string) $timeStamp,
    'mac' => $mac,
);

$json = json_encode($requestParams);

$connection = curl_init();

if ($connection == false) {
    echo "connessione fallita!";
    exit;
}

curl_setopt($connection, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($connection, CURLOPT_URL, $requestUrl);
curl_setopt($connection, CURLOPT_POST, 1);
curl_setopt($connection, CURLOPT_POSTFIELDS, $json);
curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($connection, CURLINFO_HEADER_OUT, true);
curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);

$response = curl_exec($connection);

curl_close($connection);

$risposta = json_decode($response, true);

// Controllo JSON di risposta
if (json_last_error() === JSON_ERROR_NONE) {

    $MACrisposta = sha1('esito=' . $risposta['esito'] . 'idOperazione=' . $risposta['idOperazione'] . 'timeStamp=' . $risposta['timeStamp'] . $chiaveSegreta);

    // Controllo MAC di risposta
    if ($risposta['mac'] == $MACrisposta) {

        // Controllo esito
        if ($risposta['esito'] == 'OK') {
            echo 'Operazione n. ' . $risposta['idOperazione'] . ' eseguita con successo';
        } else {
            echo 'Operazione n. ' . $risposta['idOperazione'] . ' non eseguita. esito ' . $risposta['esito'] . '<br><br>' . json_encode($risposta['errore']);
        }
    } else {
        echo 'Errore nel calcolo del MAC di risposta';
    }
} else {
    echo 'Errore nella lettura del JSON di risposta';
}
