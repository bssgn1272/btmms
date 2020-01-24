defmodule BusTerminalSystem.Repo.Migrations.TicketAddActivationStatus do
  use Ecto.Migration

  def change do
    alter table(:travel_routes) do
      add_if_not_exists :activation_status, :string
    end
  end
end
