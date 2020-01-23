defmodule ScaleDriver.Driver do
  def get_weight() do
    {:ok, pid} = Circuits.UART.start_link
    devices = Circuits.UART.enumerate |> Map.keys
    [device1] = devices
    open_status = Circuits.UART.open(pid, device1, speed: 9600, active: false)

    Circuits.UART.write(pid, "query\r\n")

     data = Circuits.UART.read(pid, 1000)
     Circuits.UART.close(pid)

    {pid, device1,{status,str_value} = data}
    
     result = str_value |> String.split("\r\n")

     [v1 | _] = result
     [_,v2,_] = String.split(v1,",")
     v3 = String.replace(v2,~r/\s+/,"")
     v3 |> String.slice(2..-1)
  end
end
