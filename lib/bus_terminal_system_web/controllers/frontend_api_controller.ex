defmodule BusTerminalSystemWeb.FrontendApiController do
  use BusTerminalSystemWeb, :controller
  use PhoenixSwagger

  alias BusTerminalSystem.RepoManager
  alias BusTerminalSystem.ApiManager
  alias BusTerminalSystem.ScaleQuery
  alias BusTerminalSystem.TicketManagement
  alias BusTerminalSystem.NapsaSmsGetway
  alias BusTerminalSystem.TicketManagement.Ticket
  alias BusTerminalSystem.AccountManager.User
  alias BusTerminalSystem.UserRole
  alias BusTerminalSystem.Notification.Table.Sms
  alias BusTerminalSystem.Market.Section


  #---------------------------------------USER--------------------------------------------------------------------------

  def swagger_definitions do
    %{}
  end

  swagger_path :list_bus_operators do
    get "/api/v1/users"
    paging size: "page[size]", number: "page[number]"
    response 200, "OK"
  end

  def list_bus_operators(conn, _params) do
    operators = RepoManager.list_bus_operators()
    conn
    |> json(operators)
  end

  def query_user_by_id(conn, params) do

    user_id = params["selected_user"]
    case user_id do
      nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
      _ ->
        case user_id |> RepoManager.find_user_by_id do
          nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
          user ->
            conn
            |> json(ApiManager.api_message_custom_handler_conn(conn,ApiManager.definition_query,"SUCCESS",0,
              %{
                "username" => user.username,
                "first_name" => user.first_name,
                "last_name" => user.last_name,
                "ssn" => user.ssn,
                "nrc" => user.nrc,
                "compliance" => user.compliance,
                "email" => user.email,
                "mobile" => user.mobile,
                "company" => user.company,
                "account_status" => user.account_status,
                "uuid" => user.uuid,
                "operator_role" => user.operator_role
              }))
          _value ->
            json conn, ["hello"]
        end
    end
  end

  def find_operator(conn, params) do

    user_id = params["payload"]["operator_id"]
    case user_id do
      nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
      _ ->
        case user_id |> RepoManager.find_user_by_id do
          nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
          user ->

            conn
            |> json(ApiManager.api_message_custom_handler_conn(conn,ApiManager.definition_query,"SUCCESS",0,
              %{
                "username" => user.username,
                "first_name" => user.first_name,
                "last_name" => user.last_name,
                "ssn" => user.ssn,
                "nrc" => user.nrc,
                "email" => user.email,
                "mobile" => user.mobile,
                "role" => user.role,
                "company" => user.company,
                "account_status" => user.account_status,
                "uuid" => user.uuid,
                "operator_role" => user.operator_role
              }))
          _value ->
            json conn, ["hello"]
        end
    end
  end

  def query_user(conn, params) do

    user_id = params["payload"]["selected_user"]
    case user_id do
      nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
      _ ->
        case user_id |> RepoManager.find_user_by_id do
          nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
          user ->

            try do
              Cachex.put(:tmp, params["logged_in_user"], user.id)
            rescue
              _ -> ""
            end

            conn
            |> json(ApiManager.api_message_custom_handler_conn(conn,ApiManager.definition_query,"SUCCESS",0,
              %{
                "account_number" => user.account_number,
                "username" => user.username,
                "first_name" => user.first_name,
                "last_name" => user.last_name,
                "ssn" => user.ssn,
                "nrc" => user.nrc,
                "company" => user.company,
                "compliance" => user.compliance,
                "email" => user.email,
                "mobile" => user.mobile,
                "account_status" => user.account_status,
                "uuid" => user.uuid,
                "operator_role" => user.operator_role,
                "role_id" => user.role_id,
#                "role_name" => BusTerminalSystem.UserRoles.find(user.id).role
              }))
          _value ->
            json conn, ["hello"]
        end
    end
  end

  def reset_password(conn, params) do


    password = BusTerminalSystem.Randomizer.randomizer(5,:numeric)
    user = BusTerminalSystem.AccountManager.User.find_by(username: params["payload"]["username"])

    spawn(fn ->
      BusTerminalSystem.Notification.Table.Sms.create!([recipient: user.mobile, message: "Password Reset. Your new BTMMS portal password is #{password}", sent: false])
    end)

    user |> BusTerminalSystem.AccountManager.User.update([password: Base.encode16(:crypto.hash(:sha512, password)), account_status: "OTP"])

    conn |> json(%{"message" => "password reset"})
  end
  
  def update_user(conn, %{"payload" => payload } = params) do

    IO.inspect params
    username = payload["username"]

    if username == nil do
      json(conn,ApiManager.api_error_handler(conn,ApiManager.definition_query,[
        "username can not be blank"
      ]))
    else
      case username do
        nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
        _ ->
          case username |> RepoManager.find_user_by_username do
            nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
            user ->

              case RepoManager.update_user(user,payload) do
                {:ok, user} ->

                  if payload["role_id"] != "0" do
                    UserRole.find_or_create_by(user: user.id)
                    |> case do
                      {:ok, user_role} -> user_role |> UserRole.update([
                        role: Decimal.new(payload["role_id"]) |> Decimal.to_integer,
                        maker: user.id,
                        auth_status: true,
                        user_description: "ROLE ATTACHED TO #{user.username}"
                      ])
                         _ -> ""
                       end
                  end

                  conn
                  |> json(ApiManager.api_message_custom_handler_conn(conn,ApiManager.definition_authentication,"SUCCESS",0,
                    %{
                      "message" => "PIN UPDATED",
                      "username" => user.username,
                      "first_name" => user.first_name,
                      "last_name" => user.last_name,
                      "ssn" => user.ssn,
                      "nrc" => user.nrc,
                      "email" => user.email,
                      "mobile" => user.mobile,
                      "account_status" => user.account_status,
                      "uuid" => user.uuid,
                      "operator_role" => user.operator_role,
                      "account_status" => user.account_status,
                      "role_id" => user.role_id,
                      "role_name" => BusTerminalSystem.UserRoles.find(user.role_id).role
                    }))
                {:error, %Ecto.Changeset{} = changeset} ->
                  conn
                  |> json(ApiManager.api_error_handler(ApiManager.definition_accounts(),ApiManager.translate_error(changeset)))
              end
          end
      end
    end
  end

  def list_users(conn,_params) do

  end

  #---------------------------------------BUS---------------------------------------------------------------------------
  def query_bus(conn, params) do

    bus_id = params["payload"]["bus_id"]
    case bus_id do
      nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
      _ ->
        case bus_id |> RepoManager.find_bus_by_id do
          nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
          bus ->
            conn
            |> json(ApiManager.api_message_custom_handler_conn(conn,ApiManager.definition_query,"SUCCESS",0,
              %{
                "license_plate" => bus.license_plate,
                "uid" => bus.uid,
                "engine_type" => bus.engine_type,
                "model" => bus.model,
                "make" => bus.make,
                "year" => bus.year,
                "color" => bus.color,
                "state_of_registration" => bus.state_of_registration,
                "vin_number" => bus.vin_number,
                "serial_number" => bus.serial_number,
                "hull_number" => bus.hull_number,
                "operator_id" => bus.operator_id,
                "vehicle_class" => bus.vehicle_class,
                "company" => bus.company,
                "company_info" => bus.company_info,
                "fitness_license" => bus.fitness_license,
                "vehicle_capacity" => bus.vehicle_capacity
              }))
          _value ->
            json conn, ["hello"]
        end
    end
  end

  def query_list_buses(conn, params) do

    bus_id = params["payload"]["bus_id"]
    case bus_id do
      nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
      _ ->
        case bus_id |> RepoManager.list_buses do
          nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
          buses ->
            conn
            |> json(buses)
          _value ->
            json conn, ["hello"]
        end
    end
  end



  def update_bus(conn, %{"payload" => payload } = params) do

    bus_uid  = payload["bus_uid"]

    if bus_uid == nil do
      json(conn,ApiManager.api_error_handler(conn,ApiManager.definition_query,[
        "bus_uid can not be blank"
      ]))
    else
      case bus_uid do
        nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
        _ ->
          case bus_uid |> RepoManager.find_bus_by_uid do
            nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
            bus ->
              case RepoManager.update_bus(bus,payload) do
                {:ok, bus} ->
                  conn
                  |> json(ApiManager.api_message_custom_handler_conn(conn,ApiManager.definition_authentication,"SUCCESS",0,
                    %{
                      "license_plate" => bus.license_plate,
                      "uid" => bus.uid,
                      "engine_type" => bus.engine_type,
                      "model" => bus.model,
                      "make" => bus.make,
                      "year" => bus.year,
                      "color" => bus.color,
                      "state_of_registration" => bus.state_of_registration,
                      "vin_number" => bus.vin_number,
                      "serial_number" => bus.serial_number,
                      "hull_number" => bus.hull_number,
                      "operator_id" => bus.operator_id,
                      "vehicle_class" => bus.vehicle_class,
                      "company" => bus.company,
                      "company_info" => bus.company_info,
                      "fitness_license" => bus.fitness_license,
                      "vehicle_capacity" => bus.vehicle_capacity
                    }))
                {:error, %Ecto.Changeset{} = changeset} ->
                  conn
                  |> json(ApiManager.api_error_handler(ApiManager.definition_accounts(),ApiManager.translate_error(changeset)))
              end
          end
      end
    end
  end

  def update_user_password(conn, params) do
    ApiManager.auth(conn,params)

    username = params["payload"]["username"]
    password = params["payload"]["password"]

    if username == nil or password == nil do
      json(conn,ApiManager.api_error_handler(conn,ApiManager.definition_query,[
        "username can not be blank",
        "password can not be blank"
      ]))
    else
      case username do
        nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_update(),ApiManager.not_found_update()))
        _ ->
          case BusTerminalSystem.AccountManager.User.find_by(username: username) do
            nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_update(),ApiManager.not_found_update()))
            user ->
              case BusTerminalSystem.AccountManager.User.update(user,[password: Base.encode16(:crypto.hash(:sha512, password)), account_status: "ACTIVE"]) do
                {:ok, user} ->
                  conn
                  |> json(ApiManager.api_message_custom_handler_conn(conn,ApiManager.definition_authentication,"SUCCESS",0,
                    %{
                      "message" => "PASSWORD UPDATED",
                      "username" => user.username,
                      "first_name" => user.first_name,
                      "last_name" => user.last_name,
                      "ssn" => user.ssn,
                      "nrc" => user.nrc,
                      "email" => user.email,
                      "mobile" => user.mobile,
                      "account_status" => user.account_status,
                      "uuid" => user.uuid,
                      "operator_role" => user.operator_role
                    }))
                _ ->
                  conn |> json(ApiManager.api_error_handler(ApiManager.definition_accounts(),%{"message" => "Could not update Password. An error occurred"}))
              end
          end
      end
    end
  end

  #---------------------------------------Routes------------------------------------------------------------------------

  def list_travel_routes(conn, _params) do
    operators = RepoManager.list_bus_routes()
    conn
    |> json(operators)
  end

  def query_route(conn, params) do
    route_id = params["payload"]["route_id"]
    conn |> json(RepoManager.route_by_id_json(route_id))
  end

  def delete_route(conn, params) do
    route_id = params["payload"]["route_id"]
    route = BusTerminalSystem.TravelRoutes.find(route_id)
    BusTerminalSystem.TravelRoutes.delete(route)
    |> case do
         {:ok, _} -> conn |> json(%{status: 0, message: "Route deleted Successfully."})
         {:error, _} -> conn |> json(%{status: 1, message: "Failed to delete Route."})
     end
  end

  def update_route_bus_route(conn, %{"payload" => %{ "route_id" => route_id } = payload} = params) do
    RepoManager.find_route_by_id(route_id)
    |> case do
         route ->
          RepoManager.update_route(route, payload)
          conn |> json(%{})

          _ -> conn |> json(%{})
       end
  end

  #---------------------------------------Scale-------------------------------------------------------------------------
  def get_scale_query(conn, _params) do
    conn |> json(ScaleQuery.query_scale(conn.remote_ip))
  end

  #---------------------------------------Luggage-------------------------------------------------------------------------

  def get_luggage_tarrif(conn,%{ "tarrif_id" => id} = params) do
      conn |> json( RepoManager.get_luggage_tarrif(id))
  end

  def get_luggage_by_ticket(conn, %{ "ticket_id" => ticket_id } = params) do
    if ticket_id == nil do
      conn |> json(RepoManager.get_luggage_by_ticket_id(0))
    else
      conn |> json(RepoManager.get_luggage_by_ticket_id(ticket_id))
    end
  end

  def get_luggage_by_ticket_total_cost(conn, %{ "ticket_id" => ticket_id } = params) do
    if ticket_id == nil do
      conn |> text(RepoManager.get_luggage_by_ticket_id_total_cost(0))
    else
      conn |> text(RepoManager.get_luggage_by_ticket_id_total_cost(ticket_id))
    end
  end

  def add_luggage(conn, params) do
    conn |> json(RepoManager.create_luggage(params))
  end

  def acquire_luggage(conn, %{"sender" => sender, "receiver" => receiver, "luggage_id" => luggage_id} = params)do

    sms_message = "Luggage from #{sender} to #{receiver} Check-in successful \n LUGGAGE ID: #{luggage_id}"
    spawn(fn ->
      NapsaSmsGetway.send_sms(sender,sms_message)
    end)

    spawn(fn ->
      NapsaSmsGetway.send_sms(receiver,sms_message)
    end)

    conn |> json(%{"status" => "done"})
  end

  def luggage_ref do
    dt = DateTime.utc_now
    {micro,_} = dt.microsecond
    "ZBMS-#{dt.year}#{dt.month}#{dt.day}-#{dt.hour}#{dt.minute}#{dt.second}#{micro}"
  end

  def ext_luggage_ref do
    dt = DateTime.utc_now
    {micro,_} = dt.microsecond
    "REF-#{dt.year}#{dt.month}#{dt.day}-#{dt.hour}#{dt.minute}#{dt.second}#{micro}"
  end

  def ext_luggage_serial do
    dt = DateTime.utc_now
    {micro,_} = dt.microsecond
    "S#{dt.year}#{dt.month}#{dt.day}#{dt.hour}#{dt.minute}#{dt.second}#{micro}"
  end

  def from(key, map) do
    Map.fetch!(map,key)
  end

  def acquire_luggage_form_view(conn,%{"unattended" => luggage_params} = params) do

    sender_mobile = Map.fetch!(luggage_params,"recipient_mobile")
    receiver_mobile = Map.fetch!(luggage_params,"sender_mobile")
    luggage_id = Map.fetch!(luggage_params,"ticket_id")

    sender_message = "You have successfully checked in and sent Luggage/Cargo to #{ receiver_mobile },ID: #{luggage_id}\nSend ID to recipient for collection. Thank you for using BTMMS Services"
    receiver_message = "Hello, the number #{sender_mobile} has sent Luggage/Cargo to this number.\nA collection ID will be sent to you by sender for collection.\nThank you for using BTMMS Services."

    spawn(fn ->
      BusTerminalSystem.Notification.Table.Sms.create!([recipient: receiver_mobile, message: receiver_message, sent: false])
    end)

    spawn(fn ->
      BusTerminalSystem.Notification.Table.Sms.create!([recipient: sender_mobile, message: sender_message, sent: false])
    end)

    ticket = Map.fetch!(luggage_params,"ticket_id") |> BusTerminalSystem.TicketManagement.Ticket.find
    bus_id = Map.fetch!(luggage_params,"bus_id")
    bus_schedule_id = Map.fetch!(luggage_params,"bus_schedule_id")
    start_route = Map.fetch!(luggage_params,"start_route")
    end_route = Map.fetch!(luggage_params,"end_route")
    nrc_id = Map.fetch!(luggage_params,"nrc_id")

    bus = bus_id |> BusTerminalSystem.BusManagement.Bus.find
    operator = bus.operator_id |> BusTerminalSystem.AccountManager.User.find
    schedule = bus_schedule_id |> BusTerminalSystem.TblEdReservations.find

    [date, _] = schedule.reserved_time |> to_string |> String.split(" ")
    [year, month, day] = date |> String.split("-")
    travel_date = " #{day}/#{month}/#{year} #{schedule.time}"
    luggage_total_cost = ticket.id |> RepoManager.get_luggage_by_ticket_id_total_cost
    reference_number = luggage_ref

    r_info = "OPERATOR: #{operator.company |> String.replace(" ","_")}: START: #{start_route} END: #{end_route}	 DEPARTURE: #{schedule.time} PRICE: K#{luggage_total_cost} GATE: #{schedule.slot}"

    ticket_update = ticket |> BusTerminalSystem.TicketManagement.Ticket.update([
      first_name: "recipient_firstname" |> from(luggage_params),
      last_name: "recipient_lastname" |> from(luggage_params),
      amount: 0.00,
      transaction_channel: "TELLER",
      payment_mode: "CASH",
      has_luggage: false,
      route: schedule.route,
      route_information: r_info,
      bus_no: bus.id |> to_string,
      bus_schedule_id: bus_schedule_id,
      maker: "maker" |> from(luggage_params),
      luggage_total: luggage_total_cost,
      reference_number: reference_number,
      external_ref: "#{ext_luggage_ref}",
      mobile_number: "recipient_mobile" |> from(luggage_params),
      travel_date: "#{year}-#{month}-#{day}",
      passenger_id: "nrc_id" |> from(luggage_params),
      id_type: "UNIVERSAL",
      info: "sender_mobile" |> from(luggage_params),
      serial_number: ext_luggage_serial
    ])

    spawn(fn ->
      %{
        "refNumber" => reference_number,
        "fName" => "recipient_firstname" |> from(luggage_params),
        "sName" => "recipient_lastname" |> from(luggage_params),
        "from" => start_route,
        "to" => end_route,
        "Price" => luggage_total_cost,
        "Bus" => operator.company,
        "gate" => BusTerminalSystem.TblSlotMappings.find_by(slot: schedule.slot).gate,
        "depatureTime" => travel_date,
        "ticketNumber" => ticket.id,
        "items" => BusTerminalSystem.RepoManager.acquire_luggage(ticket.id)
      } |> BusTerminalSystem.PrinterTcpProtocol.print_remote_cross_connect(conn.remote_ip)
    end)

    conn
    |> redirect(to: Routes.user_path(conn, :index))
  end

  def checkin_passenger(conn,%{"ticket_id" => ticket_id} = params) do
    conn |> json(RepoManager.checkin(ticket_id, conn.remote_ip))
  end

  #---------------------------------------MARKET-------------------------------------------------------------------------

  alias BusTerminalSystem.Market.MarketRepo

  @validation_params %{ "module" => :string, "action" => :string, "branch" => :string, "use_params" => :bool}
  def modules(conn, params) do

    Skooma.valid?(params,@validation_params) |> case do
        :ok ->
          %{"branch" => branch} = params
          branch |> case do
             "MARKET" -> conn |> market_module(params)
             "TERMINUS" -> ""
             "ADMINISTRATION" -> ""
             _ -> conn |> json(%{"error" => "No Branch #{branch} found"})
           end
        {:error, error_message} -> conn |> json(error_message)
    end
  end

  defp market_module(conn, %{"module" => module, "action" => action, "use_params" => use_params, "params" => parameters} = params) do
    action
    |> case do
         "LIST" ->
           module
           |> case do
                "market" ->
                  if use_params do query_list_by_params(conn,parameters,module) else
                    conn |> json(MarketRepo.market_list() |> Poison.encode! |> JSON.decode!)
                  end
                "section" ->
                  if use_params do query_list_by_params(conn,parameters,module) else
                    conn |> json(MarketRepo.market_section_list() |> Poison.encode! |> JSON.decode!)
                  end
                "stand" ->
                  if use_params do query_list_by_params(conn,parameters,module) else
                    conn |> json(MarketRepo.market_shop_list() |> Poison.encode! |> JSON.decode!)
                  end
                _ -> conn |> json(%{"error" => "No Module with name #{module}"})
              end
         "CREATE" ->
           module
           |> case do
                "market" -> create_by_params(conn,parameters,module)
                "section" -> create_by_params(conn,parameters,module)
                "stand" -> create_by_params(conn,parameters,module)
              end
         "QUERY" ->
            module
            |> case do
                "market" -> query_query_by_params(conn,parameters,module)
                "section" -> query_query_by_params(conn,parameters,module)
                "stand" -> query_query_by_params(conn,parameters,module)
               end
         "UPDATE" -> ""
         _ -> conn |> json(%{"error" => "No Action Type for #{module}"})
       end
  end

  defp query_list_by_params(conn,params,module) do
    params |> JSON.decode |> case do
       {:ok, result} ->
          module |> case do
            "market" ->
              [id] = result
              conn |> json(MarketRepo.market_list_by_market_id(id) |> Poison.encode! |> JSON.decode!)
            "section" ->
              [id] = result
              conn |> json(MarketRepo.market_section_list_by_market_id(id) |> Poison.encode! |> JSON.decode!)
            "stand" ->
              [id] = result
              conn |> json(MarketRepo.market_shop_list_by_market_id(id) |> Poison.encode! |> JSON.decode!)
          end

       {:error, error_message} -> conn |> json(%{"error" => "Could not parse parameters #{params}"})
     end
  end

  defp query_query_by_params(conn,params,module) do
    params |> JSON.decode |> case do
       {:ok, result} ->
         module
         |> case do
           "market" ->
             [id] = result
             conn |> json(MarketRepo.market_find([id: id]) |> Poison.encode! |> JSON.decode!)
           "section" ->
             [id] = result
             conn |> json(MarketRepo.market_section_find([id: id]) |> Poison.encode! |> JSON.decode!)
           "stand" ->
             [id] = result
             conn |> json(MarketRepo.market_shop_find([id: id]) |> Poison.encode! |> JSON.decode!)
         end

       {:error, error_message} -> conn |> json(%{"error" => "Could not parse parameters #{params}"})
     end
  end

  defp create_by_params(conn,params,module) do
     module
     |> case do
        "market" -> conn |> json(MarketRepo.market_create(params) |> Poison.encode! |> JSON.decode!)
        "section" -> conn |> json(MarketRepo.market_section_create(params) |> Poison.encode! |> JSON.decode!)
        "stand" ->
          spawn(fn ->
            Sms.create!([recipient: User.find(params["maketeer_id"]).mobile, message: "Your account has been allocated a new stand number #{params["shop_number"]} in section #{Section.find(params["section_id"]).section_name}", sent: false])
          end)
          conn |> json(MarketRepo.market_shop_create(params) |> Poison.encode! |> JSON.decode!)
    end
  end

  #---------------------------------------TICKETS-----------------------------------------------------------------------

  def update_ticket(conn, %{"ticket" => %{"id" => id}, "params" => params} = paramz) do

    route = BusTerminalSystem.TravelRoutes.find_by([start_route: params["start_route"], end_route: params["end_route"]])
    params = Map.put(params, "route", route.id)
    BusTerminalSystem.TicketManagement.Ticket.find(id) |> case do
      nil -> json(conn,  %{status: "FAILED", response: %{}})
      ticket ->
        {_, ticket} = BusTerminalSystem.TicketManagement.Ticket.update(ticket, params)
        spawn(fn ->
          if ticket.activation_status == "TRANSFER" do
            bus = BusTerminalSystem.BusManagement.Bus.find(ticket.bus_no)
            message = "Dear #{ticket.first_name} #{ticket.last_name}. Your Ticket has been transferred to #{params["start_route"]} - #{params["end_route"]}, Bus Operator: #{bus.company}, License Plate: #{bus.license_plate},\nThank you and have a great trip."
            BusTerminalSystem.Notification.Table.Sms.create!([recipient: ticket.mobile_number, message: message, sent: false])
          end
        end)

        json(conn,  %{status: "SUCCESS", response: ticket |> Poison.encode!})
    end
  end

  def cancel_ticket(conn, ticket_params) do
    BusTerminalSystem.TicketManagement.Ticket.find(ticket_params["ticket_id"]) |> case do
      nil -> json(conn,  %{status: "FAILED", response: %{}})
      ticket ->
        {_, ticket} = BusTerminalSystem.TicketManagement.Ticket.update(ticket, [activation_status: "CANCELED"])
        spawn(fn ->
          if ticket.activation_status == "CANCELED" do
            route = BusTerminalSystem.TravelRoutes.find(ticket.route)
            message = "Dear #{ticket.first_name} #{ticket.last_name}. Your Ticket from #{route.start_route} to #{route.end_route} ID: #{ticket.id} has been canceled"
            BusTerminalSystem.Notification.Table.Sms.create!([recipient: ticket.mobile_number, message: message, sent: false])
          end
        end)
        spawn(fn ->
          BusTerminalSystem.APIRequestMockup.send_disable(ticket.id |> to_string |> String.pad_leading(4,"0")) |> IO.inspect(lable: "TICKET CANCEL RESPONSE")
        end)
        json(conn,  %{status: "SUCCESS", response: ticket |> Poison.encode!})
    end

  end

  def create_virtual_luggage_ticket(conn, ticket_params) do
    route = BusTerminalSystem.TravelRoutes.find_by([start_route: Map.fetch!(ticket_params, "source"), end_route: Map.fetch!(ticket_params, "destination")])
    route_info = "OPERATOR: PowerTools	 START: #{Map.fetch!(ticket_params, "source")}	 END: #{Map.fetch!(ticket_params, "destination")}	 DEPARTURE: 09:00	 PRICE: K300	 GATE: slot_two"
    ticket_params = Map.put(ticket_params, "route", route.id)
    ticket_params = Map.put(ticket_params, "class", "LUGGAGE")
    ticket_params = Map.put(ticket_params, "bus_no", "0")
    ticket_params = Map.put(ticket_params, "activation_status", "VALID")
    ticket_params = Map.put(ticket_params, "route_information", route_info)

    case TicketManagement.create_virtual_ticket(ticket_params) do
      {:ok, ticket} ->

        conn
        |> json(ticket)

      {:error, %Ecto.Changeset{} = changeset} ->

        conn
        |> json(%{})
    end
  end


