defmodule BusTerminalSystem.ApiManager do
  use BusTerminalSystemWeb, :controller
  alias BusTerminalSystem.AccountManager

  defp validate_user(cred) do
    if Map.has_key?(cred, "username") && Map.has_key?(cred, "service_token") do

      {:ok, username} = Map.fetch(cred,"username")

      case AccountManager.get_user_by_username(username) do
        nil -> {:error, %{"message" => auth_error_handler"Failed to Authenticate User"}}
        _ -> {:ok, %{"message" => "AUTHENTICATED"}}
      end
    else
      {:error, auth_error_handler("Invalid Username/service_token object keys. Please check if keys exist or are properly typed")}
    end
  end

  defp auth_error_handler(message) do
    %{"error" => %{ "status" => 1,"operation" => "AUTHENTICATION","operation_status" => "FAILED","message" => "#{message}"}}
  end

  def authentication_mod(params) do
    if Map.has_key?(params, "auth") && Map.has_key?(params, "payload") do
      {:ok, auth_data} = Map.fetch(params, "auth")
      auth_data
      |> validate_user
    else
      if !Map.has_key?(params, "auth") do
        {:error, %{ "error" => auth_error_handler("The payload submitted is invalid. Authentication Object not found. Refer to documentation for more info")}}
      else
        if !Map.has_key?(params, "payload") do
          {:error, %{ "error" => auth_error_handler("The payload submitted is invalid. Payload Object not found. Refer to documentation for more info")}}
        end
      end
    end
  end

  def auth(conn, params) do
    case params |> authentication_mod do
      {:error, result} -> { json(conn, result)}
      {:ok, result} -> {:ok, result}
    end
  end

  @spec serialize(term) :: binary
  def serialize(term) do
    term
    |> :erlang.term_to_binary()
    |> Base.url_encode64()
  end

  @spec deserialize(binary) :: term
  def deserialize(str) when is_binary(str) do
    str
    |> Base.url_decode64!()
    |> :erlang.binary_to_term()
  end

  def meta do
    %{"data" => %{
      "pid_serial" => serialize(self),
      "request_uid" => Ecto.UUID.generate(),
      "date" => DateTime.utc_now,
      "tracking_id" => BusTerminalSystem.Randomizer.randomizer(32, :numeric)
    }}
  end

  def meta(conn) do

    {a,b,c,d} = conn.remote_ip

    %{"data" => %{
      "pid_serial" => serialize(self),
      "request_uid" => Ecto.UUID.generate(),
      "date" => DateTime.utc_now,
      "tracking_id" => BusTerminalSystem.Randomizer.randomizer(32, :numeric),
      "remote_ip" => "#{a}.#{b}.#{c}.#{d}"
    }}
  end

  def api_error_handler(service,message) do
    %{ "response" => %{"error" => %{ "status" => 1,"operation" => service,"operation_status" => "FAILED","message" => message }}, "meta" => meta}
  end

  def api_success_handler(service,message) do
    %{ "response" => %{"success" => %{ "status" => 0,"operation" => service,"operation_status" => "SUCCESS","message" => message }}, "meta" => meta}
  end

  def api_message_handler(service,message,status_message,status_code) do
    %{ "response" => %{service => %{ "status" => status_code,"operation" => service,"operation_status" => status_message,"message" => message}}, "meta" => meta}
  end

  def api_message_custom_handler(service,status_message,status_code,response \\%{}) do
    %{ "response" => %{service => %{ "status" => status_code,"operation" => service,"operation_status" => status_message,"data" => response}}, "meta" => meta}
  end

  def inspector(message) do
    IO.inspect(message)
  end

  def api_error_handler(conn,service,message) do
    %{ "response" => %{"error" => %{ "status" => 1,"operation" => service,"operation_status" => "FAILED","message" => message }}, "meta" => meta(conn)}
  end

  def api_success_handler(conn,service,message) do
    %{ "response" => %{"success" => %{ "status" => 0,"operation" => service,"operation_status" => "SUCCESS","message" => message }}, "meta" => meta(conn)}
  end

  def api_message_handler(conn,service,message,status_message,status_code) do
    %{ "response" => %{service => %{ "status" => status_code,"operation" => service,"operation_status" => status_message,"message" => message}}, "meta" => meta(conn)}
  end

  def api_message_custom_handler_conn(conn,service,status_message,status_code,response \\%{}) do
    %{ "response" => %{service => %{ "status" => status_code,"operation" => service,"operation_status" => status_message,"data" => response}}, "meta" => meta(conn)}
  end

  def translate_error(%{errors: errors}=_changeset) do
    Enum.map(errors, fn {field, error} ->
      Atom.to_string(field) <> " " <> translate_error(error)
    end)
  end
  def translate_error({msg, opts}) do
    case opts[:count] do
      nil -> Gettext.dgettext(BusTerminalSystemWeb.Gettext, "errors", msg, opts)
      count -> Gettext.dngettext(BusTerminalSystemWeb.Gettext, "errors", msg, msg, count, opts)
    end
  end

  #----------------SERVICES--------------------------
  def definition_authentication, do: "AUTHENTICATION"

  def definition_purchase, do: "PURCHASE"
  def support_purchase, do: "Could not complete purchase. Missing data keys. Please refer to documentation for more info"

  def definition_query, do: "QUERY"
  def support_query, do: "Could not complete Query. Missing data keys. Please refer to documentation for more info"
  def not_found_query, do: "Result not Found"

  def definition_accounts, do: "ACCOUNTS"

end