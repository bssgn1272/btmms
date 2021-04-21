defmodule BusTerminalSystem.LuggageTarrif do
  use Ecto.Schema
  import Ecto.Changeset

  @derive {Poison.Encoder, only: [:id ,:cost_per_kilo]}
  schema "probase_tbl_luggage_tarrifs" do
    field :cost_per_kilo, :float
    field :auth_status, :boolean, default: false
    field :maker, :integer
    field :checker, :integer
  end

  @doc false
def changeset(tarrif, attrs) do
  tarrif
  |> cast(attrs,[:cost_per_kilo, :auth_status, :maker_id, :checker_id])
  |> validate_required([:cost_per_kilo])
end

end
