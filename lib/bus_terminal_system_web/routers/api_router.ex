defmodule BusTerminalSystemWeb.ApiRouter do
  use BusTerminalSystemWeb, :router
  use Plug.ErrorHandler
  use Sentry.Plug

  pipeline :browser do
    plug :accepts, ["html"]
    plug :fetch_session
    plug :fetch_flash
    plug :put_secure_browser_headers
    plug(BusTerminalSystemWeb.Plugs.SessionTimeout, timeout_after_seconds: 300)
    plug(BusTerminalSystemWeb.Plugs.SetUser)
  end

#  pipeline :csrf do
#    plug :protect_from_forgery
#  end

#  pipeline :un_auth do
#    plug :put_layout, false
#  end

  # Our pipeline implements "maybe" authenticated. We'll use the `:ensure_auth` below for when we need to make sure someone is logged in.
#  pipeline :auth do
#    plug BusTerminalSystem.AccountManager.Pipeline
#  end

  # We use ensure_auth to fail if there is no one logged in
#  pipeline :ensure_auth do
#    plug Guardian.Plug.EnsureAuthenticated
#  end

  pipeline :api do
    plug :accepts, ["json"]
  end

  # Other scopes may use custom stacks.
  scope "/api/v1", BusTerminalSystemWeb do
    pipe_through :api



    post "/btms/tickets/secured/board_ticket", TicketController, :ticket_board_passenger
    post "/btms/tickets/secured/submit_ledger_transaction", TicketController, :transaction_post_to_ledger
    post "/btms/tickets/secured/find", TicketController, :find_ticket
    post "/btms/tickets/secured/find/external_ref", TicketController, :find_ticket_external_ref
    post "/btms/tickets/secured/find/serial", TicketController, :find_ticket_serial
    post "/btms/tickets/secured/purchase", TicketController, :purchase_ticket
    get "/btms/travel/secured/destinations", TicketController, :get_schedules
    post "/btms/travel/secured/cancel_trip", TicketController, :cancel_trip

    match :*, "/btms/travel/secured/routes", TicketController, :get_travel_routes
    get "/btms/tickets/secured/list", TicketController, :list_tickets


    post "/btms/market/secured/marketer_kyc", MarketApiController, :fetch_kyc
    get "/btms/market/secured/all_marketer_kyc", MarketApiController, :all_marketeer_kyc
    post "/btms/market/secured/marketer_kyc_minimal", MarketApiController, :fetch_kyc_minimal
    post "/btms/market/secured/authenticate_marketer", MarketApiController, :authenticate_marketer
    post "/btms/market/secured/update_pin", MarketApiController, :update_pin
    post "/btms/market/secured/reset_pin", MarketApiController, :reset_pin
    post "/btms/market/secured/register_market", MarketApiController, :register_marketeer



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
