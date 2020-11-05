defmodule BusTerminalSystem.RepoManager do

  import Ecto.Query, warn: false

  alias BusTerminalSystem.Repo
  alias BusTerminalSystem.Randomizer

  alias BusTerminalSystem.Market.Market
  alias BusTerminalSystem.Market.Section
  alias BusTerminalSystem.Market.Shop, as: Hub
  alias BusTerminalSystem.Tenant

  alias BusTerminalSystem.Terminus
  alias BusTerminalSystem.Station
  alias BusTerminalSystem.Gate
  alias BusTerminalSystem.TravelRoutes
  alias BusTerminalSystem.RouteMapping

  alias BusTerminalSystem.TicketManagement.Ticket
  alias BusTerminalSystem.BusManagement.Bus
  alias BusTerminalSystem.AccountManager.User
  alias BusTerminalSystem.Luggage
  alias BusTerminalSystem.LuggageTarrif

  alias BusTerminalSystem.Utility
  alias BusTerminalSystem.NapsaSmsGetway
  alias BusTerminalSystem.TblEdReservations
  alias BusTerminalSystem.TransactionCodes
  alias BusTerminalSystem.Transactions

  #--------------------------Luggage---------------------------------------------------------------

  def acquire_luggage(ticket_id) do

#    {:ok, agent} = Agent.start_link fn  -> [] end
#
#    BusTerminalSystem.Luggage.where(ticket_id: ticket_id)
#    |> Enum.with_index()
#    |> Enum.each(fn {e, index} ->
#      IO.inspect(e)
#      Agent.update(agent, fn list -> ["#{Map.fetch!(e, :description)}:#{Map.fetch!(e, :cost)}" | list ] end)
#    end)
#
#    luggage_data = Agent.get(agent, fn list -> list end)
#    Agent.stop(agent)
#    luggage_data

    ticket = BusTerminalSystem.TicketManagement.Ticket.find(ticket_id)
    sum_luggage = BusTerminalSystem.Luggage.sum(:cost,ticket_id: ticket_id)
    if ticket.class == "TICKET" do
      if sum_luggage == nil do
        ticket |> BusTerminalSystem.TicketManagement.Ticket.update(has_luggage: false)
        ["     Ticket                    :#{ticket.amount}"]
      else
        ticket |> BusTerminalSystem.TicketManagement.Ticket.update([has_luggage: true, luggage_total: sum_luggage])
        ["     Luggage                   :#{sum_luggage |> Float.round(2)}",
          "     Ticket                    :#{ticket.amount}"]
      end
    else
      ticket |> BusTerminalSystem.TicketManagement.Ticket.update([has_luggage: true, luggage_total: sum_luggage])
      ["     Luggage                   :#{sum_luggage |> Float.round(2)}"]
    end

  end

  def checkin(id, ip) do
    ticket = Ticket.find(id)

    [y,m,d] = "2020-06-30" |> String.split("-")

     ticket.class |> case do
        "TICKET" ->

#          try do
            IO.inspect("--------------------------------******---------------------------------------------")
            IO.inspect(ticket.route_information)

            ticket = BusTerminalSystem.TicketManagement.Ticket.find(ticket.id)
            sum_luggage = BusTerminalSystem.Luggage.sum(:cost,ticket_id: ticket.id)

            if sum_luggage != nil do

              [_, tBus, _, start_route, _, end_route, _, departure, _, price, _,slot | data] = ticket.route_information |> String.split()
              departure = "#{d}/#{m}/#{y} #{departure}"
              printer_payload =
                %{
                  "refNumber" => ticket.reference_number,
                  "fName" => ticket.first_name,
                  "sName" => ticket.last_name,
                  "from" => start_route,
                  "to" => end_route,
                  "Price" => ticket.amount + sum_luggage,
                  "Bus" => tBus,
                  "gate" => BusTerminalSystem.TblSlotMappings.find_by(slot: slot).gate,
                  "depatureTime" => departure,
                  "ticketNumber" => ticket.id,
                  "items" => acquire_luggage(ticket.id)
                }

              ticket |> BusTerminalSystem.TicketManagement.Ticket.update(has_luggage: false)

              spawn(fn ->
                IO.inspect("-----------------------------START TICKET PRINTING PAYLOAD--------------------------------")
                IO.inspect(printer_payload)
                BusTerminalSystem.PrinterTcpProtocol.print_remote_cross_connect(printer_payload, ip)
                IO.inspect("-----------------------------END TICKET PRINTING PAYLOAD----------------------------------")
              end)

              spawn(fn ->
                BusTerminalSystem.APIRequestMockup.send(ticket.id |> to_string)
              end)
            else

              [_, tBus, _, start_route, _, end_route, _, departure, _, price, _,slot | data] = ticket.route_information |> String.split()
              printer_payload =
                %{
                  "refNumber" => ticket.reference_number,
                  "fName" => ticket.first_name,
                  "sName" => ticket.last_name,
                  "from" => start_route,
                  "to" => end_route,
                  "Price" => ticket.amount,
                  "Bus" => tBus,
                  "gate" => BusTerminalSystem.TblSlotMappings.find_by(slot: slot).gate,
                  "depatureTime" => departure,
                  "ticketNumber" => ticket.id,
                  "items" => acquire_luggage(ticket.id)
                }

                spawn(fn ->
                  IO.inspect("-----------------------------START TICKET PRINTING PAYLOAD--------------------------------")
                  IO.inspect(printer_payload)
                  BusTerminalSystem.PrinterTcpProtocol.print_remote_cross_connect(printer_payload, ip)
                  IO.inspect("-----------------------------END TICKET PRINTING PAYLOAD----------------------------------")
                end)

                spawn(fn ->
                  BusTerminalSystem.APIRequestMockup.send(ticket.id |> to_string)
                end)

            end

