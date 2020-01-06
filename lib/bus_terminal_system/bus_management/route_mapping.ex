defmodule BusTerminalSystem.RouteMapping do
  use Ecto.Schema
  import Ecto.Changeset

  @derive {Poison.Encoder, only: [:operator_id, :bus_id, :route_id, :fare, :date, :time]}
  schema "tbl_route_mapping" do
    field :operator_id, :string
    field :bus_id, :string
    field :route_id, :string
    field :fare, :integer
    field :date, :string
    field :time, :string

    timestamps()
  end

  @doc false
  def changeset(route_mapping, attrs) do
    route_mapping
    |> cast(attrs, [:operator_id, :bus_id, :route_id, :fare, :date, :time])
    |> validate_required([:operator_id, :bus_id, :route_id, :fare])
  end

end