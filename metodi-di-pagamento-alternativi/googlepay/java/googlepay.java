                                            
// Google Pay - Integrazione tramite API

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.HttpClientBuilder;
import org.apache.http.util.EntityUtils;
import org.json.JSONObject;

import java.security.MessageDigest;
import java.text.SimpleDateFormat;
import java.util.Date;

public class googlepay {

    public static void main(String[] a) throws Exception {

        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyyMMddHHmmss");
        Date date = new Date();

        // Parametri per calcolo MAC
        String apiKey = "<ALIAS>"; // Alias fornito da Nexi
        String chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Chiave segreta fornita da Nexi

        String requestUrl = "https://int-ecommerce.nexi.it/" + "ecomm/api/paga/googlePay";

        String codTrans = "TESTAP_" + dateFormat; // Codice della transazione
        String importo = "5000"; // 5000 = 50,00 EURO (indicare la cifra in centesimi)
        String divisa = "978"; // divisa 978 indica EUR
        String timeStamp = "" + System.currentTimeMillis();
        String jsonGoogle = "{}";

        String stringaMac = "apiKey=" + apiKey
          + "codiceTransazione=" + codTrans
          + "importo=" + importo
          + "divisa=" + divisa
          + "timeStamp=" + timeStamp
          + chiaveSegreta;

        // Calcolo MAC
        String macCalculated = hashMac(stringaMac);

        // Parametri di invio
        JSONObject json = new JSONObject();
        json.put("apiKey", apiKey);
        json.put("codiceTransazione", codTrans);
        json.put("importo", importo);
        json.put("divisa", divisa);
        json.put("googlePay", jsonGoogle);
        json.put("timeStamp", timeStamp);
        json.put("mac", macCalculated);
        

        // Chiamata API
        CloseableHttpClient httpClient = HttpClientBuilder.create().build();
        HttpPost request = new HttpPost(requestUrl);
        StringEntity params = new StringEntity(json.toString());
        request.addHeader("content-type", "application/json");
        request.setEntity(params);
        HttpResponse response = httpClient.execute(request);
        HttpEntity entity = response.getEntity();
        String responseString = EntityUtils.toString(entity, "UTF-8");

        // Parametri di ritorno
        JSONObject responseObj = new JSONObject(responseString);

        System.out.print(responseObj.toString());
    }

    public static String hashMac(String stringaMac) throws Exception {
        MessageDigest digest = MessageDigest.getInstance("SHA-1");
        byte[] in = digest.digest(stringaMac.getBytes("UTF-8"));

        final StringBuilder builder = new StringBuilder();
        for (byte b : in) {
            builder.append(String.format("%02x", b));
        }
        return builder.toString();
    }
}
                    
                