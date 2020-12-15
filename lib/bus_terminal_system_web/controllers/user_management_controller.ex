defmodule BusTerminalSystemWeb.UserManagementController do
  use BusTerminalSystemWeb, :controller

  @moduledoc false

  # PAGES
  @render_roles_page_code "22K2yQRYjgb4i"
  @render_permissions_page_code "t5BhuRXFRxqyv"

  # Forms
  @form_create_new_role "haHGTyutuK"
  @form_delete_role_js "mIcCqAlJJo8TJJ6Q"
  @form_update_role_js "akjhksdakjdlaskji"
  @form_fetch_permission_name_js "Hkiismijmsihiasdd"
  @api_remove_role_permission "ashuncianjsakdmia"
  @api_add_role_permission "iajumdnuyjyciaca"

  def redirect(conn, %{"view" => render_view, "sigma" => form_name} = params) do
    IO.inspect(params)


    view = (fn fn_conn, fn_params, view_name->
      view_name |> case do
       @render_roles_page_code -> render_roles(fn_conn, fn_params)
       @render_permissions_page_code -> render_permissions(fn_conn, fn_params)
      end
    end)

    form = (fn conn, params, form_name ->
      form_name |> case do
        @form_create_new_role -> create_role(conn, params)
        @form_delete_role_js -> api_delete_role(conn, params)
        @form_update_role_js -> api_update_role(conn, params)
        @form_fetch_permission_name_js -> api_fetch_permission_name(conn, params)
        @api_remove_role_permission -> api_remove_role_permission(conn, params)
        @api_add_role_permission -> api_add_role_permission(conn, params)
      end
    end)

    case conn.method do
      "GET" -> view.(conn, params, render_view)
      "POST" -> form.(conn, params, form_name)
      _ -> render(conn, "index.html", roles: BusTerminalSystem.UserRoles.all())
    end
  end

  defp api_delete_role(conn, %{"role" => role} = params) do
    BusTerminalSystem.UserRoles.find_by(role: role)
    |> case do
         user_role ->
           BusTerminalSystem.UserRoles.delete(user_role) |> case do
            {:ok, deleted_role} -> conn |> json(%{"status" => 0, "message" => "Role Successfully deleted"})
            {:error, _} -> conn |> json(%{"status" => 1, "message" => "Failed to delete role"})
           end
         {:error, _} -> conn |> json(%{"status" => 1, "message" => "Failed to delete role"})
    end

  end

  defp api_update_role(conn, %{"role" => role, "role_id" => role_id} = params) do
    BusTerminalSystem.UserRoles.find_by(id: role_id)
    |> case do
         user_role ->
           BusTerminalSystem.UserRoles.update(user_role, [role: role]) |> case do
              {:ok, updated_role} -> conn |> json(%{"status" => 0, "message" => "Role Successfully Updated"})
              {:error, _} -> conn |> json(%{"status" => 1, "message" => "Failed to update role"})
            end
         {:error, _} -> conn |> json(%{"status" => 1, "message" => "Failed to update role"})
       end

  end

  defp remove_permission(permissions, right) do
    right = Decimal.new(right) |> Decimal.to_integer
    permissions |> Enum.member?(right) |> case do
      true -> permissions |> List.delete(right) |> Poison.encode!
      false -> permissions |> Poison.encode!
    end
  end

  def add_permission(permissions, permission) do
    permission = Decimal.new(permission) |> Decimal.to_integer
    permissions |> Enum.member?(permission) |> case do
      true -> permissions |> Poison.encode!
      false -> ([permission | [permissions]]) |> List.flatten() |> Poison.encode!()
    end
  end

  defp api_add_role_permission(conn, %{"permission" => permission, "role_id" => role_id} = params) do
    IO.inspect(params, label: "ADD PERMISSION")
    BusTerminalSystem.UserRoles.find_by(id: role_id)
    |> case do
         user_role ->
           updated_permissions = add_permission((user_role.permissions |> Poison.decode!), BusTerminalSystem.Permissions.find_by(name: permission).code)

           BusTerminalSystem.UserRoles.update(user_role, [permissions: updated_permissions]) |> case do
              {:ok, updated_role} -> conn |> json(%{"status" => 0, "message" => "Successfully added permission"})
              {:error, _} -> conn |> json(%{"status" => 1, "message" => "Failed to update permissions"})
            end
         {:error, _} -> conn |> json(%{"status" => 1, "message" => "Failed to add permission"})
       end
  end

  defp api_remove_role_permission(conn, %{"permission" => permission, "role_id" => role_id} = params) do
    BusTerminalSystem.UserRoles.find_by(id: role_id)
    |> case do
         user_role ->
           updated_permissions = remove_permission((user_role.permissions |> Poison.decode!), BusTerminalSystem.Permissions.find_by(name: permission).code)

           BusTerminalSystem.UserRoles.update(user_role, [permissions: updated_permissions]) |> case do
              {:ok, updated_role} -> conn |> json(%{"status" => 0, "message" => "Successfully removed permission"})
              {:error, _} -> conn |> json(%{"status" => 1, "message" => "Failed to update permissions"})
            end
         {:error, _} -> conn |> json(%{"status" => 1, "message" => "Failed to remove permission"})
       end
  end

  defp api_fetch_permission_name(conn, %{"role_id" => role_id} = params) do
    codes = BusTerminalSystem.UserRoles.find_by(id: role_id).permissions |> Poison.decode!
    |> Enum.map(fn code -> BusTerminalSystem.Permissions.find_by([code: "#{code}"]).name end)
    json(conn, codes)
  end

  def api_fetch_permission_name() do
    BusTerminalSystem.UserRoles.find_by(id: 2).permissions |> Poison.decode!
    |> Enum.map(fn code -> BusTerminalSystem.Permissions.find_by([code: "#{code}"]).name end)
  end

  defp render_roles(conn, params) do
    render(conn, "user_role.html", roles: BusTerminalSystem.UserRoles.all())
  end

  defp render_permissions(conn, params) do
    render(conn, "permissions.html", roles: BusTerminalSystem.UserRoles.all())
  end

  defp create_role(conn, params) do
    params =  AtomicMap.convert(params, %{safe: false})
    params = %{
      role: params.role,
      permissions: ([] |> Poison.encode!)
    }

    BusTerminalSystem.UserRoles.find_by(role: params.role)
    |> case do
       nil ->
         BusTerminalSystem.UserRoles.create(params)

         conn
         |> put_flash(:info, "Role #{params.role} created successfully")
         |> render("user_role.html", roles: BusTerminalSystem.UserRoles.all())

       role ->

         conn
         |> put_flash(:error, "Role #{params.role} Already Exists")
         |> render("user_role.html", roles: BusTerminalSystem.UserRoles.all())
     end
  end

  def search_permission(user_id, permission) do
    BusTerminalSystem.UserRoles.find(BusTerminalSystem.AccountManager.User.find_by(id: user_id).role_id).permissions
    |> Poison.decode!()
    |> Enum.member?(permission)
  end
end