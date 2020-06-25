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
    get "/platform/secure/v1/commercial/services/users", UserController, :table_users
    get "/Registration_Form", UserController, :registration_form

    # BUSTERMINUS_CONTROLLER
    resources "/platform/secure/commercial/services/auto/terminus", BusTerminusController
    get "/Register_Bus_Teminus", BusTerminusController, :form_teminus
    get "/Create_Bus_Station", BusTerminusController, :form_station
    get "/Assign_Gate", BusTerminusController, :form_gate
    get "/bus_approval", BusTerminusController, :bus_approval

    # MARKETER_CONTROLLER_______________________________________________________________________________________
    resources "/platform/secure/commercial/services/marketer/market", MarketerController

    get(
      "/platform/secure/commercial/services/marketer/market/registering_market",
      MarketerController,
      :form_market
    )

    get "/creating_section", MarketerController, :form_section
    get "/allocating_shop", MarketerController, :form_shop
    get "/stand_allocation", MarketerController, :standallocation
    post "/create", MarketerController, :market_create_actions

    # ______________________________________________________________________________________________________________

    # TICKET_CONTROLLER
    resources "/platform/secure/commercial/services/ticketing/tickets", TicketController

    # SESSION_CONTROLLER
    get "/", SessionController, :new
    post "/", SessionController, :login
    post "/login", SessionController, :login
    get "/logout", SessionController, :logout

    # ROUTE_CONTROLLER
    get "/btmms/service/routes/display", RouteController, :index
    post "/btmms/service/routes/add_route", RouteController, :create
    get "/btmms/service/routes/customise_routes", RouteController, :customise_routes

    # TELLER_CONTROLLER
    get "/btmms/service/teller/display", TellerController, :index
    get "/btmms/service/teller/documentation", TellerController, :documentation
    get "/btmms/service/teller/reports", TellerController, :reports

    # VISA_CONTROLLER
    get "/payment", VisaController, :index

    # BOOKINGS_CONTROLLER
    get "/bookings", BookingsController, :index
    get "/scheduling", BookingsController, :schedule
    post "/create_mapping", BookingsController, :create_schedule
  end

  scope "/", BusTerminalSystemWeb do
    pipe_through [:browser, :auth, :ensure_auth]

    get "/protected", PageController, :protected
  end

  scope "/dev" do
    pipe_through [:browser]
    forward "/mailbox", Plug.Swoosh.MailboxPreview, [base_path: "/dev/mailbox"]
  end

  scope "/btms/services/graphql/" do
    pipe_through :api

    forward "/interface", Absinthe.Plug.GraphiQL,
            schema: BusTerminalSystemWeb.Schema

    forward "/", Absinthe.Plug,
            schema: BusTerminalSystemWeb.Schema
  end
  # Other scopes may use custom stacks.
  scope "/api/v1", BusTerminalSystemWeb do
    pipe_through :api

    post "/btms/tickets/secured/board_ticket", TicketController, :ticket_board_passenger
    post "/btms/tickets/secured/submit_ledger_transaction", TicketController, :transaction_post_to_ledger
    post "/btms/tickets/secured/find", TicketController, :find_ticket
    post "/btms/tickets/secured/find/serial", TicketController, :find_ticket_serial
    post "/btms/tickets/secured/purchase", TicketController, :purchase_ticket
    get "/btms/travel/secured/destinations", TicketController, :get_schedules
    post "/btms/travel/secured/internal/destinations", TicketController, :get_schedules_internal
    get "/btms/tickets/secured/internal/get_luggage_weight", TicketController, :get_luggage_weight

    post "/btms/travel/secured/internal/locations/destinations",
         TicketController,
         :get_schedules_buses

    post "/btms/travel/secured/internal/locations/destinations/internal",
         TicketController,
         :get_schedules_buses_internal

    get "/btms/travel/secured/routes", TicketController, :get_travel_routes
    get "/btms/tickets/secured/list", TicketController, :list_tickets

    post "/btms/market/secured/marketer_kyc", MarketApiController, :fetch_kyc
    get "/btms/market/secured/all_marketer_kyc", MarketApiController, :all_marketeer_kyc
    post "/btms/market/secured/marketer_kyc_minimal", MarketApiController, :fetch_kyc_minimal
    post "/btms/market/secured/authenticate_marketer", MarketApiController, :authenticate_marketer
    post "/btms/market/secured/update_pin", MarketApiController, :update_pin
    post "/btms/market/secured/reset_pin", MarketApiController, :reset_pin
    post "/btms/market/secured/register_market", MarketApiController, :register_marketeer


    post "/internal/create/acquire_luggage", FrontendApiController, :acquire_luggage
    post "/internal/create/virtual_ticket", FrontendApiController, :create_virtual_luggage_ticket
    post "/internal/query/route", FrontendApiController, :query_route
    post "/internal/update/route", FrontendApiController, :update_route_bus_route

    post "/internal/list/bus", FrontendApiController, :query_list_buses
    get "/internal/list/bus_routes", FrontendApiController, :list_travel_routes
    get "/internal/list/bus_operators", FrontendApiController, :list_bus_operators
    post "/internal/query/user", FrontendApiController, :query_user
    post "/internal/query/user/id", FrontendApiController, :query_user_by_id
    post "/internal/update/user", FrontendApiController, :update_user
    post "/internal/query/bus", FrontendApiController, :query_bus
    post "/internal/update/bus", FrontendApiController, :update_bus
    post "/internal/tickets/find", TicketController, :find_ticket_internal
    get "/internal/scale/query", FrontendApiController, :get_scale_query
    post "/internal/get_luggage_tarrif", FrontendApiController, :get_luggage_tarrif
    post "/internal/get_luggage_by_ticket_id", FrontendApiController, :get_luggage_by_ticket
    post "/internal/add_luggage", FrontendApiController, :add_luggage
    post "/internal/checkin", FrontendApiController, :checkin_passenger

    post "/internal/markets", FrontendApiController, :modules
  end

  def swagger_info do
    %{
      info: %{
        version: "1.0",
        title: "BTMS",
        contact: %{
          name: "Philip Chani",
          email: "philip@probasegroup.com"
        }
      },
      definitions: %{
        "/pets": %{}
      }
    }
  end
end