#          rescue
#            _ ->
#              IO.inspect("--------------------------------******---------------------------------------------")
#              IO.inspect(ticket.route_information)
#              printer_payload =
#                %{
#                  "refNumber" => ticket.reference_number,
#                  "fName" => ticket.first_name,
#                  "sName" => ticket.last_name,
#                  "from" => "NOT DEFINED",
#                  "to" =>  "NOT DEFINED",
#                  "Price" =>  "NOT DEFINED",
#                  "Bus" =>  "NOT DEFINED",
#                  "gate" =>  "NOT DEFINED",
#                  "departureTime" =>  "NOT DEFINED",
#                  "ticketNumber" => ticket.id,
#                  "items" => acquire_luggage(ticket.id)
#                }
#
#              spawn(fn ->
#                BusTerminalSystem.PrinterTcpProtocol.print_local_connect(printer_payload)
#              end)
#
#              spawn(fn ->
#                BusTerminalSystem.APIRequestMockup.send(ticket.id |> to_string)
#              end)
#          end

        "LUGGAGE" ->
          IO.inspect(ticket.route_information)
          [_, tBus, _, start_route, _, end_route, _, departure, _, price, _,slot, _, _ | data] = ticket.route_information |> String.split()

          printer_payload =
            %{
              "refNumber" => ticket.reference_number,
              "fName" => ticket.first_name,
              "sName" => "",
              "from" => start_route,
              "to" => end_route,
              "Price" => "",
              "Bus" => "",
              "gate" => "",
              "depatureTime" => "",
              "ticketNumber" => ticket.id,
              "items" => acquire_luggage(ticket.id)
            }

          spawn(fn ->
            BusTerminalSystem.PrinterTcpProtocol.print_remote_cross_connect(printer_payload, ip)
          end)

          spawn(fn ->
            BusTerminalSystem.APIRequestMockup.send(ticket.id |> to_string)
          end)
          _ -> ""
      end


    {status, ticket} = update_ticket(ticket,%{ "activation_status" => "CHECKED_IN"})
    {status, json_result } = ticket |> Poison.encode
    {status, result} = json_result |> JSON.decode()
    result
  end

  def get_luggage_tarrif(id) do
  {status_s, tarrif_json} = Repo.get_by(LuggageTarrif,id: id) |> Poison.encode
  {status, tarrif_map} = JSON.decode(tarrif_json)
  tarrif_map
  end

  def get_luggage_by_ticket_id(ticket_id) do
    query = from r in Luggage, where: r.ticket_id == ^ticket_id
    {status_s, luggage_json} = Repo.all(query) |> Poison.encode
    {status, luggage} = JSON.decode(luggage_json)
    luggage
  end

  def get_luggage_by_ticket_id_total_cost(ticket_id) do
    BusTerminalSystem.Luggage.sum(:cost, ticket_id: ticket_id)
  end

  def generate_luggage_reference_number(route) do
    dt = DateTime.utc_now
    {micro,_} = dt.microsecond
    "ZBMS-#{dt.year}#{dt.month}#{dt.day}-#{dt.hour}#{dt.minute}#{dt.second}#{micro}"
  end

  def create_luggage(luggage) do

    luggage = Map.put(luggage, "class", "LUGGAGE")
    luggage = Map.put(luggage, "reference_number", "LUGGAGE")

    (Map.fetch!(luggage, "weight") == nil) |> if  do
      luggage = Map.put(luggage, "weight", 0)
      {status, luggage_json} = %Luggage{}
      |> Luggage.changeset(luggage)
      |> Repo.insert()
      |> Poison.encode()

      {sts, luggage_a} = JSON.encode(luggage_json)
      luggage

    else
      {status, luggage_json} = %Luggage{}
       |> Luggage.changeset(luggage)
       |> Repo.insert()
       |> Poison.encode()

      {sts, luggage_a} = JSON.encode(luggage_json)
      luggage
    end

  end

  #--------------------------ROUTES-------------------------------------------------------------------------------------

  def find_bus_by_route_code(route_code) do
    Repo.get_by(TravelRoutes, route_code: route_code)
  end

  def find_route_by_id(id) do
    Repo.get_by(TravelRoutes, id: id)
  end

  def update_route(%TravelRoutes{} = travel_routes, attrs) do
    travel_routes
    |> TravelRoutes.changeset(attrs)
    |> Repo.update()
  end

  def teller_profiler(list) do
    [date | [sales | teller_id] ] = list
    data =%{
      :date =>  date |> Date.to_string(),
      :sales => sales,
      :teller => teller_id #list |> Stream.with_index(1) |> Enum.reduce(%{}, fn({v,k}, acc)-> Map.put(acc, k, v)[1] end) |> Map.fetch!(2)
    }
    data
  end

  def report_sales_by_seller() do
    sales_query = "select * from vw_sales_by_teller;"
    {:ok, result} = Repo.query(sales_query)
    result
  end

  def operator_profiler(list) do
    [date | [sales | [company | id]] ] = list
    data =%{
      :date =>  date |> Date.to_string(),
      :sales => sales,
      :company => company,
      :id => id #list |> Stream.with_index(1) |> Enum.reduce(%{}, fn({v,k}, acc)-> Map.put(acc, k, v)[1] end) |> Map.fetch!(2)
    }
    data
  end

  def report_sales_by_operator() do
    sales_query = "select * from vw_sales_by_operator;"
    {:ok, result} = Repo.query(sales_query)
    result
  end

  def bus_profiler(list) do
    [date | [sales | licence] ] = list
    data =%{
      :date =>  date |> Date.to_string(),
      :sales => sales,
      :licence => licence#list |> Stream.with_index(1) |> Enum.reduce(%{}, fn({v,k}, acc)-> Map.put(acc, k, v)[1] end) |> Map.fetch!(2)
    }
    data
  end

  def report_sales_by_bus() do
    sales_query = "select * from vw_sales_by_bus;"
    {:ok, result} = Repo.query(sales_query)
    result
  end

  #--------------------------TELLER-------------------------------------------------------------------------------------

  def create_teller(marketer) do

    pin = Randomizer.randomizer(5, :numeric)
    uuid = "#{DateTime.utc_now.year}-#{BusTerminalSystem.Randomizer.randomizer(10,:numeric)}-#{DateTime.utc_now.month}-#{BusTerminalSystem.Randomizer.randomizer(4,:numeric)}-#{DateTime.utc_now.day}"

    marketer = Map.put(marketer, "operator_role", "TELLER")
    marketer = Map.put(marketer, "pin", encode_pin(pin))
    marketer = Map.put(marketer, "role", "TOP")
    marketer = Map.put(marketer, "password", Randomizer.randomizer(6, :upcase))
    marketer = Map.put(marketer, "account_status", "INACTIVE")
    marketer = Map.put(marketer, "uuid", uuid)

    IO.inspect marketer

    %User{}
    |> User.changeset(marketer)
    |> Repo.insert()
  end

  #--#------------------------MARKETER-----------------------------------------------------------------------------------

  def create_marketer(marketer) do

    pin = Randomizer.randomizer(5, :numeric)
    {:ok, mobile} = Map.fetch(marketer,"mobile")
    sms_message = "Hello, Your Marketeer PIN: #{pin}"
    NapsaSmsGetway.send_sms(mobile,sms_message)

    uuid = "#{DateTime.utc_now.year}-#{BusTerminalSystem.Randomizer.randomizer(10,:numeric)}-#{DateTime.utc_now.month}-#{BusTerminalSystem.Randomizer.randomizer(4,:numeric)}-#{DateTime.utc_now.day}"

    marketer = Map.put(marketer, "operator_role", "MARKETER")
    marketer = Map.put(marketer, "pin", encode_pin(pin))
    marketer = Map.put(marketer, "role", "MOP")
    marketer = Map.put(marketer, "password", Randomizer.randomizer(6, :upcase))
    marketer = Map.put(marketer, "account_status", "OTP")
    marketer = Map.put(marketer, "uuid", uuid)
    marketer = Map.put(marketer,"username","-")
    marketer = Map.put(marketer,"ssn","-")
    marketer = Map.put(marketer,"nrc","-")

    IO.inspect marketer

    %User{}
    |> User.changeset(marketer)
    |> Repo.insert()
  end

  def update_marketer_pin(%User{} = user, attrs,pin) do
    #sms_message = "OTP: #{pin}"
    #NapsaSmsGetway.send_sms(user.mobile,sms_message)

    user
    |> User.changeset(attrs)
    |> Repo.update()
  end

  def find_marketer_by_mobile(mobile) do
    Repo.get_by(User, [mobile: mobile, operator_role: "MARKETER"])
  end

  def authenticate_marketer_by_mobile(mobile,pin) do
    user = Repo.get_by(User, [mobile: mobile,pin: encode_pin(pin), operator_role: "MARKETER"])

    IO.inspect user
    IO.inspect("----------------------------------------------------------------")
    user
  end

  #-------------------------- Bus -------------------------------------------------------------------------------------

  def find_bus_by_uid(uid) do
    Repo.get_by(Bus, uid: uid)
  end

  def q do
    Repo.all(from a in "probase_tbl_trans_code", select: a.trn_code)
  end

  def list_buses(operator_id) do
    query = from r in Bus, where: r.operator_id == ^operator_id
    {status, buses} = Repo.all(query) |> Poison.encode
    {decode_status, bus_list} = JSON.decode(buses)
    bus_list
  end

  def find_bus_by_id(id) do
    Repo.get_by(Bus, id: id)
  end

  def update_bus(%Bus{} = bus, attrs) do
    bus
    |> Bus.changeset(attrs)
    |> Repo.update()
  end

  #-------------------------- USER -------------------------------------------------------------------------------------


  def list_bus_operators do
    query = from r in User, where: r.role == ^"BOP"
    {status, operators} = Repo.all(query) |> Poison.encode
    {decode_status, bus_operators} = JSON.decode(operators)
    bus_operators
  end

  def query_users(query), do: Repo.all(query)

  def find_user_by_username(username) do
    Repo.get_by(User, username: username)
  end

  def find_user_by_id(id) do
    Repo.get_by(User, id: id)
  end

  def update_user(%User{} = user, attrs) do
    user
    |> User.changeset(attrs)
    |> Repo.update()
  end

  #------------------------ HELPER METHODS -----------------------------------------------------------------------------

  def encode_pin(pin) do
    Base.encode16(:crypto.hash(:sha512,pin))
  end

  #------------------------ ROUTES -------------------------------------------------------------------------------------

  def get_route_by_route_code(code) do
    Repo.get_by(TravelRoutes, route_code: code)
  end

  def find_route_by_id(id) do
    Repo.get_by(TravelRoutes, id: id)
  end

  def route_by_id_json(id) do
    {status_s, route_json} = find_route_by_id(id) |> Poison.encode
    {status, route_map} = JSON.decode(route_json)
    route_map
  end

  def update_route(%TravelRoutes{} = travel_routes, attrs) do
    travel_routes
    |> TravelRoutes.changeset(attrs)
    |> Repo.update()
  end

  def create_mapping(attrs \\ %{}), do: %RouteMapping{} |> RouteMapping.changeset(attrs) |> Repo.insert()

  def list_bus_routes do
    {status, routes} = Repo.all(TravelRoutes) |> Poison.encode
    {decode_status, bus_routes} = JSON.decode(routes)
    bus_routes
  end

  def get_route_mapping(id), do: Repo.get!(RouteMapping, id)
  def get_route(id), do: Repo.get!(TravelRoutes, id)
