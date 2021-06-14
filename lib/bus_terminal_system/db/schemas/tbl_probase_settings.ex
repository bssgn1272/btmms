defmodule BusTerminalSystem.Settings do
  use Endon
  use Ecto.Schema
  import Ecto.Changeset

  @db_columns [:key, :view_name, :value, :status, :element_id]

  @derive {Poison.Encoder, only: @db_columns ++ [:id]}

  schema "probase_tbl_settings" do
    field :key, :string
    field :value, :string
    field :status, :boolean

    timestamps()
  end
end