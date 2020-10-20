defmodule BusTerminalSystem.MakerCheckModule do

  import Ecto.Query, warn: false
  alias BusTerminalSystem.Repo
  alias BusTerminalSystem.AccountManager.User

  defp tables do
    Repo.query!("select TABLE_NAME from information_schema.TABLES where TABLE_SCHEMA='bus_terminal_system_dev' and TABLE_NAME LIKE 'probase_%'").rows
  end

#  def unauthorised_records() do
#    tables |> Enum.map(fn schema_name ->
#      try do
#        table = Repo.query!("select * from #{schema_name} where auth_status='0'") |> IO.inspect()
#        table.rows
#        |> Enum.map(&Enum.zip(table.columns, &1)) |> Enum.map(&Enum.into(&1, %{"schema" => schema_name}))
#      rescue
#        _ -> %{}
#      end
#    end) |> List.flatten()
#  end

  def maker_checker() do
    tables |> Enum.map(fn table ->
      try do
        Repo.query("SELECT maker, maker_date_time, user_description, system_description, id
        FROM #{table |> List.to_string} WHERE auth_status='0'") |> case do
             {:ok, fields} ->
               fields.rows |> case do
                    [] -> nil
                    rows ->
                      rows |> Enum.map(fn row ->
                        %{schema: table |> List.to_string, id: row |> Enum.at(4), maker: User.find_by(id: row |> Enum.at(0)).username,
                          maker_date_time: row |> Enum.at(1), user_description: row |> Enum.at(2), system_description: row |> Enum.at(3)}
                      end)
                  end
             {:error, _} -> nil
           end
      rescue
        _ -> nil
      end
    end)
    |> Enum.filter(& !is_nil(&1))
    |> List.flatten()
  end

  def checker_view(conn, params) do
    Repo.query("SELECT * FROM "<>params["table"]<>" WHERE id="<> params["id"] <>";")
    |> case do
         {:ok, %{columns: columns, rows: rows}} ->
           data1 = rows |> Enum.map(&Enum.zip(columns, &1)) |> Enum.map(&Enum.into(&1, %{})) |> Enum.at(0)
           data = data1 |> Map.delete("maker") |> Map.put("maker", User.find_by(id: data1["maker"]).username)
                  |> Map.delete("id") |> Map.delete("auth_status") |> Map.delete("checker")
                  |> Map.delete("checker_date_time") |> Map.delete("checker_date_time")
           case params["table"] do
             "probase_tbl_travel_routes" ->
               [data |> Map.delete("ticket_id") |> Map.delete("route_uuid") ]
             _ -> [data]
           end
       end
  end

  def approve(conn, params) do
    IO.inspect params, label: "APPROVE FUNCTION"
    Repo.query("UPDATE "<>params["table"]<>" SET auth_status='1' WHERE id="<>params["id"]<>";")
    |> case do
         {:ok, _} -> {:ok, "Approval Successful!"}
         {:error} -> {:error, "Error Code: 200-9099"}
       end
  end

  def reject(conn, params) do
    Repo.query("DELETE FROM "<>params["table"]<>" WHERE id="<>params["id"]<>";")
    |> case do
         {:ok, _} ->

           # Chipasha add your email thingy here

           {:ok, "Successfully Rejected Request"}
         {:error, error} ->
           IO.inspect(error.errors)
           {:error, "Error Code: 200-2990"}
       end
  end
end