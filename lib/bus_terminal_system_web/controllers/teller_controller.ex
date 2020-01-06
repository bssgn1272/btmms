defmodule BusTerminalSystemWeb.TellerController do
  use BusTerminalSystemWeb, :controller

  def index(conn, _params) do
    render(conn, "index.html")
  end

  def documentation(conn, _params) do
    render(conn, "documentation.html")
  end

  def reports(conn, _params) do
    render(conn, "reports.html")
  end
end
