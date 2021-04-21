defmodule BusTerminalSystem.Market.Shop do
  use Ecto.Schema
  import Ecto.Changeset

  @db_columns [:shop_code, :section_id, :maketeer_id, :shop_number, :shop_price, :auth_status, :maker, :checker, :maker_date_time,
    :checker_date_time, :user_description, :system_description]
  @derive {Poison.Encoder, only: @db_columns ++ [:id]}

  schema "probase_tbl_market_section_shop" do
    field :shop_code, :string
    field :section_id, :integer
    field :maketeer_id, :integer
    field :shop_number, :integer
    field :shop_price, :float
    field :auth_status, :boolean, default: true
    field :maker, :integer, default: 1
    field :checker, :integer, default: 1
    field :maker_date_time, :naive_datetime, default: NaiveDateTime.local_now
    field :checker_date_time, :naive_datetime, default: NaiveDateTime.local_now
    field :user_description, :string, default: "User Allocated Stand"
    field :system_description, :string, default: "Stand Allocated"

    timestamps()
  end


  @doc false
  def changeset(market_hub, attrs) do
    market_hub
    |> cast(attrs, @db_columns)
    |> validate_required(@db_columns)
  end

end



