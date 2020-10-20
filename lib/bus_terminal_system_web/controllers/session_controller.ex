defmodule BusTerminalSystemWeb.SessionController do
  use BusTerminalSystemWeb,
      :controller

  import Ecto.Query, warn: false
  alias BusTerminalSystem.Repo
  alias BusTerminalSystem.{AccountManager, AccountManager.User, AccountManager.Guardian}

  def new(conn, _) do
    changeset = AccountManager.change_user(%User{})
    maybe_user = Guardian.Plug.current_resource(conn)

    if maybe_user do
      redirect(conn, to: "/secret")
    else
      conn
      |> put_layout(false)
      |> render("new.html", changeset: changeset, action: Routes.session_path(conn, :login))
    end
  end

  def login(conn, %{"user" => %{"username" => username, "password" => password}}) do
    # UserManager.authenticate_user(username, password)
    Repo.get_by(User, username: username)
    |> BusTerminalSystem.Auth.confirm_password(password)
    |> login_reply(conn)
  end

  def logout(conn, _) do
    conn
    |> configure_session(drop: true)
    |> redirect(to: "/")
  end

  defp login_reply({:ok, user}, conn) do
    conn
    |> put_flash(:info, "Welcome back!")
    # |> Guardian.Plug.sign_in(Guardian, user)
    |> put_session(:current_user, user.id)
    |> redirect(to: Routes.user_path(conn, :index))
  end

  defp login_reply({:error, message}, conn) do
    conn
    |> put_flash(:error, message)
    |> redirect(to: Routes.user_path(conn, :index))
  end

  defp login_reply({:error, reason}, conn) do
    conn
    |> put_flash(:error, to_string(reason))
    |> new(%{})
  end


end
