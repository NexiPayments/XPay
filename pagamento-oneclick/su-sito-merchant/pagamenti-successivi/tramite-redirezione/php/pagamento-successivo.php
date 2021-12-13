                                            
<?php

// Pagamento OneClik - Pagamenti successivi - Tramite redirezione - Avvio pagamento

$requestUrl = "https://int-ecommerce.nexi.it/ecomm/ecomm/DispatcherServlet";
$merchantServerUrl = "https://" . $_SERVER['HTTP_HOST'] . "/xpay/php/pagamento_semplice/one_click/";

//PARAMETRI PER CALCOLO MAC
$codTrans = "TESTPS_" . date('YmdHis');
$importo = 5000; /* <-- 5000 = 50,00 EURO (prime due cifre a destra per i centesimi) */
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>";
$divisa = "EUR"; /* <-- EUR oppure 978 */
$numContratto = "NC_TEST_" . date('YmdHis');

//CALCOLO MAC
$mac = sha1('codTrans=' . $codTrans . 'divisa=' . $divisa . 'importo=' . $importo . $chiaveSegreta);

//Param Obbligatori
$requestParams = array(
    'importo' => $importo,
    'alias' => "<ALIAS>",
    'divisa' => $divisa,
    'codTrans' => $codTrans,
    'mac' => $mac,
    'url' => "<URL DI RITORNO DOPO AVER COMPLETATO IL PAGAMENTO>", //necessita HTTP:// oppure HTTPS://
    'url_back' => "<URL DI RITORNO (pagina iniziale)", //necessita HTTP:// oppure HTTPS://
    'urlpost' => "<URL NOTIFICA POST ESITO TRANSAZIONE>", //necessita HTTP:// oppure HTTPS://
    'num_contratto' => $numContratto,
    'tipo_servizio' => 'paga_oc3d',
    'tipo_richiesta' => 'PR', /* <-- PR = Pagamento Ricorrente */
);

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
                    
                