// Report - Download

using System;
using System.Collections.Generic;
using System.Text;
using System.Security.Cryptography;

namespace XPay
{
    class SampleXPayRequest
    {
        static void Main(string[] args)
        {

            // Alias e chiave segreta
            string ALIAS = "<ALIAS>"; // Sostituire con il valore fornito da Nexi
            string CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

            string requestUrl = "https://int-ecommerce.nexi.it/ecomm/api/bo/downloadReport";

            string timeStamp = (Math.Round((DateTime.UtcNow.Subtract(new DateTime(1970, 1, 1))).TotalMilliseconds)).ToString();
            string idReport = "123";       

            // Calcolo MAC
            SampleXPayRequest sampleXPay = new SampleXPayRequest();
            string mac = sampleXPay.HashMac("apiKey=" + ALIAS + "timeStamp=" + timeStamp + "idReport=" + idReport + CHIAVESEGRETA);

            var requestParams = new Dictionary<string, string>();
            // Parametri obbligatori
            requestParams["apiKey"] = ALIAS;
            requestParams["timeStamp"] = importo;
            requestParams["idReport"] = divisa;
            requestParams["mac"] = mac;

            string uri =  "?";
            foreach (KeyValuePair<string, string> param in requestParams)
            {
                uri += param.Key + "=" + param.Value + "&";
            }

            string result = requestUrl + uri;
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