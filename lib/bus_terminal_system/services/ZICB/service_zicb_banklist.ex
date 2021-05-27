defmodule BusTerminalSystem.Service.Zicb.BankList do
  @moduledoc false

  alias BusTerminalSystem.Settings
  alias BusTerminalSystem.Banks

  def list do
    bank_response = %{
      "service" => "BNK9901",
      "request" => %{}
    }
    |> Poison.encode!()
    |> http(Settings.find_by(key: "BANK_AUTH_KEY").value)

    bank_response["response"]["bankList"]

    data = bank_response["response"]["bankList"]

    keys_to_group_by = ["bankName"]
    keys_to_list = ["branchDesc", "sortCode"]

    bank = data
    |> Enum.group_by(&Map.take(&1, keys_to_group_by))
    |> Enum.map(fn {key, values} ->
      keys_to_list |> Enum.map(fn key_to_list ->
        {key_to_list, Enum.map(values, & &1[key_to_list])}
      end) |> Enum.into(key)
    end)

    data |> Enum.map(fn bank ->
      IO.inspect bank
      case Banks.find_by([branchDesc: bank["branchDesc"], sortCode: bank["sortCode"], bicCode: bank["bicCode"], bankCode: bank["bankCode"], bankName: bank["bankName"]]) do
        nil -> Banks.create([cntryCode: bank["cntryCode"], branchDesc: bank["branchDesc"], sortCode: bank["sortCode"], bicCode: bank["bicCode"], bankCode: bank["bankCode"], bankName: bank["bankName"]])
        bank -> %{}
      end


    end)

    bank_sc_list = bank |> Enum.map(fn bank ->
      bank["branchDesc"]
      |> Enum.zip(bank["sortCode"])
      |> Enum.map(fn {branch, sort_code} ->
        ["#{branch} - #{sort_code}"]
      end) |> List.flatten
    end)

    bank |> Enum.zip(bank_sc_list) |> Enum.map(fn {bank, scl} ->
      Map.put(bank, "mapping", scl)
    end)


  end

  def bank(search_bank) do
    bank_response = %{
                      "service" => "BNK9901",
                      "request" => %{}
                    }
                    |> Poison.encode!()
                    |> http(Settings.find_by(key: "BANK_AUTH_KEY").value)

    bank_response["response"]["bankList"]

    data = bank_response["response"]["bankList"]

    keys_to_group_by = ["bankName"]
    keys_to_list = ["branchDesc", "sortCode"]

    bank = data
           |> Enum.group_by(&Map.take(&1, keys_to_group_by))
           |> Enum.map(fn {key, values} ->
      keys_to_list |> Enum.map(fn key_to_list ->
        {key_to_list, Enum.map(values, & &1[key_to_list])}
      end) |> Enum.into(key)
    end)

    bank_sc_list = bank |> Enum.map(fn bank ->
      bank["branchDesc"]
      |> Enum.zip(bank["sortCode"])
      |> Enum.map(fn {branch, sort_code} ->
        ["#{branch} - #{sort_code}"]
      end) |> List.flatten
    end)

    bank |> Enum.map(fn bank ->

    end)

    bank |> Enum.zip(bank_sc_list) |> Enum.map(fn {bank, scl} ->
      Map.put(bank, "mapping", scl)
    end) |> Enum.find(fn bank ->
      bank["bankName"] == search_bank
    end)


  end

  defp http(request, account) do

    headers = [
      {"Content-Type", "application/json"},
      {"authKey", account},
    ]

    case HTTPoison.post(Settings.find_by(key: "BANK_SECONDARY_URL").value, request,  headers, [recv_timeout: 200_000, timeout: 200_000, hackney: [:insecure]])
      do
      {status, %HTTPoison.Response{body: body, status_code: _status_code}} ->
        case status do
          :ok ->
            try do
              body |> Poison.decode!()
            rescue
              _ -> raise "An Internal Error Occurred (ERR 4-001)"
            end
        end
      {_status, %HTTPoison.Error{reason: reason}} ->
        %{"message" => reason}
    end
  end

end