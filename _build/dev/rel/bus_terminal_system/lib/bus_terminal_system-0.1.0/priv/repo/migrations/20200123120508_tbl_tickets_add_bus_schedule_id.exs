defmodule BusTerminalSystem.Repo.Migrations.TblTicketsAddBusScheduleId do
  use Ecto.Migration

  def change do
    alter table("tickets") do
      add_if_not_exists :bus_schedule_id, :string
    end
  end
end
