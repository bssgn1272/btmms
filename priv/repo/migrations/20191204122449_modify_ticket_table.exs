defmodule BusTerminalSystem.Repo.Migrations.ModifyTicketTable do
  use Ecto.Migration

  def change do
    alter table(:tickets) do
      remove_if_exists(:first_name, :string)
      remove_if_exists(:last_name, :string)
      remove_if_exists(:age, :string)
      remove_if_exists(:mobile, :string)
      remove_if_exists(:number_of_travelers, :string)
      remove_if_exists(:date_of_depature, :string)
      remove_if_exists(:date_of_return, :string)
      remove_if_exists(:traveling_to, :string)
      add_if_not_exists(:serial_number, :string)

    end
  end
end
