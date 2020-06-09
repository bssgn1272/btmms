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
    Repo.all(Bus)
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
  def create_bus_terminus(attrs \\ %{}) do
    %Bus{}
    |> Bus.changeset(attrs)
    |> Repo.insert()
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
