defmodule BusTerminalSystem.UserRole do

  use Endon
  use Ecto.Schema

  @moduledoc false


  @db_columns [:user, :auth_status, :maker, :checker, :maker_date_time, :checker_date_time, :user_description, :system_description, :permissions, :role]

  schema "probase_user_role" do
    field :user, :integer
    field :role, :integer
    field :permissions, :string
    field :auth_status, :boolean, default: false
    field :maker, :integer, default: 1
    field :checker, :integer, default: 1
    field :maker_date_time, :naive_datetime, default: NaiveDateTime.utc_now |> NaiveDateTime.truncate(:second)
    field :checker_date_time, :naive_datetime, default: NaiveDateTime.utc_now |> NaiveDateTime.truncate(:second)
    field :user_description, :string, default: "NEW PERMISSION ADDED TO ROLE"
    field :system_description, :string, default: "PERMISSION"
    timestamps()
  end

end