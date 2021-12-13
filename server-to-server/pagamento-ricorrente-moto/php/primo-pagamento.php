                                            
<?php

// Pagamento ricorrente M.O.T.O - Primo pagamento

// apiKey e chiave segreta
$apiKey = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

$requestUrl = "https://int-ecommerce.nexi.it/ecomm/api/recurring/primoPagamentoMOTO";

$codTrans = "TESTPS_" . date('YmdHis'); // Codice della transazione
$pan = "4000000000000002"; // Pan della carta
$scadenza = '202012'; // Scadenza della carta (Formato aaaamm)
$cvv = '123'; // CVV della carta
$importo = "5000"; // 5000 = 50,00 EURO (indicare la cifra in centesimi)
$divisa = "978"; // divisa 978 indica EUR

$numeroContratto = "TEST_" . date('YmdHis'); // Numero del contratto con cui poi si scateneranno le ricorrenze
$timeStamp = (time()) * 1000;

// Calcolo MAC
$mac = sha1('apiKey=' . $apiKey . 'numeroContratto=' . $numeroContratto . 'codiceTransazione=' . $codTrans . "importo=" . $importo . "divisa=" . $divisa . "pan=" . $pan . "cvv=" . $cvv . "scadenza=" . $scadenza . "timeStamp=" . $timeStamp . $chiaveSegreta);

$requestParams = array(
    'apiKey' => $apiKey,
    'numeroContratto' => $numeroContratto,
    'codiceTransazione' => $codTrans,
    'importo' => $importo,
    'divisa' => $divisa,
    'pan' => $pan,
    'scadenza' => $scadenza,
    'cvv' => $cvv,
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

$response = curl_exec($connection);
curl_close($connection);

$dataEsito = json_decode($response, true);

if ($dataEsito['esito'] == "OK") { // Transazione andata a buon fine
    // Calcolo MAC con i parametri di ritorno
    $macCalculated = sha1('esito=' . $dataEsito['esito'] . 'idOperazione=' . $dataEsito['idOperazione'] . 'timeStamp=' . $dataEsito['timeStamp'] . $chiaveSegreta);
    if ($macCalculated != $dataEsito['mac']) {
        echo 'Errore MAC: ' . $macCalculated . ' non corrisponde a ' . $dataEsito['mac'];
        exit;
    }

    echo 'La transazione ' . $codTrans . " è avvenuta con successo; codice autorizzazione: " . $dataEsito['codiceAutorizzazione'];
} else { // Transazione rifiutata
    echo 'La transazione ' . $codTrans . " è stata rifiutata; descrizione errore: " . $dataEsito['errore']['messaggio'];
}
                    
                