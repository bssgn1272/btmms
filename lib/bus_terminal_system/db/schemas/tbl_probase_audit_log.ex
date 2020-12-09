defmodule BusTerminalSystem.AuditLog do
  use Endon
  use Ecto.Schema
  import Ecto.Changeset

  @db_columns [:operation, :log]

  @derive {Poison.Encoder, only: @db_columns ++ [:id]}

  schema "probase_audit_log" do
    field :operation, :string
    field :log, :string

    timestamps()
  end
end