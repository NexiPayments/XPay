                                            
// Pagamento ricorrente/Pagamento in un click - Pagamento successivo SSL

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

public class Esempio {

    public static void main(String[] a) throws Exception {

        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyyMMddHHmmss");
        Date date = new Date();

        // Parametri per calcolo MAC
        String APIKEY = "<ALIAS>"; // Alias fornito da Nexi
        String CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Chiave segreta fornita da Nexi

        String requestUrl = "https://int-ecommerce.nexi.it/" + "ecomm/api/paga/pagamentoRicorrente";
        String codTrans = "TESTAP_" + dateFormat; // Codice della transazione
        String divisa = "978"; // divisa 978 indica EUR
        String scadenza = "203012"; // Scadenza della carta (Formato aaaamm)
        String timeStamp = "" + System.currentTimeMillis();

        String stringaMac = "apiKey=" + apiKey + "numeroContratto=" + numContratto + "codiceTransazione=" + codTrans + "importo=" + importo + "divisa=" + divisa + "scadenza=" + scadenza + "timeStamp=" + timeStamp + chiaveSegreta;

        // Calcolo MAC
        String macCalculated = hashMac(stringaMac);

        // Parametri di invio
        JSONObject json = new JSONObject();

        json.put("apiKey", apiKey);
        json.put("numeroContratto", numContratto);
        json.put("codiceTransazione", codTrans);
        json.put("importo", importo);
        json.put("divisa", divisa);
        json.put("timeStamp", timeStamp);
        json.put("mac", mac);
        

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
                    
                