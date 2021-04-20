defmodule BusTerminalSystem.Application do
  # See https://hexdocs.pm/elixir/Application.html
  # for more information on OTP Applications
  @moduledoc false

  import Supervisor.Spec

  use Application

  def start(_type, _args) do
    # List all child processes to be supervised
    children = [
      # Start the Ecto repository
      BusTerminalSystem.Repo,
      # Start the endpoint when the application starts
      BusTerminalSystemWeb.Endpoint,
      # Starts a worker by calling: BusTerminalSystem.Worker.start_link(arg)
      # {BusTerminalSystem.Worker, arg},
      # SMS job
#       BusTerminalSystem.Job.Sms
    ]

    # See https://hexdocs.pm/elixir/Supervisor.html
    # for other strategies and supported options
    opts = [strategy: :one_for_one, name: BusTerminalSystem.Supervisor]
    Supervisor.start_link(children, opts)
  end

  # Tell Phoenix to update the endpoint configuration
  # whenever the application is updated.
  def config_change(changed, _new, removed) do
    BusTerminalSystemWeb.Endpoint.config_change(changed, removed)
    :ok
  end
end
