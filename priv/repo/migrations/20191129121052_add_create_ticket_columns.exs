defmodule BusTerminalSystem.Repo.Migrations.AddCreateTicketColumns do
  use Ecto.Migration

  def change do
    alter table(:tickets) do
      add_if_not_exists(:first_name, :string)
      add_if_not_exists(:last_name, :string)
      add_if_not_exists(:age, :string)
      add_if_not_exists(:mobile, :string)
      add_if_not_exists(:traveling_from, :string)
      add_if_not_exists(:traveling_to, :string)
      add_if_not_exists(:date_of_depature, :string)
      add_if_not_exists(:date_of_return, :string)
      add_if_not_exists(:number_of_travelers, :string)
    end
  end
end
