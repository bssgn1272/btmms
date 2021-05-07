# This file is responsible for configuring your application
# and its dependencies with the aid of the Mix.Config module.
#
# This configuration file is loaded before any dependency and
# is restricted to this project.

# General application configuration
use Mix.Config

config :bus_terminal_system,
  ecto_repos: [BusTerminalSystem.Repo]

config :soap, :globals, version: "1.1"

config :bus_terminal_system, :phoenix_swagger,
   swagger_files: %{
     "priv/static/swagger.json" => [
       router: BusTerminalSystemWeb.Router,     # phoenix routes will be converted to swagger paths
       endpoint: BusTerminalSystemWeb.Endpoint  # (optional) endpoint config used to set host, port and https schemes.
     ]
   },  json_library: Jason

#config :phoenix_swagger, json_library: Jason

# Configures the endpoint
config :bus_terminal_system, BusTerminalSystemWeb.Endpoint,
  url: [host: "localhost"],
  secret_key_base: "fIOGwlNybBdW1TXJfMc4a2p5wAtX/AhLf22658KnU5l91ZN4Zs8OqoSIj+/vqX0W",
  render_errors: [view: BusTerminalSystemWeb.ErrorView, accepts: ~w(html json)],
  pubsub: [name: BusTerminalSystem.PubSub, adapter: Phoenix.PubSub.PG2]


# Configures Elixir's Logger
config :logger, :console,
  format: "$time $metadata[$level] $message\n",
  metadata: [:request_id]

config :bus_terminal_system, BusTerminalSystem.Mailer,
       adapter: Swoosh.Adapters.SMTP,
       relay: "smtp.mailgun.org",
       username: "support@report.probasegroup.com",
       password: "pbs_support",
       auth: :always,
       ssl: true,
       port: 465,
       retries: 2,
       no_mx_lookups: false

config :endon,
       repo: BusTerminalSystem.Repo

# Guardian config
config :bus_terminal_system, BusTerminalSystemWeb.Guardian,
   issuer: "bus_terminal_system",
   secret_key: "p8dKzWSIOU6Bc5/f6wcVMnHEiFmf2grFXZFE/6kFY2ZKtKjLIHspxM77BdSwFBqp"

# Use Jason for JSON parsing in Phoenix
config :phoenix, :json_library, Jason

config :bus_terminal_system, BusTerminalSystem.Scheduler,
 overlap: false,
 timeout: 30_000,
 jobs: [
   check_compliance: [
     schedule:  "* * * * *", task: {BusTerminalSystem.CheckCompliance, :run, []}
   ],
   napsa: [
     schedule:  "* * * * *", task: {BusTerminalSystem.CheckCompliance, :run, []},
     schedule:  "* * * * *", task: {BusTerminalSystem.NapsaUserUpdater, :run, []}
   ],
  bank: [
    schedule:  "* * * * *", task: {BusTerminalSystem.Service.Zicb.AccountOpening, :run, []},
    schedule: {:extended, "*/10"}, task: {BusTerminalSystem.Service.Zicb.Funding, :post_ticket_transactions, []},
  ]
 ]

# Import environment specific config. This must remain at the bottom
# of this file so it overrides the configuration defined above.
import_config "#{Mix.env()}.exs"
