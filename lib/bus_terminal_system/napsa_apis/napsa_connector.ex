defmodule BusTerminalSystem.Napsa.Connector do
  @moduledoc false

  def submit_request(request, soap_endpoint, soap_action) do
    headers = [
      {"Content-Type", "text/xml"},
      {"SOAPAction", soap_action},
    ]

    case HTTPoison.post(soap_endpoint, request, headers) do
      {status, %HTTPoison.Response{body: body, status_code: status_code}} ->
        body |> XmlToMap.naive_map |> IO.inspect()
      {_status, %HTTPoison.Error{reason: reason}} ->
        %{"message" => reason}
    end
  end

end