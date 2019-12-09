defmodule BusTerminalSystem.Gate do
    use Ecto.Schema
    import Ecto.Changeset

    schema "gate" do
      field :gate_code, :string
      timestamps()
    end

    @doc false
    def changeset(station_gate, attrs) do
       station_gate
       |> cast(attrs, [:gate_code])
       |> validate_required([:gate_code])
     end

end

