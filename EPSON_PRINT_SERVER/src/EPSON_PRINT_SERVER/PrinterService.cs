using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Diagnostics;
using System.Linq;
using System.ServiceProcess;
using System.Text;
using System.Threading.Tasks;
using System.Timers;

namespace EPSON_PRINT_SERVER
{
    partial class PrinterService : ServiceBase
    {
        private static Timer aTimer;
        public PrinterService()
        {
            InitializeComponent();
        }

        protected override void OnStart(string[] args)
        {
            // TODO: Add code here to start your service.
            aTimer = new Timer(10000); // 10 Seconds
            aTimer.Elapsed += new ElapsedEventHandler(OnTimedEvent);
            aTimer.Enabled = true;
        }

        private void OnTimedEvent(object source, ElapsedEventArgs e)
        {
            PrintProcessor printer = new PrintProcessor();
            printer.Execute();
        }

        protected override void OnStop()
        {
            aTimer.Stop();
            // TODO: Add code here to perform any tear-down necessary to stop your service.
        }
    }
}
