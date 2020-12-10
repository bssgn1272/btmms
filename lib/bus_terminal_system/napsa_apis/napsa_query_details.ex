defmodule BusTerminalSystem.Napsa.NapsaQueryDetails do
  @moduledoc false

  def connect(nrc_ssn \\ "341565/43/2"), do: nrc_ssn |> submit_request

  defp submit_request(nrc_ssn) do
    HTTPoison.get("http://10.10.1.114:8092/apis/external/v1/validatessnnrc", [], [params: [id: nrc_ssn]]) |> case do
      {status, %HTTPoison.Response{body: body, status_code: status_code}} ->
        body |> Poison.decode!
      {_status, %HTTPoison.Error{reason: reason}} ->
        %{"message" => reason}
    end
  end

end