#  def list_routes(), do: Repo.all(TravelRoutes)
  def list_routes() do
    TravelRoutes.where(auth_status: true)
  end
  def list_schedules(), do: Repo.all(RouteMapping)
  def list_ed_schedules(), do: Repo.all(TblEdReservations)
#  def create_route(attrs \\ %{}), do: %TravelRoutes{} |> TravelRoutes.changeset(attrs) |> Repo.insert()
  def create_route(conn, payload) do
    IO.inspect payload, label: "payload data"
    TravelRoutes.create(
            route_name: payload["route_name"],
            start_route: "Livingstone",
            end_route: payload["end_route"],
            route_code: payload["route_code"],
            source_state: payload["source_state"],
            route_uuid: payload["route_uuid"],
            route_fare: (payload["route_fare"] |> String.to_integer),
            auth_status: false,
            parent: (payload["parent_route"] |> String.to_integer),
            maker: conn.assigns.user.id,
            checker: nil,
            maker_date_time: Timex.now() |> NaiveDateTime.truncate(:second) |> Timex.to_naive_datetime(),
            checker_date_time: nil ,
            user_description: payload["user_description"],
            system_description: "Request to approve a route. request created by: #{conn.assigns.user.username}. on: #{Timex.today()}"
    )
    |> case  do
         {:ok, _}->
           {:ok, "route created successfully"}
         {:error, error}->
            {:error, error}
       end
  end
  def delete_route(%TravelRoutes{} = route), do: Repo.delete(route)

  #---------------------------------------------------------------------------------------------------------------------

  #-------- LIST REPO ------------////////////

  # TENANTS
  def get_tenant(id), do: Repo.get!(Tenant, id)
  def list_tenants(), do: Repo.all(Tenant)
  def create_tenant(attrs \\ %{}), do: %Tenant{} |> Tenant.changeset(attrs) |> Repo.insert()
  def update_tenant(%Tenant{} = tenant, attrs), do: tenant |> Tenant.changeset(attrs) |> Repo.update()
  def delete_tenant(%Tenant{} = tenant), do: Repo.delete(tenant)

  # OPERATORS
  def get_operator(id), do: Repo.get!(User, id)
  def list_operators(), do: Repo.all(User)
  def create_operator(attrs \\ %{}), do: %User{} |> User.changeset(attrs) |> Repo.insert()
  def update_operator(%User{} = user, attrs), do: user |> User.changeset(attrs) |> Repo.update()
  def delete_operator(%User{} = user), do: Repo.delete(user)

  # HUBS
  def get_hub(id), do: Repo.get!(Hub, id)
  def list_hubs(), do: Repo.all(Hub)
  def create_hub(attrs \\ %{}), do: %Hub{} |> Hub.changeset(attrs) |> Repo.insert()
  def update_hub(%Hub{} = hub, attrs), do: hub |> Hub.changeset(attrs) |> Repo.update()
  def delete_hub(%Hub{} = hub), do: Repo.delete(hub)

  # BUS
  def get_bus(id), do: Repo.get!(Bus, id)
  def list_buses(), do: Repo.all(Bus)
  def create_bus(attrs \\ %{}), do: %Bus{} |> Bus.changeset(attrs) |> Repo.insert()
  def update_bus(%Bus{} = bus, attrs), do: bus |> Bus.changeset(attrs) |> Repo.update()
  def delete_bus(%Bus{} = bus), do: Repo.delete(bus)

  # SECTIONS
  def get_section(id), do: Repo.get!(Section, id)
  def list_sections(), do: Repo.all(Section)
  def create_sections(attrs \\ %{}), do: %Section{} |> Section.changeset(attrs) |> Repo.insert()
  def update_hub(%Section{} = section, attrs), do: section |> Hub.changeset(attrs) |> Repo.update()
  def delete_hub(%Section{} = section), do: Repo.delete(section)

  # MARKETS
  def get_market(id), do: Repo.get!(Market, id)
  def list_markets(), do: Repo.all(Market)
  def create_market(attrs \\ %{}), do: %Market{} |> Market.changeset(attrs) |> Repo.insert()
  def update_hub(%Market{} = market, attrs), do: market |> Hub.changeset(attrs) |> Repo.update()
  def delete_hub(%Market{} = market), do: Repo.delete(market)

  # TICKETING
  def get_ticket(id), do: Repo.get(Ticket, id)
  def get_ticket_serial(ticket_id) do
    Repo.get_by(Ticket, [id: ticket_id])
  end
  def list_tickets(), do: Repo.all(from t in Ticket, where: not is_nil(t.reference_number) )

  def list_tickets_json() do
    {status, tickets_list} = Repo.all(Ticket) |> Poison.encode()
    {decode_status, tickets_map} = JSON.decode(tickets_list)
    tickets_map
  end

  def create_ticket(attrs \\ %{}) do

    attrs = Map.put(attrs, "class", "TICKET")
    IO.inspect(attrs)
    %Ticket{} |> Ticket.changeset(attrs) |> Repo.insert()
  end
  def update_ticket(%Ticket{} = ticket, attrs), do: ticket |> Ticket.changeset(attrs) |> Repo.update()
  def delete_ticket(%Ticket{} = ticket), do: Repo.delete(ticket)

  def get_ticket_by_reference_number(reference) do
    Repo.get_by(Ticket, reference_number: reference)
  end

  def get_ticket_by_external_reference(reference) do
    Repo.get_by(Ticket, external_ref: reference)
  end

  # ROUTES


  def schedule_routes(attrs \\ %{}), do: %RouteMapping{} |> RouteMapping.changeset(attrs) |> Repo.insert()

  def list_routes_json() do
    {status, travel_routes} = Repo.all(TravelRoutes) |> Poison.encode()
    {decode_status, routes_map} = JSON.decode(travel_routes)
    %{
      "travel_routes" => routes_map
    }
  end

  def route_search(start_route,end_route) do
    #query = from r in TravelRoutes, where: r.start_route == ^start_route and r.end_route == ^end_route
    {:ok, agent} = Agent.start_link fn  -> [] end

  end

  def route_mapping do

    {:ok, agent} = Agent.start_link fn  -> [] end

    {:ok, data} = Repo.all(RouteMapping) |> Poison.encode

    {status,route_mapping_data} = JSON.decode(data)

    route_mapping_data
    |> Enum.with_index()
    |> Enum.each(fn {e, index} ->

       {:ok, operator_id} = Map.fetch(e,"operator_id")
       {operator_id_int, _operator_id_string} = Integer.parse(operator_id)
       {operator_json_status, operator_json} = get_operator(operator_id_int) |> Poison.encode
       {operator_status, operator} = JSON.decode(operator_json)

       {:ok, bus_id} = Map.fetch(e,"bus_id")
       {bus_id_int, _bus_id_string} = Integer.parse(bus_id)
       {bus_json_status, bus_json} = get_bus(bus_id_int) |> Poison.encode
       {bus_status, bus} = JSON.decode(bus_json)

       #IO.inspect bus

       {:ok, route_id} = Map.fetch(e,"route_id")
       {route_id_int, _route_id_string} = Integer.parse(route_id)
       {route_json_status, route_json} = get_route(route_id_int) |> Poison.encode
       {route_status, route} = JSON.decode(route_json)

       #IO.inspect route

       {:ok, fare} = Map.fetch(e,"fare")


       Agent.update(agent, fn list -> [
          %{
            #"operator" => operator,
            "route" => route,
            "bus" => bus,
            "fare" => fare
          } | list ] end)
    end)

    {:ok, agent, Agent.get(agent, fn list -> list end) }
  end

  def route_mapping(date \\ "01/01/2019", time \\ "00:00") do

    {:ok, agent} = Agent.start_link fn  -> [] end
    query = from r in RouteMapping, where: r.date == ^date
    {st, data} = Repo.all(query) |> Poison.encode
    #IO.inspect Repo.get_by(RouteMapping, [date: date, time: time])
    {status,route_mapping_data} = JSON.decode(data)

    route_mapping_data
    |> Enum.with_index()
    |> Enum.each(fn {e, index} ->

      {:ok, operator_id} = Map.fetch(e,"operator_id")
      {operator_id_int, _operator_id_string} = Integer.parse(operator_id)
      {operator_json_status, operator_json} = get_operator(operator_id_int) |> Poison.encode
      {operator_status, operator} = JSON.decode(operator_json)

      {:ok, bus_id} = Map.fetch(e,"bus_id")
      {bus_id_int, _bus_id_string} = Integer.parse(bus_id)
      {bus_json_status, bus_json} = get_bus(bus_id_int) |> Poison.encode
      {bus_status, bus} = JSON.decode(bus_json)

      #IO.inspect bus
      IO.inspect e
      {:ok, route_id} = Map.fetch(e,"route_id")
      {route_id_int, _route_id_string} = Integer.parse(route_id)
      {route_json_status, route_json} = get_route(route_id_int) |> Poison.encode
      {route_status, route} = JSON.decode(route_json)

      #IO.inspect route

      {:ok, fare} = Map.fetch(e,"fare")
      {:ok, time} = Map.fetch(e,"time")
      {:ok, date} = Map.fetch(e,"date")


      Agent.update(agent, fn list -> [
       %{
         #"operator" => operator,
         "route" => route,
         "bus" => bus,
         "fare" => fare,
         "time" => time,
         "date" => date
       } | list ] end)
    end)

    {:ok, agent, Agent.get(agent, fn list -> list end) }
  end

  def schedule_ticket_count(schedule_id), do: Repo.one(from r in Ticket,select: count("*"), where: r.bus_schedule_id == ^schedule_id)

  defp available_seats(capacity, ticket_count) do
    Utility.string_to_int(capacity) - ticket_count
  end

  def route_mapping_by_location_internal(date \\ "01/01/2019", start_route \\ "Livingstone", end_route) do
    IO.inspect "DATE: #{date}"
    {:ok, agent} = Agent.start_link fn  -> [] end

    #    {YYYY}-{0M}-{0D}
    query = from r in TblEdReservations , where: r.reserved_time >= ^Timex.parse!(date, "{0D}/{0M}/{YYYY}") and r.status == "A"
    #    query = from r in TblEdReservations, where: fragment("?::date_time", r.reserved_time) >= ^Timex.parse!(date, "{YYYY}-{0M}-{0D}") and
    #                                                fragment("?::date_time", r.reserved_time) <= ^Timex.parse!(date, "{YYYY}-{0M}-{0D}")
    {st, data} = Repo.all(query) |> Poison.encode


    IO.inspect data
    {status,route_mapping_data} = JSON.decode(data)

    route_mapping_data
    |> Enum.with_index()
    |> Enum.each(fn {e, index} ->

      {:ok, operator_id} = Map.fetch(e,"user_id")
      {operator_id_int, _operator_id_string} = Integer.parse(operator_id |> to_string)
      {operator_json_status, operator_json} = get_operator(operator_id_int) |> Poison.encode
      {operator_status, operator} = JSON.decode(operator_json)

      {:ok, bus_id} = Map.fetch(e,"bus_id")
      {bus_id_int, _bus_id_string} = Integer.parse(bus_id |> to_string)
      {bus_json_status, bus_json} = get_bus(bus_id_int) |> Poison.encode
      {bus_status, bus} = JSON.decode(bus_json)

      IO.inspect "BUS:"
      {:ok, capacity} = Map.fetch(bus,"vehicle_capacity")
      IO.inspect Utility.string_to_int(capacity)

      {:ok, route_id} = Map.fetch(e,"route")
      {route_id_int, _route_id_string} = Integer.parse(route_id)
      {route_json_status, route_json} = get_route(route_id_int) |> Poison.encode
      {route_status, route} = JSON.decode(route_json)

      queried_route = get_route(route_id_int)

      #IO.inspect route

      {:ok, route_uid} = Map.fetch(e,"id")
      fare = queried_route.route_fare
      {:ok, time} = Map.fetch(e,"time")
      {:ok, date} = Map.fetch(e,"reserved_time")
      {:ok, slot} = Map.fetch(e,"slot")

      if queried_route.start_route == start_route and queried_route.end_route == end_route do
        Agent.update(agent, fn list -> [
           %{
             "available_seats" => available_seats(capacity,schedule_ticket_count(Utility.int_to_string(route_uid))),
             "bus_schedule_id" => route_uid |> to_string,
             "route" => route,
             "bus" => bus,
             "fare" => fare,
             "slot" => slot,
             "departure_time" => time,
             "departure_date" => date
           } | list ] end)
      end


    end)

    {:ok, agent, Agent.get(agent, fn list -> list end) }
  end

  def route_mapping_by_location(date \\ "01/01/2019", start_route \\ "Livingstone", end_route) do
    IO.inspect "DATE: #{date}"
    {:ok, agent} = Agent.start_link fn  -> [] end

