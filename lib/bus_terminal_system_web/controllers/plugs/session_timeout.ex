defmodule BusTerminalSystemWeb.Plugs.SessionTimeout do
  import Plug.Conn
  import BusTerminalSystemWeb.Router.Helpers

  @moduledoc false

  def init(opts \\ []) do
    Keyword.merge([timeout_after_seconds: 300], opts)
  end

  def call(conn, opts) do
    timeout_at = get_session(conn, :session_timeout_at) ||  new_session_timeout_at(opts[:timeout_after_seconds])

    if now() > timeout_at do
      logout_user(conn)
    else
      put_session(conn, :session_timeout_at, new_session_timeout_at(opts[:timeout_after_seconds]))
    end
  end

  defp logout_user(conn) do
    conn
    |> clear_session()
    |> configure_session([:renew])
    |> assign(:user, nil)
    |> Phoenix.Controller.redirect( to: session_path(conn, :login))

  end

  def now do
    DateTime.utc_now() |> DateTime.to_unix()
  end

  defp new_session_timeout_at(timeout_after_seconds) do
    now() + timeout_after_seconds
  end
end