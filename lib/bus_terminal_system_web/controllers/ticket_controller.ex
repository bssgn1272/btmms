defmodule BusTerminalSystemWeb.TicketController do
  use BusTerminalSystemWeb, :controller

  alias BusTerminalSystem.TicketManagement
  alias BusTerminalSystem.TicketManagement.Ticket
  alias BusTerminalSystem.ApiManager
  alias BusTerminalSystem.RepoManager
  alias BusTerminalSystem.Randomizer

  def index(conn, _params) do
    tickets = TicketManagement.list_tickets()
    render(conn, "index.html", tickets: tickets)
  end

  def new(conn, _params) do
    changeset = TicketManagement.change_ticket(%Ticket{})
    render(conn, "new.html", changeset: changeset)
  end

  def create(conn, %{"ticket" => ticket_params}) do
    case TicketManagement.create_ticket(ticket_params) do
      {:ok, ticket} ->
        conn
        |> put_flash(:info, "Ticket created successfully.")
        |> redirect(to: Routes.ticket_path(conn, :show, ticket))

      {:error, %Ecto.Changeset{} = changeset} ->
        render(conn, "new.html", changeset: changeset)
    end
  end

  def show(conn, %{"id" => id}) do
    ticket = TicketManagement.get_ticket!(id)
    render(conn, "show.html", ticket: ticket)
  end

  def edit(conn, %{"id" => id}) do
    ticket = TicketManagement.get_ticket!(id)
    changeset = TicketManagement.change_ticket(ticket)
    render(conn, "edit.html", ticket: ticket, changeset: changeset)
  end

  def update(conn, %{"id" => id, "ticket" => ticket_params}) do
    ticket = TicketManagement.get_ticket!(id)

    case TicketManagement.update_ticket(ticket, ticket_params) do
      {:ok, ticket} ->
        conn
        |> put_flash(:info, "Ticket updated successfully.")
        |> redirect(to: Routes.ticket_path(conn, :show, ticket))

      {:error, %Ecto.Changeset{} = changeset} ->
        render(conn, "edit.html", ticket: ticket, changeset: changeset)
    end
  end

  def delete(conn, %{"id" => id}) do
    ticket = TicketManagement.get_ticket!(id)
    {:ok, _ticket} = TicketManagement.delete_ticket(ticket)

    conn
    |> put_flash(:info, "Ticket deleted successfully.")
    |> redirect(to: Routes.ticket_path(conn, :index))
  end

  ##----------------------APIs---------------------------------------
  def find_ticket(conn, params) do
    case ApiManager.authentication_mod(params) do
      {:error, result} -> { json(conn, result)}

      {:ok, result} ->
        {
          json(conn, result)
        }
    end

    json conn, []
  end

  def purchase_ticket(conn,params) do
    case ApiManager.authentication_mod(params) do
      {:error, result} -> { json(conn, result)}

      {:ok, _result} ->
          {:ok, payload} = Map.fetch(params,"payload")
          IO.inspect(payload)
          if !Map.has_key?(payload,"external_ref") or !Map.has_key?(payload,"route_code") do
              json(conn, ApiManager.api_error_handler(ApiManager.definition_purchase(),"Could not complete purchase. Missing data keys. Please refer to documentation for more info"))
          else

            case validate_route(conn,payload) do
              {:error, _payload} ->
                conn
                |> json(ApiManager.api_error_handler(ApiManager.definition_purchase(),"INVALID ROUTE CODE"))

              {:ok, route} ->

                {:ok, ext_reference} = Map.fetch(payload,"external_ref")

                case validate_ext_reference(ext_reference) do
                  {:error, _reference} ->
                    conn
                    |> json(ApiManager.api_error_handler(ApiManager.definition_purchase(),"Duplicate External Reference [#{_reference}]"))

                  {:ok, _reference} ->
                    conn
                    |> db_insert_ticket(route,_reference ,%{reference_number: generate_reference_number(route),
                      serial_number: Randomizer.randomizer(16, :numeric), external_ref: ext_reference,
                      route: route.id, activation_status: "VALID"})
                end

            end
          end
    end
  end

  defp fetch(map,value) do
    result = Map.fetch(map,value)
    case result do
      {:ok, data} ->
        data
      :error ->
        value
    end
  end

  defp db_insert_ticket(conn,route ,reference, params \\ %{}) do
    IO.inspect("DB")
    case RepoManager.create_ticket(params) do
      {:ok, _ticket} ->
        conn
        |> json(ApiManager.api_message_custom_handler(
          "PURCHASE",
          "SUCCESS",
          0,
          %{ "reference_number" => params.reference_number,
            "serial_number" => params.serial_number,
            "external_reference" => params.external_ref,
            "start_route" => route.start_route,
            "end_route" => route.end_route,
            "route_code" => route.route_code,
            "price" => route.bus_fair,
            "currency" => "ZMW",
            "qr_code" => qr_generator("test")
          }))

      {:error, %Ecto.Changeset{} = _changeset} ->
        conn
        |> json(ApiManager.api_error_handler(ApiManager.definition_purchase(),"An Error Occurred, Operation Failed. Could not Purchase Ticket"))
    end
  end

  defp generate_reference_number(route) do
    dt = DateTime.utc_now
    {micro,_} = dt.microsecond
    "ZBMS-#{dt.year}#{dt.month}#{dt.day}-#{route.route_code}-#{dt.hour}#{dt.minute}#{dt.second}#{micro}"
  end

  def qr_generator(data) do
    data |> EQRCode.encode |> EQRCode.png() |> Base.encode64
  end

  def bar_code_generator(data) do
    Barlix.Code128.encode!(data) |> Barlix.PNG.print() |> Base.encode64
  end

  def validate_ext_reference(reference) do
    ticket = RepoManager.get_ticket_by_external_reference(reference)
    IO.inspect("TICKET")
    IO.inspect(ticket)
    case ticket do
      nil -> {:ok, reference}
      ticket -> {:error, ticket.external_ref}
    end
  end

  defp validate_route(conn, payload) do
    _route_code = "route_code"
    if !Map.has_key?(payload, _route_code) do
      {:error, payload}
    else
      {:ok, route_code} = Map.fetch(payload,_route_code)
      route = RepoManager.get_route_by_route_code(route_code)
      case route do
        nil -> { :error, route_code }
        route -> { :ok, route }
      end
    end
  end

  def get_routes(conn,_params) do

  end

  def list_tickets(conn,_params) do
    json(conn, RepoManager.list_tickets_json())
  end

end
