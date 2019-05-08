![Nexi](https://upload.wikimedia.org/wikipedia/commons/a/ac/Nexi_Logo.png)

# XPay Reference App - iOS
Applicazione di esempio integrazione XPay SDK iOS.

## Installazione
Tramite terminale recarsi nella cartella del progetto ed eseguire il comando:
    
```bash
        pod install
```

## Configurazione
All'interno del progetto, sotto la directory utils/settings è presente la classe denominata XPayConstants.
Al suo interno possiamo trovare tutti i parametri di configurazione necessari per completare le varie fasi di pagamento e poter testare le varie funzionalità dell'SDK XPay.

### Credenziali XPay
Le credenziali necessarie all'utilizzo dei servizi XPay, si possono trovare nella pagina iniziale dell'area DEMO, raggiungibile all'indirizzo: https://ecommerce.nexi.it/area-test

### Variabili di configurazione
#### Necessarie
- ALIAS (Alias area DEMO)
- SECRET_KEY (Chiave segreta area DEMO)
- ENVIRONMENT (Ambiente XPay utilizzato)

### Apple Pay ![Apple](https://img.icons8.com/ios/50/000000/mac-os-filled.png)
- MERCHANT_ID (Identificativo del merchant registrato tramite il portale Apple)
- DISPLAY_NAME (Nome del merchant visualizzato durante il pagamento di Apple Pay)

## Architettura
Per scrivere il codice di questa applicazione sono stati applicati i principi del pattern MVP.

---
## XPay
La versione della libreria XPay SDK è quella presente sul ramo **master** di questo progetto.

### Pagamento semplice
Di seguito viene illustrato un frammento di codice utile per aprire in UIWebView la pagina di cassa fornita da XPay.

``` swift 
    // Pay with front office page
    func pay(_ parent: UIViewController, codTrans: String, amount: Int, handler: @escaping PaymentRepositoryProtocol.QPHandler) {
        // Create the API request using the provided Alias
        let request = ApiFrontOfficeQPRequest(alias: XPayConstants.ALIAS, codTrans: codTrans, currency: CurrencyUtilsQP.EUR, amount: amount)
        // Set the environment
        xPay?._FrontOffice.SelectedEnvironment = XPayConstants.ENVIRONMENT
        xPay?._FrontOffice.paga(request, navigation: true, parentController: parent, completionHandler: handler)
    }
```

### Podfile
``` pod
    pod 'Nexi_XPay'
```

### Documentazione
https://ecommerce.nexi.it/specifiche-tecniche/sdkperapp/ios.html