defmodule BusTerminalSystem.Tenant do
    use Ecto.Schema
    import Ecto.Changeset

    schema "table_name" do
        field :first_name, :string
        field :other_names, :string
        field :last_name, :string
        field :nrc, :string

        timestamps()
    end

    @doc false
    def changeset(tenant, attrs) do
        tenant
        |> cast(attrs, [:first_name, :other_names, :last_name, :nrc])
        |> validate_required([:first_name, :other_names, :last_name, :nrc])
    end
end