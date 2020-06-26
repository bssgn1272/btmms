defmodule BusTerminalSystem.NapsaSmsGetway do

  def send_sms(phone,message) do

    Task.async(fn ->
      response = HTTPoison.get("http://10.10.1.43:13013/napsamobile/pushsms",[],
      params: %{
        smsc: "zamtelsmsc",
        username: "napsamobile",
        password: "napsamobile@kannel",
        from: "NAPSA",
        to: phone,
        text: message
        }
      )
      IO.inspect("--------------------------START SMS STATUS----------------------------------")
      IO.inspect(response)
      IO.inspect("--------------------------END SMS STATUS----------------------------------")
    end)
  end

  def send_ticket_sms(phone,ticket) do

    spawn(fn ->

      bus = BusTerminalSystem.BusManagement.Bus.find_by(id: ticket.bus_no)
      schedule = BusTerminalSystem.TblEdReservations.find_by(id: ticket.bus_schedule_id)
      [date, _] = schedule.reserved_time |> to_string |> String.split(" ")
      [year, month, day] = date |> String.split("-")
      date = "#{day}-#{month}-#{year}"

      sms_message = "Hello #{ticket.first_name} #{ticket.last_name}, Ticket Purchase was successful.\n\nTICKET ID: #{ticket.id}\nBUS: #{bus.company}\nDEPARTURE DATE: #{date}\nDEPARTURE TIME: #{schedule.time}"

      Task.async(fn ->
        response = HTTPoison.get("http://196.46.196.38:13013/napsamobile/pushsms",[],
          params: %{
            smsc: "zamtelsmsc",
            username: "napsamobile",
            password: "napsamobile@kannel",
            from: "NAPSA",
            to: phone,
            text: sms_message
          }
        )
        IO.inspect("--------------------------START SMS STATUS----------------------------------")
        IO.inspect(response)
        IO.inspect("--------------------------END SMS STATUS----------------------------------")
      end)

    end)


  end
end
