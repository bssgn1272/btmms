defmodule BusTerminalSystem.TicketManagement.Ticket do
  use Endon
  use Ecto.Schema
  import Ecto.Changeset

  alias BusTerminalSystem.TravelRoutes

  @derive {Poison.Encoder, only: [:id, :maker,:reference_number, :serial_number, :external_ref, :inserted_at, :bus_no, :class, :activation_status,:bus_schedule_id, :route,
                             :first_name, :payment_mode, :amount, :last_name, :other_name, :id_type, :passenger_id, :mobile_number, :email_address, :transaction_channel, :route_information]}

  schema "probase_tbl_tickets" do
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
    field :maker, :string
    field :route_information, :string
    field :amount, :float
    field :payment_mode, :string
    field :has_luggage, :boolean
    field :luggage_total, :float
    field :info, :string

    timestamps()
  end

  @doc false
  def changeset(ticket, attrs) do

    ticket
    |> cast(attrs, [:reference_number, :maker, :external_ref, :bus_no, :class, :serial_number, :route, :activation_status, :first_name, :bus_schedule_id,
      :last_name, :other_name, :id_type, :payment_mode, :amount, :passenger_id, :mobile_number, :email_address, :transaction_channel, :travel_date,
      :has_luggage, :luggage_total, :info, :route_information,
      :maker])

    |> validate_required([:reference_number, :external_ref, :amount, :serial_number, :route, :activation_status, :first_name,
      :last_name, :id_type, :passenger_id, :mobile_number, :transaction_channel, :travel_date])
    |> validate_format(:email_address, ~r/@/)
  end

  defp update_class(changeset) do
    changeset |> change(class: "")
  end

  def luggage_changeset(v_ticket, attrs) do
    v_ticket
    |> cast(attrs, [:reference_number, :external_ref, :class, :serial_number, :route, :activation_status, :first_name, :bus_schedule_id,
      :last_name, :other_name, :id_type, :passenger_id, :mobile_number, :email_address, :transaction_channel, :travel_date])
    |> validate_required([:class])
  end

end
