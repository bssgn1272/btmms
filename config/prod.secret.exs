# In this file, we load production configuration and secrets
# from environment variables. You can also hardcode secrets,
# although such is generally not recommended and you have to
# remember to add this file to your .gitignore.
use Mix.Config

config :bus_terminal_system, BusTerminalSystem.Repo,
   username: "root",
   password: "Qwerty12",
   database: "btmms",
   hostname: "ops.probasegroup.com",
   show_sensitive_data_on_connection_error: false,
   pool_size: 10

config :bus_terminal_system, BusTerminalSystemWeb.Endpoint,
  http: [port: String.to_integer(System.get_env("PORT") || "4000")],
  secret_key_base: "nonhZ3v4ASMVdtVV0u9+6YBkIzDe4OtBGuX+VY2fgEIcIYASbftOZ4VGsncC0AUH"

# ## Using releases (Elixir v1.9+)
#
# If you are doing OTP releases, you need to instruct Phoenix
# to start each relevant endpoint:
#
config :bus_terminal_system, BusTerminalSystemWeb.Endpoint, server: true
#
# Then you can assemble a release by calling `mix release`.
# See `mix help release` for more information.
