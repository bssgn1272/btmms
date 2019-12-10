defmodule BusTerminalSystem.AccountManager.User do

  use Ecto.Schema
  import Ecto.Changeset
  #alias Argon2

  @derive {Poison.Encoder, only: [:id, :username, :role]}
  schema "users" do
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

    timestamps()
  end

  @doc false
  def changeset(user, attrs) do
    user
    |> cast(attrs, [:username, :password, :first_name, :last_name, :ssn, :role, :nrc, :email, :mobile, :tel, :uuid])
    |> validate_required([:username, :password, :role])
    |> put_password_hash()
  end

  defp put_password_hash(%Ecto.Changeset{valid?: true, changes: %{password: password}} = changeset) do
    change(changeset, password: Base.encode16(:crypto.hash(:sha512,password)))
  end

  defp put_password_hash(changeset), do: changeset

end
