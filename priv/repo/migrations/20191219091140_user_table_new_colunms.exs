defmodule BusTerminalSystem.Repo.Migrations.UserTableNewColunms do
  use Ecto.Migration

  def change do
    alter table(:users) do
      add_if_not_exists :account_status, :string
      add_if_not_exists :operator_role, :string
      add_if_not_exists :pin, :string
      add_if_not_exists :tmp_pin, :string
    end
  end
end
