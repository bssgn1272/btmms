defmodule BusTerminalSystemWeb.TicketController do
  use BusTerminalSystemWeb, :controller

  alias BusTerminalSystem.TicketManagement
  alias BusTerminalSystem.TicketManagement.Ticket
  alias BusTerminalSystem.ApiManager
  alias BusTerminalSystem.RepoManager
  alias BusTerminalSystem.Randomizer
  alias BusTerminalSystem.AccountManager
  alias BusTerminalSystem.NapsaSmsGetway
  alias BusTerminalSystem.APIRequestMockup
  alias BusTerminalSystem.TravelRoutes

  def index(conn, _params) do
    tickets = TicketManagement.list_tickets()
    render(conn, "index.html", tickets: tickets)
  end

  def new(conn, _params) do
    changeset = TicketManagement.change_ticket(%Ticket{})
    render(conn, "new.html", changeset: changeset)
  end

  def create(conn, %{"payload" => ticket_params}) do

    [_, tBus, _, start_route, _, end_route, _, departure, _, price, _,slot, _, bus_schedule_id] = ticket_params["route_information"] |> String.split()

    bank_transaction = %{
      "srcAcc" => conn.assigns.user.account_number,
      "srcBranch" => conn.assigns.user.bank_srcBranch,
      "amount" => (price |> String.replace("K","")),
      "payDate" => "2019-08-16",
      "srcCurrency" => "ZMW",
      "remarks" => "TICKET PURCHASE",
      "referenceNo" => ticket_params["external_ref"],
      "transferRef" => ticket_params["external_ref"],
      "request_reference" => ticket_params["external_ref"],
      "service" => "TICKET_PURCHASE",
      "op_description" => "CLIENT TICKET PURCHASE",
    } |> BusTerminalSystem.Service.Zicb.Funding.withdraw()

    if bank_transaction.status != "SUCCESS" do
      conn
      |> put_flash(:info, "Failed To Purchase Ticket.")
      |> redirect(to: Routes.user_path(conn, :index))
    else
      users = AccountManager.list_users()
      tickets = RepoManager.list_tickets()

      ticket_params = Map.put(ticket_params, "class", "TICKET")
      ticket_params = Map.put(ticket_params, "route_information", ticket_params["route_information"]) #route_information


      route = BusTerminalSystem.TravelRoutes.find_by(start_route: start_route, end_route: end_route)

      ticket_params = Map.put(ticket_params, "amount", price |> String.replace("K",""))
      ticket_params = Map.put(ticket_params, "route", route.id)
      ticket_params = Map.put(ticket_params, "bus_schedule_id", bus_schedule_id)

      case TicketManagement.create_ticket(ticket_params) do
        {:ok, ticket} ->

          spawn(fn ->
            BusTerminalSystem.Notification.Table.Sms.create!([recipient: ticket.mobile_number, message: NapsaSmsGetway.send_ticket_sms(ticket), sent: false])
          end)

          conn
          |> put_flash(:info, "Ticket Purchased Successfully.")
          |> redirect(to: Routes.user_path(conn, :index))

        {:error, %Ecto.Changeset{} = changeset} ->

          conn
          |> put_flash(:info, "Failed To Purchase Ticket.")
          |> redirect(to: Routes.user_path(conn, :index))
      end
    end
  end

  def create_ticket_payload(conn, %{"payload" => ticket_params}) do


    case BusTerminalSystem.AccountManager.User.find(ticket_params["session_user_id"]) do
      nil -> conn |> json(%{"message" => "Failed", "status" => 400} )
      session_user ->

    [_, tBus, _, start_route, _, end_route, _, departure, _, price, _,slot, _, bus_schedule_id] = ticket_params["route_information"] |> String.split()

    ref = ticket_params["reference_number"]

    {cost_price, _} = price |> String.replace("K","") |> Float.parse

    IO.inspect cost_price
    IO.inspect session_user.bank_account_balance

    if cost_price > session_user.bank_account_balance do
      conn
      |> json(
           %{
             "ticket" => %{},
             "message" => "Transaction Failed, Insufficient Till balance",
             "status" => 400,
             "bank_account_balance" => session_user.bank_account_balance
           })
    else

    IO.inspect "PASSED"

      bank_transaction = %{
       "srcAcc" => session_user.account_number,
       "srcBranch" => session_user.bank_srcBranch,
       "amount" => (price |> String.replace("K","")),
       "payDate" => ticket_params["travel_date"],
       "srcCurrency" => "ZMW",
       "remarks" => ref,
       "referenceNo" => ref,
       "transferRef" => ref,
       "request_reference" => ref,
       "service" => "TICKET_PURCHASE",
       "op_description" => "CLIENT TICKET PURCHASE",
     } |> BusTerminalSystem.Service.Zicb.Funding.withdraw()
     |>IO.inspect(label: "************************************************************************")

      if bank_transaction.status != "SUCCESS" do
        conn
        |> json(%{"message" => bank_transaction.message,"status" => 400} )
      else
        #          BusTerminalSystem.Service.Zicb.AccountOpening.account_balance_inquiry(session_user.account_number)

        users = AccountManager.list_users()
        tickets = RepoManager.list_tickets()

        ticket_params = Map.put(ticket_params, "class", "TICKET")
        ticket_params = Map.put(ticket_params, "route_information", ticket_params["route_information"]) #route_information

        [_, tBus, _, start_route, _, end_route, _, departure, _, price, _,slot, _, bus_schedule_id] = ticket_params["route_information"] |> String.split()
        route = BusTerminalSystem.TravelRoutes.find_by(start_route: start_route, end_route: end_route)

        price = price |> String.replace("K","")

        ticket_params = Map.put(ticket_params, "amount", price)
        ticket_params = Map.put(ticket_params, "route", route.id)
        ticket_params = Map.put(ticket_params, "bus_schedule_id", bus_schedule_id)

        operator = BusTerminalSystem.BusManagement.Bus.find(ticket_params["bus_no"]).operator_id
                   |> BusTerminalSystem.AccountManager.User.find

        if operator.apply_discount == false do
          ticket_params = Map.put(ticket_params, "discount_applied", false)
          ticket_params = Map.put(ticket_params, "discount_amount", 0)
          ticket_purchase(conn, ticket_params, session_user)
        else
          operator.apply_discount |> case do
           nil ->
             ticket_params = Map.put(ticket_params, "discount_applied", true)
             ticket_params = Map.put(ticket_params, "discount_amount", 0.0)
             ticket_purchase(conn, ticket_params, session_user)
           false ->
             ticket_params = Map.put(ticket_params, "discount_applied", true)
             ticket_params = Map.put(ticket_params, "discount_amount", 0.0)
             ticket_purchase(conn, ticket_params, session_user)
           true ->

             discount_calculated = (fn original_amount, discount_amount ->
               parse_float(original_amount) - discount_amount
                                    end)

             ticket_params = Map.put(ticket_params, "amount", discount_calculated.(price, operator.discount_amount))
             ticket_params = Map.put(ticket_params, "discount_applied", true)
             ticket_params = Map.put(ticket_params, "discount_amount", operator.discount_amount)
             ticket_params = Map.put(ticket_params, "discount_original_amount", price)
             ticket_purchase(conn, ticket_params,session_user)
         end
        end
      end
    end
    end

  end

  defp parse_int(str) do
    {int, _} = Integer.parse(str)
    int
  end

  defp parse_float(str) do
    {flt, _} = Float.parse(str)
    flt
  end

  defp ticket_purchase(conn, ticket_params, session_user) do
    case TicketManagement.create_ticket(ticket_params) do
      {:ok, ticket} ->

        spawn(fn ->
          BusTerminalSystem.Notification.Table.Sms.create!([recipient: ticket.mobile_number, message: NapsaSmsGetway.send_ticket_sms(ticket), sent: false])
        end)

        conn
        |> json(
        %{
          "ticket" => ticket |> Poison.encode!(),
          "status" => 200,
          "bank_account_balance" => session_user.bank_account_balance
        })

      {:error, errors} ->

        conn
        |> json(
             %{
               "ticket" => %{},
                "message" => "Failed",
               "status" => 400,
               "bank_account_balance" => session_user.bank_account_balance
             })
    end
  end

  def show(conn, %{"id" => id}) do
    ticket = TicketManagement.get_ticket!(id)
    render(conn, "show.html", ticket: ticket)
  end

  def edit(conn, %{"id" => id}) do
    ticket = TicketManagement.get_ticket!(id)
    changeset = TicketManagement.change_ticket(ticket)
    render(conn, "edit.html", ticket: ticket, changeset: changeset)
  end

  def update(conn, %{"id" => id, "ticket" => ticket_params}) do
    ticket = TicketManagement.get_ticket!(id)

    case TicketManagement.update_ticket(ticket, ticket_params) do
      {:ok, ticket} ->
        conn
        |> put_flash(:info, "Ticket updated successfully.")
        |> redirect(to: Routes.ticket_path(conn, :show, ticket))

      {:error, %Ecto.Changeset{} = changeset} ->
        render(conn, "edit.html", ticket: ticket, changeset: changeset)
    end
  end

  def delete(conn, %{"id" => id}) do
    ticket = TicketManagement.get_ticket!(id)
    {:ok, _ticket} = TicketManagement.delete_ticket(ticket)

    conn
    |> put_flash(:info, "Ticket deleted successfully.")
    |> redirect(to: Routes.ticket_path(conn, :index))
  end

  ##----------------------APIs---------------------------------------

  def ticket_board_passenger(conn,%{ "ticket_id" => ticket_id } = params) do
    try do
      ticket = BusTerminalSystem.TicketManagement.Ticket.find_by(id: ticket_id)
      ticket |> BusTerminalSystem.TicketManagement.Ticket.update(activation_status: "BOARDED")
      conn |> json(%{"status" => "Ok"})
    rescue
      _ -> conn |> json(%{"status" => "Failed"})
   end


  end

  def find_ticket_external_ref(conn, params) do
    case ApiManager.authentication_mod(params) do
      {:error, result} -> { json(conn, result)}

      {:ok, result} ->
        {:ok, payload} = Map.fetch(params,"payload")
        if !Map.has_key?(payload,"external_ref") do
          json(conn, ApiManager.api_error_handler(ApiManager.definition_query,ApiManager.support_query))
        else
          {:ok, external_ref} = Map.fetch(payload,"external_ref")
          case BusTerminalSystem.TicketManagement.Ticket.find_by(external_ref: external_ref) do
            nil -> conn |> json(ApiManager.api_error_handler(ApiManager.definition_query,%{"response" => "ticket not found"}))
            ticket ->
              conn
              |> json(ApiManager.api_message_custom_handler(ApiManager.definition_query,"SUCCESS",0,
                %{
                  "activation_status" => ticket.activation_status,
                  "reference_number" => ticket.reference_number,
                  "serial_number" => ticket.serial_number,
                  "external_ref" => ticket.external_ref,
                  "first_name" => ticket.first_name,
                  "last_name" => ticket.last_name,
                  "other_name" => ticket.other_name,
                  "id_type" => ticket.id_type,
                  "id_number" => ticket.passenger_id,
                  "mobile_number" => ticket.mobile_number,
                  "email_address" => ticket.email_address,
                  "transaction_channel" => ticket.transaction_channel,
                  "travel_date" => ticket.travel_date,
                  "qr_code" => qr_generator("#{ticket.reference_number}")
                }))
          end
        end


    end

    json conn, []
  end

  def find_ticket(conn, params) do
    case ApiManager.authentication_mod(params) do
      {:error, result} -> { json(conn, result)}

      {:ok, result} ->
        {:ok, payload} = Map.fetch(params,"payload")
        if !Map.has_key?(payload,"ticket_id") do
          json(conn, ApiManager.api_error_handler(ApiManager.definition_query,ApiManager.support_query))
        else
          {:ok, ticket_id} = Map.fetch(payload,"ticket_id")
          case RepoManager.get_ticket(ticket_id) do
            nil -> ""
            ticket ->
            conn
            |> json(ApiManager.api_message_custom_handler(ApiManager.definition_query,"SUCCESS",0,
              %{
                "activation_status" => ticket.activation_status,
                "reference_number" => ticket.reference_number,
                "serial_number" => ticket.serial_number,
                "external_ref" => ticket.external_ref,
                "first_name" => ticket.first_name,
                "last_name" => ticket.last_name,
                "other_name" => ticket.other_name,
                "id_type" => ticket.id_type,
                "id_number" => ticket.passenger_id,
                "mobile_number" => ticket.mobile_number,
                "email_address" => ticket.email_address,
                "transaction_channel" => ticket.transaction_channel,
                "travel_date" => ticket.travel_date,
                "qr_code" => qr_generator("#{ticket.reference_number}")
              }))
          end
        end


    end

    json conn, []
  end

  def find_ticket_serial(conn, params) do
    case ApiManager.authentication_mod(params) do
      {:error, result} -> { json(conn, result)}

      {:ok, result} ->
        {:ok, payload} = Map.fetch(params,"payload")
        if !Map.has_key?(payload,"serial_number") do
          json(conn, ApiManager.api_error_handler(ApiManager.definition_query,ApiManager.support_query))
        else
          {:ok, ticket_id} = Map.fetch(payload,"serial_number")
          case RepoManager.get_ticket_serial(ticket_id) do
            nil -> ""
            ticket ->
              conn
              |> json(ApiManager.api_message_custom_handler(ApiManager.definition_query,"SUCCESS",0,
                %{
                  "activation_status" => ticket.activation_status,
                  "reference_number" => ticket.reference_number,
                  "serial_number" => ticket.serial_number,
                  "external_ref" => ticket.external_ref,
                  "first_name" => ticket.first_name,
                  "last_name" => ticket.last_name,
                  "other_name" => ticket.other_name,
                  "id_type" => ticket.id_type,
                  "id_number" => ticket.passenger_id,
                  "mobile_number" => ticket.mobile_number,
                  "email_address" => ticket.email_address,
                  "ticket_id" => ticket.id,
                  "transaction_channel" => ticket.transaction_channel,
                  "travel_date" => ticket.travel_date,
                  "qr_code" => qr_generator("#{ticket.reference_number}")
                }))
          end
        end


    end

    json conn, []
  end

  def purchase_ticket(conn,params) do
    case ApiManager.authentication_mod(params) do
      {:error, result} -> { json(conn, result)}

      {:ok, _result} ->
          {:ok, payload} = Map.fetch(params,"payload")
            IO.inspect payload
            if !Map.has_key?(payload,"external_ref") or !Map.has_key?(payload,"route_code") or !Map.has_key?(payload,"bus_schedule_id") do
                json(conn, ApiManager.api_error_handler(ApiManager.definition_purchase, ApiManager.support_purchase))
            else

            case validate_route(conn,payload) do
              {:error, _payload} ->
                conn
                |> json(ApiManager.api_error_handler(ApiManager.definition_purchase,"INVALID ROUTE CODE"))
              {:ok, route} ->
                {:ok, ext_reference} = Map.fetch(payload,"external_ref")
                case validate_ext_reference(ext_reference) do
                  {:error, _reference} ->
                    conn
                    |> json(ApiManager.api_error_handler(ApiManager.definition_purchase,"Duplicate External Reference"))

                  {:ok, _reference} ->
                    auth = "auth" |> from(params)
                    teller_username = "username" |> from(auth)
                    teller = BusTerminalSystem.AccountManager.User.find_by(username: teller_username)
                    serial_number = Randomizer.randomizer(7, :numeric)

                    IO.inspect("--------------------------")
                    IO.inspect(payload)

                    map = Map.put(payload, "reference_number", generate_reference_number(route))
