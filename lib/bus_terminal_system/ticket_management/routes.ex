defmodule BusTerminalSystem.TravelRoutes do
  use Ecto.Schema
  import Ecto.Changeset

  @derive {Poison.Encoder, only: [:route_name, :start_route, :end_route, :route_code]}
  schema "travel_routes" do
    field :route_name, :string
    field :start_route, :string
    field :end_route, :string
    field :route_code, :string

    timestamps()
  end

  @doc false
  def changeset(route, attrs) do
    route
    |> cast(attrs, [:route_name, :start_route, :end_route, :route_code])
    |> validate_required([:route_name, :start_route, :end_route, :route_code])
  end

end