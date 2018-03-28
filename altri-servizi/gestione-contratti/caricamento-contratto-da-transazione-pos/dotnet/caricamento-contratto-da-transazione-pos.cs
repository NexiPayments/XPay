using System;
using System.Collections.Generic;
using System.Text;
using System.Security.Cryptography;
using Newtonsoft.Json;
using System.Net.Http;

namespace XPay
{
    class SampleXPayRequest
    {
        static void Main(string[] args)
        {

            // URL + URI
            string requestUrl = "https://int-ecommerce.nexi.it/" + "ecomm/api/contratti/creazioneDaPosFisico";

            // Parametri per il calcolo MAC
            string apiKey = "<ALIAS>"; // Alias fornito da Nexi
            string chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Chiave segreta fornita da Nexi
            string timeStamp = (Math.Round((DateTime.UtcNow.Subtract(new DateTime(1970, 1, 1))).TotalMilliseconds)).ToString();           

            string numeroContratto = "<NUMERO_CONTRATTO>"; // Numero del contratto da abilitare
            string idPOSFisico = "<ID>"; // ID del POS fisico
            string codiceAutorizzazione = "<CODICE_AUTORIZZAZIONE>"; // Codice dia autorizzazione della transazione
            string importo = "5000"; // 5000 = 50,00 Euro (prime due cifre a sinistra in centesimi)
			
            string stan = "";
            string descrizione = "";
            string mail = "";

            // Calcolo MAC
            string mac = HashMac("apiKey=" + apiKey + "numeroContratto=" + numeroContratto + "idPOSFisico=" + idPOSFisico + "codiceAutorizzazione=" + codiceAutorizzazione + "stan=" + stan + "importo=" + importo + "descrizione=" + descrizione + "mail=" + mail + "timeStamp=" + timeStamp + chiaveSegreta);

			// Parametri di invio
            var contratto = new Dictionary<string, string>();
            contratto["numeroContratto"] = numeroContratto;
            contratto["idPOSFisico"] = idPOSFisico;
            contratto["importo"] = importo;
			
            var requestParams = new Dictionary<string, dynamic>();
            requestParams["apiKey"] = apiKey;
            requestParams["contratto"] = contratto;
            requestParams["mac"] = mac;
            requestParams["timeStamp"] = timeStamp;

            string json = JsonConvert.SerializeObject(requestParams);

            var response = Post(requestUrl, json);
        }

        private static dynamic Post(string requestUrl, string json)
        {
            var httpContent = new StringContent(json, Encoding.UTF8, "application/json");

            using (var httpClient = new HttpClient())
            {
                var httpResponse = httpClient.PostAsync(requestUrl, httpContent).Result;

                if (httpResponse.Content != null)
                {
                    var responseContent = httpResponse.Content.ReadAsStringAsync();

                    dynamic json_decode = JsonConvert.DeserializeObject(responseContent.Result);

                    return json_decode;
                }
            }
            return null;
        }

        public static string HashMac(string s)
        {
            byte[] bytes = Encoding.UTF8.GetBytes(s);

            var sha1 = SHA1.Create();
            byte[] hashBytes = sha1.ComputeHash(bytes);

            return HexStringFromBytes(hashBytes);
        }

        public static string HexStringFromBytes(byte[] bytes)
        {
            var sb = new StringBuilder();
            foreach (byte b in bytes)
            {
                var hex = b.ToString("x2");
                sb.Append(hex);
            }
            return sb.ToString();
        }

    }
}





