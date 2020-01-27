defmodule BusTerminalSystem.Repo.Migrations.TicketAddExternalRefColunmn do
  use Ecto.Migration

  def change do
    alter table(:tickets) do
      add(:external_ref, :string)
    end
  end
end
