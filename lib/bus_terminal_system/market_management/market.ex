defmodule BusTerminalSystem.Market do
  use Ecto.Schema
  import Ecto.Changeset

  schema "market" do
    field :market_name, :string
    field :location, :string
    field :market_uid, :string
    field :city_town, :string
  end


  @doc false
  def changeset(market, attrs) do
    market
    |> cast(attrs, [:market_name, :location, :market_uid, :city_town])
    |> validate_required([:market_name])
  end

end