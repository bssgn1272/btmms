defmodule BusTerminalSystem.Repo.Migrations.AddCreateTicketColumns do
  use Ecto.Migration

  def change do
    alter table(:tickets) do
      add_if_not_exists(:reference_number, :string)
      add_if_not_exists(:serial_number, :string)
    end
  end
end
