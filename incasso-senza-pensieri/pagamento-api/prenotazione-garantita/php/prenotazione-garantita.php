                                            
<?php

// Incasso senza Pensieri - Prenotazione Garantita con creazione nonce

$apiKey = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi
$requestUrl = "https://int-ecommerce.nexi.it/ecomm/api/vas/ig/creaNonce";
$merchantServerUrl = "https://" . $_SERVER['HTTP_HOST'] . "/xpay/S2S/payment/";
$codTrans = "TESTPS_" . date('YmdHis'); // Codice della transazione
$timeStamp = (time()) * 1000;

$codicePrenotazione = "PRENOTAZIONE_" . date('YmdHis');;
$codiceStruttura = "struttura1";
$tipoPrenotazione = "GARANTITA";
$flagFormatoData = "true";
$dataPrenotazione = "28/02/2021";
$dataFinePrenotazione = "30/03/2021";
$dataCancellazione = "25/03/2021 12:20";
$importoNoShow = "500";
$importoPrenotazione = "1000";


// Calcolo MAC
$mac1 = sha1('apiKey=' . $apiKey . 'codiceTransazione=' . $codTrans . "tipoPrenotazione=" . $tipoPrenotazione . 'dataPrenotazione=' . $dataPrenotazione  . 'importoNoShow=' . $importoNoShow . "timeStamp=" . $timeStamp . $chiaveSegreta);

$requestParams = array(
	'apiKey' => $apiKey,
	'codiceTransazione' => $codTrans,
	'incassoGarantito' => array(	  
	    'codiceStruttura' => $codiceStruttura,	    	    
	    'tipoPrenotazione' => $tipoPrenotazione,
	    'flagFormatoData' => $flagFormatoData,
	    'dataPrenotazione' => $dataPrenotazione,
	    'dataFinePrenotazione' => $dataFinePrenotazione,
		'dataCancellazione' => $dataCancellazione,  
	    'importoNoShow' => $importoNoShow,
        'importoPrenotazione' => $importoPrenotazione,
	),
	'timeStamp' => $timeStamp,
	'mac' => $mac1,
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

$data3DS = json_decode($response, true);

if ($data3DS['esito'] == "OK") {
    echo "Creazione nonce andata a buon fine";
} else {
    echo "Errore durante la verifica 3D-Secure. " . $data3DS['errore']['messaggio'];
    exit;
}

// Pagamento

$requestUrl = "https://int-ecommerce.nexi.it/ecomm/ecomm/DispatcherIG";
$merchantServerUrl = "https:/". "/xpay/php/pagamento_semplice/codice_base/";

$divisa = "EUR";
$importo = 0;
$dl_nonce= $data3DS['xpayNonce'];
$dl_tipoprenotazione = "GARANTITA";

// Calcolo MAC
$mac2 = sha1('codTrans=' . $codTrans . 'divisa=' . $divisa . 'importo=' . $importo . 'importoNoShow=' . $importoNoShow . $chiaveSegreta);

// Calcolo MAC incasso senza pensieri
$dl_mac = sha1('codTrans=' . $codTrans . 'dl_nonce=' . $dl_nonce . 'importoNoShow=' . $importoNoShow . $chiaveSegreta);

// Parametri obbligatori
$obbligatori = array(
    'alias' => $apiKey,
    'importo' => $importo,
    'divisa' => $divisa,
    'codTrans' => $codTrans,
    'url' => $merchantServerUrl . "esito.php",
    'url_back' => $merchantServerUrl . "annullo.php",
    'mac' => $mac2,
    "dl_nonce" => $dl_nonce,
    "dl_mac" => $dl_mac,
    'importoNoShow' => $importoNoShow,
);

// Parametri facoltativi
$facoltativi = array(
  
);

$requestParams = array_merge($obbligatori, $facoltativi);

$aRequestParams = array();
foreach ($requestParams as $param => $value) {
    $aRequestParams[] = $param . "=" . $value;
}

$stringRequestParams = implode("&", $aRequestParams);

$redirectUrl = $requestUrl . "?" . $stringRequestParams;
?>

<html>
    <head></head>
    <body>
        <a href="<?php echo $redirectUrl ?>">
            <button>VAI ALLA PAGINA DI CASSA</button>
        </a>
    </body>
</html>
                    
                