#  ---------------------- Discounts ------------------------------------

  def discount_operator(conn, %{ "id" => operator_id } = params) do
    json(conn, BusTerminalSystem.AccountManager.User.find(operator_id) |> Poison.encode!())
  end

  def enable_discount(conn, %{ "id" => operator_id, "status" => discount_status } = params) do
    operator = BusTerminalSystem.AccountManager.User.find(operator_id)
    status = (fn state, saved_state -> if state == "true", do: false, else: true end)
    operator |> BusTerminalSystem.AccountManager.User.update([apply_discount: status.(discount_status, operator.apply_discount)])
    |> case do
         {_, operator} -> json(conn, operator |> Poison.encode!())
    end
  end

  def set_discount(conn, %{ "id" => operator_id, "discount" => discount_value, "discount_reason" => discount_reason } = params) do
    bus_operator = BusTerminalSystem.AccountManager.User.find(operator_id)
    reason = (fn d_reason, op -> if d_reason == "", do: op.discount_reason, else: d_reason end)
     BusTerminalSystem.AccountManager.User.update(bus_operator, [discount_amount: discount_value, discount_reason: reason.(discount_reason, bus_operator)])
    |> case do
         {_, operator} -> json(conn, operator |> Poison.encode!())
       end

  end

  def minimum_route_price(conn, _params) do
    json(conn, %{threshold: BusTerminalSystem.TravelRoutes.min(:route_fare)})
  end

  def add_beneficiary(conn, params) do
    conn |> json(add_to_list( params["member_id"], params["beneficiary"]))
  end

  def list_beneficiaries(conn, params) do
    conn |> json(Cachex.get!(:tmp, params["member_id"]) || [])
  end

  def clear_beneficiaries(conn, params) do
    Cachex.put!(:tmp, params["member_id"], [])
    conn |> json(Cachex.get!(:tmp, params["member_id"]) || [])
  end

  def add_to_list(id, map) do
    Cachex.put(:tmp, id, ((Cachex.get!(:tmp, id) || []) ++ [map]))
    Cachex.put(:tmp, id, (Cachex.get!(:tmp, id) |> Enum.uniq()))
    Cachex.expire(:my_cache, "key", :timer.seconds(120))
    Cachex.get!(:tmp, id)
  end

  def funds_transfer(conn, params) do
    response = BusTerminalSystem.Service.Zicb.Funding.wallet_query_by_account_number(%{:account_number => params["account"]}) |> BusTerminalSystem.Service.Zicb.Funding.wallet_transact
    IO.inspect params
    [account] = response["response"]["accountList"]
    transfer_request = %{
      destination_account: account["accountno"],
      destination_branch: account["brnCode"],
      amount: params["amount"],
      remarks: "Bus Operator Funds Sweep to account #{params["account"]}",
      reference_number: Timex.now |> Timex.to_unix |> to_string
    }
    response = BusTerminalSystem.Service.Zicb.Funding.wallet_funds_deposit(transfer_request) |> BusTerminalSystem.Service.Zicb.Funding.wallet_transact


    spawn(fn ->
      if response["tekHeader"]["status"] == "SUCCESS" do
        user = User.find_by(account_number: params["account"])
        message = "Dear #{user.first_name} #{user.last_name} you account #{params["account"]} has been credited K#{params["amount"]} sweep transfer"
        BusTerminalSystem.Notification.Table.Sms.create!([recipient: user.mobile, message: message, sent: false])
      end
    end)

    conn |> json(response["response"])
  end

  def form_validation_api(conn, params) do
    query = "SELECT * FROM #{params["table"]} WHERE #{params["column"]}='#{params["value"]}'"
    {status, result} = BusTerminalSystem.Repo.query(query)

    conn |> json(%{exist: result.num_rows})
  end

  def test_query() do
    query = "select concat(c.first_name, ' ',c.last_name)teller ,a.* from  probase_tbl_bank_transactions a ,probase_tbl_tickets b,probase_tbl_users c
       where  a.request_reference=b.reference_number

         and c.id=b.maker
      union
       select 'INTERNAL' teller ,a.* from  probase_tbl_bank_transactions a
      where a.request_reference='NOT USED';"
