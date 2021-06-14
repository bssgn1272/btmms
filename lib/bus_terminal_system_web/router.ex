defmodule BusTerminalSystemWeb.Router do
  use BusTerminalSystemWeb, :router

  pipeline :browser do
    plug :accepts, ["html"]
    plug :fetch_session
    plug :fetch_flash
    plug :put_secure_browser_headers
    plug(BusTerminalSystemWeb.Plugs.SessionTimeout, timeout_after_seconds: 300)
    plug(BusTerminalSystemWeb.Plugs.SetUser)
  end

  pipeline :csrf do
    plug :protect_from_forgery
  end

  pipeline :un_auth do
    plug :put_layout, false
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

  scope "/", BusTerminalSystemWeb do
    pipe_through [:browser]

    match :*, "/btmms/service/user/management", UserManagementController, :redirect
    scope "/api" do
      match :*, "/btmms/service/till/management", TellerController, :redirect_process
    end

  end

  # Maybe logged in routes
  scope "/", BusTerminalSystemWeb do
    pipe_through [:browser, :auth, :csrf]

    # PAGE_CONTROLLER
    get "/platform/secure/commercial/services/management/dashboard", PageController, :index

    # USER_CONTROLLER
    resources "/platform/secure/commercial/services/users/management", UserController

    post "/platform/secure/commercial/services/users/management/register", UserController, :create_teller

    post "/platform/secure/commercial/services/users/register", UserController, :new_user
    post "/platform/secure/commercial/services/users/register/staff", UserController, :create_staff
    get "/platform/secure/commercial/services/teller/register", UserController, :new_teller
    get "/platform/secure/commercial/services/settings", UserController, :settings
    get "/platform/secure/commercial/services/register/staff", UserController, :new_staff
    get "/platform/secure/v1/json/commercial/services/users", UserController, :all_users_json
    get "/platform/secure/v1/commercial/services/users", UserController, :table_users
    get "/Registration_Form", UserController, :registration_form

    #Till Management
    get "/Registration_Form", UserController, :registration_form

    # BUSTERMINUS_CONTROLLER
    resources "/platform/secure/commercial/services/auto/terminus", BusTerminusController
    get "/Register_Bus_Teminus", BusTerminusController, :form_teminus
    get "/Create_Bus_Station", BusTerminusController, :form_station
    get "/Assign_Gate", BusTerminusController, :form_gate
    get "/bus_approval", BusTerminusController, :bus_approval
    get "/bus/register", BusTerminusController, :register

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
    get "/forgot_password", SessionController, :forgot_password
    get "/logout", SessionController, :logout

    # ROUTE_CONTROLLER
    get "/btmms/service/routes/display", RouteController, :index
    post "/btmms/service/routes/add_route", RouteController, :create
    get "/btmms/service/routes/customise_routes", RouteController, :customise_routes

    # TELLER_CONTROLLER
    get "/btmms/service/teller/display", TellerController, :index
    get "/btmms/service/teller/documentation", TellerController, :documentation
    get "/btmms/service/teller/reports", TellerController, :reports

    get "/btmms/service/teller/till/management", TellerController, :till_teller_manage

    # VISA_CONTROLLER
    get "/payment", VisaController, :index

    # BOOKINGS_CONTROLLER
    get "/bookings", BookingsController, :index
    get "/scheduling", BookingsController, :schedule
    post "/create_mapping", BookingsController, :create_schedule

    # USER_MANAGEMENT_AND_AUTHORIZATION




    scope "/Checker" do
      get "/", MakerCheckerController, :index
      post "/reject", MakerCheckerController, :reject
      post "/approve", MakerCheckerController, :approve
    end
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

  scope "/btmms/api/napsa", BusTerminalSystemWeb do
    pipe_through :api

    match :*, "/contributions", NapsaController, :contribute
    match :*, "/contributions/returns", NapsaController, :return_upload
    match :*, "/member/search", NapsaController, :search_member
    match :*, "/member/register", NapsaController, :register_member

  end


  # Other scopes may use custom stacks.
  scope "/api/v1", BusTerminalSystemWeb do
    pipe_through :api

    post "/btms/Dashboard/Checker/View", MakerCheckerController, :view

    post "/btms/secured/password/reset", SessionController, :reset_password

    post "/btms/tickets/secured/board_ticket", TicketController, :ticket_board_passenger
    post "/btms/tickets/secured/submit_ledger_transaction", TicketController, :transaction_post_to_ledger
    post "/btms/tickets/secured/find", TicketController, :find_ticket
    post "/btms/tickets/secured/find/external_ref", TicketController, :find_ticket_external_ref
    post "/btms/tickets/secured/find/serial", TicketController, :find_ticket_serial
    post "/btms/tickets/secured/purchase", TicketController, :purchase_ticket
    get "/btms/travel/secured/destinations", TicketController, :get_schedules
    post "/btms/travel/secured/cancel_trip", TicketController, :cancel_trip
    post "/btms/travel/secured/internal/destinations", TicketController, :get_schedules_internal
    get "/btms/tickets/secured/internal/get_luggage_weight", TicketController, :get_luggage_weight
    post "/btms/operator/reset_password", FrontendApiController, :reset_password
    post "/btms/operator/search", FrontendApiController, :find_operator
    post "/btms/H5TWgFg8ovMeZFZqKEdqXfetZ7LsytqO5Oilh8vHuiRnyqd1uWE6hICn", FrontendApiController, :form_validation_api
    post "/btms/H5TWgFg8ovMeZFZqKEdqXfetZ7LsytqO5Oilh8vHuiRnyqd1uWE6hIC0", FrontendApiController, :change_user_password

    post "/btms/travel/secured/internal/locations/destinations",
         TicketController,
         :get_schedules_buses

    post "/btms/travel/secured/internal/locations/destinations/internal",
         TicketController,
         :get_schedules_buses_internal

    post "/btms/plvPM5f+H5TWgFg8ovMeZFZqKEdqXfetZ7LsytqO5Oilh8vHuiRnyqd1uWE6hICn", TicketController, :create_ticket_payload

    match :*, "/btms/travel/secured/routes", TicketController, :get_travel_routes

    get "/btms/tickets/secured/list", TicketController, :list_tickets


    post "/btms/market/secured/marketer_kyc", MarketApiController, :fetch_kyc
    get "/btms/market/secured/all_marketer_kyc", MarketApiController, :all_marketeer_kyc
    post "/btms/market/secured/marketer_kyc_minimal", MarketApiController, :fetch_kyc_minimal
    post "/btms/market/secured/authenticate_marketer", MarketApiController, :authenticate_marketer
    post "/btms/market/secured/update_pin", MarketApiController, :update_pin
    post "/btms/market/secured/reset_pin", MarketApiController, :reset_pin
    post "/btms/market/secured/register_market", MarketApiController, :register_marketeer

    post "/btms/operator/secured/update_password", FrontendApiController, :update_user_password

    post "/internal/create/acquire_luggage", FrontendApiController, :acquire_luggage
    post "/internal/create/acquire_luggage_plain", FrontendApiController, :acquire_luggage_form_view
    post "/internal/create/virtual_ticket", FrontendApiController, :create_virtual_luggage_ticket
    post "/internal/query/route", FrontendApiController, :query_route
    post "/internal/delete/route", FrontendApiController, :delete_route
    post "/internal/update/route", FrontendApiController, :update_route_bus_route

    post "/internal/reset_password", FrontendApiController, :reset_password
    post "/internal/user/permissions", FrontendApiController, :get_permissions
    post "/internal/list/bus", FrontendApiController, :query_list_buses
    get "/internal/list/bus_routes", FrontendApiController, :list_travel_routes
    get "/internal/list/bus_operators", FrontendApiController, :list_bus_operators
    post "/internal/query/user", FrontendApiController, :query_user
    post "/internal/query/user/id", FrontendApiController, :query_user_by_id
    post "/internal/update/user", FrontendApiController, :update_user
    post "/internal/query/bus", FrontendApiController, :query_bus
    post "/internal/update/bus", FrontendApiController, :update_bus
    get "/internal/update/balances", FrontendApiController, :update_balances
    post "/internal/delete/bus", FrontendApiController, :delete_bus
    post "/internal/tickets/find", TicketController, :find_ticket_internal
    get "/internal/scale/query", FrontendApiController, :get_scale_query

    get "/internal/banks/list", FrontendApiController, :bank_list
    post "/internal/banks/get_bank_branches", FrontendApiController, :branch_list
    post "/internal/banks/get_bank", FrontendApiController, :get_bank


    post "/internal/get_luggage_tarrif", FrontendApiController, :get_luggage_tarrif
    post "/internal/get_luggage_by_ticket_id", FrontendApiController, :get_luggage_by_ticket
    post "/internal/get_luggage_by_ticket_id_total_cost", FrontendApiController, :get_luggage_by_ticket_total_cost
    post "/internal/add_luggage", FrontendApiController, :add_luggage
    post "/internal/checkin", FrontendApiController, :checkin_passenger
    post "/internal/tickets/cancel", FrontendApiController, :cancel_ticket
    post "/internal/tickets/update", FrontendApiController, :update_ticket

    post "/internal/napsa/add_member_beneficiary", FrontendApiController, :add_beneficiary
    post "/internal/napsa/clear_member_beneficiaries", FrontendApiController, :clear_beneficiaries

    post "/internal/discounts/operator", FrontendApiController, :discount_operator
    post "/internal/discounts/enable", FrontendApiController, :enable_discount
    post "/internal/discounts/set", FrontendApiController, :set_discount
    get "/internal/routes/threshold", FrontendApiController, :minimum_route_price

    post "/internal/markets", FrontendApiController, :modules

    post "/internal/transaction/deposit", TellerController, :deposit
    post "/internal/transaction/withdraw", TellerController, :withdraw

    post "/internal/funds_sweep", FrontendApiController, :funds_transfer
    post "/internal/update/settings", FrontendApiController, :update_settings
    post "/internal/banks/retry", FrontendApiController, :retry_account_creation
    post "/internal/verify/user_id", FrontendApiController, :verify_user_id
  end

  # Maker checker implementation
  scope "/authorisation", BusTerminalSystemWeb do
    pipe_through :browser
    get "/btms", MakerCheckerController, :index
  end

  scope "/api/swagger" do
    forward "/", PhoenixSwagger.Plug.SwaggerUI, otp_app: :bus_terminal_system, swagger_file: "swagger.json"
  end

  def swagger_info do
    %{
      schemes: ["http"],
      info: %{
        version: "1.0",
        title: "BTMMS",
        description: "API Documentation for BTMMS v1",
        termsOfService: "Open for public",
        contact: %{
          name: "Philip Chani",
          email: "philip@probasegroup.com"
        }
      },
      consumes: ["application/json"],
      produces: ["application/json"],
      tags: [
        %{name: "Users", description: "User resources"},
      ]
    }
  end

#  def swagger_info() do
#    %{
#      info: %{
#        version: "1.0",
#        title: "BTMMS",
#        contact: %{
#          name: "Philip Chani",
#          email: "philip@probasegroup.com"
#        }
#      }
#    }
#  end
end
