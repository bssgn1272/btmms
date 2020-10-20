defmodule BusTerminalSystem.Repo.Migrations.RouteMapping do
  use Ecto.Migration

  def up do
    up_()
  end

  def up_ do
    create_if_not_exists table(:probase_tbl_route_mapping) do
      add :operator_id, :string
      add :bus_id, :string
      add :route_id, :string
      add :fare, :integer

      add :date, :string
      add :time, :string

      add :route_uid, :integer
      add :auth_status, :boolean

      timestamps()
    end
  end

  def change do


  end

  def down do
    drop_if_exists table(:probase_tbl_route_mapping)
  end


end
