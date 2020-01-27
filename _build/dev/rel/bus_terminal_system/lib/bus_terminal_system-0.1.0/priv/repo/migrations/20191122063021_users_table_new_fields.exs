defmodule BusTerminalSystem.Repo.Migrations.UsersTableNewFields do
  use Ecto.Migration

  def change do
    alter table(:users) do
      add_if_not_exists :nrc, :string
    end
  end
end
