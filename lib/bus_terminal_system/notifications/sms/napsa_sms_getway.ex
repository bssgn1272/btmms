defmodule BusTerminalSystem.NapsaSmsGetway do

  alias BusTerminalSystem.Settings

  def send_sms(phone, message) do

    Task.async(fn ->
      response = HTTPoison.get(Settings.find_by(key: "SMS_GATEWAY").value,[],
      params: %{
        smsc: Settings.find_by(key: "SMS_GATEWAY_SMSC").value,
        username: Settings.find_by(key: "SMS_GATEWAY_USERNAME").value,
        password: Settings.find_by(key: "SMS_GATEWAY_PASSWORD").value,
        from: Settings.find_by(key: "SMS_GATEWAY_SENDER").value,
        to: phone,
        text: message
        }
      )
    end)
  end

  def send_sms_out_sync(phone,message) do

      response = HTTPoison.get(Settings.find_by(key: "SMS_GATEWAY").value,[],
        params: %{
          smsc: Settings.find_by(key: "SMS_GATEWAY_SMSC").value,
          username: Settings.find_by(key: "SMS_GATEWAY_USERNAME").value,
          password: Settings.find_by(key: "SMS_GATEWAY_PASSWORD").value,
          from: Settings.find_by(key: "SMS_GATEWAY_SENDER").value,
          to: phone,
          text: message
        }
      )
      IO.inspect("--------------------------START SMS STATUS----------------------------------")
      IO.inspect(response)
      IO.inspect("--------------------------END SMS STATUS----------------------------------")
      response

  end

  def send_ticket_sms(ticket) do
      bus = BusTerminalSystem.BusManagement.Bus.find_by(id: ticket.bus_no)
      schedule = BusTerminalSystem.TblEdReservations.find_by(id: ticket.bus_schedule_id)
      route = BusTerminalSystem.TravelRoutes.find(ticket.route)
      [date, _] = schedule.reserved_time |> to_string |> String.split(" ")
      [year, month, day] = date |> String.split("-")
      date = "#{day}-#{month}-#{year}"

      "Hello #{ticket.first_name} #{ticket.last_name}, Ticket Purchase was successful.\n\nTICKET ID: #{ticket.id}\nBUS: #{bus.company}\nDEPARTURE DATE: #{date}\nDEPARTURE TIME: #{schedule.time}\nDESTINATION: #{route.start_route} to #{route.end_route}"
  end
end
