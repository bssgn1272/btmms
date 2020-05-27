defmodule BusTerminalSystem.Repo.Migrations.CreateTableLuggageTarrifs do
  use Ecto.Migration

  def up do

  end

  def up_ do
    create_if_not_exists table(:probase_tbl_luggage_tarrifs) do
      add :cost_per_kilo, :float

      timestamps()
    end
  end

  def change do

  end

  def down do
    drop_if_exists table(:probase_tbl_luggage_tarrifs)
  end


end
