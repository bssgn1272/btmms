defmodule BusTerminalSystemWeb.MakerCheckerController do
  use BusTerminalSystemWeb, :controller
  alias BusTerminalSystem.MakerCheckModule, as: MakerChecker
  @moduledoc """
    maker checker module
  """

  @controller_code 400

  def index(conn, _params) do
    conn
    |> render("index.html", list: MakerChecker.maker_checker() |> IO.inspect(label: "PENDING LIST"))
  end

  def view(conn, params) do
    json(conn, conn |> MakerChecker.checker_view(params))
  end

  def approve(conn, params) do
    MakerChecker.approve(conn, params)
    |> case do
         {:ok, message} ->
           conn
           |> put_flash(:info, message)
           |> redirect(to: Routes.maker_checker_path(conn, :index))
         {:error, message} ->
           conn
           |> put_flash(:error, message)
           |> redirect(to: Routes.maker_checker_path(conn, :index))
       end
  end

  def reject(conn, params) do

    #Add the email code in the service below

    MakerChecker.reject(conn, params)
    |> case do
         {:ok, message} ->
           conn
           |> put_flash(:info, message)
           |> redirect(to: Routes.maker_checker_path(conn, :index))
         {:error, message} ->
           conn
           |> put_flash(:error, message)
           |> redirect(to: Routes.maker_checker_path(conn, :index))
       end
  end

  def update(conn, params) do
    MakerChecker.update(conn, params)
    |> case do
         {:ok, message} -> %{}
         {:error, message} -> %{}
       end
  end
end