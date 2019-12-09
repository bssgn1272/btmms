defmodule BusTerminalSystemWeb.BusTerminusController do
  use BusTerminalSystemWeb, :controller

  alias BusTerminalSystem.BusManagement
  alias BusTerminalSystem.BusManagement.BusTerminus

  plug(
    BusTerminalSystemWeb.Plugs.RequireAuth
    when action in [
      :index,
      :new,
      :create,
      :show,
      :edit,
      :update,
      :delete
    ]
  )

  def index(conn, _params) do
    bus_terminus = BusManagement.list_bus_terminus()
    render(conn, "index.html", bus_terminus: bus_terminus)
  end

  def new(conn, _params) do
    changeset = BusManagement.change_bus_terminus(%BusTerminus{})
    render(conn, "new.html", changeset: changeset)
  end

  def create(conn, %{"vehicle" => bus_terminus_params}) do
    case BusManagement.create_bus_terminus(bus_terminus_params) do
      {:ok, bus_terminus} ->
        conn
        |> put_flash(:info, "Bus terminus created successfully.")
        |> redirect(to: Routes.bus_terminus_path(conn, :show, bus_terminus))

      {:error, %Ecto.Changeset{} = changeset} ->
        render(conn, "new.html", changeset: changeset)
    end
  end

  def show(conn, %{"id" => id}) do
    bus_terminus = BusManagement.get_bus_terminus!(id)
    render(conn, "show.html", bus_terminus: bus_terminus)
  end

  def edit(conn, %{"id" => id}) do
    bus_terminus = BusManagement.get_bus_terminus!(id)
    changeset = BusManagement.change_bus_terminus(bus_terminus)
    render(conn, "edit.html", bus_terminus: bus_terminus, changeset: changeset)
  end

  def update(conn, %{"id" => id, "bus_terminus" => bus_terminus_params}) do
    bus_terminus = BusManagement.get_bus_terminus!(id)

    case BusManagement.update_bus_terminus(bus_terminus, bus_terminus_params) do
      {:ok, bus_terminus} ->
        conn
        |> put_flash(:info, "Bus terminus updated successfully.")
        |> redirect(to: Routes.bus_terminus_path(conn, :show, bus_terminus))

      {:error, %Ecto.Changeset{} = changeset} ->
        render(conn, "edit.html", bus_terminus: bus_terminus, changeset: changeset)
    end
  end

  def delete(conn, %{"id" => id}) do
    bus_terminus = BusManagement.get_bus_terminus!(id)
    {:ok, _bus_terminus} = BusManagement.delete_bus_terminus(bus_terminus)

    conn
    |> put_flash(:info, "Bus terminus deleted successfully.")
    |> redirect(to: Routes.bus_terminus_path(conn, :index))
  end
end
