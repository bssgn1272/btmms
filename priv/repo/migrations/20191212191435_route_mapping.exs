defmodule BusTerminalSystem.Repo.Migrations.RouteMapping do
  use Ecto.Migration

  def change do
    create table(:tbl_route_mapping) do
      add :operator_id, :string
      add :bus_id, :string
      add :route_id, :string
      add :fare, :string

      timestamps()
    end

    alter table(:travel_routes) do
      remove_if_exists :bus_fair, :string
    end
  end
end
