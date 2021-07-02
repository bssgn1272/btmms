defmodule BusTerminalSystem.MixProject do
  use Mix.Project

  def project do
    [
      app: :bus_terminal_system,
      version: "0.1.0",
      elixir: "~> 1.5",
      elixirc_paths: elixirc_paths(Mix.env()),
      compilers: [:phoenix, :gettext, :phoenix_swagger] ++ Mix.compilers ++ [:phoenix_swagger],
      start_permanent: Mix.env() == :prod,
      aliases: aliases(),
      deps: deps()
    ]
  end

  # Configuration for the OTP application.
  #
  # Type `mix help compile.app` for more information.
  def application do
    [
      mod: {BusTerminalSystem.Application, []},
      extra_applications: [:logger, :runtime_tools, :elixir_xml_to_map, :soap, :swoosh, :gen_smtp]
    ]
  end

  # Specifies which paths to compile per environment.
  defp elixirc_paths(:test), do: ["lib", "test/support"]
  defp elixirc_paths(_), do: ["lib"]

  # Specifies your project dependencies.
  #
  # Type `mix help deps` for examples and options.
  defp deps do
    [
      {:phoenix, "~> 1.5.0"},
      {:phoenix_pubsub, "~> 2.0"},
      {:phoenix_ecto, "~> 4.0"},
      {:endon, "~> 1.0"},
      {:ecto_sql, "~> 3.1"},
      {:myxql, ">= 0.0.0"},
      {:phoenix_html, "~> 2.13.4"},
      {:phoenix_live_reload, "~> 1.2", only: :dev},
      {:gettext, "~> 0.11"},
      {:jason, "~> 1.1"},
      {:sentry, "~> 7.0"},
      {:plug_cowboy, "~> 2.1"},
      {:guardian, "~> 2.0.0"},
      {:poison, "~> 3.1.0"},
      {:json, "~> 1.3.0"},
      {:eqrcode, "~> 0.1.6"},
      {:barlix, "~> 0.6.0"},
      {:swoosh, "~> 0.24"},
      {:gen_smtp, "~> 0.13"},
      {:skooma, "~> 0.2.0"},
      {:httpoison, "~> 1.6"},
      #{:circuits_uart, "~> 1.4"},
#      {:redix, ">= 0.10.4"},
      {:timex, "~> 3.5"},
      {:absinthe, "~> 1.4"},
      {:absinthe_plug, "~> 1.4"},
      {:toolshed, "~> 0.2.13"},
      {:chartkick, "~>0.4.0"},
      {:distillery, "~> 2.0"},
      {:uuid, "~> 1.1.8" },
      {:atomic_map, "~> 0.9.3"},
      {:elixir_xml_to_map, "~> 2.0"},
      {:soap, "~> 1.0.1"},
      {:quantum, "~> 3.3.0"},
      {:phoenix_swagger, "~> 0.8"},
      {:ex_json_schema, "~> 0.5"},
      {:cachex, "~> 3.3"},
      {:ets, "~> 0.8.1"},
#      {:bamboo, "~> 2.0.1"},
#      {:bamboo_smtp, "~> 4.0.0"},
      {:xlsxir, "~> 1.6.4"},
      {:csv, "~> 2.3"},
      {:elixlsx, "~> 0.4.2"},
      {:zombie, "~> 0.1.1"},
      {:random_password, "~> 1.0"}
      #      {:scrivener_ecto, "~> 2.6.0"}
#      {:redix, ">= 0.0.0"},
#      {:castore, ">= 0.0.0"}
    ]
  end

  # Aliases are shortcuts or tasks specific to the current project.
  # For example, to create, migrate and run the seeds file at once:
  #
  #     $ mix ecto.setup
  #
  # See the documentation for `Mix` for more info on aliases.
  defp aliases do
    [
      "ecto.setup": ["ecto.create", "ecto.migrate", "run priv/repo/seeds.exs"],
      "ecto.reset": ["ecto.drop", "ecto.setup"],
      test: ["ecto.create --quiet", "ecto.migrate", "test"]
    ]
  end
end
