defmodule BusTerminalSystem.BusManagement.Bus do
  use Endon
  use Ecto.Schema
  import Ecto.Changeset

  @derive {Poison.Encoder, only: [:id, :license_plate, :company, :operator_id, :vehicle_capacity]}
  schema "probase_tbl_bus" do
    field :fitness_license, :string
    field :license_plate, :string
    field :uid, :string
    field :engine_type, :string
    field :model, :string
    field :make, :string
    field :year, :string
    field :color, :string
    field :state_of_registration, :string
    field :vin_number, :string
    field :serial_number, :string
    field :hull_number, :string
    field :operator_id, :string
#    field :operator_id, :integer
    field :vehicle_class, :string
    field :company, :string
    field :company_info, :string
    field :vehicle_capacity, :string

    field :auth_status, :boolean, default: false
    field :maker, :integer, default: 1
    field :checker, :integer, default: 1
    field :maker_date_time, :naive_datetime, default: NaiveDateTime.local_now
    field :checker_date_time, :naive_datetime, default: NaiveDateTime.local_now
    field :user_description, :string, default: "New User Bus Request"
    field :system_description, :string, default: "New Bus"
    field :cosec, :string
    field :card, :string

    timestamps()
  end

  @doc false
  def changeset(bus_terminus, attrs) do
    bus_terminus
    |> cast(attrs, [:license_plate, :fitness_license, :uid, :engine_type, :model, :make, :year, :color,
      :state_of_registration, :company, :operator_id, :vin_number, :serial_number, :hull_number, :vehicle_class,
      :company_info, :vehicle_capacity, :auth_status, :maker, :checker, :cosec,
      :maker_date_time,:checker_date_time, :user_description, :system_description, :card])
    |> validate_required([:license_plate, :company, :operator_id])
  end

  defp register_to_cosec(changeset) do

  end
end
