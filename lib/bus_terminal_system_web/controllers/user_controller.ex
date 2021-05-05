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
           :delete,
           :new_teller
         ]
  )

  def index(conn, _params) do

    routes = RepoManager.list_routes()
    users = BusTerminalSystem.AccountManager.User.where(auth_status: true)
    f = Timex.today |> Timex.to_datetime
    tickets =  BusTerminalSystem.TicketManagement.Ticket.where(travel_date: Timex.today() |> to_string)
    IO.inspect(tickets)
    buses = RepoManager.list_buses()
    conn
    |> render("index.html", users: users, tickets: tickets, buses: buses, routes: routes)
  end

  def new(conn, params) do
    changeset = AccountManager.change_user(%User{})
    render(conn, "new.html", changeset: changeset)
  end

  def new_teller(conn, params) do
    changeset = AccountManager.change_user(%User{})
    render(conn, "new_teller.html", changeset: changeset)
  end

  def new_user(conn, params) do
    changeset = AccountManager.change_user(%User{})
    napsa_user = BusTerminalSystem.Napsa.NapsaQueryDetails.connect(%{"id" => params["napsa_member"]})
    IO.inspect(napsa_user["payload"])
    render(conn, "new.html", changeset: changeset, napsa_user: napsa_user["payload"])
  end

  def create(conn, %{"payload" => payload} = user_params) do
    IO.inspect payload

    {s,first_name} = Map.fetch(payload,"first_name")
    {s,password} = Map.fetch(payload,"password")
    {s,username} = Map.fetch(payload,"username")
    {s,email} = Map.fetch(payload,"email")
    {s,mobile_number} = Map.fetch(payload,"mobile")
    {s,role} = Map.fetch(payload,"role")
    {s,pin} = Map.fetch(payload,"pin")

    if User.find_by(account_number: Map.fetch!(payload, "account_number")) != nil do
      conn
      |> put_flash(:error, "Account Number #{Map.fetch!(payload, "account_number")} already exists")
      |> redirect(to: Routes.user_path(conn, :new))
    else
      role |> case  do
          "MOP" ->

            spawn(fn ->
              message = " Hello #{first_name}, \n Your BTMMS ACCOUNT CREDENTIALS ARE .Username: #{username} Password: #{password} Pin for mobile #{mobile_number} is #{pin}"
              NapsaSmsGetway.send_sms(mobile_number,message)
            end)

            payload = Map.put(payload, "operator_role", "MARKETER")
            user_create_payload(conn, payload)

          "BOP" ->

            spawn(fn ->
              message = " Hello #{first_name}, \n Your BTMMS BUS OPERATOR CREDENTIALS ARE .Username: #{username} Password: #{password}"
              NapsaSmsGetway.send_sms(mobile_number,message)
            end)

            payload = Map.put(payload, "operator_role", "BUS OPERATOR")
            payload = Map.put(payload, "account_status", "OTP")
            user_create_payload(conn, payload)

          "TOP" ->

            spawn(fn ->
              message = " Hello #{first_name}, \n Your BTMMS TELLER ACCOUNT CREDENTIALS ARE .Username: #{username} Password: #{password}"
              NapsaSmsGetway.send_sms(mobile_number,message)
            end)

            payload = Map.put(payload, "operator_role", "TELLER")
            user_create_payload(conn, payload)

          "SADMIN" ->

            spawn(fn ->
              message = " Hello #{first_name}, \n Your BTMMS SUPER ADMINISTRATIVE ACCOUNT CREDENTIALS ARE .Username: #{username} Password: #{password}"
              NapsaSmsGetway.send_sms(mobile_number,message)
            end)

            payload = Map.put(payload, "operator_role", "SUPER_ADMINISTRATOR")
            user_create_payload(conn, payload)
          _ ->

            spawn(fn ->
              message = " Hello #{first_name}, \n Your BTMMS ADMINISTRATIVE ACCOUNT CREDENTIALS ARE .Username: #{username} Password: #{password}"
              NapsaSmsGetway.send_sms(mobile_number,message)
            end)

            payload = Map.put(payload, "operator_role", "ADMINISTRATOR")
            user_create_payload(conn, payload)
        end

        render(conn, "new.html")
    end


  end

  def create_teller(conn, %{"payload" => payload} = user_params) do

    {s,first_name} = Map.fetch(payload,"first_name")
    {s,password} = Map.fetch(payload,"password")
    {s,username} = Map.fetch(payload,"username")
    {s,email} = Map.fetch(payload,"email")
    {s,mobile_number} = Map.fetch(payload,"mobile")
    {s,role} = Map.fetch(payload,"role")
    {s,pin} = Map.fetch(payload,"pin")

    if User.find_by(account_number: Map.fetch!(payload, "account_number")) != nil do
      conn
      |> put_flash(:error, "Account Number #{Map.fetch!(payload, "account_number")} already exists")
      |> redirect(to: Routes.user_path(conn, :new))
    else
      role |> case do
                "MOP" ->

                  spawn(fn ->
                    message = " Hello #{first_name}, \n Your BTMMS ACCOUNT CREDENTIALS ARE .Username: #{username} Password: #{password} Pin for mobile #{mobile_number} is #{pin}"
                    NapsaSmsGetway.send_sms(mobile_number,message)
                  end)

                  payload = Map.put(payload, "operator_role", "MARKETER")
                  user_create_payload(conn, payload)

                "BOP" ->

                  spawn(fn ->
                    message = " Hello #{first_name}, \n Your BTMMS BUS OPERATOR CREDENTIALS ARE .Username: #{username} Password: #{password}"
                    NapsaSmsGetway.send_sms(mobile_number,message)
                  end)

                  payload = Map.put(payload, "operator_role", "BUS OPERATOR")
                  payload = Map.put(payload, "account_status", "OTP")
                  user_create_payload(conn, payload)

                "TOP" ->

                  spawn(fn ->
                    message = " Hello #{first_name}, \n Your BTMMS TELLER ACCOUNT CREDENTIALS ARE .Username: #{username} Password: #{password}"
                    NapsaSmsGetway.send_sms(mobile_number,message)
                  end)

                  payload = Map.put(payload, "operator_role", "TELLER")
                  user_create_payload(conn, payload)

                "SADMIN" ->

                  spawn(fn ->
                    message = " Hello #{first_name}, \n Your BTMMS SUPER ADMINISTRATIVE ACCOUNT CREDENTIALS ARE .Username: #{username} Password: #{password}"
                    NapsaSmsGetway.send_sms(mobile_number,message)
                  end)

                  payload = Map.put(payload, "operator_role", "SUPER_ADMINISTRATOR")
                  user_create_payload(conn, payload)
                _ ->

                  spawn(fn ->
                    message = " Hello #{first_name}, \n Your BTMMS ADMINISTRATIVE ACCOUNT CREDENTIALS ARE .Username: #{username} Password: #{password}"
                    NapsaSmsGetway.send_sms(mobile_number,message)
                  end)

                  payload = Map.put(payload, "operator_role", "ADMINISTRATOR")
                  user_create_payload(conn, payload)
              end

      render(conn, "new_teller.html")
    end


  end

  defp user_create_payload(conn, payload) do

    napsa_user = %{
      "first_name" => payload["first_name"],
      "last_name" => payload["last_name"],
      "nrc" => payload["nrc"],
      "ssn" => payload["ssn"],
    }

    case AccountManager.create_user(payload) |> IO.inspect(label: "returned error") do
      {:ok, user} ->

#        conn
#        |> put_flash(:info, "User created successfully.")
#        |> redirect(to: Routes.user_path(conn, :new, [napsa_user: napsa_user]))

        changeset = AccountManager.change_user(%User{})

        conn
        |> put_flash(:info, "User created successfully.")
        |> render("new_teller.html", [changeset: changeset, napsa_user: napsa_user])

      {:error, %Ecto.Changeset{} = changeset} ->

        IO.inspect(ApiManager.translate_error(changeset), label: "Error ____________________________")

        conn
        |> put_flash(:error,"Failed To Create User")
        |> render("new_teller.html", [changeset: changeset, napsa_user: napsa_user])

    end
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
    users = BusTerminalSystem.AccountManager.User.where(auth_status: true)
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
