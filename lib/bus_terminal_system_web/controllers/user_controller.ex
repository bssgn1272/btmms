defmodule BusTerminalSystemWeb.UserController do
  use BusTerminalSystemWeb, :controller

  alias BusTerminalSystem.AccountManager
  alias BusTerminalSystem.AccountManager.User
  alias BusTerminalSystem.RepoManager
  alias BusTerminalSystem.MarketManagement
  alias BusTerminalSystem.MarketManagement.MarketTenant
  alias BusTerminalSystem.ApiManager
  alias BusTerminalSystem.EmailSender
  alias BusTerminalSystem.NapsaSmsGetway

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
    tickets = RepoManager.list_tickets()
    buses = RepoManager.list_buses()
    render(conn, "index.html", users: users, tickets: tickets, buses: buses)
  end

  def new(conn, _params) do
    changeset = AccountManager.change_user(%User{})
    render(conn, "new.html", changeset: changeset)
  end

  def create(conn, %{"payload" => payload} = user_params) do
    IO.inspect payload

    {s,first_name} = Map.fetch(payload,"first_name")
    {s,password} = Map.fetch(payload,"password")
    {s,username} = Map.fetch(payload,"username")
    {s,email} = Map.fetch(payload,"email")
    {s,mobile_number} = Map.fetch(payload,"mobile")

    message = " Hello #{first_name}, \n Your BTMS TELLER ACCOUNT CREDENTIALS ARE .Username: #{username} Password: #{password}"

    case AccountManager.create_user(payload) do
      {:ok, user} ->

        NapsaSmsGetway.send_sms(mobile_number,message)

        conn
        |> put_flash(:info, "User created successfully.")
        |> redirect(to: Routes.user_path(conn, :new))

      {:error, %Ecto.Changeset{} = changeset} ->

        IO.inspect ApiManager.translate_error(changeset)

        conn
        |> put_flash(:error,"Failed To Create User")
        |> render("new.html", changeset: changeset)

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

  def registration_form(conn, _params) do
    render(conn, "form.html")
  end

  def register_marketeer(conn, params) do
    case RepoManager.create_teller(params["payload"]) do
      {:ok, user} ->
        conn
        |> json(
          ApiManager.api_message_custom_handler(ApiManager.definition_query(), "SUCCESS", 0, %{
            "username" => user.username,
            "first_name" => user.first_name,
            "last_name" => user.last_name,
            "ssn" => user.ssn,
            "nrc" => user.nrc,
            "email" => user.email,
            "mobile" => user.mobile,
            "account_status" => user.account_status,
            "uuid" => user.uuid,
            "operator_role" => user.operator_role
          })
        )

      {:error, %Ecto.Changeset{} = _changeset} ->
        conn
        |> json(
          ApiManager.api_error_handler(
            ApiManager.definition_accounts(),
            ApiManager.translate_error(_changeset)
          )
        )
    end
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
