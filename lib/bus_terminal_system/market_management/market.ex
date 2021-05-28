defmodule BusTerminalSystem.Market.Market do
  use Ecto.Schema
  use Endon
  import Ecto.Changeset

  @db_columns [:market_name, :location, :market_uid, :city_town, :estimated_population, :auth_status, :maker, :checker, :maker_date_time,
    :checker_date_time, :user_description, :system_description]
  @derive {Poison.Encoder, only: @db_columns ++ [:id]}

  schema "probase_tbl_market" do
    field :market_name, :string
    field :location, :string
    field :market_uid, :string
    field :city_town, :string
    field :estimated_population, :string
    field :auth_status, :boolean, default: false
    field :maker, :integer, default: 1
    field :checker, :integer, default: 1
    field :maker_date_time, :naive_datetime, default: NaiveDateTime.local_now
    field :checker_date_time, :naive_datetime, default: NaiveDateTime.local_now
    field :user_description, :string, default: "New market creation request"
    field :system_description, :string, default: "Market Creation"

    timestamps()
  end



  @doc false
  def changeset(market, attrs) do
    market
    |> cast(attrs, @db_columns)
    |> validate_required(@db_columns)
  end

end