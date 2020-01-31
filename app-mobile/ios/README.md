![Nexi](https://upload.wikimedia.org/wikipedia/commons/a/ac/Nexi_Logo.png)

# XPay Reference App - iOS
Applicazione di esempio integrazione XPay SDK iOS.

## Installazione
Tramite terminale recarsi nella cartella del progetto ed eseguire il comando:
    
```bash
        pod install
```

## Configurazione
All'interno del progetto, sotto la directory utils/settings � presente la classe denominata XPayConstants.
Al suo interno possiamo trovare tutti i parametri di configurazione necessari per completare le varie fasi di pagamento e poter testare le varie funzionalit� dell'SDK XPay.

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
#### WebView
Di seguito viene illustrato un frammento di codice utile per aprire in WKWebView la pagina di cassa fornita da XPay.</BR>
***Le versioni inferiori precedenti alla 1.2.0, utilizzano la UIWebView***

``` swift 
    // Pay with front office page -> WebView (WKWebView or UIWebView)
    func pay(_ parent: UIViewController, codTrans: String, amount: Int) {
        // Create the API request using the provided Alias
        let request = ApiFrontOfficeQPRequest(alias: XPayConstants.ALIAS, codTrans: codTrans, currency: CurrencyUtilsQP.EUR, amount: amount)
        // Choose if navigation will be enabled into the WebView
        let isNavigationEnabled = true
        // Set the application environment
        xPay?._FrontOffice.SelectedEnvironment = XPayConstants.ENVIRONMENT
        xPay?._FrontOffice.paga(request, navigation: isNavigationEnabled, parentController: parent) { (response) in
            if response.IsValid {
                if response.Error != nil && !(response.Error!.Message).isEmpty {
                    print("Error during payament process: \(response.Error!.Message)")
                }
                else if !response.IsCanceled {
                    print("Payment was successful with the circuit \(response.Brand!)")
                } else {
                    print("Payment was cancelled")
                }
            } else {
                print("There are security errors during the payment process")
            }
        }
    }
```
#### SafariViewController (Apple Pay)
Dalla versione 1.1.5 e successive è possibile utilizzare il componente SafariViewController tramite il meotodo **pagaSafari** presente nella sezione _FrontOffice.</BR>
***Questo metodo di integrazione permette l'utilizzo del metodo di pagamento Apple Pay***
``` swift 
    // Pay with front office page -> SafariViewController
    func pay(_ parent: UIViewController, codTrans: String, amount: Int) {
        // Create the API request using the provided Alias
        let request = ApiFrontOfficeQPRequest(alias: XPayConstants.ALIAS, codTrans: codTrans, currency: CurrencyUtilsQP.EUR, amount: amount)
        // Set the application environment
        xPay?._FrontOffice.SelectedEnvironment = XPayConstants.ENVIRONMENT
        xPay?._FrontOffice.pagaSafari(request, parentController: parent) { (response) in
            if response.IsValid {
                if response.Error != nil && !(response.Error!.Message).isEmpty {
                    print("Error during payament process: \(response.Error!.Message)")
                }
                else if !response.IsCanceled {
                    print("Payment was successful with the circuit \(response.Brand!)")
                } else {
                    print("Payment was cancelled")
                }
            } else {
                print("There are security errors during the payment process")
            }
        }
    }
```

### Podfile
``` pod
    pod 'Nexi_XPay'
```

### Documentazione
https://ecommerce.nexi.it/specifiche-tecniche/sdkperapp/ios.html