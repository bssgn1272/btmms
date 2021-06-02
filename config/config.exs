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
#  pubsub: [name: BusTerminalSystem.PubSub, adapter: Phoenix.PubSub.PG2],
  pubsub_server: BusTerminalSystem.PubSub


# Configures Elixir's Logger
config :logger, :console,
  format: "$time $metadata[$level] $message\n",
  metadata: [:request_id]

config :bus_terminal_system, BusTerminalSystem.Mailer,
   adapter: Swoosh.Adapters.Mailjet,
   api_key: "474f8555a04e9f4107f6cfdfc7129667",
   secret: "8916c07ae488aea0fd26eadc846f76fd",
   relay: "in-v3.mailjet.com",
   port: 587

#config :bus_terminal_system, BusTerminalSystem.Mailer,
#       adapter: Swoosh.Adapters.SMTP,
#       relay: "smtp.office365.com",
#       username: "BTMMS@napsa.co.zm",
#       password: "Welcome@2020",
#       auth: :always,
#       tls: :always,
#       port: 587,
#       retries: 3,
#       no_mx_lookups: false

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
   napsa: [
     schedule:  "* * * * *", task: {BusTerminalSystem.CheckCompliance, :run, []},
     schedule:  "* * * * *", task: {BusTerminalSystem.NapsaUserUpdater, :run, []},
   ],
  bank: [
    schedule:  {:extended, "*/4"}, task: {BusTerminalSystem.Service.Zicb.AccountOpening, :run, []},
  ],
   sales: [
     schedule: {:extended, "*/2"}, task: {BusTerminalSystem.Service.Zicb.Funding, :post_ticket_transactions, []}
   ],
   email: [
     schedule: {:extended, "*/3"}, task: {BusTerminalSystem.EmailSender, :run, []}
   ],
   sms: [
     schedule:  {:extended, "*/2"}, task: {BusTerminalSystem.Job.Sms, :send, []}
   ],
   cosec:
   [
     schedule:  {:extended, "*/2"}, task: {BusTerminalSystem.Cosec, :run, []}
   ]
 ]

# Import environment specific config. This must remain at the bottom
# of this file so it overrides the configuration defined above.
import_config "#{Mix.env()}.exs"
