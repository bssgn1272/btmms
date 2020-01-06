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
      case mobile |> RepoManager.authenticate_marketer_by_mobile(pin) do
       nil  ->
         conn
         |> json(ApiManager.api_error_handler(ApiManager.definition_authentication(),%{
          "message" => "AUTHENTICATION FAILED",
          "mobile" => mobile,
          "pin" => "[HIDDEN]"
         }))
       user ->
         conn
         |> json(ApiManager.api_message_custom_handler_conn(conn,ApiManager.definition_authentication,"SUCCESS",0,
           %{
             "message" => "AUTHENTICATION SUCCESS",
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
              case RepoManager.update_marketer_pin(user,%{"pin" => pin |> RepoManager.encode_pin}) do
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
              case RepoManager.update_marketer_pin(user,%{"pin" => pin |> RepoManager.encode_pin}) do
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

  def register_marketeer(conn, params) do
    ApiManager.auth(conn,params)

    case RepoManager.create_marketer(params["payload"]) do
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