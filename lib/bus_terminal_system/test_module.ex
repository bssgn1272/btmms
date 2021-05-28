defmodule TestModule do
  alias BusTerminalSystem.AccountManager.User
  alias BusTerminalSystem.Repo

  import Ecto.Query, warn: false

  def case1 do
    query = from u in User, where: u."username" == "manager"
    User.where(query, limit: 1)
  end

  def case2(column \\ "username", value \\ "") do
#    query = from u in User, where: u."#{column}" == ^value
    query = "SELECT * FROM probase_tbl_users WHERE #{column}='#{value}'"
    {status, result} = Repo.query(query)
    result.num_rows
  end

  def json do
    %{
      "errorList" => %{},
      "operation_status" => "SUCCESS",
      "preauthUUID" => "b2cf000e-2435-4fa8-ad48-5fed7b251906",
      "request" => %{
        "accountType" => "WB",
        "isfetchAllAccounts" => false,
        "mobileNo" => "260976815726"
      },
      "request-reference" => "2021285-ZICB-1622194200",
      "response" => %{
        "custAccDetails" => [
          %{
            "accDesc" => "ilekeshamenda",
            "accType" => "W",
            "accountNo" => "1019000001566",
            "branch" => "101",
            "ccy" => "ZMW",
            "custNo" => "9000520",
            "mobileNo" => "976815726",
            "nationalId" => nil,
            "noCredit" => "N",
            "noDebit" => "N",
            "passport" => nil,
            "statDormant" => "N",
            "statFrozen" => "N",
            "status" => "A",
            "telephoneNo" => nil,
            "uniqueIdName" => "DRIVING LICENCE",
            "uniqueIdVal" => "743177"
          }
        ],
        "tekHeader" => %{
          "errList" => %{},
          "hostrefno" => nil,
          "msgList" => %{},
          "status" => "SUCCESS",
          "tekesbrefno" => "9b0196cb-70d3-0938-ddd3-a124506a31fe",
          "username" => "DEMO",
          "warnList" => %{}
        }
      },
      "status" => 200,
      "timestamp" => 1622194200669
    } |> Poison.encode!()

  end
  
end