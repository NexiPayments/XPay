import java.util.Map;
import java.util.HashMap;
import java.security.MessageDigest;
import java.util.Date;
import java.text.SimpleDateFormat;
import java.net.URLEncoder;

public class primo_pagamento {

  public static void main(String[] args) throws Exception {

    // Alias e chiave segreta
    String ALIAS = "<ALIAS>"; // Sostituire con il valore fornito da CartaSi
    String CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da CartaSi

    String HTTP_HOST = "my-server.example.tdl";
    String session_id = "12345";

    String requestUrl = "https://int-ecommerce.cartasi.it/ecomm/ecomm/DispatcherServlet";
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
    String requestParams = "";
    requestParams += "alias=" + URLEncoder.encode(ALIAS,"UTF-8") + "&";
    requestParams += "importo=" + URLEncoder.encode(importo,"UTF-8") + "&";
    requestParams += "divisa=" + URLEncoder.encode(divisa,"UTF-8") + "&";
    requestParams += "codTrans=" + URLEncoder.encode(codTrans,"UTF-8") + "&";
    requestParams += "url=" + URLEncoder.encode(merchantServerUrl + "esito.html","UTF-8") + "&";
    requestParams += "url_back=" + URLEncoder.encode(merchantServerUrl + "annullo.html","UTF-8") + "&";
    requestParams += "mac=" + URLEncoder.encode(macCalculated,"UTF-8") + "&";
    requestParams += "urlpost=" + URLEncoder.encode(merchantServerUrl + "notifica.html","UTF-8") + "&";
    requestParams += "num_contratto" + URLEncoder.encode( numContratto,"UTF-8") + "&";
    requestParams += "tipo_servizio" + URLEncoder.encode( "paga_multi","UTF-8") + "&";
    requestParams += "tipo_richiesta" + URLEncoder.encode( tipoRichiesta,"UTF-8") + "&";
    requestParams += "gruppo" + URLEncoder.encode( "GRUPPOTEST","UTF-8") + "&";

    // parametri facoltativi
    requestParams += "mail=" + URLEncoder.encode("mail@cliente.it","UTF-8") + "&";
    requestParams += "languageId=" + URLEncoder.encode("ITA","UTF-8") + "&";
    requestParams += "descrizione=" + URLEncoder.encode("Prova di pagamento","UTF-8") + "&";
    requestParams += "session_id=" + URLEncoder.encode(session_id,"UTF-8") + "&";
    requestParams += "Note1=" + URLEncoder.encode("NOTA 1","UTF-8") + "&";
    requestParams += "Note2=" + URLEncoder.encode("NOTA 2","UTF-8") + "&";
    requestParams += "Note3=" + URLEncoder.encode("NOTA 3","UTF-8") + "&";
    requestParams += "OPTION_CF=" + URLEncoder.encode("RSSMRA74D22A001Q","UTF-8") + "&";
    requestParams += "selectedcard=" + URLEncoder.encode("VISA","UTF-8") + "&";
    requestParams += "TCONTAB=" + URLEncoder.encode("D","UTF-8") + "&";
    requestParams += "infoc=" + URLEncoder.encode("Info su pagamento per compagnia","UTF-8") + "&";
    requestParams += "infob=" + URLEncoder.encode("Info su pagamento per banca","UTF-8") + "&";
    requestParams += "modo_gestione_consegna=" + URLEncoder.encode("completo","UTF-8");

    String redirectUrl = requestUrl + "?" + requestParams;

    System.out.println(redirectUrl);
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
