                                            
//  Pagamento OneClik - Su pagina di cassa - Avvio pagamento

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

            string requestUrl = "https://int-ecommerce.nexi.it/ecomm/ecomm/DispatcherServlet";
            string merchantServerUrl = "https://my-server.example.tdl/";

            DateTime data = DateTime.Now;
            string codTrans = "TESTPS_" + data.ToString("yyyyMMddHHmmss");
            string divisa = "EUR";
            string importo = "5000";
            
            string numContratto = "NC_TEST_" + data.ToString("yyyyMMddHHmmss");
            string gruppo = "<GRUPPO>" // Sostituire con il valore fornito da Nexi

            // Calcolo MAC
            SampleXPayRequest sampleXPay = new SampleXPayRequest();
            string mac = sampleXPay.HashMac("codTrans=" + codTrans + "divisa=" + divisa + "importo=" + importo + "gruppo=" + gruppo + "num_contratto=" + numContratto + CHIAVESEGRETA);

            var requestParams = new Dictionary<string, string>();
            // Parametri obbligatori
            requestParams["alias"] = ALIAS;
            requestParams["importo"] = importo;
            requestParams["divisa"] = divisa;
            requestParams["codTrans"] = codTrans;
            requestParams["url"] = merchantServerUrl + "esito.aspx";
            requestParams["url_back"] = merchantServerUrl + "annullo.aspx";
            requestParams["mac"] = mac;
            requestParams["num_contratto"] = numContratto;
            requestParams["tipo_servizio"] = "paga_1click";
            

            // Parametri facoltativi
			
            
            /**
            * Creare un form html con metodo post verso requestUrl con campi hidden contenenti requestParams
            */
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
                    
                