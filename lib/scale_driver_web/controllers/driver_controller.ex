defmodule ScaleDriverWeb.DriverController do
  use ScaleDriverWeb, :controller

  alias ScaleDriver.Driver

  def read_scale(conn,_params) do
    conn
    |> json(%{ "weight" => Driver.get_weight })
  end

end
