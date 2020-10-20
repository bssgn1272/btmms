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

      add :auth_status, :integer, default: 0
      add :maker, :integer
      add :checker, :integer
      add :maker_date_time, :naive_datetime
      add :checker_date_time, :naive_datetime
      add :user_description, :string
      add :system_description, :string

      timestamps()
    end

  end



  def down do
    drop_if_exists table(:probase_tbl_bus)
  end


end
