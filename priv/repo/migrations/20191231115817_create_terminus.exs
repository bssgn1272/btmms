defmodule BusTerminalSystem.Repo.Migrations.CreateTerminus do
  use Ecto.Migration


  def up do
    create_if_not_exists table(:probase_tbl_terminus) do
      add :terminus_name, :string
      add :terminus_location, :string
      add :estimated_buses, :integer
      add :city_town, :string

      timestamps()
    end

    alter table(:probase_tbl_terminus) do
      add :auth_status, :integer, default: 0
      add :maker, :integer
      add :checker, :integer
      add :maker_date_time, :naive_datetime
      add :checker_date_time, :naive_datetime
      add :user_description, :string
      add :system_description, :string
    end

  end

  def down do
    drop_if_exists table(:probase_tbl_terminus)
  end


end
