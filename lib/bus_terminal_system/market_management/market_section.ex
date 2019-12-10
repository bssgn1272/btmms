defmodule BusTerminalSystem.Section do
    use Ecto.Schema
    import Ecto.Changeset

    alias BusTerminalSystem.Market

    schema "market_section" do
        field :name, :string
        field :label, :string
        #field :assigned_market, Market

        timestamps()
    end

    @doc false
    def changeset(section, attrs) do
        section
        |> cast(attrs, [:name, :label])
        |> validate_required([:name, :label])
    end
end