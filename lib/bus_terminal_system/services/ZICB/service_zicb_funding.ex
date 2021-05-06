defmodule BusTerminalSystem.Service.Zicb.Funding do
  @moduledoc false

  alias BusTerminalSystem.Settings
  alias BusTerminalSystem.AccountManager.User
  alias Ecto.Multi
  alias BusTerminalSystem.Database.Tables.Transactions

  def withdraw(args) do
    bank_response = %{
      "service" => "ZB0641",
      "request" => %{
        "srcAcc" => args["srcAcc"],
        "srcBranch" => args["srcBranch"],
        "amount" => args["amount"],
        "payDate" => args["payDate"],
        "srcCurrency" => args["srcCurrency"],
        "remarks" => args["remarks"],
        "referenceNo" => args["referenceNo"],
        "transferRef" => args["transferRef"]
      }
    } |> Poison.encode!() |> http(Settings.find_by(key: "BANK_AUTH_KEY").value) |> IO.inspect

    try do
      if bank_response["response"]["tekHeader"]["status"] == "SUCCESS" do
        %{
          "hostrefno" => bank_response["response"]["tekHeader"]["hostrefno"],
          "status" => bank_response["response"]["tekHeader"]["status"]
        }
        |> Map.merge(args)
        |> transaction
      else
        %{
          "status" => bank_response["tekHeader"]["status"]
        }
        |> Map.merge(args)
        |> transaction
      end
    rescue
      _ -> %{:status => "FAILED", :message => "Bank Connection Failed", :transaction => %{}}
    end


  end

  def deposit(args) do
    bank_response = %{
      "service" => "ZB0628",
      "request" => %{
        "destAcc" => args["destAcc"],
        "destBranch" => args["destBranch"],
        "amount" => args["amount"],
        "payDate" => args["payDate"],
        "payCurrency" => args["payCurrency"],
        "remarks" => args["remarks"],
        "referenceNo" => args["referenceNo"],
        "transferRef" => args["transferRef"]
      }
    } |> Poison.encode!() |> http(Settings.find_by(key: "BANK_AUTH_KEY").value)

    try do
      if bank_response["response"]["tekHeader"]["status"] == "SUCCESS" do
        %{
          "hostrefno" => bank_response["response"]["tekHeader"]["hostrefno"],
          "status" => bank_response["response"]["tekHeader"]["status"]
        }
        |> Map.merge(args)
        |> transaction
      else
        %{
          "status" => bank_response["tekHeader"]["status"]
        }
        |> Map.merge(args)
        |> transaction
      end
    rescue
      _ -> %{:status => "FAILED", :message => "Bank Connection Failed", :transaction => %{}}
    end

  end

  def transaction(params) do

    Multi.new()
    |> Multi.insert(:transaction, Map.merge(%Transactions{}, (for {key, val} <- params, into: %{}, do: {String.to_atom(key), val})))
    |> BusTerminalSystem.Repo.transaction
    |> case do
         {:ok, %{:transaction => transaction}} ->
           BusTerminalSystem.Service.Zicb.AccountOpening.run()
           %{:status => transaction.status, :message => "Transaction Complete", :transaction => %{}}
          {:error, message} -> %{:status => "FAILED", :message => "Transaction Failed", :transaction => %{}}
       end

  end

  defp http(request, account) do
    headers = [
      {"Content-Type", "application/json"},
      {"authKey", account},
    ]

    case HTTPoison.post(Settings.find_by(key: "BANK_URL").value, request,  headers, [recv_timeout: 200_000, timeout: 200_000, hackney: [:insecure]]) do
      {status, %HTTPoison.Response{body: body, status_code: _status_code}} ->
        case status do
          :ok ->
            try do
              body |> Poison.decode!()
            rescue
              _ -> %{ "response" => "An Internal Error Occurred (ERR 4-001)" }
            end
        end
      {_status, %HTTPoison.Error{reason: reason}} ->
        %{"message" => reason}
    end
  end

end