defmodule BusTerminalSystem.Repo.Migrations.CreateRouteTableAddRouteCode do
  use Ecto.Migration

  def change do
    alter table(:travel_routes) do
      add_if_not_exists(:route_code, :string)
    end
  end
end
