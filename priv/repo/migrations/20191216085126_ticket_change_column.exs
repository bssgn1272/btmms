defmodule BusTerminalSystem.Repo.Migrations.TicketChangeColumn do
  use Ecto.Migration

  def change do
    alter table(:tickets) do
      remove_if_exists :transaction, :string
    end

    alter table(:tickets) do
      add_if_not_exists :transaction_channel, :string
    end
  end
end
