defmodule BusTerminalSystem.Repo.Migrations.TicketAddPassengerDetails do
  use Ecto.Migration

  def change do
    alter table(:tickets) do
      add_if_not_exists :first_name, :string
      add_if_not_exists :last_name, :string
      add_if_not_exists :id_type, :string
      add_if_not_exists :passenger_id, :string
    end
  end
end
