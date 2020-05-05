defmodule BusTerminalSystemWeb.Resolvers.Content do

  def list_tickets(_parent, _args, _resolution) do
    {:ok, BusTerminalSystem.RepoManager.list_tickets()}
  end

  def find_ticket(_parent, %{id: id} = _args, _resolution) do
    case id |> BusTerminalSystem.RepoManager.get_ticket do
      nil -> {:error, "Ticket ID #{id} not found"}
      ticket -> {:ok, ticket}
    end
  end

  def find_ticket_serial(_parent, %{serial_number: serial_number} = _args, _resolution) do
    case serial_number |> BusTerminalSystem.RepoManager.get_ticket_serial do
      nil -> {:error, "Ticket Serial_number #{serial_number} not found"}
      ticket -> {:ok, ticket}
    end
  end

  def find_ticket_reference(_parent, %{reference_number: reference_number, service_key: service_key} = _args, _resolution) do
    IO.inspect(_args)
    case reference_number |> BusTerminalSystem.RepoManager.get_ticket_by_reference_number do
      nil -> {:error, "Ticket Reference Number #{reference_number} not found"}
      ticket -> {:ok, ticket}
    end
  end

end