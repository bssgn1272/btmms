defmodule BusTerminalSystemWeb.RouteController do
  use BusTerminalSystemWeb, :controller

  alias BusTerminalSystem.ApiManager
  alias BusTerminalSystem.RepoManager
  alias BusTerminalSystem.Randomizer
  alias BusTerminalSystem.AccountManager

  def index(conn, _params) do
    routes = RepoManager.list_routes()
    render(conn, "index.html",routes: routes)
  end

  def customise_routes(conn, _params) do
    render(conn, "routes.html")
  end

  def create(conn, %{"payload" => payload} = params) do
    payload = Map.put(payload,"start_route","Livingstone")

    case RepoManager.create_route(conn, payload) do
      {:ok, route} ->
        conn
        |> put_flash(:info, "Route created successfully.")
        |> redirect(to: Routes.route_path(conn, :index))

      {:error, %Ecto.Changeset{} = changeset} ->

        IO.inspect changeset

        conn
        |> redirect(to: Routes.route_path(conn, :index))
    end
  end
end
