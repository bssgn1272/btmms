defmodule BusTerminalSystem.TransactionCodes do
  use Ecto.Schema
  import Ecto.Changeset

  @db_columns [:trn_code, :trn_desc, :auth_status, :maker, :checker]
#    :maker_date_time, :checker_date_time, :user_description, :system_description]

  schema "probase_trans_code" do
    field :trn_code, :string
    field :trn_desc, :string
    field :auth_status, :boolean, default: false
    field :maker, :integer
    field :checker, :integer
#    field :maker_date_time, :naive_datetime
#    field :checker_date_time, :naive_datetime
#    field :user_description, :string
#    field :system_description, :string
  end

  @doc false
  def changeset(code, attrs) do
    code
    |> cast(attrs, @db_columns)
  end
end