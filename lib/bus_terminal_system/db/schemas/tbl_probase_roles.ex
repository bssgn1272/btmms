defmodule BusTerminalSystem.UserRoles do
  use Endon
  use Ecto.Schema
  import Ecto.Changeset

  @db_columns [:auth_status, :maker_id, :checker_id, :maker_date_time, :checker_date_time, :user_description, :system_description, :permissions, :role]

  @derive {Poison.Encoder, only: @db_columns ++ [:id]}

  schema "probase_roles" do
    field :permissions, :string
    field :role, :string
    field :auth_status, :integer
    field :maker_id, :integer
    field :checker_id, :integer
    field :maker_date_time, :naive_datetime
    field :checker_date_time, :naive_datetime
    field :user_description, :string
    field :system_description, :string

  end

  #  @doc false
  #  def changeset(route, attrs) do
  #    route
  #    |> cast(attrs, @db_columns)
  ##    |> validate_required(@db_columns)
  #  end

end