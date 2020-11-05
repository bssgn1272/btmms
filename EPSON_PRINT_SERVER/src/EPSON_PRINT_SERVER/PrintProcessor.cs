using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
using PrinterUtility;
using System;
using System.Collections;
using System.Collections.Generic;
using System.Drawing;
using System.Globalization;
using System.IO;
using System.Linq;
using System.Net.Sockets;
using System.Text;
using System.Threading.Tasks;

namespace EPSON_PRINT_SERVER
{
    internal class PrintProcessor
    {
        internal void Execute()
        {
            try
            {
                Process();
            }
            catch (Exception e)
            {
                Console.WriteLine("Exception: " + e.Message);
            }
        }

        public class ticket
        {
            public string refNumber { get; set; }
            public string fName { get; set; }
            public string sName { get; set; }

            public string Price { get; set; }
            public string[] items { get; set; }

            public string from { get; set; }
            public string to { get; set; }
            public string ticketNumber { get; set; }
            public string depatureTime { get; set; }
            public string Bus { get; set; }
            public string gate { get; set; }
        }





        private void Process()
        {


            DateTime nowDate = DateTime.Now;                            //System date
            DateTimeFormatInfo dateFormat = new DateTimeFormatInfo();   //Date Format
            dateFormat.MonthDayPattern = "MMMM";
            string strDate = nowDate.ToString("MMMM,dd,yyyy  HH:mm", dateFormat);





            /*var server = new PrintServer();
            Console.WriteLine("Listing Shared Printers");
            var queues = server.GetPrintQueues(new[]
            { EnumeratedPrintQueueTypes.Shared, EnumeratedPrintQueueTypes.Connections });
            foreach (var item in queues)
            {
                Console.WriteLine(item.FullName);
            }
            Console.WriteLine("\nListing Local Printers Now");
            queues = server.GetPrintQueues(new[]
            { EnumeratedPrintQueueTypes.Local });
            foreach (var item in queues)
            {
                Console.WriteLine(item.FullName);
            }
            Console.ReadLine();
            */


            TcpListener listener = new TcpListener(System.Net.IPAddress.Any, 1302);
            listener.Start();
            while (true)
            {
                Console.WriteLine("Waiting for a connection(s).");
                TcpClient client = listener.AcceptTcpClient();
                Console.WriteLine("Client accepted.");
                NetworkStream stream = client.GetStream();
                StreamReader sr = new StreamReader(client.GetStream());
                StreamWriter sw = new StreamWriter(client.GetStream());
                try
                {
                    byte[] buffer = new byte[1024];
                    stream.Read(buffer, 0, buffer.Length);
                    int recv = 0;
                    foreach (byte b in buffer)
                    {
                        if (b != 0)
                        {
                            recv++;
                        }
                    }
                    string request = Encoding.UTF8.GetString(buffer, 0, recv);
                    JObject json = JObject.Parse(request);
                    ticket ticketObj = JsonConvert.DeserializeObject<ticket>(request);
                    
                 

                    //  Console.WriteLine("request FNAME  "+ ticketObj.fName);
                    sw.WriteLine("{ \"Message\":\"Successfully Printed\" }");
                    sw.Flush();

                    PrinterUtility.EscPosEpsonCommands.EscPosEpson obj = new PrinterUtility.EscPosEpsonCommands.EscPosEpson();
                    //  var BytesValue = GetLogo("@D:\\logo.bmp");

                    /*  BytesValue = PrintExtensions.AddBytes(BytesValue,obj.Separator);
                      BytesValue = PrintExtensions.AddBytes(BytesValue, obj.CharSize.DoubleWidth6);
                      BytesValue = PrintExtensions.AddBytes(BytesValue, obj.FontSelect.FontA);
                      BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Alignment.Center);
                      BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("TITLE\n"));
                      BytesValue = PrintExtensions.AddBytes(BytesValue, obj.CharSize.Nomarl);
                      BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Separator);
                      BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Alignment.Left);
                      BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("TICKET No... :\n"));
                      BytesValue = PrintExtensions.AddBytes(BytesValue, obj.BarCode.Code128("123456"));
                      BytesValue = PrintExtensions.AddBytes(BytesValue, obj.QrCode.Print("12345678"),PrinterUtility.Enums.QrCodeSize.Medio);
                      BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Alignment.Left);
                      BytesValue = PrintExtensions.AddBytes(BytesValue, CutPage());
                      PrinterUtility.PrintExtensions.Print(BytesValue);*/
                    var BytesValue = Encoding.ASCII.GetBytes(string.Empty);
                    /*BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Separator());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.CharSize.DoubleWidth6());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.FontSelect.FontA());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Alignment.Center());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("     Title\n"));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.CharSize.DoubleWidth4());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("     Sub Title\n"));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.CharSize.Nomarl());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Separator());*/
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.CharSize.Nomarl());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Alignment.Center());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("BTMMS BOARDING PASS \n"));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Lf());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Lf());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Alignment.Left());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("     TICKET REF.       :" + ticketObj.refNumber + "\n"));
                   // BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("     Print Date        :" + strDate + "\n"));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("     First Name        :" + ticketObj.fName + " \n"));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("     Last Name         :" + ticketObj.sName + "\n"));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("     From              :" + ticketObj.from + "\n"));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("     To                :" + ticketObj.to + "\n"));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("     Departure         :" + ticketObj.depatureTime + "\n"));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("     Bus               :" + ticketObj.Bus + "\n"));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("     Gate              :" + ticketObj.gate + "\n"));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Separator());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.CharSize.Nomarl());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("     Itm                                          Total\n"));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Separator());
                    foreach (var item in ticketObj.items)
                    {
                        string[] words = item.Split(':');


                        item.Replace(":", "                  ");

                        Console.WriteLine("Arrray items:" + item.Replace(":", "                   "));
                        BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes(item.Replace(":", "                   ") + "\n"));
                    }
                   // BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("     TICKET                   1                  "+ ticketObj.Price+"\n"));
                    // BytesValue = PrintExtensions.AddBytes(BytesValue, string.Format("{0,-40}{1,6}{2,9}{3,9:N2}\n", "TICKET", 1, "", ticketObj.Price));
                    //BytesValue = PrintExtensions.AddBytes(BytesValue, string.Format("{0,-40}{1,6}{2,9}{3,9:N2}\n", "item 2", 2, 0, ticketObj.Price));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Alignment.Right());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Separator());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("Total\n"));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("ZMW " + ticketObj.Price + "\n"));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Separator());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Lf());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Alignment.Center());
                    //BytesValue = PrintExtensions.AddBytes(BytesValue, obj.CharSize.DoubleHeight6());
                    //BytesValue = PrintExtensions.AddBytes(BytesValue, obj.BarCode.Code128("12345"));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.QrCode.Print(ticketObj.ticketNumber, PrinterUtility.Enums.QrCodeSize.Gigante));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Lf());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, "-------------------      Thank you    ------------------------\n");
                   // BytesValue = PrintExtensions.AddBytes(BytesValue, Encoding.ASCII.GetBytes("     Print Date        :" + strDate + "\n"));
                    BytesValue = PrintExtensions.AddBytes(BytesValue, obj.Alignment.Left());
                    BytesValue = PrintExtensions.AddBytes(BytesValue, CutPage());



                    PrinterUtility.PrintExtensions.Print(BytesValue, "\\\\127.0.0.1\\EPS");




                }
                catch (Exception e)
                {
                    Console.WriteLine("Something went wrong stack." + e.Message+" "+e.StackTrace);
                    sw.WriteLine(e.ToString());
                }
            }



        }

        private static byte[] CutPage()
        {
            List<byte> oby = new List<byte>();
            oby.Add(Convert.ToByte(Convert.ToChar(0x1D)));
            oby.Add(Convert.ToByte('V'));
            oby.Add((byte)66);
            oby.Add((byte)3);
            return oby.ToArray();

        }

        public byte[] GetLogo(string LogoPath)
        {
            List<byte> byteList = new List<byte>();
            if (!File.Exists(LogoPath))
                return null;
            BitmapData data = GetBitmapData(LogoPath);
            BitArray dots = data.Dots;
            byte[] width = BitConverter.GetBytes(data.Width);

            int offset = 0;
            MemoryStream stream = new MemoryStream();
            // BinaryWriter bw = new BinaryWriter(stream);
            byteList.Add(Convert.ToByte(Convert.ToChar(0x1B)));
            //bw.Write((char));
            byteList.Add(Convert.ToByte('@'));
            //bw.Write('@');
            byteList.Add(Convert.ToByte(Convert.ToChar(0x1B)));
            // bw.Write((char)0x1B);
            byteList.Add(Convert.ToByte('3'));
            //bw.Write('3');
            //bw.Write((byte)24);
            byteList.Add((byte)24);
            while (offset < data.Height)
            {
                byteList.Add(Convert.ToByte(Convert.ToChar(0x1B)));
                byteList.Add(Convert.ToByte('*'));
                //bw.Write((char)0x1B);
                //bw.Write('*');         // bit-image mode
                byteList.Add((byte)33);
                //bw.Write((byte)33);    // 24-dot double-density
                byteList.Add(width[0]);
                byteList.Add(width[1]);
                //bw.Write(width[0]);  // width low byte
                //bw.Write(width[1]);  // width high byte

                for (int x = 0; x < data.Width; ++x)
                {
                    for (int k = 0; k < 3; ++k)
                    {
                        byte slice = 0;
                        for (int b = 0; b < 8; ++b)
                        {
                            int y = (((offset / 8) + k) * 8) + b;
                            // Calculate the location of the pixel we want in the bit array.
                            // It'll be at (y * width) + x.
                            int i = (y * data.Width) + x;

                            // If the image is shorter than 24 dots, pad with zero.
                            bool v = false;
                            if (i < dots.Length)
                            {
                                v = dots[i];
                            }
                            slice |= (byte)((v ? 1 : 0) << (7 - b));
                        }
                        byteList.Add(slice);
                        //bw.Write(slice);
                    }
                }
                offset += 24;
                byteList.Add(Convert.ToByte(0x0A));
                //bw.Write((char));
            }
            // Restore the line spacing to the default of 30 dots.
            byteList.Add(Convert.ToByte(0x1B));
            byteList.Add(Convert.ToByte('3'));
            //bw.Write('3');
            byteList.Add((byte)30);
            return byteList.ToArray();
            //bw.Flush();
            //byte[] bytes = stream.ToArray();
            //return logo + Encoding.Default.GetString(bytes);
        }


        public BitmapData GetBitmapData(string bmpFileName)
        {
            using (var bitmap = (Bitmap)Bitmap.FromFile(bmpFileName))
            {
                var threshold = 127;
                var index = 0;
                double multiplier = 570; // this depends on your printer model. for Beiyang you should use 1000
                double scale = (double)(multiplier / (double)bitmap.Width);
                int xheight = (int)(bitmap.Height * scale);
                int xwidth = (int)(bitmap.Width * scale);
                var dimensions = xwidth * xheight;
                var dots = new BitArray(dimensions);

                for (var y = 0; y < xheight; y++)
                {
                    for (var x = 0; x < xwidth; x++)
                    {
                        var _x = (int)(x / scale);
                        var _y = (int)(y / scale);
                        var color = bitmap.GetPixel(_x, _y);
                        var luminance = (int)(color.R * 0.3 + color.G * 0.59 + color.B * 0.11);
                        dots[index] = (luminance < threshold);
                        index++;
                    }
                }

                return new BitmapData()
                {
                    Dots = dots,
                    Height = (int)(bitmap.Height * scale),
                    Width = (int)(bitmap.Width * scale)
                };
            }
        }



        public class BitmapData
        {
            public BitArray Dots
            {
                get;
                set;
            }

            public int Height
            {
                get;
                set;
            }

            public int Width
            {
                get;
                set;
            }
        }



    }




}
