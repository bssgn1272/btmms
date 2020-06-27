defmodule BusTerminalSystem.Notification.Table.Sms do
  use Endon
  use Ecto.Schema

  schema "probase_tbl_sms" do
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