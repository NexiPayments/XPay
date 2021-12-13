                                            
<?php

// Pagamento ricorrente - Primo pagamento - Avvio pagamento

// Alias e chiave segreta
$ALIAS = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
$CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

$requestUrl = "https://int-ecommerce.nexi.it/ecomm/ecomm/DispatcherServlet";
$merchantServerUrl = "https://" . $_SERVER['HTTP_HOST'] . "/xpay/php/pagamento_semplice/recurring/";

$codTrans = "TESTPS_" . date('YmdHis');
$divisa = "EUR";
$importo = 5000;

// Calcolo MAC
$mac = sha1('codTrans=' . $codTrans . 'divisa=' . $divisa . 'importo=' . $importo . $CHIAVESEGRETA);

// Viene creato il numero contratto e viene richiesto alla pagina di cassa un nuovo pagamento
$numContratto = "NC_TEST_" . date('YmdHis');
$tipoRichiesta = 'PP';

// Parametri obbligatori
$obbligatori = array(
    'alias' => $ALIAS,
    'importo' => $importo,
    'divisa' => $divisa,
    'codTrans' => $codTrans,
    'url' => $merchantServerUrl . "esito.php",
    'url_back' => $merchantServerUrl . "annullo.php",
    'mac' => $mac,
    'num_contratto' => $numContratto,
    'tipo_servizio' => 'paga_multi',
    'tipo_richiesta' => $tipoRichiesta
);

// Parametri facoltativi
$facoltativi = array(    
);

$requestParams = array_merge($obbligatori, $facoltativi);

?>

<html>
    <head></head>
    <body>
        <form method='POST' action='<?php echo $requestUrl ?>'>
            <?php foreach ($requestParams as $name => $value) { ?>
                <input type='hidden' name='<?php echo $name; ?>' value='<?php echo htmlentities($value); ?>' />
            <?php } ?>
            
            <input type='submit' value='VAI ALLA PAGINA DI CASSA' />
        </form>
    </body>
</html>
                    
                