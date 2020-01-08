defmodule BusTerminalSystemWeb.BookingsController do
  use BusTerminalSystemWeb, :controller

  alias BusTerminalSystem.RepoManager
  alias BusTerminalSystem.AccountManager

  def index(conn, _params) do
    render(conn, "index.html")
  end

  def schedule(conn, _params) do

    route_mapping = RepoManager.list_schedules()

    render(conn, "schedule.html", schedules: route_mapping)
  end

  def create_schedule(conn, %{"payload" => mapping_params}) do

    users = AccountManager.list_users()
    tickets = RepoManager.list_tickets()
    route_mapping = RepoManager.list_schedules()

    case RepoManager.create_mapping(mapping_params) do
      {:ok, mapping} ->
        conn
        |> put_flash(:info, "Mapping created successfully.")
        |> redirect(to: Routes.bookings_path(conn, :schedule))

      {:error, %Ecto.Changeset{} = changeset} ->

        IO.inspect changeset

        conn
        |> redirect(to: Routes.user_path(conn, :index))
    end
  end

end
