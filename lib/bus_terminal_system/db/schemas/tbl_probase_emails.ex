defmodule BusTerminalSystem.Notification.Table.Email do
  use Endon
  use Ecto.Schema

  schema "probase_tbl_emails" do
    field :status, :string
    field :status_code, :integer
    field :recipient, :string
    field :message, :string
    field :request, :string
    field :response, :string
    field :sent, :boolean

    timestamps()
  end

end