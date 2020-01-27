defmodule BusTerminalSystem.Repo.Migrations.TicketMoveBusFairColumn do
  use Ecto.Migration

  def change do
    alter table(:travel_routes) do
      add_if_not_exists :bus_fair, :string
    end

    alter table(:tickets) do
      remove_if_exists :bus_fair, :string
    end
  end
end
