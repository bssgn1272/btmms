defmodule BusTerminalSystem.TicketManagement.Ticket do
  use Ecto.Schema
  import Ecto.Changeset

  alias BusTerminalSystem.TravelRoutes

  @derive {Poison.Encoder, only: [:reference_number, :serial_number, :external_ref, :inserted_at, :bus_no, :class, :activation_status]}
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
    field :id_type, :string
    field :passenger_id, :string

    timestamps()
  end

  @doc false
  def changeset(ticket, attrs) do
    ticket
    |> cast(attrs, [:reference_number, :external_ref, :serial_number, :route, :activation_status])
    |> validate_required([:reference_number, :external_ref, :serial_number, :route, :activation_status])
  end
end
