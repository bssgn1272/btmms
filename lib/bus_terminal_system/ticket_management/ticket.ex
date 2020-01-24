defmodule BusTerminalSystem.TicketManagement.Ticket do
  use Ecto.Schema
  import Ecto.Changeset

  alias BusTerminalSystem.TravelRoutes

  @derive {Poison.Encoder, only: [:reference_number, :serial_number, :external_ref, :inserted_at, :bus_no, :class, :activation_status,
                             :first_name, :last_name, :other_name, :id_type, :passenger_id, :mobile_number, :email_address, :transaction_channel]}
  schema "tickets" do
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

    field :transaction_channel, :string
    field :travel_date, :string

    timestamps()
  end

  @doc false
  def changeset(ticket, attrs) do

    ticket
    |> cast(attrs, [:reference_number, :external_ref, :serial_number, :route, :activation_status, :first_name,
      :last_name, :other_name, :id_type, :passenger_id, :mobile_number, :email_address, :transaction_channel, :travel_date])

    |> validate_required([:reference_number, :external_ref, :serial_number, :route, :activation_status, :first_name,
      :last_name, :id_type, :passenger_id, :mobile_number, :transaction_channel, :travel_date])
    |> validate_format(:email_address, ~r/@/)
  end
end
