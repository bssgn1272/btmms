defmodule BusTerminalSystem.Repo.Migrations.CreateRouteTableAddTimestamp do
  use Ecto.Migration

  def change do
    alter table(:travel_routes) do
      timestamps()
    end
  end
end
