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
            string requestUrl = "https://int-ecommerce.cartasi.it/" + "ecomm/api/bo/richiestaPayMail";

            // Parametri per calcolo MAC
            string apiKey = "<ALIAS>"; // Alias fornito da CartaSi
            string chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Chiave segreta fornita da CartaSi
            string codTrans = "<CODICE TRANSAZIONE>"; // Codice della transazione
            string timeout = 2; // Durata in ore del link di pagamento che verrà generato 
            string importo = 5000; // 5000 = 50,00 EURO (indicare la cifra in centesimi)
            string url = "url"; // URL dove viene rimandato il cliente al termine del pagamento (prefisso necessario http:// oppure https://)
            string timeStamp = (Math.Round((DateTime.UtcNow.Subtract(new DateTime(1970, 1, 1))).TotalMilliseconds)).ToString();
            
            // Calcolo MAC
            string mac = HashMac("apiKey=" + apiKey + "codiceTransazione=" + codTrans + "importo=" + importo + "timeStamp=" + timeStamp + chiaveSegreta);

			// Parametri di invio
            var requestParams = new Dictionary<string, string>();
            requestParams["apiKey"] = apiKey;
            requestParams["importo"] = importo;
            requestParams["timeout"] = timeout;
            requestParams["codiceTransazione"] = codTrans;
            requestParams["mac"] = mac;
            requestParams["timeStamp"] = timeStamp;
            requestParams["url"] = url;

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





