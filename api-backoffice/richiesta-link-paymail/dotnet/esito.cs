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

            // chiave segreta
            string CHIAVESEGRETA = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Sostituire con il valore fornito da CartaSi

            // Calcolo MAC
            string mac = HashMac(  "codTrans=" + args.codTrans +
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

        public static string HashMac(string s)
        {
            byte[] bytes = Encoding.UTF8.GetBytes(s);

            var sha1 = SHA1.Create();
            byte[] hashBytes = sha1.ComputeHash(bytes);

            return HexStringFromBytes(hashBytes);
        }
    }
}