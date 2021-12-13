                                            
<?php

// Incasso senza Pensieri - Prepagato non Rimborsabile

$apiKey = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi
$codTrans = "TESTPS_" . date('YmdHis'); // Codice della transazione
$requestUrl = "https://int-ecommerce.nexi.it/ecomm/ecomm/DispatcherIG";
$merchantServerUrl = "https:/". "/xpay/php/pagamento_semplice/codice_base/";
$divisa = "EUR";
$importo = 1000;

$dl_tipoprenotazione = "NONRIMBORSABILE";
$dl_codicestruttura = "struttura1";
$dl_dataprenotazione = "04/03/2021";
$dl_datafineprenotazione = "12/04/2021";
$dl_formatodata = "PERIODO";

// Calcolo MAC
$mac = sha1('codTrans=' . $codTrans . 'divisa=' . $divisa . 'importo=' . $importo . $chiaveSegreta);

// Calcolo MAC Incasso senza Pensieri
$dl_mac = sha1('codTrans=' . $codTrans . 'dl_tipoprenotazione=' . $dl_tipoprenotazione  . 'dl_dataprenotazione=' . $dl_dataprenotazione . $dl_datafineprenotazione . $chiaveSegreta);

// Parametri obbligatori
$obbligatori = array(
    'alias' => $apiKey,
    'importo' => $importo,
    'divisa' => $divisa,
    'codTrans' => $codTrans,
    'url' => $merchantServerUrl . "esito.php",
    'url_back' => $merchantServerUrl . "annullo.php",
    'mac' => $mac,
    'dl_tipoprenotazione'=> $dl_tipoprenotazione,
    'dl_codicestruttura'=> $dl_codicestruttura,
    'dl_dataprenotazione'=> $dl_dataprenotazione,
    'dl_datafineprenotazione'=> $dl_datafineprenotazione,
    'dl_formatodata'=> $dl_formatodata,  
    'dl_mac'=> $dl_mac,    
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
                    
                