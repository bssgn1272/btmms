defmodule BusTerminalSystem.Repo.Migrations.AddReferenceNumber do
  use Ecto.Migration

  def change do
    alter table(:tickets) do
      add_if_not_exists(:reference_number, :string)
    end
  end
end
