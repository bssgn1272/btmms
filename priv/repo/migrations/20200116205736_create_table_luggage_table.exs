defmodule BusTerminalSystem.Repo.Migrations.CreateTableLuggageTable do
  use Ecto.Migration

  def up do

  end

  def up_ do
    create_if_not_exists table(:probase_tbl_luggage) do
      add :description, :string
      add :ticket_id, :integer
      add :weight, :float
      add :cost, :float

      timestamps()
    end
  end

  def down do
    #drop_if_exists table(:probase_tbl_luggage)
  end


end
