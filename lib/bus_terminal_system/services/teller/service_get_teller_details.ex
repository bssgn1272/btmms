defmodule BusTerminalSystem.Service.Teller.GetTellDetails do

  def index(_conn, _params) do
    %{status: 0, message: "Successfully got Teller details",
      details: %{name: "James", account: "0000 0009 0089 0202", till_balance: 2000, wallet_balance: 3000}}
  end
end