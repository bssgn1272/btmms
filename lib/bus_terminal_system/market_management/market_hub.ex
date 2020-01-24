defmodule BusTerminalSystem.Hub do
  use Ecto.Schema
  import Ecto.Changeset

  alias BusTerminalSystem.Section

  schema "market_kiosk" do
    field :kiosk, :string
    #field :assigned_section, Section

    timestamps()
  end

  @doc false
  def changeset(market_hub, attrs) do
    market_hub
    |> cast(attrs, [:hub])
    |> validate_required(attrs, [:hub])
  end

end



