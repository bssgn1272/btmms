defmodule BusTerminalSystemWeb.Schema.ContentTypes do
  use Absinthe.Schema.Notation

  object :tickets do
    field :id, :id
    field :reference_number, :string
    field :serial_number, :string
  end

  object :ticket_by_id do
    field :id, :id
    field :reference_number, :string
    field :serial_number, :string
  end

  object :ticket_by_serial do
    field :id, :id
    field :reference_number, :string
    field :serial_number, :string
  end

  object :ticket_by_reference do
    field :id, :id
    field :reference_number, :string
    field :serial_number, :string
    field :external_ref, :string
    field :route, :integer
    field :date, :string
    field :bus_no, :string
    field :class, :string
    field :activation_status, :string
    field :first_name, :string
    field :last_name, :string
    field :other_name, :string
    field :id_type, :string
    field :passenger_id, :string
    field :mobile_number, :string
    field :email_address, :string
    field :bus_schedule_id, :string
    field :transaction_channel, :string
    field :travel_date, :string
  end

end