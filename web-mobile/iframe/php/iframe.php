<?php

// Alias e chiave segreta
$alias = "<ALIAS>"; // Sostituire con il valore fornito da CartaSi
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da CartaSi

$requestUrl = "https://int-ecommerce.cartasi.it/ecomm/ecomm/DispatcherServlet";
$merchantServerUrl = "https://" . $_SERVER['HTTP_HOST'] . "/xpay/pagamento_semplice/codice_base/";

$codTrans = "TESTPS_" . date('YmdHis');
$divisa = "EUR";
$importo = 5000;

// Calcolo MAC
$mac = sha1('codTrans=' . $codTrans . 'divisa=' . $divisa . 'importo=' . $importo . $chiaveSegreta);

// Parametri Obbligatori
$obbligatori = array(
    'alias' => $alias,
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
    'modo_gestione_consegna' => "completo",
    'primary-color' => urlencode("#f44242"),
    'back-To-Default' => "SI",
);

$aRequestParams = array_merge($obbligatori, $facoltativi);array();
foreach ($requestParams as $param => $value) {
    $aRequestParams[] = $param . "=" . $value;
}

$stringRequestParams = implode("&", $aRequestParams);

$redirectUrl = $requestUrl . "?" . $stringRequestParams;
?>

<html>
    <head></head>
    <body style="color:orange;">
        <h1 style="text-align: center;">
            I-Frame
        </h1>
        <br>
        <br>
        <iframe style="width: 800px;height: 600px;" src="<?php echo $redirectUrl ?>">
            
        </iframe>
    </body>
</html>





