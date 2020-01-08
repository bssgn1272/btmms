defmodule BusTerminalSystemWeb.FrontendApiController do
  use BusTerminalSystemWeb, :controller

  alias BusTerminalSystem.RepoManager
  alias BusTerminalSystem.ApiManager

  #---------------------------------------USER--------------------------------------------------------------------------

  def list_bus_operators(conn, _params) do
    operators = RepoManager.list_bus_operators()
    conn
    |> json(operators)
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
                      "operator_role" => user.operator_role
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

  #---------------------------------------Routes---------------------------------------------------------------------------

  def list_travel_routes(conn, _params) do
    operators = RepoManager.list_bus_routes()
    conn
    |> json(operators)
  end
end