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
  
end