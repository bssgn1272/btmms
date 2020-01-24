defmodule BusTerminalSystem.Repo.Migrations.AddRouteUidColumnToRouteMapping do
  use Ecto.Migration

  def change do
    alter table("tbl_route_mapping") do
      add_if_not_exists :route_uid, :integer
    end
  end
end
