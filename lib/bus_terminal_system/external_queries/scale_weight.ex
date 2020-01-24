defmodule BusTerminalSystem.ScaleQuery do

  def query_scale do

    {:ok,%HTTPoison.Response{body: body}} = HTTPoison.get("localhost:4321/v1/driver/weight")
    {:ok, %{"weight" => weight}} = body |> Poison.decode()
    weight

  end

end