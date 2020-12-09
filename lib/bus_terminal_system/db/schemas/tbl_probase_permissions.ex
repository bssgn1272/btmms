defmodule BusTerminalSystem.Permissions do
  use Endon
  use Ecto.Schema
  import Ecto.Changeset

  @db_columns [:name, :code]

  @derive {Poison.Encoder, only: @db_columns ++ [:id]}

  schema "probase_permissions" do
    field :name, :string
    field :code, :string

    timestamps()
  end

  #  @doc false
  #  def changeset(route, attrs) do
  #    route
  #    |> cast(attrs, @db_columns)
  ##    |> validate_required(@db_columns)
  #  end

end