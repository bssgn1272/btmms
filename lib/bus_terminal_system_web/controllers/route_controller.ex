defmodule BusTerminalSystemWeb.RouteController do
  use BusTerminalSystemWeb, :controller

  def index(conn, _params) do
    render(conn, "index.html")
  end

  def customise_routes(conn, _params) do
    render(conn, "routes.html")
  end
end
