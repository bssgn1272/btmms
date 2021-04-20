defmodule BusTerminalSystem.TransactionCodes do
  use Ecto.Schema
  import Ecto.Changeset

  @db_columns [:trn_code, :trn_desc, :auth_status, :maker_id, :checker_id]

  schema "probase_trans_code" do
    field :trn_code, :string
    field :trn_desc, :string
    field :auth_status, :string
    field :maker_id, :string
    field :checker_id, :string
  end

  @doc false
  def changeset(code, attrs) do
    code
    |> cast(attrs, @db_columns)
  end
end