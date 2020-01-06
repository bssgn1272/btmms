defmodule BusTerminalSystemWeb.MarketerController do
  use BusTerminalSystemWeb, :controller

  alias BusTerminalSystem.MarketManagement
  alias BusTerminalSystem.MarketManagement.Marketer

  plug(
    BusTerminalSystemWeb.Plugs.RequireAuth
    when action in [
           :index,
           :new,
           :create,
           :show,
           :edit,
           :update,
           :delete
         ]
  )

  def index(conn, _params) do
    marketers = MarketManagement.list_marketers()
    render(conn, "index.html", marketers: marketers)
  end

  def new(conn, _params) do
    # changeset = MarketManagement.changeset(%MarketerManagement{}, %{})
    changeset = MarketManagement.change_marketer(%Marketer{})
    render(conn, "new.html", changeset: changeset)
  end

  def create(conn, %{"marketer" => marketer_params}) do
    case MarketManagement.create_marketer(marketer_params) do
      {:ok, marketer} ->
        conn
        |> put_flash(:info, "Marketer created successfully.")
        |> redirect(to: Routes.marketer_path(conn, :show, marketer))

      {:error, %Ecto.Changeset{} = changeset} ->
        render(conn, "new.html", changeset: changeset)
    end
  end

  def show(conn, %{"id" => id}) do
    marketer = MarketManagement.get_marketer!(id)
    render(conn, "show.html", marketer: marketer)
  end

  def edit(conn, %{"id" => id}) do
    marketer = MarketManagement.get_marketer!(id)
    changeset = MarketManagement.change_marketer(marketer)
    render(conn, "edit.html", marketer: marketer, changeset: changeset)
  end

  def update(conn, %{"id" => id, "marketer" => marketer_params}) do
    marketer = MarketManagement.get_marketer!(id)

    case MarketManagement.update_marketer(marketer, marketer_params) do
      {:ok, marketer} ->
        conn
        |> put_flash(:info, "Marketer updated successfully.")
        |> redirect(to: Routes.marketer_path(conn, :show, marketer))

      {:error, %Ecto.Changeset{} = changeset} ->
        render(conn, "edit.html", marketer: marketer, changeset: changeset)
    end
  end

  def delete(conn, %{"id" => id}) do
    marketer = MarketManagement.get_marketer!(id)
    {:ok, _marketer} = MarketManagement.delete_marketer(marketer)

    conn
    |> put_flash(:info, "Marketer deleted successfully.")
    |> redirect(to: Routes.marketer_path(conn, :index))
  end

  def form_market(conn, _params) do
    render(conn, "form_market.html")
  end

  def form_section(conn, _params) do
    render(conn, "form_section.html")
  end

  def form_shop(conn, _params) do
    render(conn, "form_shop.html")
  end
end
