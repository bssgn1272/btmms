defmodule BusTerminalSystem.Transactions do
  use Ecto.Schema
  import Ecto.Changeset

  @db_columns [:trn_date, :val_date, :trans_ref_no, :ac_no, :trn_code, :drcr_ind, :lcy_amount, :fin_cycle, :auth_stat,
    :transaction_channel, :maker_id, :checker_id, :cust_gl, :related_customer]

  schema "probase_tbl_transactions" do
    field :trn_date, :date
    field :val_date, :date
    field :trans_ref_no, :string
    field :ac_no, :string
    field :trn_code, :string
    field :drcr_ind, :string

    field :lcy_amount, :float

    field :fin_cycle, :string
    field :auth_stat, :string
    field :transaction_channel, :string
    field :maker_id, :string
    field :checker_id, :string
    field :cust_gl, :string
    field :related_customer, :integer
  end

  @doc false
  def changeset(transaction, attrs) do
    transaction
    |> cast(attrs, @db_columns)
  end

end