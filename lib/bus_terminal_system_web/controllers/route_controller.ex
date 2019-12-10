defmodule BusTerminalSystemWeb.RouteController do
  use BusTerminalSystemWeb, :controller

  def index(conn, _params) do
    render(conn, "index.html")
  end
end
