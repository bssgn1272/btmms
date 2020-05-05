defmodule BusTerminalSystem.Luggage do
  use Ecto.Schema
  import Ecto.Changeset

  @derive {Poison.Encoder, only: [:id, :description, :ticket_id, :weight, :cost]}
  schema "probase_tbl_luggage" do
    field :description, :string
    field :ticket_id, :integer
    field :weight, :float
    field :cost, :float

    timestamps()
  end

  @doc false
  def changeset(luggage, attrs) do
    luggage
    |> cast(attrs, [:description, :ticket_id, :weight, :cost])
    |> validate_required([:ticket_id, :weight, :cost])
  end
end
