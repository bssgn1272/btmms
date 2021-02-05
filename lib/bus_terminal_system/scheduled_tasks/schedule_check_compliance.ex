defmodule BusTerminalSystem.CheckCompliance do


  def run() do
    BusTerminalSystem.AccountManager.User.all()
    |> Enum.each(fn user ->
      compliance = check_compliance(user.ssn)

      if compliance["http_status"] == 0 do
        if compliance["isCompliant"] == true do
          BusTerminalSystem.AccountManager.User.update(user, [compliance: compliance["isCompliant"]])
        else
          BusTerminalSystem.AccountManager.User.update(user, [compliance: false])
        end
      end
    end)
  end

  defp check_compliance(employer_number, year \\ "2020", month \\ "12") do
    case HTTPoison.get("http://10.10.1.57:5000/api/Compliance/#{employer_number}/#{year}/#{month}") do
      {status, %HTTPoison.Response{body: body, status_code: status_code}} ->
        if status_code == 200, do: body |> Poison.decode!() |> Map.put("http_status", 0), else: %{"http_status" => 1}
      {_status, %HTTPoison.Error{reason: reason}} ->
        %{
          "http_status" => 1,
          "message" => reason
        }
    end
  end
  
end