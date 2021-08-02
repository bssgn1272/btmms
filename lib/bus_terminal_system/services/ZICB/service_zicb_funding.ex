defmodule BusTerminalSystem.Service.Zicb.Funding do
  @moduledoc false

  alias BusTerminalSystem.Settings
  alias BusTerminalSystem.AccountManager.User
  alias Ecto.Multi
  alias BusTerminalSystem.Database.View.Transactions
  alias Ecto.Multi


  def post_ticket_transactions() do
    if Settings.find_by(key: "BANK_ENABLE_TICKET_POSTING").value == "TRUE" do
      Transactions.where([status: "PENDING"])
      |> Enum.each(fn transaction ->
        BusTerminalSystem.Database.Tables.Transactions.update(BusTerminalSystem.Database.Tables.Transactions.find(transaction.id), [status: "PROCESSING"])
        |> case do
             {:ok, txn} -> post_ticket_to_bank(transaction)
             {:error, _} -> %{}
           end

      end)
    end

  end

  defp post_ticket_to_bank(transaction) do
    bank_request = (fn txn ->

      if txn.destAcc != "NOT USED" do
        %{
          "service" => "BNK9940",
          "type" => "DEST",
          "request" => %{
            "destAcc" => txn.destAcc,
            "destBranch" => txn.destBranch,
            "amount" => txn.amount,
            "payDate" => txn.payDate,
            "payCurrency" => txn.payCurrency,
            "remarks" => txn.remarks,
            "referenceNo" => txn.referenceNo,
            "transferRef" => txn.transferRef
          }
        }
      else
        %{
          "service" => "BNK9941",
          "type" => "SRC",
          "request" => %{
            "srcAcc" => txn.srcAcc,
            "srcBranch" => txn.srcBranch,
            "amount" => txn.amount,
            "payDate" => txn.payDate,
            "srcCurrency" => txn.srcCurrency,
            "remarks" => txn.remarks,
            "referenceNo" => txn.referenceNo,
            "transferRef" => txn.transferRef
          }
        }
      end

    end)
    txn_request = bank_request.(transaction)
    bank_response = (fn req -> if req["type"] == "SRC", do: req |> Poison.encode!() |> http(Settings.find_by(key: "BANK_SETTLEMENT_AUTH_KEY").value), else: req |> Poison.encode!() |> http(Settings.find_by(key: "BANK_COLLECTIONS_AUTH_KEY").value)  end)
