defmodule BusTerminalSystem.Market.Section do
    use Ecto.Schema
    use Endon
    import Ecto.Changeset

    @db_columns [:section_name, :section_lable, :number_of_shops, :market_id, :auth_status, :maker_id, :checker_id, :maker_date_time,
        :checker_date_time, :user_description, :system_description]
    @derive {Poison.Encoder, only: @db_columns ++ [:id]}

    schema "probase_tbl_market_section" do
        field :section_name, :string
        field :section_lable, :string
        field :number_of_shops, :integer
        field :market_id, :integer

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
    def changeset(section, attrs) do
        section
        |> cast(attrs, @db_columns)
        |> validate_required(@db_columns)
    end
end