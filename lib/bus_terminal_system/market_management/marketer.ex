defmodule BusTerminalSystem.MarketManagement.Marketer do
  use Ecto.Schema
  import Ecto.Changeset

  schema "marketers" do
    field :stand_uid, :string

    timestamps()
  end

  @doc false
  def changeset(marketer, attrs) do
    marketer
    |> cast(attrs, [:stand_uid])
    |> validate_required([:stand_uid])
  end
end
