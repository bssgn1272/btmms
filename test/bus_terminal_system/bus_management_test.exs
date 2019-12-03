defmodule BusTerminalSystem.BusManagementTest do
  use BusTerminalSystem.DataCase

  alias BusTerminalSystem.BusManagement

  describe "bus_terminus" do
    alias BusTerminalSystem.BusManagement.BusTerminus

    @valid_attrs %{liscense_plate: "some liscense_plate"}
    @update_attrs %{liscense_plate: "some updated liscense_plate"}
    @invalid_attrs %{liscense_plate: nil}

    def bus_terminus_fixture(attrs \\ %{}) do
      {:ok, bus_terminus} =
        attrs
        |> Enum.into(@valid_attrs)
        |> BusManagement.create_bus_terminus()

      bus_terminus
    end

    test "list_bus_terminus/0 returns all bus_terminus" do
      bus_terminus = bus_terminus_fixture()
      assert BusManagement.list_bus_terminus() == [bus_terminus]
    end

    test "get_bus_terminus!/1 returns the bus_terminus with given id" do
      bus_terminus = bus_terminus_fixture()
      assert BusManagement.get_bus_terminus!(bus_terminus.id) == bus_terminus
    end

    test "create_bus_terminus/1 with valid data creates a bus_terminus" do
      assert {:ok, %BusTerminus{} = bus_terminus} = BusManagement.create_bus_terminus(@valid_attrs)
      assert bus_terminus.liscense_plate == "some liscense_plate"
    end

    test "create_bus_terminus/1 with invalid data returns error changeset" do
      assert {:error, %Ecto.Changeset{}} = BusManagement.create_bus_terminus(@invalid_attrs)
    end

    test "update_bus_terminus/2 with valid data updates the bus_terminus" do
      bus_terminus = bus_terminus_fixture()
      assert {:ok, %BusTerminus{} = bus_terminus} = BusManagement.update_bus_terminus(bus_terminus, @update_attrs)
      assert bus_terminus.liscense_plate == "some updated liscense_plate"
    end

    test "update_bus_terminus/2 with invalid data returns error changeset" do
      bus_terminus = bus_terminus_fixture()
      assert {:error, %Ecto.Changeset{}} = BusManagement.update_bus_terminus(bus_terminus, @invalid_attrs)
      assert bus_terminus == BusManagement.get_bus_terminus!(bus_terminus.id)
    end

    test "delete_bus_terminus/1 deletes the bus_terminus" do
      bus_terminus = bus_terminus_fixture()
      assert {:ok, %BusTerminus{}} = BusManagement.delete_bus_terminus(bus_terminus)
      assert_raise Ecto.NoResultsError, fn -> BusManagement.get_bus_terminus!(bus_terminus.id) end
    end

    test "change_bus_terminus/1 returns a bus_terminus changeset" do
      bus_terminus = bus_terminus_fixture()
      assert %Ecto.Changeset{} = BusManagement.change_bus_terminus(bus_terminus)
    end
  end
end
