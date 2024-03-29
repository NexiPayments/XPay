                                            
<?php

// Pagamento ricorrente M.O.T.O - Pagamento successivo

// apiKey e chiave segreta - sostituire con i valori forniti da Nexi
$apiKey = "<ALIAS>";
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>";

$requestUrl = "https://int-ecommerce.nexi.it/ecomm/api/recurring/pagamentoRicorrenteMOTO";

$codTrans = "TESTPS_" . date('YmdHis'); // Codice della transazione
$importo = "5000"; // 5000 = 50,00 EURO (indicare la cifra in centesimi)
$divisa = "978"; // divisa 978 indica EUR
$scadenza = '202012'; // Scadenza della carta di credito (utilizzato per aggiornare il valore)
$numContratto = "TEST_20170706104423"; // Numero contratto precedentemente generato
$timeStamp = (time()) * 1000;

// Calcolo MAC
$mac = sha1('apiKey=' . $apiKey . 'numeroContratto=' . $numContratto . 'codiceTransazione=' . $codTrans . 'importo=' . $importo . "divisa=" . $divisa . "scadenza=" . $scadenza . "timeStamp=" . $timeStamp . $chiaveSegreta);

$requestParams = array(
    'apiKey' => $apiKey,
    'numeroContratto' => $numContratto,
    'codiceTransazione' => $codTrans,
    'importo' => $importo,
    'divisa' => $divisa,
    'timeStamp' => $timeStamp,
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
                    
                