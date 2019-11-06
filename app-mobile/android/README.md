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
Di seguito viene illustrato un frammento di codice utile per aprire in WebView la pagina di cassa fornita da XPay.

```java 
    // Generate ApiFrontOfficeQp request
    private ApiFrontOfficeQPRequest getFrontOfficeRequest(String transactionCode, int amount) {
        // Create the API request using the QP Alias
        ApiFrontOfficeQPRequest apiFrontOfficeQPRequest = null;
        try {
            apiFrontOfficeQPRequest = new ApiFrontOfficeQPRequest(Constants.ALIAS,
                    transactionCode, CurrencyUtilsQP.EUR, amount);
        } catch (UnsupportedEncodingException | MacException e) {
            e.printStackTrace();
        }
        return apiFrontOfficeQPRequest;
    }

    // Pay with front office page (QP method with WebView)
    @Override
    public void pay(String transactionCode, int amount, FrontOfficeCallbackQP handler) {
        setXPay();
        ApiFrontOfficeQPRequest apiFrontOfficeQPRequest = getFrontOfficeRequest(transactionCode, amount);

        // Set the environment
        xPay.FrontOffice.setEnvironment(ENVIRONMENT);
        xPay.FrontOffice.paga(apiFrontOfficeQPRequest, true, handler);
    }
```

### Form nativa
``` xml
    <it.nexi.xpay.CardFormView.CardFormViewMultiline
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
    implementation 'it.nexi.xpay:XPaySDK:1.2.0'
```

### Documentazione
https://ecommerce.nexi.it/specifiche-tecniche/sdkperapp/android.html
