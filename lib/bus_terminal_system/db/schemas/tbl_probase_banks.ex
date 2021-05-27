defmodule BusTerminalSystem.Banks do
  use Endon
  use Ecto.Schema
  import Ecto.Changeset

  @db_columns [:bankName, :branch, :bankCode, :bicCode, :branchDesc, :cntryCode, :sortCode]

  @derive {Poison.Encoder, only: @db_columns ++ [:id]}

  schema "probase_tbl_banks" do
    field :bankName, :string
    field :branch, :string
    field :bankCode, :string
    field :bicCode, :string
    field :branchDesc, :string
    field :cntryCode, :string
    field :sortCode, :string

    timestamps()
  end

  #  @doc false
  #  def changeset(route, attrs) do
  #    route
  #    |> cast(attrs, @db_columns)
  ##    |> validate_required(@db_columns)
  #  end

end