defmodule BusTerminalSystem.Repo.Migrations.TblRoute_MappingDatestringToDateformat2 do
  use Ecto.Migration

  def change do
    alter table("tbl_route_mapping") do
      remove_if_exists :date, :date
      add_if_not_exists :date, :string
    end
  end
end
