defmodule BusTerminalSystemWeb.UserManagementController do
  use BusTerminalSystemWeb, :controller

  @moduledoc false

  def redirect(conn, %{"view" => render_view} = params) do

    view = (fn fn_conn, fn_params, view_name->
      view_name |> case do
       "22K2yQRYjgb4i" -> render_roles(fn_conn, fn_params)
       "t5BhuRXFRxqyv" -> render_permissions(fn_conn, fn_params)
      end
    end)

    case conn.method do
      "GET" -> view.(conn, params, render_view)
      "POST" -> render(conn, "index.html")
      _ -> render(conn, "index.html")
    end
  end

  defp render_roles(conn, params) do
    render(conn, "user_role.html")
  end

  defp render_permissions(conn, params) do
    render(conn, "permissions.html")
  end


end