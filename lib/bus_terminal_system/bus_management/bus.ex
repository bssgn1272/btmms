defmodule BusTerminalSystem.BusManagement.Bus do
  use Ecto.Schema
  import Ecto.Changeset

  @derive {Poison.Encoder, only: [:id, :liscense_plate, :company, :operator_id]}
  schema "tbl_bus" do
    field :fitness_liscence, :string
    field :liscense_plate, :string
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
    field :vehicle_class, :string
    field :company, :string
    field :company_info, :string
    field :vehicle_capacity, :string

    timestamps()
  end

  @doc false
  def changeset(bus_terminus, attrs) do
    bus_terminus
    |> cast(attrs, [:liscense_plate, :fitness_liscence, :uid, :engine_type, :model, :make, :year, :color,
      :state_of_registration, :company, :operator_id, :vin_number, :serial_number, :hull_number, :vehicle_class,
    :company_info, :vehicle_capacity])
    |> validate_required([:liscense_plate, :company, :operator_id])
  end
end
