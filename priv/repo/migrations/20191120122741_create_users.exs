defmodule BusTerminalSystem.Repo.Migrations.CreateUsers do
  use Ecto.Migration

  def change do
    create table(:users) do
      add :username, :string
      add :password, :string
      add :first_name, :string
      add :last_name, :string
      add :ssn, :string
      add :role, :string
      add :email, :string
      add :mobile, :string
      add :tel, :string
      add :uuid, :string
      add :nrc, :string

      timestamps()
    end

    alter table(:users) do
      add_if_not_exists :nrc, :string
    end
  end
end
