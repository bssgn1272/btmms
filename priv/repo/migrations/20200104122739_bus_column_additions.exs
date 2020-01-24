defmodule BusTerminalSystem.Repo.Migrations.BusColumnAdditions do
  use Ecto.Migration

  def change do
    alter table(:tbl_bus) do
      add_if_not_exists :vehicle_capacity, :string
    end
  end
end
