defmodule BusTerminalSystemWeb.FrontendApiController do
  use BusTerminalSystemWeb, :controller

  alias BusTerminalSystem.RepoManager
  alias BusTerminalSystem.ApiManager
  alias BusTerminalSystem.ScaleQuery

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

  #---------------------------------------Scale-------------------------------------------------------------------------
  def get_scale_query(conn, _params) do
    conn |> json(ScaleQuery.query_scale)
  end

  #---------------------------------------Luggage-------------------------------------------------------------------------

  def get_luggage_tarrif(conn,%{ "tarrif_id" => id} = params) do
      conn |> json( RepoManager.get_luggage_tarrif(id))
  end

  def get_luggage_by_ticket(conn, %{ "ticket_id" => ticket_id } = params) do
    conn |> json(RepoManager.get_luggage_by_ticket_id(ticket_id))
  end

  def add_luggage(conn, params) do
    conn |> json(RepoManager.create_luggage(params))
  end

  def checkin_passenger(conn,%{"ticket_id" => ticket_id} = params) do
    conn |> json(RepoManager.checkin(ticket_id))
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

end