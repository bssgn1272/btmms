defmodule BusTerminalSystem.CheckCompliance do

  alias BusTerminalSystem.Settings

  def run() do
    if Settings.find_by(key: "NAPSA_COMPLIANCE_SERVICE").value == "TRUE" do
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

  end

  defp check_compliance(employer_number, year \\ previous_month(Timex.today).year |> to_string,
         month \\ previous_month(Timex.today).month |> to_string |> String.pad_leading(2, "0")
       ) do

    result = HTTPoison.get("http://10.10.1.57:5000/api/Compliance/#{employer_number}/#{year}/#{month}")
#    IO.inspect(result)
    case result do
      {status, %HTTPoison.Response{body: body, status_code: status_code}} ->
        if status_code == 200, do: body |> Poison.decode!() |> Map.put("http_status", 0), else: %{"http_status" => 1}
      {_status, %HTTPoison.Error{reason: reason}} ->
        %{
          "http_status" => 1,
          "message" => reason
        }
    end
  end

  def previous_month(%Date{day: day} = date) do
    days = max(day, (Date.add(date, -day)).day)
    Date.add(date, -days)
  end
  
end