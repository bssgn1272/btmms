defmodule BusTerminalSystem.NapsaSmsGetway do

  def send_sms(phone,message) do

    Task.async(fn ->
      HTTPoison.get("http://196.46.196.38:13013/napsamobile/pushsms",[],
      params: %{
        smsc: "zamtelsmsc",
        username: "napsamobile",
        password: "napsamobile@kannel",
        from: "BTMMS",
        to: phone,
        text: message
        }
      )
    end)
  end
end
