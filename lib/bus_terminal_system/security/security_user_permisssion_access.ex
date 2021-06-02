defmodule BusTerminalSystemWeb.Security.UserPermissionAccess do

  alias BusTerminalSystem.AccountManager.User
  alias BusTerminalSystem.UserRoles

  def check(id, code) do
    user = User.find(id)
    UserRoles.find(user.role_id)
    |> case do
       role ->
        role.permissions
          |> Poison.decode!()
          |> Enum.member?(code)
       _ -> false
     end
  end

end