#    query = "select concat(c.first_name, ' ',c.last_name)teller ,a.* from  probase_tbl_bank_transactions a ,probase_tbl_tickets b,probase_tbl_users c\n where a.request_reference=b.reference_number and c.id=b.maker;"
#    {status, result} = BusTerminalSystem.Repo.query(query)
    BusTerminalSystem.Repo.query(query)
#    Enum.map(result.rows, fn row ->
#
#    end)
  end

  def bank_list(conn, _params) do
    conn |> json(BusTerminalSystem.Banks.all |> Enum.map(fn bank -> bank.bankName end))
  end

  def branch_list(conn, params) do
    conn |> json(BusTerminalSystem.Banks.where([bankName: params["bank"]]) |> Enum.map(fn bank -> bank.branchDesc end))
  end

  def get_bank(conn, params) do
    conn |> json(BusTerminalSystem.Banks.find_by([bankName: params["bank"], branchDesc: params["branch"]]) |> Poison.encode!())
  end

  def change_user_password(conn, params) do

    user = User.find_by(username: params["username"])
    if user.password != Base.encode16(:crypto.hash(:sha512, params["password"])) do
      conn |> json(%{status: 1, message: "Current Password Does not match"})
    else
      user |> BusTerminalSystem.AccountManager.User.update([password: Base.encode16(:crypto.hash(:sha512, params["new_password"]))])
      |> case do
           {:ok, user} -> conn |> json(%{status: 0, message: "Password Updated Successfully"})
           {:error, error} ->

             conn |> json(%{status: 1, message: "Password Update Failed"})
         end
       end
  end

  def get_permissions(conn, params) do
    permissions = BusTerminalSystem.UserRoles.find_by(id: params["role"]).permissions
    |> Poison.decode!()
    |> Enum.map(fn permission ->
      BusTerminalSystem.Permissions.find_by(code: permission |> to_string ).name
    end)
    conn |> json(permissions)
  end

  def delete_bus(conn,  %{"payload" => payload } = params) do
    bus_uid  = payload["bus_uid"]
    bus = BusTerminalSystem.BusManagement.Bus.find_by([uid: bus_uid])
    BusTerminalSystem.BusManagement.Bus.delete(bus)
    |> case do
         {:ok, _} -> conn |> json(%{status: 0, message: "Bus deleted successfully"})
         {:error, _} -> conn |> json(%{status: 1, message: "Failed to delete bus"})
     end
  end

  def update_balances(conn \\ %{}, _params \\ %{}) do
    BusTerminalSystem.Service.Zicb.AccountOpening.update_accounts() |> IO.inspect
    |> case do
         :ok ->
           conn |> json(%{
            status: 0,
            message: "Account balance Updated Successfully."
           })
         _ ->
           conn |> json(%{
             status: 1,
             message: "Failed to update account balance."
           })
       end
  end

end