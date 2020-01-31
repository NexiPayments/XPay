![Nexi](https://upload.wikimedia.org/wikipedia/commons/a/ac/Nexi_Logo.png)

# XPay Reference App - Android
Applicazione di esempio integrazione XPay SDK Android.

## Installazione
Aprire il progetto con Android Studio e sincronizzare Gradle.

## Configurazione
All'interno del progetto, sotto il package "it.nexi.xpaysdksample.utils" è presente la classe denominata Constants.
Al suo interno possiamo trovare tutti i parametri di configurazione necessari per completare le varie fasi di pagamento e poter testare le varie funzionalità dell'SDK XPay.

### Credenziali XPay
Le credenziali necessarie all'utilizzo dei servizi XPay, si possono trovare nella pagina iniziale dell'area DEMO, raggiungibile all'indirizzo: https://ecommerce.nexi.it/area-test

### Variabili di configurazione
#### Necessarie
- ALIAS (Alias area DEMO)
- SECRET_KEY (Chiave segreta area DEMO)
- ENVIRONMENT (Ambiente XPay utilizzato)

### Google Pay ![Google](https://img.icons8.com/color/48/000000/google-logo.png)

- TERMINAL_ID (Numero di terminale fornito da Nexi)
- MERCHANT_NAME (Nome del merchant visualizzato durante il pagamento con Google Pay)

## Architettura
Per scrivere il codice di questa applicazione sono stati applicati i principi del pattern MVP.

---
## XPay
La versione utilizzata della libreria XPay SDK è quella presente sul ramo **master** di questo progetto.

### Pagamento semplice
#### WebView
Di seguito viene illustrato un frammento di codice utile per aprire in WKWebView la pagina di cassa fornita da XPay.</BR>
```java 

    // Pay with front office page (QP method with WebView)
    public void pay(String transactionCode, int amount, FrontOfficeCallbackQP handler) {
        // Create the API request using the QP Alias
        try {
            ApiFrontOfficeQPRequest apiFrontOfficeQPRequest = new ApiFrontOfficeQPRequest(Constants.ALIAS, transactionCode, CurrencyUtilsQP.EUR, amount);
            // Choose if navigation will be enabled into the WebView
            boolean isNavigationEnabled = true;
            // Set the environment
            xPay.FrontOffice.setEnvironment(ENVIRONMENT);
            xPay.FrontOffice.paga(apiFrontOfficeQPRequest, isNavigationEnabled, new FrontOfficeCallbackQP() {
                @Override
                public void onConfirm(ApiFrontOfficeQPResponse confirmResponse) {
                    if (confirmResponse.isValid())
                        Log.i(TAG, "Payment was successful with the circuit " + confirmResponse.getBrand());
                    else
                        Log.e(TAG, "There are security errors during the payment process");
                }

                @Override
                public void onError(ApiErrorResponse errorResponse) {
                    Log.e(TAG, "Error during payament process: " + errorResponse.getError().getMessage());
                }

                @Override
                public void onCancel(ApiFrontOfficeQPResponse cancelResponse) {
                    Log.w(TAG, "Payment was cancelled");
                }
            });
        } catch (UnsupportedEncodingException | MacException e) {
            Log.e(TAG, "Error during request creation: " + e.getLocalizedMessage());
        }
    }
```
#### ChromeCustomTabs
Dalla versione 1.1.0 e successive è possibile utilizzare il componente ChromeCustomTabs tramite il meotodo **pagaChrome** presente nella sezione FrontOffice.</BR>
***Questo metodo di integrazione è consigliato per l'utilizzo di Google Pay***
```java 

    // Pay with front office page (QP method with WebView)
    public void payCustomTabs(String transactionCode, int amount, FrontOfficeCallbackQP handler) {
        // Create the API request using the QP Alias
        try {
            ApiFrontOfficeQPRequest apiFrontOfficeQPRequest = new ApiFrontOfficeQPRequest(Constants.ALIAS, transactionCode, CurrencyUtilsQP.EUR, amount);
            // Set the environment
            xPay.FrontOffice.setEnvironment(ENVIRONMENT);
            xPay.FrontOffice.pagaChrome(apiFrontOfficeQPRequest, false, new FrontOfficeCallbackQP() {
                @Override
                public void onConfirm(ApiFrontOfficeQPResponse confirmResponse) {
                    if (confirmResponse.isValid())
                        Log.i(TAG, "Payment was successful with the circuit " + confirmResponse.getBrand());
                    else
                        Log.e(TAG, "There are security errors during the payment process");
                }

                @Override
                public void onError(ApiErrorResponse errorResponse) {
                    Log.e(TAG, "Error during payament process: " + errorResponse.getError().getMessage());
                }

                @Override
                public void onCancel(ApiFrontOfficeQPResponse cancelResponse) {
                    Log.w(TAG, "Payment was cancelled");
                }
            });
        } catch (UnsupportedEncodingException | MacException e) {
            Log.e(TAG, "Error during request creation: " + e.getLocalizedMessage());
        }
    }
```

### Form nativa
``` xml
    <it.nexi.xpay.nativeForm.CardFormViewMultiline
        android:id="@+id/cardFormMultiline"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:layout_alignParentStart="true"
        android:layout_marginLeft="15dp"
        android:layout_marginTop="50dp"
        android:layout_marginRight="15dp" />
```

### Gradle
```gradle
    implementation 'it.nexi.xpay:XPaySDK:1.2.1'
```

### Documentazione
https://ecommerce.nexi.it/specifiche-tecniche/sdkperapp/android.html
