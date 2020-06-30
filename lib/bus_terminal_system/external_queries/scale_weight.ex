defmodule BusTerminalSystem.ScaleQuery do

  def query_scale(ip) do
    {a, b, c, d} = ip
    remote_ip = "#{a}.#{b}.#{c}.#{d}"
    {:ok,%HTTPoison.Response{body: body}} = HTTPoison.get("http://#{remote_ip}:4321/v1/driver/weight")
    IO.inspect("------------START WIGHT-----------------")
    IO.inspect(body)
    IO.inspect("------------END WIGHT-----------------")
    {:ok, %{"weight" => weight}} = body |> Poison.decode()
    weight

  end

end