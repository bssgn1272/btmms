defmodule BusTerminalSystem.Repo.Migrations.RoutesAddTicketId do
  use Ecto.Migration

  def change do
    alter table(:travel_routes) do
      add_if_not_exists :ticket_id, :integer
    end
  end
end
