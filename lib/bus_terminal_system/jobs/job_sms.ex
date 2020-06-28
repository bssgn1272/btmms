defmodule BusTerminalSystem.Job.Sms do
  use Task

  @moduledoc false

  @check_after 80_000

  alias BusTerminalSystem.Notification.Table.Sms

  def start_link(_args) do
    Task.start_link(&send_messages/0)
  end

  def push_message(sms) do

    spawn(fn ->
      BusTerminalSystem.NapsaSmsGetway.send_sms_out_sync(sms.recipient,sms.message)
      |> case do
           {:ok, %HTTPoison.Response{:body => body, :headers => headers, :request => request, :status_code => status_code}} ->
             sms |> Sms.update([status_code: status_code, request: request.params |> Poison.encode!, response: body, sent: true, status: "SENT"])
           {:error, _} ->
             sms |> Sms.update([status_code: 401, status: "FAILED", sent: true])
         end
    end)

  end

  def send_messages() do
    receive do
    after
      @check_after ->
        Enum.each(Sms.stream_where(sent: false), &push_message/1)
        send_messages()
    end
  end
end