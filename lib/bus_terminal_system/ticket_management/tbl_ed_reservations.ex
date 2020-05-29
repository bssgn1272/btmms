defmodule BusTerminalSystem.TblEdReservations do
  use Ecto.Schema
  import Ecto.Changeset

  @db_column [:slot, :status, :route, :user_id, :bus_id, :time, :reserved_time]

  @derive {Poison.Encoder, only: [:id] ++ @db_column}
  schema "ed_reservations" do
    field :slot, :string
    field :status, :string
    field :route, :string
    field :user_id, :integer
    field :bus_id, :integer
    field :time, :string
    field :reserved_time, :date
  end

end