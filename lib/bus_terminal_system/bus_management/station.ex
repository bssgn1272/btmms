defmodule BusTerminalSystem.Station do
    use Ecto.Schema
    import Ecto.Changeset

    alias BusTerminalSystem.Terminus

    schema "probase_tbl_terminus_stations" do
        field :name, :string
        field :label, :string
        field :route, :string
        #field :assigned_termius, Terminus

        timestamps()
    end

    @doc false
    def changeset(terminus, attrs) do
        terminus
        |> cast(attrs, [:name, :label, :route])
        |> validate_required([:name, :label, :route])
    end
end