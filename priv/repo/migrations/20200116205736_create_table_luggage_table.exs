defmodule BusTerminalSystem.Repo.Migrations.CreateTableLuggageTable do
  use Ecto.Migration

  def change do
    create table(:tbl_luggage) do
      add :description, :string
      add :ticket_id, :integer
      add :weight, :float
      add :cost, :float

      timestamps()
    end
  end
end
