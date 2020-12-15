defmodule BusTerminalSystem.AccountManager.User do
  use Endon
  use Ecto.Schema
  import Ecto.Changeset
  # alias Argon2

  @db_columns [:username, :password, :first_name, :last_name, :ssn, :role, :nrc, :email, :mobile, :account_number, :tel, :uuid, :account_status, :operator_role,
    :pin, :tmp_pin, :company, :auth_status, :maker, :checker, :maker_date_time, :checker_date_time, :user_description, :system_description, :role_id]

  @validate_columns [:username, :account_number, :nrc, :mobile, :ssn, :password, :role, :account_status, :operator_role, :role_id]

  @derive {Poison.Encoder,only: [:id,:account_type,:username,:first_name,:last_name,:ssn,:nrc,:email,:mobile,:account_status,:operator_role,:role,:company,:account_number,
  :auth_status, :maker, :checker, :maker_date_time,:checker_date_time, :role_id, :user_description, :system_description, :role_id]}

  schema "probase_tbl_users" do
    field :password, :string
    field :username, :string
    field :first_name, :string
    field :last_name, :string
    field :ssn, :string
    field :role, :string
    field :nrc, :string
    field :email, :string
    field :mobile, :string
    field :tel, :string
    field :uuid, :string
    field :account_status, :string
    field :operator_role, :string
    field :pin, :string
    field :tmp_pin, :string
    field :company, :string
    field :account_number, :string
    field :account_type, :string
    field :auth_status, :boolean, default: false
    field :maker, :integer
    field :checker, :integer
    field :maker_date_time, :naive_datetime
    field :checker_date_time, :naive_datetime
    field :user_description, :string
    field :system_description, :string
    field :role_id, :string

    timestamps()
  end

  @doc false
  def changeset(user, attrs) do
    user
    |> cast(attrs, @db_columns)
    |> validate_required(@validate_columns)
    # |> unique_constraint([:ssn])
    |> put_password_hash()
    |> harsh_password_pin()
  end

  defp put_password_hash(%Ecto.Changeset{valid?: true, changes: %{password: password}} = changeset) do
    change(changeset, password: Base.encode16(:crypto.hash(:sha512, password)))
  end

  defp harsh_password_pin(%Ecto.Changeset{valid?: true, changes: %{pin: pin}} = changeset) do
    change(changeset, pin: BusTerminalSystem.RepoManager.encode_pin(pin))
  end

  defp put_password_hash(changeset), do: changeset

  defp harsh_password_pin(changeset), do: changeset

#  BusTerminalSystemWeb.UserManagementController.search_permission(@user.id, 100)

end
