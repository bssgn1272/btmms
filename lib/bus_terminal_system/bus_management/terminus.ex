defmodule BusTerminalSystem.Terminus do
    use Ecto.Schema
    import Ecto.Changeset

    schema "probase_tbl_terminus" do
        field :name, :string
        field :location, :string
        field :terminus_id, :string
        field :town, :string

        timestamps()
    end

    @doc false
    def changeset(change_table_name, attrs) do
        change_table_name
        |> cast(attrs, [:name, :location, :terminus_id, :town])
        |> validate_required([:name, :location, :terminus, :town])
    end
end