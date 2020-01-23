defmodule ScaleDriverWeb.Router do
  use ScaleDriverWeb, :router

  pipeline :api do
    plug :accepts, ["json"]
  end

  scope "/v1", ScaleDriverWeb do
    pipe_through :api

    get "/driver/weight", DriverController, :read_scale

  end
end
