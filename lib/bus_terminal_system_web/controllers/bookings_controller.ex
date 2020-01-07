defmodule BusTerminalSystemWeb.BookingsController do
  use BusTerminalSystemWeb, :controller

  def index(conn, _params) do
    render(conn, "index.html")
  end

  def schedule(conn, _params) do
    render(conn, "schedule.html")
  end
end
