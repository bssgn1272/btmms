defmodule BusTerminalSystem.Napsa.MobileContribution do


  def connect(params) do

      [
        id: params["id"],
        mobile: params["mobile"],
        amount: params["amount"],
        mno: params["mno"]
      ] |> submit_request
  end



#  defp submit_request(params) do
#    IO.inspect("10.10.1.114:8092/apis/external/v1/ecispayment/?id=341565/43/1&mno=ZAMTEL&mobile=260950773797&amount=1")
#    HTTPoison.get("10.10.1.114:8092/apis/external/v1/ecispayment/?id=341565/43/1&mno=ZAMTEL&mobile=260950773797&amount=1", [], [recv_timeout: 200_000, timeout: 200_000]) |> IO.inspect |> case do
#       {status, %HTTPoison.Response{body: body, status_code: status_code}} ->
#         body |> Poison.decode!
#       {_status, %HTTPoison.Error{reason: reason}} ->
#         %{"message" => reason}
#     end
#  end

  defp submit_request(params) do
    IO.inspect(params)
    HTTPoison.get("http://10.10.1.114:8092/apis/external/v1/ecispayment", [], [params: params, recv_timeout: 200_000, timeout: 200_000]) |> IO.inspect |> case do
       {status, %HTTPoison.Response{body: body, status_code: status_code}} ->
         body |> Poison.decode!
       {_status, %HTTPoison.Error{reason: reason}} ->
         %{"message" => reason}
     end
  end

end