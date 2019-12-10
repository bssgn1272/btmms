defmodule BusTerminalSystem.Repo.Migrations.TicketTableAddRouteRelationship do
  use Ecto.Migration

  def change do
    alter table(:tickets) do
      add_if_not_exists(:route, :int)
    end
  end
end