#                    map = Map.put(map, "date", payload["date"])
                    map = Map.put(map, "serial_number", serial_number)
                    map = Map.put(map, "activation_status", "VALID")
                    map = Map.put(map, "route", route.id)
                    map = Map.put(map, "amount", route.route_fare)
                    map = Map.put(map, "maker", teller.id |> to_string)

                    schedule = BusTerminalSystem.TblEdReservations.find_by(id: Map.fetch!(map, "bus_schedule_id"))
                    bus = BusTerminalSystem.BusManagement.Bus.find_by(id: schedule.bus_id)
                    operator = BusTerminalSystem.AccountManager.User.find_by(id: bus.operator_id)


                    map = Map.put(map, "bus_no", bus.id |> to_string)
                    r_info = "OPERATOR: #{operator.company |> String.replace(" ","_")}: START: #{route.start_route} END: #{route.end_route}	 DEPARTURE: #{schedule.time} PRICE: K#{route.route_fare} GATE: #{schedule.slot}"

                    map = Map.put(map, "route_information", r_info)

                    #serial_number = Integer.to_string(serial_number)
                    IO.inspect(serial_number)
                    spawn(fn ->
                      APIRequestMockup.send(serial_number)
                    end)


                    conn
                    |> db_insert_ticket(route,_reference ,map)
                end

            end
          end
    end
  end

  def from(key, map) do
    Map.fetch!(map,key)
  end

  defp fetch(map,value) do
    result = Map.fetch(map,value)
    case result do
      {:ok, data} ->
        data
      :error ->
        value
    end
  end

  defp db_insert_ticket(conn,route ,reference, params \\ %{}) do

    case RepoManager.create_ticket(params) do
      {:ok, ticket} ->

      spawn(fn ->
        BusTerminalSystem.Notification.Table.Sms.create!([recipient: ticket.mobile_number, message: NapsaSmsGetway.send_ticket_sms(ticket), sent: false])
      end)

        conn
        |> json(ApiManager.api_message_custom_handler(
          "PURCHASE",
          "SUCCESS",
          0,
          %{
            "route_information" => ticket.route_information,
            "activation_status" => ticket.activation_status,
            "ticket_id" => ticket.id,
            "reference_number" => ticket.reference_number,
            "serial_number" => ticket.serial_number,
            "external_reference" => ticket.external_ref,
            "first_name" => ticket.first_name,
            "last_name" => ticket.last_name,
            "other_name" => ticket.other_name,
            "email" => ticket.email_address,
            "id_type" => ticket.id_type,
            "passenger_id" => ticket.passenger_id,
            "travel_date" => ticket.travel_date,
            "mobile_number" => ticket.mobile_number,
            "start_route" => route.start_route,
            "end_route" => route.end_route,
            "route_code" => route.route_code,
            "bus_schedule_id" => ticket.bus_schedule_id,
            "currency" => "ZMW",
            "qr_code" => qr_generator("#{ticket.serial_number}")
          }))

        {:error, %Ecto.Changeset{} = _changeset} ->
          conn
          |> json(ApiManager.api_error_handler(ApiManager.definition_purchase,ApiManager.translate_error(_changeset)))
    end
  end

  def generate_reference_number(route) do
    dt = DateTime.utc_now
    {micro,_} = dt.microsecond
    "ZBMS-#{dt.year}#{dt.month}#{dt.day}-#{dt.hour}#{dt.minute}#{dt.second}#{micro}"
  end

  def generate_reference_number do
    dt = DateTime.utc_now
    {micro,_} = dt.microsecond
    "ZBMS-#{dt.year}#{dt.month}#{dt.day}-LSTL-#{dt.hour}#{dt.minute}#{dt.second}#{micro}"
  end

  def qr_generator(data) do
    try do
      data |> EQRCode.encode |> EQRCode.png() |> Base.encode64
    rescue
      _ -> ""
     end
  end

  def bar_code_generator(data) do
    Barlix.Code128.encode!(data) |> Barlix.PNG.print() |> Base.encode64
  end

  def validate_ext_reference(reference) do
    ticket = RepoManager.get_ticket_by_external_reference(reference)
    IO.inspect("TICKET")
    IO.inspect(ticket)
    case ticket do
      nil -> {:ok, reference}
      ticket -> {:error, ticket.external_ref}
    end
  end

  defp validate_route(conn, payload) do
    _route_code = "route_code"
    if !Map.has_key?(payload, _route_code) do
      {:error, payload}
    else
      {:ok, route_code} = Map.fetch(payload,_route_code)
      route = RepoManager.get_route_by_route_code(route_code)
      case route do
        nil -> { :error, route_code }
        route -> { :ok, route }
      end
    end
  end

  def get_schedules(conn,_params) do
    {:ok,agent,schedules} = RepoManager.route_mapping()
    Agent.stop(agent)
    json(conn, schedules)
  end

  def get_schedules_internal(conn, %{"payload" => %{ "date" => date, "time" => time}} = params) do
    IO.inspect params
    case RepoManager.route_mapping(date, time) do
      {:ok,agent,schedules} ->
        Agent.stop(agent)
        json(conn, schedules)

      _ ->
        json(conn, [])
    end

  end

  def get_schedules_buses(conn, %{"payload" => %{ "date" => date, "start_route" => start_route, "end_route" => end_route}} = params) do
    {:ok,agent,schedules} = RepoManager.route_mapping_by_location_internal(date, start_route,end_route)
    Agent.stop(agent)
    json(conn, schedules)
  end

  def get_schedules_buses_internal(conn, %{"payload" => %{ "date" => date, "start_route" => start_route, "end_route" => end_route}} = params) do
    {:ok,agent,schedules} = RepoManager.route_mapping_by_location_internal(date, start_route,end_route)
    Agent.stop(agent)
    json(conn, schedules)
  end

  def get_schedules_buses_by_date(conn, %{"payload" => %{ "start_date" => start_date, "end_date" => end_date, "start_route" => start_route, "end_route" => end_route}} = params) do
    IO.inspect params
    {:ok,agent,schedules} = RepoManager.route_mapping_by_date(Date.from_iso8601!(start_date),Date.from_iso8601!(end_date), start_route,end_route)
    Agent.stop(agent)
    json(conn, schedules)
  end

  def get_schedules_by_location(conn,%{"payload" => %{ "date" => date, "start_route" => start_route, "end_route" => end_route}} = params) do
    IO.inspect params
    {:ok,agent,schedules} = RepoManager.route_mapping_by_location(date, start_route,end_route)
    Agent.stop(agent)
    json(conn,schedules)
  end

  @validation_param %{ "auth" => %{ "username" => :string, "service_token" => :string }, "payload" => %{ "route_code" => :string }}
  def get_travel_routes(conn, params) do
    IO.inspect(params)
    if Enum.empty?(params) == true do
      json(conn, RepoManager.list_routes_json())
    else
      Skooma.valid?(params,@validation_param)
      |> case do
        :ok ->

          route = fn route_code ->

            route = BusTerminalSystem.TravelRoutes.find_by(route_code: route_code) |> Poison.encode!() |> Poison.decode!()

            if route == nil do
                %{status: "FAILED", message: "ROUTE NOT FOUND"}
             else
              sub_routes = BusTerminalSystem.RepoManager.stops(route["id"], []) |> Enum.sort_by( fn(r) -> r["order"] end)
              %{ status: "SUCCESS", travel_route: route |> Map.put("sub_routes", sub_routes)}
              end
          end

          json(conn, route.(params["payload"]["route_code"]))
        {:error, message} ->
          error = %{
            :status => "FAILED",
            :error => message,
            :request_structure_required => %{
              :auth => %{
                :username => "<USER_NAME>",
                :service_key => "<SERVICE_KEY>"
              },
              :payload => %{
                :route_code => "<ROUTE_CODE>"
              }
            }
          }
          json(conn, error)
      end

    end

  end

  def get_luggage_weight(conn,_params) do
    json conn, %{"weight" => BusTerminalSystem.BinaryConverter.usb}
  end

  def list_tickets(conn,_params) do
    json(conn, RepoManager.list_tickets_json())
  end

  def find_ticket_internal(conn, params) do
    {:ok, payload} = Map.fetch(params,"payload")
    if !Map.has_key?(payload,"ticket_id") do
      json(conn, ApiManager.api_error_handler(ApiManager.definition_query,ApiManager.support_query))
    else
      {:ok, ticket_id} = Map.fetch(payload,"ticket_id")
      case RepoManager.get_ticket(ticket_id) do
        nil -> json(conn,[])
        ticket ->

        conn
        |> json(ApiManager.api_message_custom_handler(ApiManager.definition_query,"SUCCESS",0,
          %{
            "activation_status" => ticket.activation_status,
            "ticket_id" => ticket.id,
            "reference_number" => ticket.reference_number,
            "serial_number" => ticket.serial_number,
            "external_ref" => ticket.external_ref,
            "first_name" => ticket.first_name,
            "last_name" => ticket.last_name,
            "other_name" => ticket.other_name,
            "id_type" => ticket.id_type,
            "id_number" => ticket.passenger_id,
            "mobile_number" => ticket.mobile_number,
            "email_address" => ticket.email_address,
            "transaction_channel" => ticket.transaction_channel,
            "travel_date" => ticket.travel_date,
            "qr_code" => qr_generator("#{ticket.reference_number}")
          }))
          _ -> json(conn,[])
      end
    end
  end

  @ledger_params %{ "payload" => %{"account" => :string, "amount" => :int, "transaction_code" => :string, "date" => :string} }
  def transaction_post_to_ledger(conn, params) do
    ApiManager.authentication_mod(params) |> case do
       {:error, result} -> { json(conn, result)}
       {:ok, result} ->
          Skooma.valid?(params,@ledger_params) |> case do
             {:error, message} ->
               conn |> json(ApiManager.api_error_handler(ApiManager.definition_transactions(),message))
             :ok ->

               #"01-06-2020" |> Timex.parse!("{D}-{0M}-{YYYY}") |> Timex.to_date

               conn |> json(ApiManager.api_message_custom_handler(ApiManager.definition_transactions(),"SUCCESS",0, params))
           end
    end
  end

  def add_luggage(conn, _params) do

  end

  def cancel_trip(conn, %{"bus_no" => bus_no, "route_code" => route_id, "schedule_id" => bus_schedule_id} = params) do

