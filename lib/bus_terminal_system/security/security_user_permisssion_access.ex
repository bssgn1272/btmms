defmodule BusTerminalSystemWeb.Security.UserPermissionAccess do

  alias BusTerminalSystem.AccountManager.User
  alias BusTerminalSystem.UserRoles

  def check(id, code) do
    user = User.find(id)
    UserRoles.find(user.role_id)
    |> IO.inspect
    |> case do
       role ->
        role.permissions
          |> Poison.decode!()
          |> IO.inspect
          |> Enum.member?(code)
       _ -> false
     end
  end

end