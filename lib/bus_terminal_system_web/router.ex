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

    # PAGE_CONTROLLER
    get "/platform/secure/commercial/services/management/dashboard", PageController, :index

    # USER_CONTROLLER
    resources "/platform/secure/commercial/services/users/management", UserController
    get "/platform/secure/v1/json/commercial/services/users", UserController, :all_users_json
    get "/TableUsers", UserController, :table_users
    get "/Registration_Form", UserController, :registration_form

    # BUSTERMINUS_CONTROLLER
    resources "/platform/secure/commercial/services/auto/terminus", BusTerminusController
    get "/Register_Bus_Teminus", BusTerminusController, :form_teminus
    get "/Create_Bus_Station", BusTerminusController, :form_station
    get "/Assign_Gate", BusTerminusController, :form_gate

    # MARKETER_CONTROLLER
    resources "/platform/secure/commercial/services/marketer/market", MarketerController
    get("/Registering_Market", MarketerController, :form_market)
    get("/Creating_Section", MarketerController, :form_section)
    get("/Allocating_shop", MarketerController, :form_shop)

    # TICKET_CONTROLLER
    resources "/platform/secure/commercial/services/ticketing/tickets", TicketController
    get "/", SessionController, :new
    post "/", SessionController, :login
    post "/login", SessionController, :login
    get "/logout", SessionController, :logout

    # ROUTE_CONTROLLER
    get "/routes", RouteController, :index
  end

  scope "/", BusTerminalSystemWeb do
    pipe_through [:browser, :auth, :ensure_auth]

    get "/protected", PageController, :protected
  end

  # Other scopes may use custom stacks.
  scope "/api/v1", BusTerminalSystemWeb do
    pipe_through :api

    post "/tickets/secured/find", TicketController, :find_ticket
    post "/tickets/secured/purchase", TicketController, :purchase_ticket
    get "/tickets/secured/list", TicketController, :list_tickets
  end
end
