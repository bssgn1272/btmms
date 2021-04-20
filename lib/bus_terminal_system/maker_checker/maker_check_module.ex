defmodule BusTerminalSystem.MakerCheckModule do

  import Ecto.Query, warn: false
  alias BusTerminalSystem.Repo

  defp tables do
    Repo.query!("select TABLE_NAME from information_schema.TABLES where TABLE_SCHEMA='bus_terminal_system_dev' and TABLE_NAME LIKE '%probase_tbl%'").rows
    |> List.flatten()
  end

  def unauthorised_records() do
    tables |> Enum.map(fn schema_name ->
      try do
        table = Repo.query!("select * from #{schema_name} where auth_status=false") |> IO.inspect()
        table.rows |> Enum.map(&Enum.zip(table.columns, &1)) |> Enum.map(&Enum.into(&1, %{"schema" => schema_name}))
      rescue
        _ -> %{}
      end

    end) |> List.flatten()
  end

end