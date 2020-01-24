defmodule BusTerminalSystem.MarketManagementTest do
  use BusTerminalSystem.DataCase

  alias BusTerminalSystem.MarketManagement

  describe "marketers" do
    alias BusTerminalSystem.MarketManagement.Marketer

    @valid_attrs %{stand_uid: "some stand_uid"}
    @update_attrs %{stand_uid: "some updated stand_uid"}
    @invalid_attrs %{stand_uid: nil}

    def marketer_fixture(attrs \\ %{}) do
      {:ok, marketer} =
        attrs
        |> Enum.into(@valid_attrs)
        |> MarketManagement.create_marketer()

      marketer
    end

    test "list_marketers/0 returns all marketers" do
      marketer = marketer_fixture()
      assert MarketManagement.list_marketers() == [marketer]
    end

    test "get_marketer!/1 returns the marketer with given id" do
      marketer = marketer_fixture()
      assert MarketManagement.get_marketer!(marketer.id) == marketer
    end

    test "create_marketer/1 with valid data creates a marketer" do
      assert {:ok, %Marketer{} = marketer} = MarketManagement.create_marketer(@valid_attrs)
      assert marketer.stand_uid == "some stand_uid"
    end

    test "create_marketer/1 with invalid data returns error changeset" do
      assert {:error, %Ecto.Changeset{}} = MarketManagement.create_marketer(@invalid_attrs)
    end

    test "update_marketer/2 with valid data updates the marketer" do
      marketer = marketer_fixture()
      assert {:ok, %Marketer{} = marketer} = MarketManagement.update_marketer(marketer, @update_attrs)
      assert marketer.stand_uid == "some updated stand_uid"
    end

    test "update_marketer/2 with invalid data returns error changeset" do
      marketer = marketer_fixture()
      assert {:error, %Ecto.Changeset{}} = MarketManagement.update_marketer(marketer, @invalid_attrs)
      assert marketer == MarketManagement.get_marketer!(marketer.id)
    end

    test "delete_marketer/1 deletes the marketer" do
      marketer = marketer_fixture()
      assert {:ok, %Marketer{}} = MarketManagement.delete_marketer(marketer)
      assert_raise Ecto.NoResultsError, fn -> MarketManagement.get_marketer!(marketer.id) end
    end

    test "change_marketer/1 returns a marketer changeset" do
      marketer = marketer_fixture()
      assert %Ecto.Changeset{} = MarketManagement.change_marketer(marketer)
    end
  end
end
