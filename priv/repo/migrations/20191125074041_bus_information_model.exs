defmodule BusTerminalSystem.Repo.Migrations.BusInformationModel do
  use Ecto.Migration

  def change do

    alter table(:bus_terminus) do
      add_if_not_exists :liscense_plate, :string
      add_if_not_exists :uid, :string
      add_if_not_exists :engine_type, :string
      add_if_not_exists :model, :string
      add_if_not_exists :make, :string
      add_if_not_exists :year, :string
      add_if_not_exists :color, :string
      add_if_not_exists :state_of_registration, :string
      add_if_not_exists :vin_number, :string
      add_if_not_exists :serial_number, :string
      add_if_not_exists :hull_number, :string
      add_if_not_exists :operator_id, :string
      add_if_not_exists :vehicle_class, :string
      add_if_not_exists :company, :string
      add_if_not_exists :company_info, :string
    end

  end
end
