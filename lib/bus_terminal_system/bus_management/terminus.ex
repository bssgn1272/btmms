defmodule BusTerminalSystem.Terminus do
    use Ecto.Schema
    import Ecto.Changeset

    schema "probase_tbl_terminus" do
        field :name, :string
        field :location, :string
        field :terminus_id, :string
        field :town, :string

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
    def changeset(change_table_name, attrs) do
        change_table_name
        |> cast(attrs, [:name, :location, :terminus_id, :town, :auth_status, :maker, :checker,
        :maker_date_time, :checker_date_time, :user_description, :system_description])
        |> validate_required([:name, :location, :terminus, :town])
    end
end