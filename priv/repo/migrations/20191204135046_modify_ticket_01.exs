defmodule BusTerminalSystem.Repo.Migrations.ModifyTicket01 do
  use Ecto.Migration

  def change do
    alter table(:tickets) do
      remove_if_exists(:traveling_from,:string)
    end
  end
end
