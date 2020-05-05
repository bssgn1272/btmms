defmodule BusTerminalSystem.Repo do
  use Ecto.Repo,
    otp_app: :bus_terminal_system,
    adapter: Ecto.Adapters.MyXQL
    #adapter: Ecto.Adapters.Postgres
end
