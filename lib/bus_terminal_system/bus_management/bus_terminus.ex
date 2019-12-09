defmodule BusTerminalSystem.BusManagement.BusTerminus do
  use Ecto.Schema
  import Ecto.Changeset

  schema "bus_terminus" do
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

    timestamps()
  end

  @doc false
  def changeset(bus_terminus, attrs) do
    bus_terminus
    |> cast(attrs, [:liscense_plate])
    |> validate_required([:liscense_plate])
  end
end
