using System;
using System.Collections;
using System.Collections.Generic;
using System.IO;
using System.Text;
using PrinterUtility;
using System.Linq;
using System.Threading.Tasks;
using System.Drawing;
using System.Globalization;

using System.Net.Sockets;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
using System.ServiceProcess;
//using System.Printing;

namespace EPSON_PRINT_SERVER
{
    class MainClass
    {






        public static void Main(string[] args)
        {
            //Console.WriteLine("Hello World!");



            if ((!Environment.UserInteractive))
            {
                MainClass.RunAsAService();
            }
            else
            {
                if (args != null && args.Length > 0)
                {
                    if (args[0].Equals("-i", StringComparison.OrdinalIgnoreCase))
                    {
                        SelfInstaller.InstallMe();
                    }
                    else
                    {
                        if (args[0].Equals("-u", StringComparison.OrdinalIgnoreCase))
                        {
                            SelfInstaller.UninstallMe();
                        }
                        else
                        {
                            Console.WriteLine("Invalid argument!");
                        }
                    }
                }
                else
                {
                    MainClass.RunAsAConsole();
                }







            }









        }

        private static void RunAsAConsole()
        {
            PrintProcessor printerProcessor = new PrintProcessor();
            printerProcessor.Execute();
        }

        private static void RunAsAService()
        {

            ServiceBase[] servicesToRun = new ServiceBase[]
           {
                new PrinterService()
           };
            ServiceBase.Run(servicesToRun);
        }
    }
}
