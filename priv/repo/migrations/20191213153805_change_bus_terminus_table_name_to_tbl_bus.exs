defmodule BusTerminalSystem.Repo.Migrations.ChangeBusTerminusTableNameToTblBus do
  use Ecto.Migration

  def change do
    rename table(:bus_terminus), to: table(:tbl_bus)
  end
end
