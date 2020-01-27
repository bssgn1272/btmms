defmodule BusTerminalSystem.Repo.Migrations.TravelRoutesUpdate do
  use Ecto.Migration

  def change do
    alter table(:travel_routes) do
      add_if_not_exists :source_state, :string
      add_if_not_exists :route_uuid, :string
    end
  end
end
