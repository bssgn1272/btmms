defmodule BusTerminalSystemWeb.BusTerminusControllerTest do
  use BusTerminalSystemWeb.ConnCase

  alias BusTerminalSystem.BusManagement

  @create_attrs %{liscense_plate: "some liscense_plate"}
  @update_attrs %{liscense_plate: "some updated liscense_plate"}
  @invalid_attrs %{liscense_plate: nil}

  def fixture(:bus_terminus) do
    {:ok, bus_terminus} = BusManagement.create_bus_terminus(@create_attrs)
    bus_terminus
  end

  describe "index" do
    test "lists all bus_terminus", %{conn: conn} do
      conn = get(conn, Routes.bus_terminus_path(conn, :index))
      assert html_response(conn, 200) =~ "Listing Bus terminus"
    end
  end

  describe "new bus_terminus" do
    test "renders form", %{conn: conn} do
      conn = get(conn, Routes.bus_terminus_path(conn, :new))
      assert html_response(conn, 200) =~ "New Bus terminus"
    end
  end

  describe "create bus_terminus" do
    test "redirects to show when data is valid", %{conn: conn} do
      conn = post(conn, Routes.bus_terminus_path(conn, :create), bus_terminus: @create_attrs)

      assert %{id: id} = redirected_params(conn)
      assert redirected_to(conn) == Routes.bus_terminus_path(conn, :show, id)

      conn = get(conn, Routes.bus_terminus_path(conn, :show, id))
      assert html_response(conn, 200) =~ "Show Bus terminus"
    end

    test "renders errors when data is invalid", %{conn: conn} do
      conn = post(conn, Routes.bus_terminus_path(conn, :create), bus_terminus: @invalid_attrs)
      assert html_response(conn, 200) =~ "New Bus terminus"
    end
  end

  describe "edit bus_terminus" do
    setup [:create_bus_terminus]

    test "renders form for editing chosen bus_terminus", %{conn: conn, bus_terminus: bus_terminus} do
      conn = get(conn, Routes.bus_terminus_path(conn, :edit, bus_terminus))
      assert html_response(conn, 200) =~ "Edit Bus terminus"
    end
  end

  describe "update bus_terminus" do
    setup [:create_bus_terminus]

    test "redirects when data is valid", %{conn: conn, bus_terminus: bus_terminus} do
      conn = put(conn, Routes.bus_terminus_path(conn, :update, bus_terminus), bus_terminus: @update_attrs)
      assert redirected_to(conn) == Routes.bus_terminus_path(conn, :show, bus_terminus)

      conn = get(conn, Routes.bus_terminus_path(conn, :show, bus_terminus))
      assert html_response(conn, 200) =~ "some updated liscense_plate"
    end

    test "renders errors when data is invalid", %{conn: conn, bus_terminus: bus_terminus} do
      conn = put(conn, Routes.bus_terminus_path(conn, :update, bus_terminus), bus_terminus: @invalid_attrs)
      assert html_response(conn, 200) =~ "Edit Bus terminus"
    end
  end

  describe "delete bus_terminus" do
    setup [:create_bus_terminus]

    test "deletes chosen bus_terminus", %{conn: conn, bus_terminus: bus_terminus} do
      conn = delete(conn, Routes.bus_terminus_path(conn, :delete, bus_terminus))
      assert redirected_to(conn) == Routes.bus_terminus_path(conn, :index)
      assert_error_sent 404, fn ->
        get(conn, Routes.bus_terminus_path(conn, :show, bus_terminus))
      end
    end
  end

  defp create_bus_terminus(_) do
    bus_terminus = fixture(:bus_terminus)
    {:ok, bus_terminus: bus_terminus}
  end
end
