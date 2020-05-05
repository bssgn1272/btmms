defmodule BusTerminalSystemWeb.MarketerController do
  use BusTerminalSystemWeb, :controller

  alias BusTerminalSystem.MarketManagement
  alias BusTerminalSystem.MarketManagement.Marketer
  alias BusTerminalSystem.Market.MarketRepo

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
    # marketers = MarketManagement.list_marketers()
    # render(conn, "index.html", marketers: marketers)
    render(conn, "index.html",[
      markets: MarketRepo.market_list(),
      sections: MarketRepo.market_section_list(),
      shops: MarketRepo.market_shop_list()]
    )
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


  def market_create_actions(conn, %{"action_type" => action_type} = params) do
    IO.inspect(params)
    action_type
    |> case do
       "create_market" ->
         MarketRepo.market_create(params)
         |> case do
              {:ok,_market} -> conn |> put_flash(:info, "Market Created") |> redirect(to: Routes.marketer_path(conn, :index))
              {:error,_market} -> conn |> put_flash(:error, "Failed to Create Market") |> redirect(to: Routes.marketer_path(conn, :index))
            end
       "create_section" ->
         MarketRepo.market_section_create(params)
         |> case do
              {:ok,_section} -> conn |> put_flash(:info, "Section Created") |> redirect(to: Routes.marketer_path(conn, :index))
              {:error,_section} -> conn |> put_flash(:error, "Failed to Create Section") |> redirect(to: Routes.marketer_path(conn, :index))
            end
       "create_shop" ->
         MarketRepo.market_shop_create(params)
         |> case do
              {:ok,_shop} -> conn |> put_flash(:info, "Shop Created") |> redirect(to: Routes.marketer_path(conn, :index))
              {:error,_shop} -> conn |> put_flash(:error, "Failed to Create Shop") |> redirect(to: Routes.marketer_path(conn, :index))
            end
       _ -> conn |> put_flash(:error, "Invalid Action Type") |> redirect(to: Routes.marketer_path(conn, :index))
     end
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

  def standallocation(conn, _params) do
    render(conn, "stand_allocation.html",[marketeers: MarketRepo.market_users()])
  end
end
