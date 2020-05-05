defmodule BusTerminalSystem.Market.Market do
  use Ecto.Schema
  import Ecto.Changeset

  @db_columns [:market_name, :location, :market_uid, :city_town, :estimated_population]
  @derive {Poison.Encoder, only: @db_columns ++ [:id]}

  schema "probase_tbl_market" do
    field :market_name, :string
    field :location, :string
    field :market_uid, :string
    field :city_town, :string
    field :estimated_population, :string

    timestamps()
  end



  @doc false
  def changeset(market, attrs) do
    market
    |> cast(attrs, @db_columns)
    |> validate_required(@db_columns)
  end

end