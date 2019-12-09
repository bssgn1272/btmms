defmodule BusTerminalSystem.Repo.Migrations.CreateTickets do
  use Ecto.Migration

  def change do
    create table(:tickets) do
      add :reference_number, :string

      timestamps()
    end

    create unique_index(:tickets, [:reference_number])
  end
end
