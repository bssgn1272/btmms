defmodule BusTerminalSystem.Market.ShopMinimal do
  use Ecto.Schema
  import Ecto.Changeset

  @db_columns [:shop_number, :shop_price]
  @derive {Poison.Encoder, only: @db_columns}

  schema "probase_tbl_market_section_shop" do
    field :shop_code, :string
    field :section_id, :integer
    field :maketeer_id, :integer
    field :shop_number, :integer
    field :shop_price, :float

    timestamps()
  end


  @doc false
  def changeset(market_hub, attrs) do
    market_hub
    |> cast(attrs, @db_columns)
    |> validate_required(@db_columns)
  end

end
