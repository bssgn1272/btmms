defmodule BusTerminalSystem.Service.Teller.SweepFund do

  def index(_conn, _params) do
    %{status: 0, message: "Successfully Swept Teller Funds"}
  end
end