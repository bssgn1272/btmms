using System.ComponentModel;
using System.Configuration.Install;
using System.ServiceProcess;

namespace EPSON_PRINT_SERVER
{
    [RunInstaller(true)]
    public class PrinterSvxServiceInstaller : Installer
    {
        public PrinterSvxServiceInstaller()
        {
            ServiceProcessInstaller serviceProcessInstaller = new ServiceProcessInstaller();
            ServiceInstaller serviceInstaller = new ServiceInstaller();

            // Setup the Service Account type per your requirement
            serviceProcessInstaller.Account = ServiceAccount.LocalSystem;
            serviceProcessInstaller.Username = null;
            serviceProcessInstaller.Password = null;


            serviceInstaller.ServiceName = "BTMMS Printer Service";
            serviceInstaller.DisplayName = "BTMMS Printer Service";
            serviceInstaller.StartType = ServiceStartMode.Automatic;
            serviceInstaller.Description = "Probase EPSON printer sevice";

            this.Installers.Add(serviceProcessInstaller);
            this.Installers.Add(serviceInstaller);
        }

    }
}