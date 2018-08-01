<?php
$APIKEY = ""; // Sostituire con il valore fornito da Nexi
$CHIAVESEGRETA = ""; // Sostituire con il valore fornito da Nexi

$codiceTransazione = "TESTPS_" . date('YmdHis');
$divisa = "EUR";
$importo = "5000";
$timeStamp = (time()) * 1000;

// Calcolo MAC
$mac = sha1('apiKey=' . $APIKEY . 'codiceTransazione=' . $codiceTransazione . 'divisa=' . $divisa . 'importo=' . $importo . 'timeStamp=' . $timeStamp . $CHIAVESEGRETA);
?>

<html>
    <head>
        <script type="text/javascript" src="https://int-ecommerce.nexi.it/ecomm/hostedPayments/JavaScript/custom?bundle=HP_FULL&alias=<?php echo $APIKEY; ?>"></script>
        <script type="text/javascript">

            // Configurazione iniziale SDK            
            $(document).ready(function () {

                // 1.1 Inizializzazione SDK
                XPay.init();

                // 1.2 Impostazione ambiente. Valori ammessi:
                // XPay.Environments.INTEG: collaudo
                // XPay.Environments.PROD: produzione
                XPay.setEnvironment(XPay.Environments.INTEG);

                // 1.3 Configurazione XPay SDK con API Key del merchant
                XPay.setAPIKey('<?php echo $APIKEY; ?>');

                // 2 Iniezione del calcolo nonce nel processo di submit della form;
                // NB: l'effettiva implementazione dipende da come il merchant gestisce il submit

                var $form = $('#payment-form');

                $form.find('#pagaBtn').click(function () {
                    // 2.1 Inibizione del click sul bottone di invio della form
                    $(this).prop('disabled', true);
                    // 2.2 Creazione del nonce e assegnazione dell'handler di gestione della risposta di XPay; il submit del form verso back-end verrà nell’handler, che deve essere implementato dal merchant
                    XPay.createNonce("payment-form", xpayResponseHandler);
                });
            });

            function xpayResponseHandler(response) {
                console.log(response);

                // Recupero il form
                var $form = $('#payment-form');

                if (response.esito && response.esito === "OK") { // nonce creato
                    // 3.A Recupero del nonce e di altre proprietà in output; inserimento come campi nascosti 
                    // del form. Il backend dovrà eventualmente validare il mac della risposta

                    $form.append($('<input type="hidden" name="xpayNonce">').val(response.xpayNonce));
                    $form.append($('<input type="hidden" name="xpayIdOperazione" > ').val(response.idOperazione));
                    $form.append($('<input type="hidden" name="xpayTimeStamp">').val(response.timeStamp));
                    $form.append($('<input type="hidden" name="xpayEsito">').val(response.esito));
                    $form.append($('<input type="hidden" name="xpayMac">').val(response.mac));

                    // Submit del form
                    $form.get(0).submit();
                } else {
                    // 3.B Visualizzazione errore e ripristino bottone form
                    $form.find('.payment-error').text("[" + response.errore.codice + "] " + response.errore.messaggio);
                    $form.find('#pagaBtn').prop('disabled', false);
                }
            }
        </script>
    </head>
    <body>
        <form action="primo_pagamento.php" id="payment-form" method="POST">

            <input type="hidden" data-xpay-order="importo" name="importo" value="<?php echo $importo; ?>" id="importo">
            <input type="hidden" data-xpay-order="timeStamp" name="timeStamp" value="<?php echo $timeStamp; ?>" id="timeStamp">
            <input type="hidden" data-xpay-order="divisa" name="divisa" value="<?php echo $divisa; ?>" id="divisa">
            <input type="hidden" data-xpay-order="mac" name="mac" value="<?php echo $mac; ?>" id="mac"> 
            <input type="hidden" data-xpay-order="codiceTransazione" name="codiceTransazione" value="<?php echo $codiceTransazione; ?>" id="codiceTransazione">
            <input type="hidden" name="alias" value="<?php echo $APIKEY; ?>" id="alias">

            <h2>Dati Pagamento</h2>
            <br>
            <span class="payment-error" style="color: red;"></span>
            <br>
            <label for="_importo" >Importo:  </label>
            <label id="_importo" ><?php echo $importo; ?></label>
            <br><br>
            <label for="_nOrdine" >Numero di ordine:  </label>
            <label id="_nOrdine" ><?php echo $codiceTransazione; ?></label>
            <br><br>
            <label for="_email" >Indirizzo e-mail</label>
            <input id="_email" type="text" >
            <br><br>
            <label for="_nCarta" >N. Carta</label>
            <input id="_nCarta" type="text" maxlength="20" data-xpay-card="pan" placeholder="Numero carta" >
            <br><br>
            <label><span>Scadenza (MM/YY)</span></label>
            <input type="text" size="5" data-xpay-card="scadenza">
            <br><br>
            <label for="cvv" >CVV</label>
            <input type="text" maxlength="3" data-xpay-card="cvv" id="cvv">
            <br><br>
            <input type="button" value="Paga" id="pagaBtn"> 

        </form>
    </body>
</html>