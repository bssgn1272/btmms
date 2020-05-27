defmodule BusTerminalSystem.APIRequestMockup do

  @request_headers %{
    "Authorization" => "Basic #{"sa" <> "12345" |> Base.encode64}"
  }

  def send(code) do
      params = {:form, [card: code]}
       response = HTTPoison.post("http://10.70.3.55:5000/enable/",params,%{"Content-type" => "multipart/form-data"})
       IO.inspect(response)
  end

  def cosec_add_user(bus_plate_number) do

    #Task.async(fn ->
#      HTTPoison.post("http://10.70.3.55/cosec/api.svc/v2/user",@request_headers,
#        params: %{
#          action: "set",
#          id: bus_plate_number,
#          name: bus_plate_number,
#          short_name: bus_plate_number,
#          active: 1,
#          module: "U"
#        }
#      )

      HTTPoison.get("http://10.70.3.55/cosec/api.svc/v2/user?action=set;id=BAE1234;name=BAE1234;short-name=BAE1234;active=1;module=U",[],@request_headers)
    #end)
  end

end