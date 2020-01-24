defmodule BusTerminalSystem.Repo.Migrations.TicketAddActivationStatus3 do
  use Ecto.Migration

  def change do
    alter table(:travel_routes) do
      remove_if_exists :activation_status, :string
    end

    alter table(:tickets) do
      add_if_not_exists :activation_status, :string
    end
  end
end
