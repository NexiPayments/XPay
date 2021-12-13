                                            
<?php

// XPay Build - Verifica carta 3D Secure - Form raccolta dati carta

$APIKEY = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
$CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

$importo = 1000; // Importo della transazione da esprimere in centesimi
$codiceTransazione = "codice_" . time(); // Codice univoco della transazione
$divisa = 978;
$timestamp = time() * 1000;
$mac = sha1("codTrans=" . $codiceTransazione . "divisa=" . $divisa . "importo=" . $importo . $CHIAVESEGRETA);
$url = "https://www.merchant-server.it/build/url.php";
$urlBack = "https://www.merchant-server.it/build/urlBack.php";
$urlPost = "https://www.merchant-server.it/build/urlPost.php";
?>

<html>

    <head>

        <!-- Inclusione SDK XPay -->
        <script src="https://int-ecommerce.nexi.it/ecomm/XPayBuild/js?alias=<?php echo $APIKEY; ?>"></script>

        <script type='text/javascript'>

            // Dichiarazione variabili            
            var apiKey = "<?php echo $APIKEY; ?>";
            var importo = <?php echo $importo; ?>;
            var codiceTransazione = "<?php echo $codiceTransazione; ?>";
            var divisa = "<?php echo $divisa; ?>";
            var timeStamp = "<?php echo $timestamp; ?>";
            var mac = "<?php echo $mac; ?>";
            var url = "<?php echo $url; ?>";
            var urlBack = "<?php echo $urlBack; ?>";
            var urlPost = "<?php echo $urlPost; ?>";

            window.addEventListener('load', function () {

                // Inizializzazione SDK
                XPay.init();

                // Configurazione del pagamento
                var config = {
                    baseConfig: {
                        apiKey: apiKey,
                        enviroment: XPay.Environments.INTEG
                    },
                    paymentParams: {
                        amount: importo,
                        transactionId: codiceTransazione,
                        currency: divisa,
                        timeStamp: timeStamp,
                        mac: mac,
                        url: url,
                        urlBack: urlBack,
                    },
                    customParams: {
                    },
                    language: XPay.LANGUAGE.ITA,
					requestType: "VC",
                 
                };

                // Configurazione SDK
                XPay.setConfig(config);
                
                // 3D Secure 2.1
                XPay.setInformazioniSicurezza({});

                // Configurazione dello stile per il form dei dati carta
                var style = {
                };

                // Creazione dell'elemento carta
                var card = XPay.create(XPay.OPERATION_TYPES.CARD, style);
                card.mount("xpay-card");

                // Creazione dei bottoni per i metodi di pagamento disponibili per il proprio profilo
                var buttons = XPay.create(XPay.OPERATION_TYPES.PAYMENT_BUTTON);
                buttons.mount("xpay-btn");

                // Handler per gestire click sul bottone paga (fa la chiamata per generare il nonce)
                document.getElementById('pagaBtn').addEventListener("click", function (e) {
                    e.preventDefault();
                    this.disabled = true;

                    // Creazione del nonce
                    XPay.createNonce("payment-form", card);
                });
            });

            // Handler per la gestione degli errori di validazione carta
            window.addEventListener("XPay_Card_Error", function (event) {
                var displayError = document.getElementById('xpay-card-errors');

                if (event.detail.errorMessage) // Visualizzo il messaggio di errore                    
                    displayError.innerHTML = event.detail.errorMessage;
                else // Nessun errore nascondo eventuali messaggi rimasti                    
                    displayError.textContent = '';
            });

            // Handler per ricevere nonce pagamento
            window.addEventListener("XPay_Nonce", function (event) {
                var response = event.detail;

                if (response.esito && response.esito === "OK") {
                    document.getElementById("xpayNonce").setAttribute("value", response.xpayNonce);
                    document.getElementById("xpayIdOperazione").setAttribute("value", response.idOperazione);
                    document.getElementById("xpayTimeStamp").setAttribute("value", response.timeStamp);
                    document.getElementById("xpayEsito").setAttribute("value", response.esito);
                    document.getElementById("xpayMac").setAttribute("value", response.mac);

                    // Submit del form contenente il nonce verso il server del merchant
                    document.getElementById('payment-form').submit();
                } else {
                    // Visualizzazione errore creazione nonce e ripristino bottone form
                    var displayError = document.getElementById('xpay-card-errors');

                    displayError.textContent = "[" + response.errore.codice + "] " + response.errore.messaggio;

                    document.getElementById('pagaBtn').disabled = false;
                }
            });

            window.addEventListener("XPay_Ready", function (event) {
                console.log('Build ' + event.detail + ' è caricato');
            });

            window.addEventListener("XPay_Payment_Started", function (event) {
                console.log('Metodo selezionato dall\'utente: ' + event.detail);
            });
        </script>

    </head>

    <body>

        <form action="/build/paga.php" name="payment-form" id="payment-form" method="POST">
            <label for='importo'>Importo</label> <br>
            <input type='text' disabled="" value='<?php echo $importo ?>' id='importo'>

            <br>

            <label for='codiceTransazione'>Codice transazione</label> <br>
            <input type='text' disabled="" value='<?php echo $codiceTransazione ?>' id='codiceTransazione'>            

            <br>

            <!-- Contiene il form dei dati carta -->
            <div id="xpay-card"></div>

            <br>

            <!-- Contiene gli errori -->
            <div id="xpay-card-errors"></div>

            <br>

            <!-- Contiene i bottoni -->
            <div id='xpay-btn'></div>

            <br>

            <input type='hidden' name='importo' value='<?php echo $importo ?>'>
            <input type='hidden' name='codiceTransazione' value='<?php echo $codiceTransazione ?>'>
            <input type='hidden' name='divisa' value='<?php echo $divisa ?>'>

            <!-- input valorizzati dopo la chiamata 'creaNonce' -->
            <input type='hidden' name='xpayNonce' id='xpayNonce'>
            <input type='hidden' name='xpayIdOperazione' id='xpayIdOperazione'>
            <input type='hidden' name='xpayTimeStamp' id='xpayTimeStamp'>
            <input type='hidden' name='xpayEsito' id='xpayEsito'>
            <input type='hidden' name='xpayMac' id='xpayMac'>

            <button id='pagaBtn'>PAGA</button>
        </form>

    </body>

</html>
                    
                