defmodule BusTerminalSystem.Repo.Migrations.CreateBusTerminus do
  use Ecto.Migration

  def change do
    create table(:bus_terminus) do
      add :liscense_plate, :string

      timestamps()
    end

  end
end
