defmodule BusTerminalSystem.Repo.Migrations.TicketAddFair do
  use Ecto.Migration

  def change do
    alter table(:tickets) do
      add_if_not_exists :bus_fair, :string
    end
  end
end
