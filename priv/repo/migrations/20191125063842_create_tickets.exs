defmodule BusTerminalSystem.Repo.Migrations.CreateTickets do
  use Ecto.Migration

  def up do
    create_if_not_exists table(:probase_tbl_tickets) do
      add :reference_number, :string
      add :serial_number, :string
      add :external_ref, :string
      add :route, :int
      add :date, :string
      add :bus_no, :string
      add :class, :string
      add :activation_status, :string

      add :first_name, :string
      add :last_name, :string
      add :other_name, :string
      add :id_type, :string
      add :passenger_id, :string
      add :mobile_number, :string
      add :email_address, :string
      add :transaction_channel, :string
      add :travel_date, :string
      add :bus_schedule_id, :string

      timestamps()
    end

  end

  def change do

  end

  def down do
    drop_if_exists table(:probase_tbl_tickets)
  end



end
