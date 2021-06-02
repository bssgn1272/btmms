defmodule BusTerminalSystem.Notification.Table.Email do
  use Endon
  use Ecto.Schema

  schema "probase_tbl_emails" do
    field :subject, :string
    field :to, :string
    field :from, :string
    field :message, :string
    field :status, :string
    field :attended, :boolean

    timestamps()
  end

end