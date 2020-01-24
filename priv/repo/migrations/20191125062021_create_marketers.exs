defmodule BusTerminalSystem.Repo.Migrations.CreateMarketers do
  use Ecto.Migration

  def change do
    create table(:marketers) do
      add :stand_uid, :string

      timestamps()
    end

  end
end
