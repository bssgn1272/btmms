defmodule BusTerminalSystem.Utility do

  # Create Map from json String
  def json_string_to_map(json_string) do
    a = Regex.replace(~r/([a-z0-9]+):/, json_string, "\"\\1\":")
    a |> String.replace("'", "\"") |> Poison.decode!()
  end
  
end