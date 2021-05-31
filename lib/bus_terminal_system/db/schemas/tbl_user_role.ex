defmodule BusTerminalSystem.UserRole do

  use Endon
  use Ecto.Schema

  @moduledoc false


  @db_columns [:user, :auth_status, :maker, :checker, :maker_date_time, :checker_date_time, :user_description, :system_description, :permissions, :role]

  schema "probase_user_role" do
    field :user, :integer
    field :role, :integer
    field :permissions, :string
    field :auth_status, :integer
    field :maker, :integer
    field :checker, :integer
    field :maker_date_time, :naive_datetime
    field :checker_date_time, :naive_datetime
    field :user_description, :string
    field :system_description, :string
    timestamps()
  end

end