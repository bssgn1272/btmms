defmodule BusTerminalSystemWeb.Router do
  use BusTerminalSystemWeb, :router

  pipeline :browser do
    plug :accepts, ["html"]
    plug :fetch_session
    plug :fetch_flash
    plug :protect_from_forgery
    plug :put_secure_browser_headers
    plug(BusTerminalSystemWeb.Plugs.SetUser)
  end

  # Our pipeline implements "maybe" authenticated. We'll use the `:ensure_auth` below for when we need to make sure someone is logged in.
  pipeline :auth do
    plug BusTerminalSystem.AccountManager.Pipeline
  end

  # We use ensure_auth to fail if there is no one logged in
  pipeline :ensure_auth do
    plug Guardian.Plug.EnsureAuthenticated
  end

  pipeline :api do
    plug :accepts, ["json"]
  end

  # Maybe logged in routes
  scope "/", BusTerminalSystemWeb do
    pipe_through [:browser, :auth]

    get "/platform/secure/commercial/services/management/dashboard", PageController, :index

    resources "/platform/secure/commercial/services/users/management", UserController
    get "/platform/secure/commercial/services/user/management/profile", UserController, :profile
    get "/platform/secure/v1/json/commercial/services/users", UserController, :all_users_json
    get "/TableUsers", UserController, :table_users

    resources "/platform/secure/commercial/services/auto/terminus", BusTerminusController
    resources "/platform/secure/commercial/services/marketer/market", MarketerController
    get("/creating_market", MarketerController, :form)
    get("/creating_section", MarketerController, :formSection)
    get("/creating_shop", MarketerController, :formShop)
    resources "/platform/secure/commercial/services/ticketing/tickets", TicketController

    get "/", SessionController, :new
    post "/login", SessionController, :login
    get "/logout", SessionController, :logout
  end

  scope "/", BusTerminalSystemWeb do
    pipe_through [:browser, :auth, :ensure_auth]

    get "/protected", PageController, :protected
  end

  # Other scopes may use custom stacks.
  scope "/api/v1", BusTerminalSystemWeb do
    pipe_through :api

    post "/platform/secure/commercial/services/users/management/create_user",
         UserController,
         :api_create_user

    get "/platform/secure/commercial/services/users/management/get_users",
        UserController,
        :all_users_json
  end
end
