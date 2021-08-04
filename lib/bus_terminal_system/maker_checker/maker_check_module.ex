defmodule BusTerminalSystem.MakerCheckModule do

  import Ecto.Query, warn: false
  alias BusTerminalSystem.Repo
  alias BusTerminalSystem.AccountManager.User

  defp tables do
    BusTerminalSystem.Repo.query!("select TABLE_NAME from information_schema.TABLES where TABLE_SCHEMA='btmms' and TABLE_NAME LIKE 'probase_%'").rows |> List.flatten |> IO.inspect(lable: "ROWS")
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
#(User.find_by(id: user.(row |> Enum.at(0))).username
  def maker_checker() do
    tables |> Enum.map(fn table ->

#      try do
        Repo.query("SELECT maker, maker_date_time, user_description, system_description, id
        FROM #{table} WHERE auth_status='0'") |> case do
             {:ok, fields} ->
               fields.rows |> case do
                    [] -> nil
                    rows ->
                      rows |> Enum.map(fn row ->
                        %{schema: table, id: row |> Enum.at(4), maker: row |> Enum.at(0),
                          maker_date_time: row |> Enum.at(1), user_description: row |> Enum.at(2), system_description: row |> Enum.at(3)}
                      end)
                  end
             {:error, _} -> nil
           end
#      rescue
#        _ -> nil
#      end (User.find_by(id: row |> Enum.at(0)).username || 1)
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
             "probase_tbl_users" -> [data
               |> Map.delete("password")
               |> Map.delete("pin")
               |> Map.delete("company")
               |> Map.delete("role")
               |> Map.delete("uuid")
               |> Map.delete("account_status")
#               |> Map.delete("operator_role")
               |> Map.delete("tmp_pin")
               |> Map.delete("company")
               |> Map.delete("account_number")
               |> Map.delete("account_type")
               |> Map.delete("apply_discount")
               |> Map.delete("discount_amount")
               |> Map.delete("discount_reason")
               |> Map.delete("compliance")
               |> Map.delete("employer_number")
               |> Map.delete("dob")
               |> Map.delete("sex")
               |> Map.delete("bank_message")
               |> Map.delete("bank_account_status")
               |> Map.delete("bank_account_balance")
               |> Map.delete("bank_srcBranch")
               |> Map.delete("bank_destBranch")
               |> Map.delete("system_description")
               |> Map.delete("bank_destBranch")
               |> Map.delete("compliance")
               |> Map.delete("token")
               |> Map.delete("role_id")
               |> Map.delete("created_at")
               |> Map.delete("deleted_at")
               |> Map.delete("inserted_at")
               |> Map.delete("updated_at")
               |> Map.delete("status")
               |> Map.delete("tel")
               |> Map.delete("route_uuid") ]
             _ -> [data]
           end
       end
  end

  def approve(conn, params) do
    Repo.query("UPDATE "<>params["table"]<>" SET auth_status='1', checker='#{conn.assigns.user.id}' WHERE id="<>params["id"]<>";")
    |> case do
         {:ok, _} -> {:ok, "Approval Successful!"}
         {:error} -> {:error, "Error Code: 200-9099"}
       end
  end

  def reject(conn, params) do

    if params["table"] == "probase_tbl_users" do
      Repo.query("select script,max(script_date) from probase_tbl_auth_hist where id_fk=#{params["id"]} and table_name='PROBASE_TBL_USERS'")
      |> case do
           {:ok, _} ->
             {:ok, "Successfully Rejected user Request"}
           {:error, error} ->
             IO.inspect error
             {:error, "Failed to reject request, An Error Occurred"}
         end
    else
      Repo.query("UPDATE "<>params["table"]<>" SET auth_status=1 WHERE id="<>params["id"]<>";")
      |> case do
           {:ok, _} ->
             {:ok, "Successfully Rejected Request"}
           {:error, error} ->
             IO.inspect error
             {:error, "Failed to reject request, An Error Occurred"}
         end
    end
    end
end