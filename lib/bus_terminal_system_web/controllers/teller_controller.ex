defmodule BusTerminalSystemWeb.TellerController do
  use BusTerminalSystemWeb, :controller

  @form_default ""
  @form_enable_teller "4fmiiEabJx9REm8HwTzryIzyXyN5WEFE-TfpOce0AtF4k6QGpK"
  @form_disable_teller "o-O0uvssIvTRqCQws1J6dSfuYmS-nnrK5qz-pn3D9RooOrsbc5"
  @form_get_teller_details "qvtgAo2H-KgWD3orJsCX1Pvqp42OsWPkps09slGOjrMHVkKsRf"
  @form_fund_teller "TiqnDuGp8-dG0WY2nWzMxi2CXV0GF8ivD-4ocEhRA_w-CR1uqx"
  @form_sweep_teller_fund "ELX-qA1yK3jeb6RTlaOoPDlCpapxdRcds0MiXvlZpqqjaNjwQU"

  def redirect_process(conn, %{"view" => render_view, "sigma" => form_name} = params) do
    view = (fn fn_conn, fn_params, view_name->
      view_name
      |> case do
           @form_default -> IO.inspect()
         end
       end)

    form = (fn conn, params, form_name ->
      form_name
      |> case do
           @form_enable_teller -> form_enable_teller(conn, params)
           @form_disable_teller -> form_disable_teller(conn, params)
           @form_get_teller_details ->  form_get_teller_details(conn, params)
           @form_fund_teller ->  form_fund_teller(conn, params)
           @form_sweep_teller_fund ->  form_sweep_teller_fund(conn, params)
         end
       end)

    case conn.method do
      "GET" -> view.(conn, params, render_view)
      "POST" -> form.(conn, params, form_name)
      _ -> render(conn, "index.html", roles: BusTerminalSystem.UserRoles.all())
    end
  end

  defp form_enable_teller(conn, params), do: json(conn, BusTerminalSystem.Service.User.EnableUser.index(conn, params))

  defp form_disable_teller(conn, params), do: json(conn, BusTerminalSystem.Service.User.DisableUser.index(conn, params))

  defp form_fund_teller(conn, params), do: json(conn, BusTerminalSystem.Service.Teller.FundTeller.index(conn, params))

  defp form_sweep_teller_fund(conn, params), do: json(conn, BusTerminalSystem.Service.Teller.SweepFund.index(conn, params))

  defp form_get_teller_details(conn, params), do: json(conn, BusTerminalSystem.Service.Teller.GetTellDetails.index(conn, params))

  def index(conn, _params) do
    render(conn, "index.html")
  end

  def documentation(conn, _params) do
    render(conn, "documentation.html")
  end

  def reports(conn, _params) do
    render(conn, "reports.html")
  end

  def till_teller_manage(conn, params) do
    render(conn, "till_teller_manage.html",
      transactions: BusTerminalSystem.Database.Tables.Transactions.all(),
      teller: BusTerminalSystem.AccountManager.User.where(role: "TOP"))
  end


  def deposit(conn, params) do

    ref = Timex.now |> Timex.to_unix |> to_string

    extras = %{
      "referenceNo" => ref,
      "transferRef" => ref,
    }

    params = params |> Map.merge(extras)
    conn |> json(BusTerminalSystem.Service.Zicb.Funding.deposit(params))
  end

  def deposit do
    params = %{
      "destAcc" => "1019000001189",
      "destBranch" => "101",
      "amount" => "1",
      "payDate" => "2019-08-16",
      "payCurrency" => "ZMW",
      "remarks" => "API test2",
      "request_reference" => "",
      "service" => "",
      "op_description" => "",
      "referenceNo" => Timex.now |> Timex.to_unix |> to_string
    }
    BusTerminalSystem.Service.Zicb.Funding.deposit(params)
  end

  def withdraw do
  params = %{
    "srcAcc" => "1019000001189",
    "srcBranch" => "101",
    "amount" => "1.0",
    "payDate" => "2019-08-16",
    "srcCurrency" => "ZMW",
    "remarks" => "Debit 5.0 From ",
    "referenceNo" => "28249160848",
    "transferRef" => "54445423232321",
    "request_reference" => "",
    "service" => "",
    "op_description" => "",
  } |> BusTerminalSystem.Service.Zicb.Funding.withdraw()
end

  def withdraw(conn, params) do
    conn |> json(BusTerminalSystem.Service.Zicb.Funding.withdraw(params))
  end

end
