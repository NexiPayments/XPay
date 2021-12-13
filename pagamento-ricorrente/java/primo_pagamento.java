                                            
// Pagamento ricorrente - Primo pagamento - Avvio pagamento

import java.util.Map;
import java.util.HashMap;
import java.security.MessageDigest;
import java.util.Date;
import java.text.SimpleDateFormat;
import java.net.URLEncoder;

public class primo_pagamento {

  public static void main(String[] args) throws Exception {

    // Alias e chiave segreta
    String ALIAS = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
    String CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi
    String GRUPPO = "<GRUPPO>"; // Sostituire con il valore fornito da Nexi

    String HTTP_HOST = "my-server.example.tdl";

    String requestUrl = "https://int-ecommerce.nexi.it/ecomm/ecomm/DispatcherServlet";
    String merchantServerUrl = "https://" + HTTP_HOST + "/xpay/pagamento_semplice_python/recurring/";

    SimpleDateFormat dateFormat = new SimpleDateFormat("yyyyMMddHHmmss");
    Date date = new Date();

    String codTrans = "TESTPS_" + dateFormat.format(date);
    String divisa = "EUR";
    String importo = "5000";    

    // Calcolo MAC
    String stringaMac = "codTrans=" + codTrans +
            "divisa=" + divisa +
            "importo=" + importo +
            CHIAVESEGRETA;

    String macCalculated = hashMac(stringaMac);

    String numContratto = "NC_TEST_" + dateFormat.format(date);
    String tipoRichiesta = "PP";

    // Parametri obbligatori
    HashMap<String, String> requestParams = new HashMap<String, String>();

    requestParams["alias"] = ALIAS;
    requestParams["importo"] = importo;
    requestParams["divisa"] = divisa;
    requestParams["codTrans"] = codTrans;
    requestParams["url"] = merchantServerUrl + "esito.html";
    requestParams["url_back"] = merchantServerUrl + "annullo.html";
    requestParams["mac"] = macCalculated;
    requestParams["num_contratto"] = numContratto;
    requestParams["tipo_servizio"] = "paga_multi";
    requestParams["tipo_richiesta"] = tipoRichiesta;

    // parametri facoltativi
                  

    /**
     * Creare un form html con metodo post verso requestUrl con campi hidden contenenti requestParams
     */
  }

  public static String hashMac(String stringaMac) throws Exception {
    MessageDigest digest = MessageDigest.getInstance("SHA-1");
    byte[] in = digest.digest(stringaMac.getBytes("UTF-8"));

    final StringBuilder builder = new StringBuilder();
    
    for(byte b : in) {
      builder.append(String.format("%02x", b));
    }
    
    return builder.toString();
  }

}
                    
                