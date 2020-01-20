defmodule BusTerminalSystem.Repo.Migrations.CreateTableLuggageTarrifs do
  use Ecto.Migration

  def change do
    create table(:tbl_luggage_tarrifs) do
      add :cost_per_kilo, :float

      timestamps()
    end
  end
end
