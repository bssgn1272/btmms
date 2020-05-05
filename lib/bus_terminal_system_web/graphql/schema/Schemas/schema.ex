defmodule BusTerminalSystemWeb.Schema do
  use Absinthe.Schema
  import_types BusTerminalSystemWeb.Schema.ContentTypes

  alias BusTerminalSystemWeb.Resolvers

  query do

    @desc "Get all tickets"
    field :tickets, list_of(:tickets) do
      resolve &Resolvers.Content.list_tickets/3
    end

    @desc "Find ticket by id"
    field :ticket_by_id, :ticket_by_id do
      arg :id, non_null(:id)
      resolve &Resolvers.Content.find_ticket/3
    end

    @desc "Find ticket by serial"
    field :ticket_by_serial, :ticket_by_serial do
      arg :serial_number, non_null(:id)
      resolve &Resolvers.Content.find_ticket_serial/3
    end

    @desc "Find ticket by reference"
    field :ticket_by_reference, :ticket_by_reference do
      arg :reference_number , non_null(:string)
      arg :service_key, non_null(:string)
      resolve &Resolvers.Content.find_ticket_reference/3
    end

  end
end