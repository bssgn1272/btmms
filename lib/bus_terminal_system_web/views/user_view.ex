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

end

