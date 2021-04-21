defmodule BusTerminalSystem.Market.Market do
  use Ecto.Schema
  import Ecto.Changeset

  @db_columns [:market_name, :location, :market_uid, :city_town, :estimated_population, :auth_status, :maker_id, :checker_id, :maker_date_time,
    :checker_date_time, :user_description, :system_description]
  @derive {Poison.Encoder, only: @db_columns ++ [:id]}

  schema "probase_tbl_market" do
    field :market_name, :string
    field :location, :string
    field :market_uid, :string
    field :city_town, :string
    field :estimated_population, :string

    field :auth_status, :boolean, default: false
    field :maker, :integer
    field :checker, :integer
    field :maker_date_time, :naive_datetime
    field :checker_date_time, :naive_datetime
    field :user_description, :string
    field :system_description, :string

    timestamps()
  end



  @doc false
  def changeset(market, attrs) do
    market
    |> cast(attrs, @db_columns)
    |> validate_required(@db_columns)
  end

end