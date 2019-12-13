defmodule BusTerminalSystem.Repo.Migrations.RouteMappingChangeFareColumnDataType do
  use Ecto.Migration

  def change do
    alter table(:tbl_route_mapping) do
      remove_if_exists :fare, :string
    end

    alter table(:tbl_route_mapping) do
      add_if_not_exists :fare, :integer
    end
  end
end
