defmodule BusTerminalSystem.TravelRoutes do
  use Endon
  use Ecto.Schema
  import Ecto.Changeset

  @db_columns [:route_name, :start_route, :end_route, :route_code, :source_state, :route_uuid, :route_fare, :parent]

  @derive {Poison.Encoder, only: @db_columns ++ [:id]}

  schema "probase_tbl_travel_routes" do
    field :route_name, :string
    field :start_route, :string
    field :end_route, :string
    field :route_code, :string
    field :source_state, :string
    field :route_uuid, :string
    field :route_fare, :integer
    field :auth_status, :boolean, default: false
    field :maker, :integer, default: 1
    field :checker, :integer, default: 1
    field :maker_date_time, :naive_datetime, default: NaiveDateTime.local_now
    field :checker_date_time, :naive_datetime, default: NaiveDateTime.local_now
    field :user_description, :string, default: "New Route Creation Request"
    field :system_description, :string, default: "NEW ROUTE CREATION"
    field :parent, :integer

    timestamps()
  end

  @doc false
  def changeset(route, attrs) do
    route
    |> cast(attrs, @db_columns)
#    |> validate_required(@db_columns)
  end

end