defmodule BusTerminalSystemWeb.MarketerControllerTest do
  use BusTerminalSystemWeb.ConnCase

  alias BusTerminalSystem.MarketManagement

  @create_attrs %{stand_uid: "some stand_uid"}
  @update_attrs %{stand_uid: "some updated stand_uid"}
  @invalid_attrs %{stand_uid: nil}

  def fixture(:marketer) do
    {:ok, marketer} = MarketManagement.create_marketer(@create_attrs)
    marketer
  end

  describe "index" do
    test "lists all marketers", %{conn: conn} do
      conn = get(conn, Routes.marketer_path(conn, :index))
      assert html_response(conn, 200) =~ "Listing Marketers"
    end
  end

  describe "new marketer" do
    test "renders form", %{conn: conn} do
      conn = get(conn, Routes.marketer_path(conn, :new))
      assert html_response(conn, 200) =~ "New Marketer"
    end
  end

  describe "create marketer" do
    test "redirects to show when data is valid", %{conn: conn} do
      conn = post(conn, Routes.marketer_path(conn, :create), marketer: @create_attrs)

      assert %{id: id} = redirected_params(conn)
      assert redirected_to(conn) == Routes.marketer_path(conn, :show, id)

      conn = get(conn, Routes.marketer_path(conn, :show, id))
      assert html_response(conn, 200) =~ "Show Marketer"
    end

    test "renders errors when data is invalid", %{conn: conn} do
      conn = post(conn, Routes.marketer_path(conn, :create), marketer: @invalid_attrs)
      assert html_response(conn, 200) =~ "New Marketer"
    end
  end

  describe "edit marketer" do
    setup [:create_marketer]

    test "renders form for editing chosen marketer", %{conn: conn, marketer: marketer} do
      conn = get(conn, Routes.marketer_path(conn, :edit, marketer))
      assert html_response(conn, 200) =~ "Edit Marketer"
    end
  end

  describe "update marketer" do
    setup [:create_marketer]

    test "redirects when data is valid", %{conn: conn, marketer: marketer} do
      conn = put(conn, Routes.marketer_path(conn, :update, marketer), marketer: @update_attrs)
      assert redirected_to(conn) == Routes.marketer_path(conn, :show, marketer)

      conn = get(conn, Routes.marketer_path(conn, :show, marketer))
      assert html_response(conn, 200) =~ "some updated stand_uid"
    end

    test "renders errors when data is invalid", %{conn: conn, marketer: marketer} do
      conn = put(conn, Routes.marketer_path(conn, :update, marketer), marketer: @invalid_attrs)
      assert html_response(conn, 200) =~ "Edit Marketer"
    end
  end

  describe "delete marketer" do
    setup [:create_marketer]

    test "deletes chosen marketer", %{conn: conn, marketer: marketer} do
      conn = delete(conn, Routes.marketer_path(conn, :delete, marketer))
      assert redirected_to(conn) == Routes.marketer_path(conn, :index)
      assert_error_sent 404, fn ->
        get(conn, Routes.marketer_path(conn, :show, marketer))
      end
    end
  end

  defp create_marketer(_) do
    marketer = fixture(:marketer)
    {:ok, marketer: marketer}
  end
end
