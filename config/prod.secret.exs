# In this file, we load production configuration and secrets
# from environment variables. You can also hardcode secrets,
# although such is generally not recommended and you have to
# remember to add this file to your .gitignore.
use Mix.Config

config :bus_terminal_system, BusTerminalSystem.Repo,
   username: "probase",
   password: "V1neyard",
   database: "btmms",
   hostname: "10.70.1.4",
   timeout: :infinity,
   port: 3306,
   show_sensitive_data_on_connection_error: false,
   pool_size: 10

config :sentry,
   dsn: "https://5a7a2fc1e6284a3b91fc509a7b2ff299@app.glitchtip.com/342",
   environment_name: :prod,
   enable_source_code_context: true,
   root_source_code_path: File.cwd!,
   tags: %{
    env: "production"
   },
   included_environments: [:prod]

config :bus_terminal_system, BusTerminalSystemWeb.Endpoint,
   http: [port: String.to_integer(System.get_env("PORT") || "4000")],
   secret_key_base: "07MuWl3Z1fuEoGxPgpLoGGiy3/DMF1pgbUItrCY+PyMO6NNEMwWvpqXm72K1b1kP"

config :bus_terminal_system, BusTerminalSystemWeb.ApiEndpoint,
   http: [port: String.to_integer(System.get_env("PORT") || "4001")],
   secret_key_base: "kbI+dvsxztnGHY03dBMj3g37NPHV0V3Klxe/sDgZunKrfp5njkMIm/5Si/27zUbC"

# ## Using releases (Elixir v1.9+)
#
# If you are doing OTP releases, you need to instruct Phoenix
# to start each relevant endpoint:
#
config :bus_terminal_system, BusTerminalSystemWeb.Endpoint, server: true
config :bus_terminal_system, BusTerminalSystemWeb.ApiEndpoint, server: true
#
# Then you can assemble a release by calling `mix release`.
# See `mix help release` for more information.
