defmodule BusTerminalSystemWeb.Schema do
  use Absinthe.Schema
  import_types BusTerminalSystemWeb.Schema.ContentTypes

  alias BusTerminalSystemWeb.Resolvers

  query do
    @desc "Get all tickets"
    field :tickets, list_of(:tickets) do
      resolve &Resolvers.Content.list_tickets/3
    end

  end
end