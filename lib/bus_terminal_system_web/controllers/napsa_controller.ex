defmodule BusTerminalSystemWeb.NapsaController do
  use BusTerminalSystemWeb, :controller

  @valid_contribution_params %{

      "provider_id"  => :string,
      "employer_account_number"  => :string,
      "year"  => :string,
      "month"  => :string,
      "principal_amount"  => :string,
      "penalty_amount"  => :string,
      "total_amount"  => :string,
      "number_of_employees"  => :string,

      "ssn" => :string,
      "national_id" => :string,
      "surname" => :string,
      "firstname" => :string,
      "othername" => :string,
      "date_of_birth" => :string,
      "gross_wage" => :string,
      "employee_share" => :string,
      "employer_share" => :string,
      "siebel_id" => :string

  }
  def return_upload(conn, params \\ %{}) do
    Skooma.valid?(params, @valid_contribution_params) |> case do
      :ok ->
        result = BusTerminalSystem.Napsa.NapsaContribution.connect(conn, params)["{http://schemas.xmlsoap.org/soap/envelope/}Envelope"]["{http://schemas.xmlsoap.org/soap/envelope/}Body"]["ResultWithRef"]
        json(conn, %{status: 0, response: result})
       {:error, error_message} ->
         [message] = error_message
         json(conn, %{status: 1, response: message})
    end
  end

  @deposit_params %{"id" => :string, "mno" => :string, "mobile" => :string, "amount" => :string}
  def contribute(conn, params) do
    Skooma.valid?(params, @deposit_params) |> case do
      :ok ->
        result = BusTerminalSystem.Napsa.MobileContribution.connect(params)
        json(conn, %{status: 0, response: result})
      {:error, error_message} ->
        [message] = error_message
        json(conn, %{status: 1, response: message})
    end
  end

  @user_details_params %{"id" => :string}
  def search_member(conn, params) do
    Skooma.valid?(params, @user_details_params) |> case do
      :ok ->
        result = BusTerminalSystem.Napsa.NapsaQueryDetails.connect(params)
        json(conn, %{status: 0, response: result})
      {:error, error_message} ->
        [message] = error_message
        json(conn, %{status: 1, response: message})
    end
  end

  def register_member(conn, params) do
    IO.inspect(params)
    conn |> json(BusTerminalSystem.Napsa.RegisterMember.connect(params))
  end
  
end