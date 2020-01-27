defmodule BusTerminalSystem.Repo.Migrations.TicketMoveBusFairDataType do
  use Ecto.Migration

  def change do
    alter table(:travel_routes) do
      remove_if_exists :bus_fair, :string
    end

    alter table(:travel_routes) do
      add_if_not_exists :bus_fair, :integer
    end
  end
end
