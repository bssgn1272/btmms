defmodule BusTerminalSystem.RepoManager do

  import Ecto.Query, warn: false

  alias BusTerminalSystem.Repo

  alias BusTerminalSystem.Market
  alias BusTerminalSystem.Section
  alias BusTerminalSystem.Hub
  alias BusTerminalSystem.Tenant

  alias BusTerminalSystem.Terminus
  alias BusTerminalSystem.Station
  alias BusTerminalSystem.Gate
  alias BusTerminalSystem.TravelRoutes

  alias BusTerminalSystem.TicketManagement.Ticket

  #-------- LIST REPO ------------////////////

  # TENANTS
  def get_tenant(id), do: Repo.get!(Tenant, id)
  def list_tenants(), do: Repo.all(Tenant)
  def create_tenant(attrs \\ %{}), do: %Tenant{} |> Tenant.changeset(attrs) |> Repo.insert()
  def update_tenant(%Tenant{} = tenant, attrs), do: tenant |> Tenant.changeset(attrs) |> Repo.update()
  def delete_tenant(%Tenant{} = tenant), do: Repo.delete(tenant)

  # HUBS
  def get_hub(id), do: Repo.get!(Hub, id)
  def list_hubs(), do: Repo.all(Hub)
  def create_hub(attrs \\ %{}), do: %Hub{} |> Hub.changeset(attrs) |> Repo.insert()
  def update_hub(%Hub{} = hub, attrs), do: hub |> Hub.changeset(attrs) |> Repo.update()
  def delete_hub(%Hub{} = hub), do: Repo.delete(hub)

  # SECTIONS
  def get_section(id), do: Repo.get!(Section, id)
  def list_sections(), do: Repo.all(Section)
  def create_sections(attrs \\ %{}), do: %Section{} |> Section.changeset(attrs) |> Repo.insert()
  def update_hub(%Section{} = section, attrs), do: section |> Hub.changeset(attrs) |> Repo.update()
  def delete_hub(%Section{} = section), do: Repo.delete(section)

  # MARKETS
  def get_market(id), do: Repo.get!(Market, id)
  def list_markets(), do: Repo.all(Market)
  def create_market(attrs \\ %{}), do: %Market{} |> Market.changeset(attrs) |> Repo.insert()
  def update_hub(%Market{} = market, attrs), do: market |> Hub.changeset(attrs) |> Repo.update()
  def delete_hub(%Market{} = market), do: Repo.delete(market)

  # TICKETING
  def get_ticket(id), do: Repo.get!(Ticket, id)
  def list_tickets(), do: Repo.all(Ticket)

  def list_tickets_json() do
    {status, tickets_list} = Repo.all(Ticket) |> Poison.encode()
    {decode_status, tickets_map} = JSON.decode(tickets_list)
    tickets_map
  end

  def create_ticket(attrs \\ %{}), do: %Ticket{} |> Ticket.changeset(attrs) |> Repo.insert()
  def update_ticket(%Ticket{} = ticket, attrs), do: ticket |> Hub.changeset(attrs) |> Repo.update()
  def delete_ticket(%Ticket{} = ticket), do: Repo.delete(ticket)

  def get_ticket_by_external_reference(reference) do
    Repo.get_by(Ticket, external_ref: reference)
  end

  # ROUTES
  def get_route(id), do: Repo.get!(TravelRoutes, id)
  def list_routes(), do: Repo.all(TravelRoutes)
  def create_route(attrs \\ %{}), do: %TravelRoutes{} |> TravelRoutes.changeset(attrs) |> Repo.insert()
  def update_route(%TravelRoutes{} = ticket, attrs), do: ticket |> TravelRoutes.changeset(attrs) |> Repo.update()
  def delete_route(%TravelRoutes{} = ticket), do: Repo.delete(ticket)

  def get_route_by_route_code(code) do
    Repo.get_by(TravelRoutes, route_code: code)
  end



end