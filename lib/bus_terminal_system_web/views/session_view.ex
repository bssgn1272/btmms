defmodule BusTerminalSystemWeb.SessionView do
  use BusTerminalSystemWeb, :view

  import BusTerminalSystemWeb.Security.UserPermissionAccess

  def access(id, code), do: check(id, code)
end