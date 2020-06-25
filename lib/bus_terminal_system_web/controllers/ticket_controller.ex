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

  def index(conn, _params) do
    tickets = TicketManagement.list_tickets()
    render(conn, "index.html", tickets: tickets)
  end

  def new(conn, _params) do
    changeset = TicketManagement.change_ticket(%Ticket{})
    render(conn, "new.html", changeset: changeset)
  end

  def create(conn, %{"payload" => ticket_params}) do

    users = AccountManager.list_users()
    tickets = RepoManager.list_tickets()

    ticket_params = Map.put(ticket_params, "route", 1)
    ticket_params = Map.put(ticket_params, "class", "TICKET")
    ticket_params = Map.put(ticket_params, "route_information", ticket_params["route_information"]) #route_information

    IO.inspect(ticket_params)

    case TicketManagement.create_ticket(ticket_params) do
      {:ok, ticket} ->

        sms_message = "Hello #{ticket.first_name} #{ticket.last_name}, \n Ticket Purchase was successful \n TICKET ID: #{ticket.id}"
        spawn(fn ->
          NapsaSmsGetway.send_sms(ticket.mobile_number,sms_message)
        end)

        conn
        |> put_flash(:info, "Ticket created successfully.")
        |> redirect(to: Routes.user_path(conn, :index))

      {:error, %Ecto.Changeset{} = changeset} ->

      IO.inspect changeset

      conn
      |> redirect(to: Routes.user_path(conn, :index))
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
                    serial_number = Randomizer.randomizer(7, :numeric)
                    map = Map.put(payload, "reference_number", generate_reference_number(route))
                    map = Map.put(map, "serial_number", serial_number)
                    map = Map.put(map, "activation_status", "VALID")
                    map = Map.put(map, "route", route.id)

                    schedule = BusTerminalSystem.TblEdReservations.find_by(id: Map.fetch!(map, "bus_schedule_id"))
                    bus = BusTerminalSystem.BusManagement.Bus.find_by(id: schedule.bus_id)
                    operator = BusTerminalSystem.AccountManager.User.find_by(id: bus.operator_id)

                    IO.inspect bus

                    map = Map.put(map, "bus_no", bus.id |> to_string)
                    r_info = "OPERATOR: #{operator.company |> String.replace(" ","_")}: START: #{route.start_route} END: #{route.end_route}	 DEPARTURE: #{schedule.time} PRICE: K#{route.route_fare} GATE: #{schedule.slot}"

                    map = Map.put(map, "route_information", r_info)

                    #serial_number = Integer.to_string(serial_number)
                    IO.inspect(serial_number)
                    APIRequestMockup.send(serial_number)

                    conn
                    |> db_insert_ticket(route,_reference ,map)
                end

            end
          end
    end
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

        sms_message = "Hello #{ticket.first_name} #{ticket.last_name}, \n Ticket Purchase was successful \n TICKET ID: #{ticket.id}"
        NapsaSmsGetway.send_sms(ticket.mobile_number,sms_message)

        #spawn(fn ->

        #end)

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
    {:ok,agent,schedules} = RepoManager.route_mapping_by_location(date, start_route,end_route)
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

  def get_travel_routes(conn,_params) do
    json(conn, RepoManager.list_routes_json())
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

end
