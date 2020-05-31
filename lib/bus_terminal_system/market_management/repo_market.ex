defmodule BusTerminalSystem.Market.MarketRepo do
  import Ecto.Query, warn: false

  alias BusTerminalSystem.Repo
  alias BusTerminalSystem.Market.{Market,Section,Shop}

  def market_users() do
    Repo.all(from o in BusTerminalSystem.AccountManager.User, where: o.role == ^"MOP")
  end

  def market_create(params), do: %Market{} |> Market.changeset(params) |> Repo.insert()
  def market_list(), do: Repo.all(Market)
  def market_list_by_market_id(id), do: Repo.all(from q in Market, where: q.id == ^id)
  def market_find(query_map), do: Repo.get_by(Market,query_map)
  def market_update(%Market{} = market, attrs), do: market |> Market.changeset(attrs) |> Repo.update()

  def market_section_create(params), do: %Section{} |> Section.changeset(params) |> Repo.insert()
  def market_section_list(), do: Repo.all(Section)
  def market_section_list_by_market_id(id), do: Repo.all(from q in Section, where: q.market_id == ^id)
  def market_section_find(query_map), do: Repo.get_by(Section,query_map)
  def market_section_update(%Section{} = section, attrs), do: section |> Section.changeset(attrs) |> Repo.update()

  def market_shop_create(params), do: %Shop{} |> Shop.changeset(params) |> Repo.insert!()
  def market_shop_list(), do: Repo.all(Shop)
  def market_shop_list_by_market_id(id), do: Repo.all(from q in Shop, where: q.section_id == ^id)
  def market_shop_by_user_id(id), do: Repo.get_by(Shop,maketeer_id: id)
  def market_shop_find(query_map), do: Repo.get_by(Shop,query_map)
  def market_shop_update(%Shop{} = section, attrs), do: section |> Shop.changeset(attrs) |> Repo.update()

  def stand_details(user_id) do
    user_id
    |> market_shop_by_user_id
    |> case do
         nil -> %{}
         assigned_stand ->
           section = market_section_find([id: assigned_stand.section_id])
           market = market_find([id: section.market_id])
           %{
             "stand_number" => assigned_stand.shop_number,
             "stand_price" => assigned_stand.shop_price,
             #"stand_code" => assigned_stand.shop_code,
             #"market_section" => section.section_name,
             #"section_label" => section.section_lable,
             #"market" => market.market_name,
             #"market_location" => market.location,
             #"market_city_town" => market.city_town
           }
         _ -> %{}
     end

  end

  def stand_details_minimal(user_id) do
    user_id
    |> market_shop_by_user_id
    |> case do
         nil -> %{}
         assigned_stand ->
           section = market_section_find([id: assigned_stand.section_id])
           market = market_find([id: section.market_id])

           stand_data = Map.merge(assigned_stand, section)
           stand_data = Map.merge(stand_data, market)
           stand_data
         _ -> nil
       end

  end

end