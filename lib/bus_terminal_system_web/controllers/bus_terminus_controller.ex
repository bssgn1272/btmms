defmodule BusTerminalSystemWeb.BusTerminusController do
  use BusTerminalSystemWeb, :controller

  alias BusTerminalSystem.BusManagement
  alias BusTerminalSystem.BusManagement.Bus

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
    changeset = BusManagement.change_bus_terminus(%Bus{})
    render(conn, "new.html", changeset: changeset)
  end

  def create(conn, %{"payload" => bus_terminus_params}) do
    buses = BusManagement.list_bus_terminus()
    IO.inspect bus_terminus_params

    [license_plate: bus_terminus_params["license_plate"]] |> BusManagement.get_bus_terminus_query |> case do
      :bus_exists ->
         conn
         |> put_flash(:error, "This Bus is already registered.")
         |> redirect(to: Routes.user_path(conn, :index))
      :ok ->
        case BusManagement.create_bus_terminus(bus_terminus_params) do
          {:ok, bus_terminus} ->
            conn
            |> put_flash(:info, "Bus Created Successfully.")
            |> redirect(to: Routes.user_path(conn, :index))

          {:error, %Ecto.Changeset{} = changeset} ->
            IO.inspect changeset
            render(conn, "new.html", changeset: changeset)
        end
    end


  end

  def createTerminus(conn, _params) do
    changeset = BusManagement.change_bus_terminus(%Bus{})
    render(conn, "createTerminus.html", changeset: changeset)
  end

  def new(conn, _params) do
    changeset = BusManagement.change_bus_terminus(%Bus{})
    render(conn, "createStation.html", changeset: changeset)
  end

  def new(conn, _params) do
    changeset = BusManagement.change_bus_terminus(%Bus{})
    render(conn, "createGate.html", changeset: changeset)
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

  def form_teminus(conn, _params) do
    render(conn, "form_teminus.html")
  end

  def form_station(conn, _params) do
    render(conn, "form_station.html")
  end

  def form_gate(conn, _params) do
    render(conn, "form_gate.html")
  end

  def bus_approval(conn, _params) do
    bus_terminus = BusManagement.list_bus_terminus()
    render(conn, "bus_approval.html", bus_terminus: bus_terminus)
  end
end
