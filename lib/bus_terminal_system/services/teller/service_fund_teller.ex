defmodule BusTerminalSystem.Service.Teller.FundTeller do

  def index(_conn, _params) do
    %{status: 0, message: "Successfully Funded Teller"}
  end
end