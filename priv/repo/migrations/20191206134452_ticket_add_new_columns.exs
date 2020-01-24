defmodule BusTerminalSystem.Repo.Migrations.TicketAddNewColumns do
  use Ecto.Migration

  def change do
    alter table(:tickets) do
      add_if_not_exists(:date, :string)
      add_if_not_exists(:bus_no, :string)
      add_if_not_exists(:class, :string)
    end
  end
end
