// Report - Download

import java.util.Map;
import java.util.HashMap;
import java.security.MessageDigest;
import java.util.Date;
import java.text.SimpleDateFormat;
import java.net.URLEncoder;

public class codice_base {

  public static void main(String[] args) throws Exception {

    // Alias e chiave segreta
    String ALIAS = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
    String CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

    String requestUrl = "https://int-ecommerce.nexi.it/ecomm/api/bo/downloadReport";

    String timeStamp = "" + System.currentTimeMillis();
    String idReport = "123";

    // Calcolo MAC
    String stringaMac = "apiKey=" + ALIAS + "timeStamp=" + timeStamp + "idReport=" + idReport + CHIAVESEGRETA;

    String macCalculated = hashMac(stringaMac);
    
    // Parametri obbligatori
    String requestParams = "";
    requestParams += "apiKey=" + URLEncoder.encode(ALIAS, "UTF-8");
    requestParams += "&timeStamp=" + URLEncoder.encode(timeStamp, "UTF-8");
    requestParams += "&idReport=" + URLEncoder.encode(idReport, "UTF-8");
    requestParams += "&mac=" + URLEncoder.encode(macCalculated, "UTF-8");

    String downloadUrl = requestUrl + "?" + requestParams;
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
