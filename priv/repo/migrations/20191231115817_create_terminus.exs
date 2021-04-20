defmodule BusTerminalSystem.Repo.Migrations.CreateTerminus do
  use Ecto.Migration

  def up do

  end

  def up_ do
    create_if_not_exists table(:probase_tbl_terminus) do
      add :terminus_name, :string
      add :terminus_location, :string
      add :estimated_buses, :integer
      add :city_town, :string
      add :auth_status, :boolean, default: false
      add :maker, :integer
      add :checker, :integer

      timestamps()
    end
  end

  def change do

  end

  def down do
    drop_if_exists table(:probase_tbl_terminus)
  end


end
