# In this file, we load production configuration and secrets
# from environment variables. You can also hardcode secrets,
# although such is generally not recommended and you have to
# remember to add this file to your .gitignore.
use Mix.Config

config :bus_terminal_system, BusTerminalSystem.Repo,
   username: "probase",
   password: "V1neyard",
   database: "btmms",
   hostname: "10.10.1.88",
   timeout: :infinity,
   port: 3306,
   show_sensitive_data_on_connection_error: false,
   pool_size: 10

config :bus_terminal_system, BusTerminalSystemWeb.Endpoint,
  http: [port: String.to_integer(System.get_env("PORT") || "4000")],
  secret_key_base: "07MuWl3Z1fuEoGxPgpLoGGiy3/DMF1pgbUItrCY+PyMO6NNEMwWvpqXm72K1b1kP"

# ## Using releases (Elixir v1.9+)
#
# If you are doing OTP releases, you need to instruct Phoenix
# to start each relevant endpoint:
#
config :bus_terminal_system, BusTerminalSystemWeb.Endpoint, server: true
#
# Then you can assemble a release by calling `mix release`.
# See `mix help release` for more information.
