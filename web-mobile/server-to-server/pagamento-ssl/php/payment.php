<?php

// apiKey e chiave segreta
$apiKey = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

$requestUrl = "https://int-ecommerce.nexi.it/ecomm/api/paga/pagaSSL";

$codTrans = "TESTPS_" . date('YmdHis'); // Codice della transazione
$importo = "5000"; // 5000 = 50,00 EURO (indicare la cifra in centesimi)
$divisa = "978"; // divisa 978 indica EUR
$pan = "4000000000000002"; // Pan della carta
$scadenza = '202012'; // Scadenza della carta (Formato aaaamm)
$cvv = '123'; // CVV della carta
$timeStamp = (time()) * 1000;

// Calcolo MAC
$mac = sha1('apiKey=' . $apiKey . 'codiceTransazione=' . $codTrans . "pan=" . $pan . "scadenza=" . $scadenza . "cvv=" . $cvv . 'importo=' . $importo . "divisa=" . $divisa . "timeStamp=" . $timeStamp . $chiaveSegreta);

$requestParams = array(
    'apiKey' => $apiKey,
    'codiceTransazione' => $codTrans,
    'importo' => $importo,
    'divisa' => $divisa,
    'pan' => $pan,
    'scadenza' => $scadenza,
    'cvv' => $cvv,
    'mail' => "cardHolder@mail.it",
    'nome' => 'Mario',
    'cognome' => 'Rossi',
    'parametriAggiuntivi' => array(
        'mail' => "cardHolder@mail.it",
        'descrizione' => "descrizione",
        'note1' => "note"
    ),
    'timeStamp' => (string) $timeStamp,
    'mac' => $mac
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

$response = curl_exec($connection);
curl_close($connection);

$dataVerifica = json_decode($response, true);

if ($dataVerifica['esito'] == "OK") { // Transazione andata a buon fine
    // Calcolo MAC con i parametri di ritorno
    $macCalculated = sha1('esito=' . $dataVerifica['esito'] . 'idOperazione=' . $dataVerifica['idOperazione'] . 'timeStamp=' . $dataVerifica['timeStamp'] . $chiaveSegreta);
    if ($macCalculated != $dataVerifica['mac']) {
        echo 'Errore MAC: ' . $macCalculated . ' non corrisponde a ' . $dataVerifica['mac'];
        exit;
    }

    echo 'La transazione ' . $codTrans . " è avvenuta con successo; codice autorizzazione: " . $dataVerifica['codiceAutorizzazione'];
} else { // Transazione rifiutata
    echo 'La transazione ' . $codTrans . " è stata rifiutata; descrizione errore: " . $dataVerifica['errore']['messaggio'];
}