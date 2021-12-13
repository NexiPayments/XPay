                                            
// Pagamento ricorrente - Pagamento successivo - Chiamata sincrona

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

public class pagamentiSuccessvi-ChiamataSincrona {

    public static void main(String[] a) throws Exception {

        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyyMMddHHmmss");
        Date date = new Date();

        // URL + URI
        String requestUrl = "https://int-ecommerce.nexi.it/" + "ecomm/api/recurring/pagamentoRicorrente";

        // PARAMETRI PER CALCOLO MAC
        String apiKey = "<ALIAS>"; // Alias fornito da Nexi
        String numeroContratto = "3dsa3asc34a"; // Numero del contratto
        String codTrans = "23ds45s"; // Codice Transazione da eseguire
        String importo = "5000"; // 5000 = 50,00 Euro (cifre in centesimi)
        String divisa = "EUR"; // EUR = 978
        String scadenza = "203012"; // Scadenza aaaamm
        String codiceGruppo = "<GRUPPO>"; // Codice Gruppo fornito da Nexi
        String timeStamp = "" + System.currentTimeMillis();
        String chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Chiave segreta fornita da Nexi

        // CONCATENAMENTO STRINGA (MAC)
        String stringaMac = "apiKey=" + apiKey
                + "numeroContratto=" + numeroContratto
                + "codiceTransazione=" + codTrans
                + "importo=" + importo
                + "divisa=" + divisa
                + "scadenza=" + scadenza
                + "timeStamp=" + timeStamp
                + chiaveSegreta;

		
        // CALCOLO MAC
        String macCalculated = hashMac(stringaMac);

		// PARAMETRI DI INVIO	
        JSONObject json = new JSONObject();
        json.put("apiKey", apiKey);
        json.put("numeroContratto", numeroContratto);
        json.put("codiceTransazione", codTrans);
        json.put("importo", importo);
        json.put("divisa", divisa);
        json.put("scadenza", scadenza);
        json.put("timeStamp", timeStamp);
        json.put("mac", macCalculated);
        

        // CHIAMATA API
        CloseableHttpClient httpClient = HttpClientBuilder.create().build();
        HttpPost request = new HttpPost(requestUrl);
        StringEntity params = new StringEntity(json.toString());
        request.addHeader("content-type", "application/json");
        request.setEntity(params);
        HttpResponse response = httpClient.execute(request);
        HttpEntity entity = response.getEntity();
        String responseString = EntityUtils.toString(entity, "UTF-8");

        // PARAMETRI DI RITORNO
        JSONObject responseObj = new JSONObject(responseString);
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
                    
                