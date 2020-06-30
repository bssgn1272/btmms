defmodule BusTerminalSystem.ScaleQuery do

  def query_scale(ip) do
    {:ok,%HTTPoison.Response{body: body}} = HTTPoison.get("http://#{ip}:4321/v1/driver/weight")
    {:ok, %{"weight" => weight}} = body |> Poison.decode()
    weight

  end

end