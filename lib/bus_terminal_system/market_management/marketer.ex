defmodule BusTerminalSystem.MarketManagement.Marketer do
  use Ecto.Schema
  import Ecto.Changeset

  schema "probase_tbl_marketer" do
    field :stand_id, :string
    field :first_name, :string
    field :last_name, :string
    field :nrc_number, :string
    field :account_number, :integer
    field :mobile_number, :integer

    timestamps()
  end

  @doc false
  def changeset(marketer, attrs) do
    marketer
    |> cast(attrs, [
      :stand_id,
      :first_name,
      :last_name,
      :nrc_number,
      :account_number,
      :mobile_number
    ])
    |> validate_required([
      :stand_id,
      :first_name,
      :last_name,
      :nrc_number,
      :account_number,
      :mobile_number
    ])
  end
end
