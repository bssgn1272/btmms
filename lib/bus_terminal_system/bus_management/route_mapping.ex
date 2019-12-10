defmodule BusTerminalSystem.RouteMapping do
  use Ecto.Schema
  import Ecto.Changeset

  schema "tbl_route_mapping" do
    field :operator_id, :string
    field :bus_id, :string
    field :route_id, :string
    field :fare, :integer

    timestamps()
  end

  @doc false
  def changeset(route_mapping, attrs) do
    route_mapping
    |> cast(attrs, [:operator_id, :bus_id, :route_id, :fair])
    |> validate_required([:operator_id, :bus_id, :route_id, :fair])
  end

end