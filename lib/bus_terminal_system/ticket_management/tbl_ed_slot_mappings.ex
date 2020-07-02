defmodule BusTerminalSystem.TblSlotMappings do
  use Endon
  use Ecto.Schema
  import Ecto.Changeset

  @db_column [:slot, :gate]

  @derive {Poison.Encoder, only: [:id] ++ @db_column}
  schema "ed_slot_mappings" do
    field :slot, :string
    field :gate, :string
  end

  def changeset(ed, attrs) do
    ed
    |> cast(attrs, @db_column)
  end

end