#    %{"bus_no" => bus_no, "route_code" => route_code, "schedule_id" => schedule_id} = params
#    BusTerminalSystem.TicketManagement.Ticket.find_by([bus_no: bus_no, bus_schedule_id: schedule_id])

    route_code = (fn r ->
      case TravelRoutes.find_by(route_code: r) do
        nil -> 0
        route -> route.id
      end
    end)

    tickets = Ticket.where([bus_no: bus_no, bus_schedule_id: bus_schedule_id, route: route_code.(route_id)])

    if Enum.count(tickets) < 1 do
      conn |> json(%{
        "tickets_canceled" => Enum.count(tickets),
        "status" => 1,
        "message" => "No Tickets Found",
      })
    else

      tickets |> Task.async_stream(
                   fn ticket ->
                     if ticket.activation_status != "CANCELED" do
                       BusTerminalSystem.TicketManagement.Ticket.update(ticket, [activation_status: "CANCELED"])
                     end
                   end, max_concurrency: 20, timeout: 50_000, on_timeout: :kill_task)
      |> Stream.run()
      |> case do
           :ok ->
             conn |> json(%{
               "tickets_canceled" => Enum.count(tickets),
               "status" => 0,
               "message" => "Tickets Successfully canceled",
             })
           _ ->
             conn |> json(%{
               "tickets_canceled" => Enum.count(tickets),
               "status" => 1,
               "message" => "Failed to Cancel Tickets",
             })
         end

    end
  end

end
