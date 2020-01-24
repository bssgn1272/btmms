defmodule BusTerminalSystem.Utility do
  use Timex

  # Create Map from json String
  def json_string_to_map(json_string) do
    a = Regex.replace(~r/([a-z0-9]+):/, json_string, "\"\\1\":")
    a |> String.replace("'", "\"") |> Poison.decode!()
  end

  def parse_date(time), do: time |> Timex.parse!("%d-%m-%Y", :strftime)

  def string_to_int(value) do
    {integer,_} = (value |> Integer.parse)
    integer
  end

  def int_to_string(value), do: "#{value}"

end