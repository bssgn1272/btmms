defmodule BusTerminalSystem.TravelRoutes do
  use Ecto.Schema
  import Ecto.Changeset

  @derive {Poison.Encoder, only: [:id, :route_name, :start_route, :end_route, :route_code, :source_state]}
  schema "probase_tbl_travel_routes" do
    field :route_name, :string
    field :start_route, :string
    field :end_route, :string
    field :route_code, :string
    field :source_state, :string
    field :route_uuid, :string

    timestamps()
  end

  @doc false
  def changeset(route, attrs) do
    route
    |> cast(attrs, [:route_name, :start_route, :end_route, :route_code, :source_state, :route_uuid])
    |> validate_required([:route_name, :start_route, :end_route, :route_code, :source_state, :route_uuid])
  end

end