defmodule BusTerminalSystemWeb.UserView do
  use BusTerminalSystemWeb, :view
  import Plug.Conn
  alias BusTerminalSystem.AccountManager.User

  def user_id(id) do
    "<>"
  end

  def get_user_id(a) do
    "<>"
  end

  def view_conn(conn) do
    conn
    |> get_session(:current_user)
    |> User.find
  end

  def permissions(conn) do
    id = Cachex.get(:tmp, conn.assigns.user.id)
    try do
      IO.inspect("Done")
      BusTerminalSystem.UserRole.find([user: id])
      BusTerminalSystem.UserRoles.all
    rescue
      _ ->
        IO.inspect("Failed")
        []
    end

  end

end

