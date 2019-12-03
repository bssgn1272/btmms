defmodule BusTerminalSystem.TicketManagement.Ticket do
  use Ecto.Schema
  import Ecto.Changeset

  schema "tickets" do
    field :reference_number, :string
    field :first_name, :string
    field :last_name, :string
    field :age, :string
    field :mobile, :string
    field :traveling_from, :string
    field :traveling_to, :string
    field :date_of_depature, :string
    field :date_of_return, :string
    field :number_of_travelers, :string

    timestamps()
  end

  @doc false
  def changeset(ticket, attrs) do
    ticket
    |> cast(attrs, [:reference_number])
    |> validate_required([:reference_number])
    |> unique_constraint(:reference_number)
  end
end
