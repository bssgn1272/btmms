defmodule BusTerminalSystem.Repo.Migrations.CreateBusTable do
  use Ecto.Migration

  def up do
    create_if_not_exists table(:probase_tbl_bus) do
      add :license_plate, :string
      add :uid, :string
      add :engine_type, :string
      add :model, :string
      add :make, :string
      add :year, :string
      add :color, :string
      add :state_of_registration, :string
      add :vin_number, :string
      add :serial_number, :string
      add :hull_number, :string
      add :operator_id, :string
      add :vehicle_class, :string
      add :company, :string
      add :company_info, :string
      add :fitness_license, :string
      add :vehicle_capacity, :string
      timestamps()
    end

  end

  def change do

  end

  def down do
    drop_if_exists table(:probase_tbl_bus)
  end


end
