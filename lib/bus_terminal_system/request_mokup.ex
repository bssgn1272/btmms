defmodule BusTerminalSystem.APIRequestMockup do

    def send(code) do
        params = {:form, [card: code]}
         response = HTTPoison.post("http://192.168.8.90:5000/enable/",params,%{"Content-type" => "multipart/form-data"})
         IO.inspect(response)
    end
    
end