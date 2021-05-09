defmodule BusTerminalSystem.BusManagement do
  @moduledoc """
  The BusManagement context.
  """
  import Ecto.Query, warn: false
  alias BusTerminalSystem.Repo

  alias BusTerminalSystem.BusManagement.Bus

  @doc """
  Returns the list of bus_terminus.

  ## Examples

      iex> list_bus_terminus()
      [%BusTerminus{}, ...]

  """
  def list_bus_terminus do
    Repo.all(from t in Bus, where: [auth_status: 1])
  end

  @doc """
  Gets a single bus_terminus.

  Raises `Ecto.NoResultsError` if the Bus terminus does not exist.

  ## Examples

      iex> get_bus_terminus!(123)
      %BusTerminus{}

      iex> get_bus_terminus!(456)
      ** (Ecto.NoResultsError)

  """
  def get_bus_terminus!(id), do: Repo.get!(Bus, id)

  def get_bus_terminus_query(query) do
    query_result = Repo.get_by(Bus, query)
    if query_result != nil do
      :bus_exists
    else
      :ok
    end
  end

  @doc """
  Creates a bus_terminus.

  ## Examples

      iex> create_bus_terminus(%{field: value})
      {:ok, %BusTerminus{}}

      iex> create_bus_terminus(%{field: bad_value})
      {:error, %Ecto.Changeset{}}

  """
  def create_bus_terminus(conn, params) do
    Bus.create(
      vehicle_capacity: params["vehicle_capacity"],
      fitness_license: params["fitness_license"],
      license_plate: params["license_plate"],
      uid: params["uid"],
      engine_type: params["engine_type"],
      model: params["model"],
      make: params["make"],
      year: params["year"],
      color: params["color"],
      state_of_registration: params["state_of_registration"],
      vin_number: params["vin_number"],
      serial_number: params["serial_number"],
      hull_number: params["hull_number"],
      vehicle_class: params["vehicle_class"],
      operator_id: params["bus_operator"],
      company: params["company"],
      company_info: params["company_info"],
      auth_status: 0,
      maker: conn.assigns.user.id,
      maker_date_time: Timex.now() |> NaiveDateTime.truncate(:second) |> Timex.to_naive_datetime(),
      user_description: params["user_description"],
      system_description: "Request to add bus by #{conn.assigns.user.first_name} #{conn.assigns.user.last_name} at #{Timex.today()}")
    |> case do
         {:ok, bus} ->
           IO.inspect "passed"
           spawn(fn -> BusTerminalSystem.Cosec.register_to_cosec(bus) end)
           {:ok, "New Bus Created"}
         {:error, error} ->
           IO.inspect "failed"
           IO.inspect error.errors
           {:error, "Please insert all fields!"}
       end
  end

  @doc """
  Updates a bus_terminus.

  ## Examples

      iex> update_bus_terminus(bus_terminus, %{field: new_value})
      {:ok, %BusTerminus{}}

      iex> update_bus_terminus(bus_terminus, %{field: bad_value})
      {:error, %Ecto.Changeset{}}

  """
  def update_bus_terminus(%Bus{} = bus_terminus, attrs) do
    bus_terminus
    |> Bus.changeset(attrs)
    |> Repo.update()
  end

  @doc """
  Deletes a BusTerminus.

  ## Examples

      iex> delete_bus_terminus(bus_terminus)
      {:ok, %BusTerminus{}}

      iex> delete_bus_terminus(bus_terminus)
      {:error, %Ecto.Changeset{}}

  """
  def delete_bus_terminus(%Bus{} = bus_terminus) do
    Repo.delete(bus_terminus)
  end

  @doc """
  Returns an `%Ecto.Changeset{}` for tracking bus_terminus changes.

  ## Examples

      iex> change_bus_terminus(bus_terminus)
      %Ecto.Changeset{source: %BusTerminus{}}

  """
  def change_bus_terminus(%Bus{} = bus_terminus) do
    Bus.changeset(bus_terminus, %{})
  end
end
