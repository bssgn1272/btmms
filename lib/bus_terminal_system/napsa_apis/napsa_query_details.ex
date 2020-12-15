defmodule BusTerminalSystem.Napsa.NapsaQueryDetails do
  @moduledoc false

  def connect(params), do: params |> submit_request

  defp submit_request(params) do
    HTTPoison.get("http://10.10.1.114:8092/apis/external/v1/validatessnnrc", [], [params: [id: params["id"]]]) |> case do
      {status, %HTTPoison.Response{body: body, status_code: status_code}} ->
        body |> Poison.decode!
      {_status, %HTTPoison.Error{reason: reason}} ->
        %{"message" => reason}
    end
  end

end