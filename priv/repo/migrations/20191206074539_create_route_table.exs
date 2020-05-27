defmodule BusTerminalSystem.Repo.Migrations.CreateRouteTable do
  use Ecto.Migration

  def up do
    up_
  end

  def up_ do
    create_if_not_exists table(:probase_tbl_travel_routes) do
      add :route_name, :string
      add :start_route, :string
      add :end_route, :string
      add :route_code, :string
      add :ticket_id, :int
      add :route_fare, :int

      add :source_state, :string
      add :route_uuid, :string

      timestamps()
    end

  end


  def down do
    #drop_if_exists table(:probase_tbl_travel_routes)
  end


end
