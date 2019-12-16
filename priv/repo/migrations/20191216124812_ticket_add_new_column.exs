defmodule BusTerminalSystem.Repo.Migrations.TicketAddNewColumn do
  use Ecto.Migration

  def change do
    alter table(:tickets) do
      add_if_not_exists :travel_date, :string
    end
  end
end
