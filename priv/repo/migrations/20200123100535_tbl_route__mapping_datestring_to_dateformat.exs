defmodule BusTerminalSystem.Repo.Migrations.TblRoute_MappingDatestringToDateformat do
  use Ecto.Migration

  def change do
    alter table("tbl_route_mapping") do
      remove_if_exists :date, :string
      add_if_not_exists :date, :date
    end
  end
end
