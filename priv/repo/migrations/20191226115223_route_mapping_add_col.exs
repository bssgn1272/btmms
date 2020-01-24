defmodule BusTerminalSystem.Repo.Migrations.RouteMappingAddCol do
  use Ecto.Migration

  def change do
    alter table(:tbl_route_mapping) do
      add_if_not_exists :date, :string
      add_if_not_exists :time, :string
    end
  end
end
