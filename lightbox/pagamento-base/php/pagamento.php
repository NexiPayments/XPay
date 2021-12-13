                                            
<?php

// Lightbox - Pagamento

$APIKEY = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
$CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

$importo = 1000; // Importo della transazione da esprimere in centesimi
$codiceTransazione = "codice_" . time(); // Codice univoco della transazione
$divisa = 'EUR';
$timestamp = time() * 1000;
$mac = sha1("codTrans=" . $codiceTransazione . "divisa=" . $divisa . "importo=" . $importo . $CHIAVESEGRETA);
$urlPost = "https://www.merchant-server.it/build/urlPost.php";

?>

<html>
    <head>
        <script src="https://int-ecommerce.nexi.it/ecomm/XPayBuild/js?alias=<?php echo $APIKEY; ?>"></script>
    </head>
    <body>
        <button id="btnPaga">Paga</button>

        <script type='text/javascript'>
            window.addEventListener('load', function () {
                // Inizializzazione SDK
                XPay.init();

                // Oggetto contenente la configurazione del pagamento
                var config = {
                    baseConfig: {
                        apiKey: '<?php echo $APIKEY; ?>',
                        enviroment: XPay.Environments.INTEG
                    },
                    paymentParams: {
                        amount: '<?php echo $importo; ?>',
                        transactionId: '<?php echo $codiceTransazione; ?>',
                        currency: '<?php echo $divisa; ?>',
                        timeStamp: '<?php echo $timestamp; ?>',
                        mac: '<?php echo $mac; ?>',
                        //urlPost: '<?php $urlPost; ?>'
                    },
                    customParams: {},
                    language: XPay.LANGUAGE.ITA
                };
                
                // Configurazione lightbox
                XPay.initLightbox(config);
            });

            document.getElementById('btnPaga').addEventListener('click', function (e) {
                // Avvio del pagamento
                XPay.openLightbox();
            });

            window.addEventListener("XPay_Payment_Result", function (event) {
                alert(event.detail.messaggio);
            });
        </script>
    </body>
</html>
                    
                