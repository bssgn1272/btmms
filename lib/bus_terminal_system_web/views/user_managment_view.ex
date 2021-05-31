defmodule BusTerminalSystemWeb.UserManagementView do
  use BusTerminalSystemWeb, :view

  @moduledoc false

  import BusTerminalSystemWeb.Security.UserPermissionAccess

  def access(id, code), do: check(id, code)


end
