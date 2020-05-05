defmodule BusTerminalSystem.Tenant do
  use Ecto.Schema
  import Ecto.Changeset

  schema "probase_tbl_market_tenant" do
    field :stand_id, :string
    field :first_names, :string
    field :last_name, :string
    field :nrc_number, :string
    field :account_number, :integer
    field :mobile_number, :integer

    timestamps()
  end

  @doc false
  def changeset(tenant, attrs) do
    tenant
    |> cast(attrs, [
      :stand_id,
      :first_name,
      :last_names,
      :nrc_number,
      :account_number,
      :mobile_number
    ])
    |> validate_required([
      :stand_id,
      :first_names,
      :last_name,
      :nrc_number,
      :account_number,
      :mobile_number
    ])
  end
end