#    {YYYY}-{0M}-{0D}
    query = from r in TblEdReservations , where: r.reserved_time >= ^Timex.parse!(date, "{YYYY}-{0M}-{0D}") and r.status == "A"
#    query = from r in TblEdReservations, where: fragment("?::date_time", r.reserved_time) >= ^Timex.parse!(date, "{YYYY}-{0M}-{0D}") and
#                                                fragment("?::date_time", r.reserved_time) <= ^Timex.parse!(date, "{YYYY}-{0M}-{0D}")
    {st, data} = Repo.all(query) |> Poison.encode
#    {st, data} = TblEdReservations.where([status: "B", reserved_time: Timex.parse!(date, "{0D}/{0M}/{YYYY}")]) |> Poison.encode


    IO.inspect data
    {status,route_mapping_data} = JSON.decode(data)

    route_mapping_data
    |> Enum.with_index()
    |> Enum.each(fn {e, index} ->

      {:ok, operator_id} = Map.fetch(e,"user_id")
      {operator_id_int, _operator_id_string} = Integer.parse(operator_id |> to_string)
      {operator_json_status, operator_json} = get_operator(operator_id_int) |> Poison.encode
      {operator_status, operator} = JSON.decode(operator_json)

      {:ok, bus_id} = Map.fetch(e,"bus_id")
      {bus_id_int, _bus_id_string} = Integer.parse(bus_id |> to_string)
      {bus_json_status, bus_json} = get_bus(bus_id_int) |> Poison.encode
      {bus_status, bus} = JSON.decode(bus_json)

      IO.inspect "BUS:"
      {:ok, capacity} = Map.fetch(bus,"vehicle_capacity")
      IO.inspect Utility.string_to_int(capacity)

      route_id = Map.fetch!(e,"route")
      {route_id_int, _route_id_string} = Integer.parse(route_id)
      {route_json_status, route_json} = get_route(route_id_int) |> Poison.encode
      {route_status, route} = JSON.decode(route_json)

       stops = Enum.map(BusTerminalSystem.TravelRoutes.all(), fn route ->
#        for found_route <- 1..BusTerminalSystem.TravelRoutes.count() do
          try do
#            if route_id == 1 do
#              if BusTerminalSystem.TravelRoutes.find_by([parent: route_id]).parent == route.id and BusTerminalSystem.TravelRoutes.find_by([parent: route_id]) != route do
#                route |> Poison.encode!
#              end
#            else
              if BusTerminalSystem.TravelRoutes.find_by([parent: route.id]).parent == route_id and BusTerminalSystem.TravelRoutes.find_by([parent: route.id]) != route do
                route |> Poison.encode!
              end
#            end

          rescue
            _ -> nil
          end
#        end
      end) |> List.flatten() |> Enum.filter(& !is_nil(&1)) |> IO.inspect(label: "TREE")

      queried_route = get_route(route_id_int)

      #IO.inspect route

      {:ok, route_uid} = Map.fetch(e,"id")
       fare = queried_route.route_fare
      {:ok, time} = Map.fetch(e,"time")
      {:ok, date} = Map.fetch(e,"reserved_time")
      {:ok, slot} = Map.fetch(e,"slot")

#      IO.inspect "start route #{queried_route.start_route} : end route #{queried_route.end_route}"
      route_stops = stops(route_id, []) |> Enum.sort_by( fn(r) -> r["order"] end)
      if queried_route.start_route == start_route and queried_route.end_route == end_route do
        Agent.update(agent, fn list -> [
           %{
             "available_seats" => available_seats(capacity,schedule_ticket_count(Utility.int_to_string(route_uid))),
             "bus_schedule_id" => route_uid |> to_string,
             "route" => route,
             "bus" => bus,
             "fare" => fare,
             "route_stops" => route_stops,
             "slot" => slot,
             "departure_time" => time,
             "departure_date" => date
           } | list ] end)
      end


    end)

    {:ok, agent, Agent.get(agent, fn list -> list end) }
  end

  def stops(r_id, result) do
    r = BusTerminalSystem.TravelRoutes.find_by([parent: r_id])
    if r == nil do
      result
    else
      result = [ %{
        "route_name" => r.route_name,
        "start_route" => r.start_route,
        "end_route" => r.end_route,
        "route_code" => r.route_code,
        "route_fare" => r.route_fare,
        "route_uuid" => r.route_uuid,
        "source_state" => r.source_state,
        "order" => r.parent
       } | result]
      IO.inspect(result)
      stops(r.id, result)
    end
  end

  def route_mapping_by_location_(date \\ "01/01/2019", start_route \\ "Livingstone", end_route) do
    IO.inspect "DATE: #{date}"
    {:ok, agent} = Agent.start_link fn  -> [] end

    query = from r in RouteMapping, where: r.date == ^date
    {st, data} = Repo.all(query) |> Poison.encode


    IO.inspect data
    {status,route_mapping_data} = JSON.decode(data)

    route_mapping_data
    |> Enum.with_index()
    |> Enum.each(fn {e, index} ->

      {:ok, operator_id} = Map.fetch(e,"operator_id")
      {operator_id_int, _operator_id_string} = Integer.parse(operator_id)
      {operator_json_status, operator_json} = get_operator(operator_id_int) |> Poison.encode
      {operator_status, operator} = JSON.decode(operator_json)

      {:ok, bus_id} = Map.fetch(e,"bus_id")
      {bus_id_int, _bus_id_string} = Integer.parse(bus_id)
      {bus_json_status, bus_json} = get_bus(bus_id_int) |> Poison.encode
      {bus_status, bus} = JSON.decode(bus_json)

      IO.inspect "BUS:"
      {:ok, capacity} = Map.fetch(bus,"vehicle_capacity")
      IO.inspect Utility.string_to_int(capacity)

      {:ok, route_id} = Map.fetch(e,"route_id")
      {route_id_int, _route_id_string} = Integer.parse(route_id)
      {route_json_status, route_json} = get_route(route_id_int) |> Poison.encode
      {route_status, route} = JSON.decode(route_json)

      queried_route = get_route(route_id_int)

      #IO.inspect route

      {:ok, route_uid} = Map.fetch(e,"route_uid")
      {:ok, fare} = Map.fetch(e,"fare")
      {:ok, time} = Map.fetch(e,"time")
      {:ok, date} = Map.fetch(e,"date")

      IO.inspect "start route #{queried_route.start_route} : end route #{queried_route.end_route}"
      if queried_route.start_route == start_route and queried_route.end_route == end_route do
        Agent.update(agent, fn list -> [
         %{
           "available_seats" => available_seats(capacity,schedule_ticket_count(Utility.int_to_string(route_uid))),
           "bus_schedule_id" => route_uid,
           "route" => route,
           "bus" => bus,
           "fare" => fare,
           "departure_time" => time,
           "departure_date" => date
         } | list ] end)
      end


    end)

    {:ok, agent, Agent.get(agent, fn list -> list end) }
  end

  def route_mapping_by_date(start_date \\ ~D[2020-01-11],end_date \\ ~D[2020-12-11], start_route \\ "Livingstone", end_route \\ "Lusaka") do

    {:ok, agent} = Agent.start_link fn  -> [] end

    query = from r in RouteMapping, where: fragment("?::date", r.inserted_at) >= ^start_date and
                                           fragment("?::date", r.inserted_at) <= ^end_date

    {st, data} = Repo.all(query) |> Poison.encode
    #IO.inspect Repo.get_by(RouteMapping, [date: date, time: time])
    {status,route_mapping_data} = JSON.decode(data)

    route_mapping_data
    |> Enum.with_index()
    |> Enum.each(fn {e, index} ->

      {:ok, operator_id} = Map.fetch(e,"operator_id")
      {operator_id_int, _operator_id_string} = Integer.parse(operator_id)
      {operator_json_status, operator_json} = get_operator(operator_id_int) |> Poison.encode
      {operator_status, operator} = JSON.decode(operator_json)

      {:ok, bus_id} = Map.fetch(e,"bus_id")
      {bus_id_int, _bus_id_string} = Integer.parse(bus_id)
      {bus_json_status, bus_json} = get_bus(bus_id_int) |> Poison.encode
      {bus_status, bus} = JSON.decode(bus_json)

      #IO.inspect bus

      {:ok, route_id} = Map.fetch(e,"route_id")
      {route_id_int, _route_id_string} = Integer.parse(route_id)
      {route_json_status, route_json} = get_route(route_id_int) |> Poison.encode
      {route_status, route} = JSON.decode(route_json)

      queried_route = get_route(route_id_int)

      #IO.inspect route

      {:ok, fare} = Map.fetch(e,"fare")
      {:ok, time} = Map.fetch(e,"time")
      {:ok, date} = Map.fetch(e,"date")
      {:ok, route_uid} = Map.fetch(e,"route_uid")

      IO.inspect "start route #{queried_route.start_route} : end route #{queried_route.end_route}"
      if queried_route.start_route == start_route and queried_route.end_route == end_route do
        Agent.update(agent, fn list -> [
         %{
           #"operator" => operator,
           "route_id" => route_uid,
           "route" => route,
           "bus" => bus,
           "fare" => fare,
           "time" => time,
           "date" => date
         } | list ] end)
      end


    end)

    {:ok, agent, Agent.get(agent, fn list -> list end) }
  end

  #------------------------------------------TRANSACTIONS -----------------------------------
  def transaction_to_leger(args) do

  end

  def find_transaction_code(code) do
    query = from tc in TransactionCodes,
                  where: tc.trn_code == ^code,
                  select: [:trn_code, :trn_desc, :auth_status, :maker_id, :checker_id]

    Repo.one(query) |> case do
       nil -> "No Record for #{code} Found"
       tr_code -> tr_code
     end
  end

end
