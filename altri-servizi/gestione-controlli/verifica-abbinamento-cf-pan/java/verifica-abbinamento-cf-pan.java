import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.HttpClientBuilder;
import org.apache.http.util.EntityUtils;
import java.text.SimpleDateFormat;
import java.util.Date;

public class controlloEsistenza {

    public static void main(String[] a) throws Exception {

        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyyMMddHHmmss");
        Date date = new Date();

        // URL + URI
        String requestUrl = "https://int-ecommerce.nexi.it/" + "ecomm/api/cfpan/controllaEsistenza";

        // Parametri per calcolo MAC
        String apiKey = "<ALIAS>"; // Alias fornito da Nexi
        String chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Chiave segreta fornita da Nexi
        String valore = "<valore>"; // Numero contratto oppure codice fiscale
        String tipo = "CodiceContratto"; // "CodiceContratto" oppure "CodiceFiscale"
        String timeStamp = "" + System.currentTimeMillis();
        
        String stringaMac = "apiKey=" + apiKey
                + "tipo=" + tipo
                + "valore=" + valore
                + "timeStamp=" + timeStamp
                + chiaveSegreta;

        // Calcolo MAC
        String macCalculated = hashMac(stringaMac);

        // Parametri di invio
        JSONObject json = new JSONObject();
        json.put("apiKey", apiKey);
        json.put("tipo", tipo);
        json.put("valore", valore);
        json.put("codiceGruppo", codiceGruppo);
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
