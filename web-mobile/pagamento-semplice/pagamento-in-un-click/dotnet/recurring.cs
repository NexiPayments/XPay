using System;
using System.Collections.Generic;
using System.Text;
using System.Security.Cryptography;
using Newtonsoft.Json;
using System.Net.Http;
using System.Diagnostics;

namespace XPay
{
    class SampleXPayRequest
    {
        static void Main(string[] args)
        {

            // apikey e chiave segreta
            string APIKEY = "<ALIAS>"; // Sostituire con il valore fornito da CartaSi
            string CHIAVESEGRETA = "CsT830052L63QHNd1E351uh73272Q23h175650k9wU28T7EU1Hd6l156N5I2oBY6U7OW7kP34282C5965r8V0hpG72ojq5B58896G4Q6oXGc36a6z3Tn6J271B4N33p45C28369j7E025O2245GK7T5p1MNN5T25S05UJxCKH0TMc98fBQ66M2NxRDzrR66c7RG2K367D4xiV54X9kY592K5E3V1X1U01AO85P3n4z28eJIL13t8Ww3P28eg24y2"; // Sostituire con il valore fornito da CartaSi

            string requestUrl = "https://int-ecommerce.cartasi.it/ecomm/api/recurring/pagamentoRicorrente";

            DateTime data = DateTime.Now;
            string codTrans = "TESTPS_" + data.ToString("yyyyMMddHHmmss");
            string divisa = "EUR";
            string importo = "5000";
            string scadenza = "202012";
            string timeStamp = (Math.Round((DateTime.UtcNow.Subtract(new DateTime(1970, 1, 1))).TotalMilliseconds)).ToString();
            string numContratto = "NC_TEST_20170407125522";

            //CALCOLO MAC
            SampleXPayRequest sampleXPay = new SampleXPayRequest();
            string mac = sampleXPay.HashMac("apiKey=" + APIKEY + "numeroContratto=" + numContratto + "codiceTransazione=" + codTrans + "importo=" + importo + "divisa=" + divisa + "scadenza=" + scadenza + "timeStamp=" + timeStamp + CHIAVESEGRETA);

            var requestParams = new Dictionary<string, string>();
            requestParams["apiKey"] = APIKEY;
            requestParams["importo"] = importo;
            requestParams["divisa"] = divisa;
            requestParams["codiceTransazione"] = codTrans;
            requestParams["mac"] = mac;
            requestParams["numeroContratto"] = numContratto;
            requestParams["scadenza"] = scadenza;
            requestParams["codiceGruppo"] = "GRUPPOTEST";
            requestParams["timeStamp"] = timeStamp;

            string json = JsonConvert.SerializeObject(requestParams);

            var response = sampleXPay.Post(requestUrl, json);

            if (response["esito"] == "OK")
            { // Transazione andata a buon fine
                // Calcolo con i parametri di ritorno
                string macCalculated = sampleXPay.HashMac("esito=" + response["esito"] + "idOperazione=" + response["idOperazione"] + "timeStamp=" + response["timeStamp"] + CHIAVESEGRETA);
                if (macCalculated != response["mac"])
                {
                    Console.WriteLine("errore MAC: " + macCalculated + " non corrisponde a " + response["mac"]);
                }
                else
                {
                    Console.WriteLine("La transazione " + codTrans + " è avvenuta con successo; codice autorizzazione: " + response["codiceAutorizzazione"]);
                }
            }
            else
            { // Transazione rifiutata
                Console.WriteLine("La transazione " + codTrans + " è stata rifiutata");
            }

        }

        private Dictionary<string, string> Post(string requestUrl, string json)
        {
            var httpContent = new StringContent(json, Encoding.UTF8, "application/json");

            using (var httpClient = new HttpClient())
            {
                var httpResponse = httpClient.PostAsync(requestUrl, httpContent).Result;

                if (httpResponse.Content != null)
                {
                    var responseContent = httpResponse.Content.ReadAsStringAsync();

                    Dictionary<string, string> json_decode = JsonConvert.DeserializeObject<Dictionary<string, string>>(responseContent.Result);
                    return json_decode;
                }
            }
            return null;
        }

        public string HashMac(string s)
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


