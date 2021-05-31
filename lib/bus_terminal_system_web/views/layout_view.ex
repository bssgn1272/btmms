defmodule BusTerminalSystemWeb.LayoutView do
  use BusTerminalSystemWeb, :view

  import BusTerminalSystemWeb.Security.UserPermissionAccess

  def access(id, code), do: check(id, code)

  def title do
    "LMB Management Company Ltd."
  end


end
