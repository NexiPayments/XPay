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
            string requestUrl = "https://int-ecommerce.nexi.it/" + "ecomm/api/blacklist/rimuovi";

            // Parametri per calcolo MAC
            string apiKey = "<ALIAS>"; // Alias fornito da Nexi
            string chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Chiave segreta fornita da Nexi
            string tipo = "<CODICE>"; //CodiceFiscale o NumeroContratto
            string valore = "<NUMERO>"; //Numero Codice Fiscale o Numero Contratto
            string timeStamp = (Math.Round((DateTime.UtcNow.Subtract(new DateTime(1970, 1, 1))).TotalMilliseconds)).ToString();            

            // Calcolo MAC
            SampleXPayRequest sampleXPay = new SampleXPayRequest();
            string mac = HashMac("apiKey=" + apiKey + "tipo=" + tipo + "valore=" + valore + "timeStamp=" + timeStamp + chiaveSegreta);

			// Parametri di invio
            var requestParams = new Dictionary<string, string>();
            requestParams["apiKey"] = apiKey;
            requestParams["tipo"] = tipo;
            requestParams["valore"] = valore;
            requestParams["timeStamp"] = timeStamp;
            requestParams["mac"] = mac;

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





