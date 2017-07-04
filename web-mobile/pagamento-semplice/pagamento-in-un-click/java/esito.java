import java.util.Map;
import java.util.HashMap;
import java.security.MessageDigest;

public class esito {

  public static void main(String[] args) throws Exception {

    // Chiave segreta
    String CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da CartaSi

    // Questi sono i parametri in ingresso della richiesta
    Map < String, String > paramFromRequest = new HashMap < String, String > ();
    paramFromRequest.put("codTrans", "");
    paramFromRequest.put("esito", "OK");
    paramFromRequest.put("importo", "");
    paramFromRequest.put("divisa", "");
    paramFromRequest.put("data", "");
    paramFromRequest.put("orario", "");
    paramFromRequest.put("codAut", "");
    paramFromRequest.put("mac", "58815356e9052f7d6a355a399d1d8edfc4a58bd7");   

    // Calcolo MAC con i parametri di ritorno
    String stringaMac = "codTrans=" + paramFromRequest.get("codTrans") +
            "esito=" + paramFromRequest.get("esito") +
            "importo=" + paramFromRequest.get("importo") +
            "divisa=" + paramFromRequest.get("divisa") +
            "data=" + paramFromRequest.get("data") +
            "orario=" + paramFromRequest.get("orario") +
            "codAut=" + paramFromRequest.get("codAut") +
            CHIAVESEGRETA;

    String macCalculated = hashMac(stringaMac);

    // Verifico corrispondenza tra MAC calcolato e parametro mac di ritorno
    if (!macCalculated.equals(paramFromRequest.get("mac"))) {
      throw new Exception("Errore MAC: " + macCalculated + " non corrisponde a " + paramFromRequest.get("mac"));
    }

    // Nel caso in cui non ci siano errori gestisco il parametro esito
    if ("OK".equals(paramFromRequest.get("esito"))) {
      System.out.println("La transazione " + paramFromRequest.get("codTrans") + " è avvenuta con successo; codice autorizzazione: " + paramFromRequest.get("codAut"));
      System.out.println("codice autorizzazione: " + paramFromRequest.get("codAut"));
      System.out.println("Codice Contratto: " + paramFromRequest.get("num_contratto"));
    } else {
      System.out.println("La transazione " + paramFromRequest.get("codTrans") + " è stata rifiutata");
      System.out.println("descrizione errore: " + paramFromRequest.get("messaggio"));
    }
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
