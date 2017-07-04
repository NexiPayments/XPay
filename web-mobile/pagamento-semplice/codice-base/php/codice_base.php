<?php

// Alias e chiave segreta 
$ALIAS = '<ALIAS>'; // Sostituire con il valore fornito da CartaSi
$CHIAVESEGRETA = '<CHIAVE SEGRETA PER CALCOLO MAC>'; // Sostituire con il valore fornito da CartaSi

$requestUrl = "https://int-ecommerce.cartasi.it/ecomm/ecomm/DispatcherServlet";
$merchantServerUrl = "https://" . $_SERVER['HTTP_HOST'] . "/xpay/php/pagamento_semplice/codice_base/";

$codTrans = "TESTPS_" . date('YmdHis');
$divisa = "EUR";
$importo = 5000;

// Calcolo MAC
$mac = sha1('codTrans=' . $codTrans . 'divisa=' . $divisa . 'importo=' . $importo . $CHIAVESEGRETA);

// Parametri obbligatori
$obbligatori = array(
    'alias' => $ALIAS,
    'importo' => $importo,
    'divisa' => $divisa,
    'codTrans' => $codTrans,
    'url' => $merchantServerUrl . "esito.php",
    'url_back' => $merchantServerUrl . "annullo.php",
    'mac' => $mac,
    'urlpost' => $merchantServerUrl . "notifica.php"    
);

// Parametri facoltativi
$facoltativi = array(
    'mail' => "mail@cliente.it",
    'languageId' => "ITA",
    'descrizione' => "Prova di pagamento",
    'session_id' => session_id(),
    'Note1' => "NOTA 1",
    'Note2' => "NOTA 2",
    'Note3' => "NOTA 3",
    'OPTION_CF' => "RSSMRA74D22A001Q",
    'selectedcard' => "VISA",
    'TCONTAB' => "D",
    'infoc' => "Info su pagamento per compagnia",
    'infob' => "Info su pagamento per banca",
    'modo_gestione_consegna' => "completo"
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





