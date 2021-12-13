                                            
// Pagamento OneClik - Pagamenti successivi - Tramite redirezione - Notifica

using System;
using System.Collections.Generic;
using System.Text;
using System.Security.Cryptography;

namespace XPay
{
    class SampleXPayResponse
    {
        static void Main(string[] args)
        {

            // Chiave segreta
            string CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da Nexi

            // Calcolo MAC
            SampleXPayRequest sampleXPay = new SampleXPayRequest();
            string mac = sampleXPay.HashMac(  "codTrans=" + args.codTrans +
                                              "esito=" + args.esito +
                                              "importo=" + args.importo +
                                              "divisa=" + args.divisa +
                                              "data=" + args.data +
                                              "orario=" + args.orario +
                                              "codAut=" + args.codAut +
                                              CHIAVESEGRETA);

            // Verifico corrispondenza tra MAC calcolato e MAC di ritorno
            if(mac != args.mac)
            {
                Console.WriteLine("Errore MAC: " + mac + " non corrisponde a " + args.mac);
            } else
            {
                // Nel caso in cui non ci siano errori gestisco il parametro esito
                if (args.esito == "OK")
                {
                    Console.WriteLine("OK, pagamento avvenuto, preso riscontro");
                } else
                {
                    Console.WriteLine("KO, pagamento non avvenuto, preso riscontro");
                }
            }


        }
    }
}
                    
                