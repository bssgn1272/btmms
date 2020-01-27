defmodule BusTerminalSystem.Repo.Migrations.TicketAddUserKyc do
  use Ecto.Migration

  def change do
    alter table(:tickets) do
      add_if_not_exists :first_name, :string
      add_if_not_exists :last_name, :string
      add_if_not_exists :other_name, :string
      add_if_not_exists :id_type, :string
      add_if_not_exists :passenger_id, :string
      add_if_not_exists :mobile_number, :string
      add_if_not_exists :email_address, :string
      add_if_not_exists :transaction, :string
    end


  end
end