#    bank_response = txn_request |> Poison.encode!() |> http(Settings.find_by(key: "BANK_SETTLEMENT_AUTH_KEY").value)
      bank_response = bank_response.(txn_request)
      txn_response = bank_response["response"]["txn"]

        if txn_request["type"] == "SRC" and txn_response["tekHeader"]["status"] == "SUCCESS" do
          [account] = bank_response["response"]["srcAcc"]["response"]["accountList"]
          [dest_account] = bank_response["response"]["destAcc"]["response"]["accountList"]


          Ecto.Multi.new()
          |> Multi.update(:account, Ecto.Changeset.change(User.find_by(id: transaction.user_id), %{bank_account_balance: Decimal.new(account["availablebalance"]) |> Decimal.to_float}))
          |> BusTerminalSystem.Repo.transaction
          |> case do
               {:ok, _} ->
                 %{
                   :hostrefno => txn_response["tekHeader"]["hostrefno"],
                   :status => txn_response["tekHeader"]["status"]
                 } |> update_transaction(transaction)
               {:errot, _} ->
                 %{
                   :status => "PENDING"
                 }
                 |> update_transaction(transaction)
             end
        else
          [account] = bank_response["response"]["destAcc"]["response"]["accountList"]
          [src_account] = bank_response["response"]["srcAcc"]["response"]["accountList"]
          Ecto.Multi.new()
          |> Multi.update(:account, Ecto.Changeset.change(User.find_by(id: transaction.user_id), %{bank_account_balance: Decimal.new(account["availablebalance"]) |> Decimal.to_float}))
          |> BusTerminalSystem.Repo.transaction
          |> case do
               {:ok, _} ->
                 %{
                   :hostrefno => txn_response["tekHeader"]["hostrefno"],
                   :status => txn_response["tekHeader"]["status"]
                 } |> update_transaction(transaction)
               {:errot, _} ->
                 %{
                   :status => "PENDING"
                 }
                 |> update_transaction(transaction)
             end
        end
  end

  def update_transaction(updates, transaction) do
    Multi.new()
    |> Multi.update(:transaction, Ecto.Changeset.change(BusTerminalSystem.Database.Tables.Transactions.find(transaction.id), updates))
    |> BusTerminalSystem.Repo.transaction
    |> case do
         {:ok, %{:transaction => transaction}} ->

           %{:status => "SUCCESS", :message => "Transaction Complete", :transaction => %{}}
         {:error, message} -> %{:status => "FAILED", :message => "Transaction Failed", :transaction => %{}}
       end
  end
  
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
    } |> Poison.encode!()

        %{
          "status" => "PENDING"
        }
        |> Map.merge(args)
        args |> transaction

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
    } |> Poison.encode!()

        %{
          "status" => "PENDING"
        }
        |> Map.merge(args)
         args |> transaction

  end


  def transaction(params) do

    Multi.new()
    |> Multi.insert(:transaction, Map.merge(%BusTerminalSystem.Database.Tables.Transactions{}, (for {key, val} <- params, into: %{}, do: {String.to_atom(key), val})))
    |> BusTerminalSystem.Repo.transaction
    |> case do
         {:ok, %{:transaction => transaction}} ->
           %{:status => "SUCCESS", :message => "Transaction Complete", :transaction => %{}}
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
              _ -> raise "An Internal Error Occurred (ERR 4-001)"
            end
        end
      {_status, %HTTPoison.Error{reason: reason}} ->
        %{"message" => reason}
    end
  end



  def wallet_create_account(args) do
    request = %{
      "service" => "ZB0631",
      "request" => %{
        "firstName" => args.first_name,
        "lastName" => args.last_name,
        "add1" => args.add1,
        "add2" => args.add2,
        "add3" => args.add3,
        "add4" => args.add4,
        "add5" => args.add5,
        "uniqueType" => "NRC",
        "uniqueValue" => args.nrc,
        "dateOfBirth" => args.date_of_birth,
        "email" => args.email,
        "sex" => args.sex,
        "mobileNumber" => args.mobile_number,
        "accType" => "WA",
        "currency" => "ZMW",
        "idFront" => "",
        "idBack" => "",
        "custImg" => "",
        "custSig" => "",
      }
    }

  end

  def wallet_query_by_account_number(args) do
    request = %{
      "service" => "ZB0627",
      "request" => %{
        "accountNos" => args.account_number
      }
    }
  end

  def wallet_query_by_phone_number(args) do
    request = %{
      "service" => "ZB0640",
      "request" => %{
        "mobileNo" => args.mobile_number,
        "accountType" => "WB",
        "isfetchAllAccounts" => false
      }
    }
  end

  def wallet_funds_deposit(args) do
    request = %{
      "service" => "ZB0628",
      "request" => %{
        "destAcc" => args.destination_account,
        "destBranch" => args.destination_branch,
        "amount" => args.amount,
        "payDate" => Timex.today |> to_string,
        "payCurrency" => "ZMW",
        "remarks" => args.remarks,
        "referenceNo" => args.reference_number
      }
    }
  end

  def wallet_funds_withdraw(args) do
    request = %{
      "service" => "ZB0641",
      "request" => %{
        "srcAcc" => args.destination_account,
        "srcBranch" => args.destination_branch,
        "amount" => args.amount,
        "payDate" => Timex.today |> to_string,
        "srcCurrency" => "ZMW",
        "remarks" => args.remarks,
        "referenceNo" => args.reference_number,
        "transferRef" => args.transfer_reference
      }
    }
  end

  def wallet_transact(request) do
    headers = [
      {"Content-Type", "application/json"},
      {"authKey", Settings.find_by(key: "BANK_COLLECTIONS_AUTH_KEY").value}
    ]

    #    try do
    HTTPoison.post(Settings.find_by(key: "BANK_URL").value, request |> Poison.encode!, headers)
    |> case do
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
           {:error, %{"message" => reason}}
       end
    #    rescue
    #      error ->
    #        IO.puts("ZICB_URL: #{"NOT CONFIGURED"}")
    #     end

  end

end