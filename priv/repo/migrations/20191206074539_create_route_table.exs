defmodule BusTerminalSystem.Repo.Migrations.CreateRouteTable do
  use Ecto.Migration

  def change do
    create table(:travel_routes) do
      add :route_name, :string
      add :start_route, :string
      add :end_route, :string
    end
  end
end
