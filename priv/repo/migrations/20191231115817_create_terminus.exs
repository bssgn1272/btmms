defmodule BusTerminalSystem.Repo.Migrations.CreateTerminus do
  use Ecto.Migration

  def change do
    create table(:tbl_terminus) do
      add :terminus_name, :string
      add :terminus_location, :string
      add :estimated_buses, :integer
      add :city_town, :string

      timestamps()
    end
  end
end
