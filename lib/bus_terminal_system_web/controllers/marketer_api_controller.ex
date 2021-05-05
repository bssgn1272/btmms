defmodule BusTerminalSystemWeb.MarketApiController do
  use BusTerminalSystemWeb, :controller

  alias BusTerminalSystem.RepoManager
  alias BusTerminalSystem.ApiManager



  def fetch_kyc(conn, params) do
    ApiManager.auth(conn,params)

    key = params["payload"]["mobile"]
    case key do
      nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
      _ ->
        case key |> RepoManager.find_marketer_by_mobile do
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
                "dob" => user.dob,
                "email" => user.email,
                "mobile" => user.mobile,
                "account_status" => user.account_status,
                "uuid" => user.uuid,
                "operator_role" => user.operator_role,
                "stands" => BusTerminalSystem.Market.MarketRepo.stand_details_list(user.id)
              }))
          _value ->
          IO.inspect _value
            json conn, ["hello"]
        end
    end
  end

  def fetch_kyc_minimal(conn, params) do
    ApiManager.auth(conn,params)

    key = params["payload"]["mobile"]
    case key do
      nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
      _ ->
        case key |> RepoManager.find_marketer_by_mobile do
          nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
          user ->
            conn
            |> json(ApiManager.api_message_custom_handler_conn(conn,ApiManager.definition_query,"SUCCESS",0,
              %{
                "notice" => "Service Decommissioned",
                "first_name" => user.first_name,
                "last_name" => user.last_name,
                "mobile" => user.mobile,
                "stand_number" => -1,#Map.get(BusTerminalSystem.Market.MarketRepo.stand_details_minimal(user.id), :shop_number) || -1,
                "stand_price" => -1#Map.get(BusTerminalSystem.Market.MarketRepo.stand_details_minimal(user.id), :shop_price) || -1
              }))
          _value ->
            IO.inspect _value
            json conn, ["-"]
        end
    end
  end

  def all_marketeer_kyc(conn, _params) do

    BusTerminalSystem.Market.MarketRepo.market_users_list_res() |> case do
     {:ok, agent, users} ->
     Agent.stop(agent)
       conn |> json(ApiManager.api_success_handler(conn, ApiManager.definition_query, users))
     _ -> conn |> json([])
     end
  end

  def authenticate_marketer(conn, params) do
    ApiManager.auth(conn,params)

    mobile = params["payload"]["mobile"]
    pin = params["payload"]["pin"]

    if mobile == nil or pin == nil do
      json(conn,ApiManager.api_error_handler(conn,ApiManager.definition_query,[
        "mobile can not be blank",
        "pin can not be blank"
      ]))
    else

      if pin |> String.length != 5 or mobile |> String.length != 12 do

        if pin |> String.length != 5 do
          json(conn,ApiManager.api_error_handler(conn,ApiManager.definition_query,[
            "Pin Length invalid, required length 5 "
          ]))
        end

        if mobile |> String.length != 12 do
          json(conn,ApiManager.api_error_handler(conn,ApiManager.definition_query,[
            "Mobile Number length invalid, required length 12 e.g 260*** "
          ]))
        end

      else
        case mobile |> RepoManager.authenticate_marketer_by_mobile(pin) do
          nil  ->
            conn
            |> json(ApiManager.api_error_handler(ApiManager.definition_authentication(),%{
              "message" => "AUTHENTICATION FAILED INVALID ACCOUNT OR PASSWORD",
              "mobile" => mobile,
              "pin" => "[HIDDEN]"
            }))
          [user] ->
          IO.inspect(user)
            conn
            |> json(ApiManager.api_message_custom_handler_conn(conn,ApiManager.definition_authentication,"SUCCESS",0,
              %{
                "message" => "AUTHENTICATION SUCCESS",
                "username" => user.username,
                "first_name" => user.first_name,
                "last_name" => user.last_name,
                "ssn" => user.ssn,
                "dob" => user.dob,
                "role" => user.role,
                "nrc" => user.nrc,
                "email" => user.email,
                "mobile" => user.mobile,
                "account_status" => user.account_status,
                "uuid" => user.uuid,
                "operator_role" => user.operator_role
              }))
        end
      end


    end
  end

  def update_pin(conn, params) do
    ApiManager.auth(conn,params)

    mobile = params["payload"]["mobile"]
    pin = params["payload"]["pin"]

    if mobile == nil or pin == nil do
      json(conn,ApiManager.api_error_handler(conn,ApiManager.definition_query,[
        "mobile can not be blank",
        "pin can not be blank"
      ]))
    else
      case mobile do
        nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
        _ ->
          case mobile |> RepoManager.find_marketer_by_mobile do
            nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
            user ->
              case RepoManager.update_marketer_pin(user,%{"account_status" => "ACTIVE","pin" => pin},pin) do
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
                _ ->
                  conn
                  |> json(ApiManager.api_error_handler(ApiManager.definition_accounts(),%{"message" => "Could not update pin. An error occurred"}))
              end
          end
      end
    end
  end

  def reset_pin(conn, params) do
    ApiManager.auth(conn,params)

    mobile = params["payload"]["mobile"]
    pin = BusTerminalSystem.Randomizer.randomizer(5,:numeric)

    if mobile == nil or pin == nil do
      json(conn,ApiManager.api_error_handler(conn,ApiManager.definition_query,[
        "mobile can not be blank",
        "pin can not be blank"
      ]))
    else
      case mobile do
        nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
        _ ->
          case mobile |> RepoManager.find_marketer_by_mobile do
            nil -> json(conn,ApiManager.api_success_handler(conn,ApiManager.definition_query,ApiManager.not_found_query))
            user ->
              case RepoManager.update_marketer_pin(user,%{"account_status" => "OTP","pin" => pin},pin) do
                {:ok, user} ->
                  conn
                  |> json(ApiManager.api_message_custom_handler_conn(conn,ApiManager.definition_authentication,"SUCCESS",0,
                  %{
                    "message" => "PIN RESET SUCCESSFUL",
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
                  conn
                  |> json(ApiManager.api_error_handler(ApiManager.definition_accounts(),%{"message" => "Could not reset pin. An error occurred"}))
              end
          end
      end
    end

  end

  def register_marketeer(conn,%{"payload" => user_payload} = params) do
    ApiManager.auth(conn,params)

    mobile = params["payload"]["mobile"]

    if mobile == nil do
      json(conn,ApiManager.api_error_handler(conn,ApiManager.definition_query,[
        "mobile can not be blank",
        "pin can not be blank"
      ]))
    else
      if mobile |> String.length != 12 do

        if mobile |> String.length != 12 do
          json(conn,ApiManager.api_error_handler(conn,ApiManager.definition_query,[
            "Mobile Number length invalid, required length 12 e.g 260*** "
          ]))
        end

      else


        case RepoManager.create_marketer(user_payload) do
          {:ok, user} ->
            conn
            |> json(ApiManager.api_message_custom_handler(ApiManager.definition_query,"SUCCESS",0,
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

          {:error, %Ecto.Changeset{} = changeset} ->
            conn
            |> json(ApiManager.api_error_handler(ApiManager.definition_accounts(),ApiManager.translate_error(changeset)))
        end
      end
    end


  end

end