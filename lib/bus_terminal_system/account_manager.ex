defmodule BusTerminalSystem.AccountManager do
  @moduledoc """
  The AccountManager context.
  """

  import Ecto.Query, warn: false
  alias BusTerminalSystem.Repo
  alias BusTerminalSystem.AccountManager.User
  # alias BusTerminalSystem.Auth
  # alias Argon2

  #  def authenticate_user(username, plain_text_password) do
  #    query = from u in User, where: u.username == ^username
  #    case Repo.one(query) do
  #      nil ->
  #        Argon2.no_user_verify()
  #        {:error, :invalid_credentials}
  #      user ->
  #        if Argon2.verify_pass(plain_text_password,user.password) do
  #          {:ok, user}
  #        else
  #          {:error, :invalid_credentials}
  #        end
  #    end
  #  end

  @doc """
  Returns the list of users.

  ## Examples

      iex> list_users()
      [%User{}, ...]

  """
  def list_users do
    Repo.all(User)
  end

  def list_users_json do
    {status, users_list} = Repo.all(User) |> Poison.encode()
    {decode_status, users_map} = JSON.decode(users_list)
    users_map
  end

  def users do
    User
    |> select([c], c)
    |> Repo.all()
  end

  @doc """
  Gets a single user.

  Raises `Ecto.NoResultsError` if the User does not exist.

  ## Examples

      iex> get_user!(123)
      %User{}

      iex> get_user!(456)
      ** (Ecto.NoResultsError)

  """

  def verify_uuid!(uuid), do: Repo.get!(User, uuid)

  def get_user_limited_properties(id) do
    Repo.get_by(User, id: id)
  end

  def get_user_by_username(username) do
    Repo.get_by(User, username: username)
  end

  def get_user!(id), do: Repo.get!(User, id)

  @doc """
  Creates a user.

  ## Examples

      iex> create_user(%{field: value})
      {:ok, %User{}}

      iex> create_user(%{field: bad_value})
      {:error, %Ecto.Changeset{}}

  """
  def create_user(attrs \\ %{}) do
    %User{}
    |> User.changeset(attrs)
    |> Repo.insert()
  end

  @doc """
  Updates a user.

  ## Examples

      iex> update_user(user, %{field: new_value})
      {:ok, %User{}}

      iex> update_user(user, %{field: bad_value})
      {:error, %Ecto.Changeset{}}

  """
  def update_user(%User{} = user, attrs) do
    user
    |> User.changeset(attrs)
    |> Repo.update()
  end

  @doc """
  Deletes a User.

  ## Examples

      iex> delete_user(user)
      {:ok, %User{}}

      iex> delete_user(user)
      {:error, %Ecto.Changeset{}}

  """
  def delete_user(%User{} = user) do
    Repo.delete(user)
  end

  @doc """
  Returns an `%Ecto.Changeset{}` for tracking user changes.

  ## Examples

      iex> change_user(user)
      %Ecto.Changeset{source: %User{}}

  """
  def change_user(%User{} = user) do
    User.changeset(user, %{})
  end
end
