defmodule BusTerminalSystemWeb.Resolvers.Content do
  def list_tickets(_parent, _args, _resolution) do
    {:ok, BusTerminalSystem.RepoManager.list_tickets()}
  end
end