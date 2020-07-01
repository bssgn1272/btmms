defmodule BusTerminalSystemWeb.FrontendApiController do
  use BusTerminalSystemWeb, :controller

  alias BusTerminalSystem.RepoManager
  alias BusTerminalSystem.ApiManager
  alias BusTerminalSystem.ScaleQuery
  alias BusTerminalSystem.TicketManagement
  alias BusTerminalSystem.NapsaSmsGetway
  alias BusTerminalSystem.TicketManagement.Ticket

  #---------------------------------------USER--------------------------------------------------------------------------

  def list_bus_operators(conn, _params) do
    operators = RepoManager.list_bus_operators()
    conn
    |> json(operators)
  end

  def query_user_by_id(conn, params) do

    user_id = params["user_id"]
    case user_id do
      nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
      _ ->
        case user_id |> RepoManager.find_user_by_id do
          nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
          user ->

            IO.inspect user

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
                "account_status" => user.account_status,
                "uuid" => user.uuid,
                "operator_role" => user.operator_role
              }))
          _value ->
            IO.inspect _value
            json conn, ["hello"]
        end
    end
  end

  def query_user(conn, params) do

    user_id = params["payload"]["user_id"]
    case user_id do
      nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
      _ ->
        case user_id |> RepoManager.find_user_by_id do
          nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
          user ->

            IO.inspect user

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
                "account_status" => user.account_status,
                "uuid" => user.uuid,
                "operator_role" => user.operator_role
              }))
          _value ->
            IO.inspect _value
            json conn, ["hello"]
        end
    end
  end

  def update_user(conn, %{"payload" => payload } = params) do

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
                      "account_status" => user.account_status
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
                "license_plate" => bus.liscense_plate,
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
                "fitness_license" => bus.fitness_liscence,
                "vehicle_capacity" => bus.vehicle_capacity
              }))
          _value ->
            IO.inspect _value
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
            IO.inspect _value
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
                      "license_plate" => bus.liscense_plate,
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
                      "fitness_license" => bus.fitness_liscence,
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

  #---------------------------------------Routes------------------------------------------------------------------------

  def list_travel_routes(conn, _params) do
    operators = RepoManager.list_bus_routes()
    conn
    |> json(operators)
  end

  def query_route(conn, params) do
    route_id = params["payload"]["route_id"]
    conn |> json(RepoManager.route_by_id_json(1))
  end

  def update_route_bus_route(conn, %{"payload" => %{ "route_id" => route_id } = payload} = params) do
    IO.inspect(params)
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
    IO.inspect(params)
    conn |> json(RepoManager.create_luggage(params))
  end

  def acquire_luggage(conn, %{"sender" => sender, "receiver" => receiver, "luggage_id" => luggage_id} = params)do

    IO.inspect(params)
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
    IO.inspect("------------------------------------UNATTENDED LUGGAGE CREATE ----------------------")

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
    travel_date = "#{schedule.time} #{day}-#{month}-#{year}"
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

    IO.inspect(ticket_update)

    spawn(fn ->
      %{
        "refNumber" => reference_number,
        "fName" => "recipient_firstname" |> from(luggage_params),
        "sName" => "recipient_lastname" |> from(luggage_params),
        "from" => start_route,
        "to" => end_route,
        "Price" => luggage_total_cost,
        "Bus" => operator.company,
        "gate" => schedule.slot,
        "depatureTime" => travel_date,
        "ticketNumber" => ticket.id,
        "items" => BusTerminalSystem.RepoManager.acquire_luggage(ticket.id)
      } |> BusTerminalSystem.PrinterTcpProtocol.print_local_connect
    end)

    IO.inspect("------------------------------------END UNATTENDED LUGGAGE CREATE ----------------------")

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
    IO.inspect(params)
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
        "stand" -> conn |> json(MarketRepo.market_shop_create(params) |> Poison.encode! |> JSON.decode!)
    end
  end

  #---------------------------------------TICKETS-----------------------------------------------------------------------

  def create_virtual_luggage_ticket(conn, ticket_params) do
    IO.inspect("---------------------------VIRTUAL------------------------------------------------------")
    route = BusTerminalSystem.TravelRoutes.find_by([start_route: Map.fetch!(ticket_params, "source"), end_route: Map.fetch!(ticket_params, "destination")])
    route_info = "OPERATOR: PowerTools	 START: #{Map.fetch!(ticket_params, "source")}	 END: #{Map.fetch!(ticket_params, "destination")}	 DEPARTURE: 09:00	 PRICE: K300	 GATE: slot_two"
    ticket_params = Map.put(ticket_params, "route", route.id)
    ticket_params = Map.put(ticket_params, "class", "LUGGAGE")
    ticket_params = Map.put(ticket_params, "bus_no", "0")
    ticket_params = Map.put(ticket_params, "activation_status", "VALID")
    ticket_params = Map.put(ticket_params, "route_information", route_info)

    IO.inspect(ticket_params)

    case TicketManagement.create_virtual_ticket(ticket_params) do
      {:ok, ticket} ->

        conn
        |> json(ticket)

      {:error, %Ecto.Changeset{} = changeset} ->

        IO.inspect changeset

        conn
        |> json(%{})
    end
  end

end