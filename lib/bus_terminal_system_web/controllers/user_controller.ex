defmodule BusTerminalSystemWeb.UserController do
  use BusTerminalSystemWeb, :controller

  alias BusTerminalSystem.AccountManager
  alias BusTerminalSystem.AccountManager.User
  alias BusTerminalSystem.Utility

  plug(
    BusTerminalSystemWeb.Plugs.RequireAuth
    when action in [
           :profile,
           :index,
           :new,
           :create,
           :show,
           :edit,
           :update,
           :delete
         ]
  )

  def index(conn, _params) do
    users = AccountManager.list_users()
    render(conn, "index.html", users: users)
  end

  def new(conn, _params) do
    changeset = AccountManager.change_user(%User{})
    render(conn, "new.html", changeset: changeset)
  end

  def create(conn, user_params) do
    #     %{"user" => user_params}
    case AccountManager.create_user(user_params) do
      {:ok, user} ->
        conn
        |> put_flash(:info, "User created successfully.")
        |> redirect(to: Routes.user_path(conn, :show, user))

      {:error, %Ecto.Changeset{} = changeset} ->
        render(conn, "new.html", changeset: changeset)
    end

    render(conn, "new.html")
  end

  # %{"id" => id}
  def show(conn, _params) do
    # user = AccountManager.get_user!(id)
    render(conn, "show.html")
  end

  def edit(conn, %{"id" => id}) do
    user = AccountManager.get_user!(id)
    changeset = AccountManager.change_user(user)
    render(conn, "edit.html", user: user, changeset: changeset)
  end

  def update(conn, %{"id" => id, "user" => user_params}) do
    user = AccountManager.get_user!(id)

    case AccountManager.update_user(user, user_params) do
      {:ok, user} ->
        conn
        |> put_flash(:info, "User updated successfully.")
        |> redirect(to: Routes.user_path(conn, :show, user))

      {:error, %Ecto.Changeset{} = changeset} ->
        render(conn, "edit.html", user: user, changeset: changeset)
    end
  end

  def delete(conn, %{"id" => id}) do
    user = AccountManager.get_user!(id)
    {:ok, _user} = AccountManager.delete_user(user)

    conn
    |> put_flash(:info, "User deleted successfully.")
    |> redirect(to: Routes.user_path(conn, :index))
  end

  def profile(conn, _params) do
    render(conn, "profile.html")
  end

  def table_users(conn, _params) do
    users = AccountManager.list_users()
    render(conn, "TableUsers.html", users: users)
  end

  # ----APIs -----------------------------
  def all_users_json(conn, _params) do
    case AccountManager.list_users() |> Poison.encode() do
      {:ok, users} ->
        {
          json(conn, %{"user" => users, "status" => 0, "statusDesc" => "Success"})
        }

      _ ->
        {
          json(conn, %{
            "message" => "Could not query user",
            "status" => 1,
            "statusDesc" => "Failed"
          })
        }
    end
  end

  def api_create_user(conn, user_params) do
    case AccountManager.create_user(user_params) do
      {:ok, user} ->
        {:ok, user_json} = user |> Poison.encode()

        conn
        |> json(%{"status" => 0, "user" => user_json})

      {:error, %Ecto.Changeset{} = changeset} ->
        json(conn, %{"status" => 1, "errors" => changeset.errors})
    end
  end
end
