defmodule BusTerminalSystem.Service.Zicb.AccountOpening do
  @moduledoc false

  import Ecto.Query, warn: false

  alias BusTerminalSystem.Settings
  alias BusTerminalSystem.AccountManager.User
  alias Ecto.Multi


  def run() do
    if Settings.find_by(key: "BANK_ENABLE_ACCOUNT_OPENING_TASK").value == "TRUE" do
      query = from u in User, where: (u.role == "TOP" or u.role == "ADMIN") and u.auth_status == true and u.account_number == "00000000"
      User.where(query)
#      User.where(from u in User, where: (u.role == "TOP" or u.role == "ADMIN") )
      |> Enum.each(fn user ->
        bank_response = query_account_by_mobile(user.mobile, user)
        if bank_response == %{} do
          parse_date = (fn date_string ->
            try do
              [day, month, year] = String.split(date_string," ")
              "#{year}-#{Timex.month_to_num(month) |> to_string |> String.pad_leading(2,"0")}-#{day}"
            rescue
              _ -> date_string
            end
          end)

          teller_details = %{
            "firstName" => user.first_name,
            "lastName" => user.last_name,
            "uniqueValue" => user.nrc,
            "dateOfBirth" => parse_date.(user.dob),
            "email" => user.email,
            "sex" => user.sex,
            "mobileNumber" => user.mobile,
          }
          create_wallet(teller_details, user)

        else

        end
      end)
    end
  end

  def query_account_by_mobile(mobile_number, user) do
    %{
      "service" => "ZB0640",
      "request" => %{
         "mobileNo" => mobile_number,
         "accountType" => "WB",
         "isfetchAllAccounts" => false
      }
    }
    |> Poison.encode!()
    |> http()
    |> query_by_account(user)

  end

  @wallet_creation_params %{
    "firstName" => :string,
    "lastName" => :string,
#    "add1" => :string,
    "uniqueValue" => :string,
    "dateOfBirth" => :string,
    "email" => :string,
    "sex" => :string,
    "mobileNumber" => :string,
  }

  def create_wallet(args, user) do
    case Skooma.valid?(args, @wallet_creation_params) do
      :ok ->
        %{
          "service" => Settings.find_by(key: "BANK_PROXY_ACCOUNT_OPENING_SERVICE_CODE").value,
          "request" => %{
            "firstName" => args["firstName"],
            "lastName" => args["lastName"],
            "add1" => "",
            "add2" => "",
            "add3" => "",
            "add4" => "",
            "add5" => "",
            "uniqueType" => Settings.find_by(key: "BANK_ACCOUNT_OPENING_UNIQUE_TYPE").value,
            "uniqueValue" => args["uniqueValue"],
            "dateOfBirth" => args["dateOfBirth"],
            "email" => args["email"],
            "sex" => args["sex"],
            "mobileNumber" => args["mobileNumber"],
            "accType" => Settings.find_by(key: "BANK_ACCOUNT_OPENING_TYPE").value,
            "currency" => Settings.find_by(key: "BANK_ACCOUNT_OPENING_CURRENCY").value,
            "idFront" => "",
            "idBack" => "",
            "custImg" => "",
            "custSig" => ""
          }
        }
        |> Poison.encode!()
        |> http()


        query_account_by_mobile(args["mobileNumber"], user)
        |> account_balance_inquiry2(user)

#      {:error, message} -> {:error, message}
    end

    
  end

  def account_balance_inquiry2(aq_response, user) do


      if aq_response == %{} do
        aq_response
      else
        [details] = aq_response["response"]["custAccDetails"]
        response = %{
                     "service" => "ZB0629",
                     "request" => %{
                       "accountNos" =>  details["accountNo"],
                       "serviceKey" => Settings.find_by(key: "BANK_AUTH_SERVICE_KEY").value
                     }
                   } |> Poison.encode!() |> http()

        [response] = response["response"]["accountList"]
        #      account_number = response["accountnos"]

        Ecto.Multi.new()
        |> Multi.update(:account, Ecto.Changeset.change(user, %{account_number: response["accountno"], bank_account_balance: Decimal.new(response["availablebalance"]) |> Decimal.to_float}))
        |> BusTerminalSystem.Repo.transaction
        |> case do
             {:ok, _} -> %{}
             {:errot, _} -> %{}
           end
      end




  end

  def query_by_account(response, user) do

    if response["operation_status"] == "FAIL" do
      %{}
    else
      [details] = response["response"]["custAccDetails"]

#      response = account_balance_inquiry2(details["accountNo"])


      response =  %{
        "service" => "ZB0640",
        "request" => %{
          "mobileNo" => user.mobile,
          "accountType" => "WB",
          "isfetchAllAccounts" => false
        }
      } |> Poison.encode!() |> http()


      Map.merge(details, response)
      account_balance_inquiry2(response, user)
    end

  end
  

  defp http(request) do
    headers = [
      {"Content-Type", "application/json"},
      {"authKey", Settings.find_by(key: "BANK_AUTH_KEY").value},
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
  
end