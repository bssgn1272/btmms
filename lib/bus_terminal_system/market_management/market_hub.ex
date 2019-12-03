defmodule BusTerminalSystem.Hub do
  use Ecto.Schema
  import Ecto.Changeset

  schema "hub" do
    field :hub, :string

    timestamps()
  end

  @doc false
  def changeset(market_hub, attrs) do
    market_hub
    |> cast(attrs, [:hub])
    |> validate_required(attrs, [:hub])
  end

end



