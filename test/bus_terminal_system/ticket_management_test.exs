defmodule BusTerminalSystem.TicketManagementTest do
  use BusTerminalSystem.DataCase

  alias BusTerminalSystem.TicketManagement

  describe "tickets" do
    alias BusTerminalSystem.TicketManagement.Ticket

    @valid_attrs %{reference_number: "some reference_number"}
    @update_attrs %{reference_number: "some updated reference_number"}
    @invalid_attrs %{reference_number: nil}

    def ticket_fixture(attrs \\ %{}) do
      {:ok, ticket} =
        attrs
        |> Enum.into(@valid_attrs)
        |> TicketManagement.create_ticket()

      ticket
    end

    test "list_tickets/0 returns all tickets" do
      ticket = ticket_fixture()
      assert TicketManagement.list_tickets() == [ticket]
    end

    test "get_ticket!/1 returns the ticket with given id" do
      ticket = ticket_fixture()
      assert TicketManagement.get_ticket!(ticket.id) == ticket
    end

    test "create_ticket/1 with valid data creates a ticket" do
      assert {:ok, %Ticket{} = ticket} = TicketManagement.create_ticket(@valid_attrs)
      assert ticket.reference_number == "some reference_number"
    end

    test "create_ticket/1 with invalid data returns error changeset" do
      assert {:error, %Ecto.Changeset{}} = TicketManagement.create_ticket(@invalid_attrs)
    end

    test "update_ticket/2 with valid data updates the ticket" do
      ticket = ticket_fixture()
      assert {:ok, %Ticket{} = ticket} = TicketManagement.update_ticket(ticket, @update_attrs)
      assert ticket.reference_number == "some updated reference_number"
    end

    test "update_ticket/2 with invalid data returns error changeset" do
      ticket = ticket_fixture()
      assert {:error, %Ecto.Changeset{}} = TicketManagement.update_ticket(ticket, @invalid_attrs)
      assert ticket == TicketManagement.get_ticket!(ticket.id)
    end

    test "delete_ticket/1 deletes the ticket" do
      ticket = ticket_fixture()
      assert {:ok, %Ticket{}} = TicketManagement.delete_ticket(ticket)
      assert_raise Ecto.NoResultsError, fn -> TicketManagement.get_ticket!(ticket.id) end
    end

    test "change_ticket/1 returns a ticket changeset" do
      ticket = ticket_fixture()
      assert %Ecto.Changeset{} = TicketManagement.change_ticket(ticket)
    end
  end
end
