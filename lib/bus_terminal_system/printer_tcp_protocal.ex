defmodule BusTerminalSystem.PrinterTcpProtocol do

  def print_local_connect(payload) do
    {:ok, socket} = :gen_tcp.connect({127, 0, 0, 1}, 1302, [:binary])
    :ok = :gen_tcp.send(socket, Poison.encode!(payload))
    :ok = :gen_tcp.close(socket)
  end

  def print_remote_connect(ip, payload) do
    {:ok, socket} = :gen_tcp.connect(ip, 1302, [:binary])
    :ok = :gen_tcp.send(socket, Poison.encode!(payload))
    :ok = :gen_tcp.close(socket)

  end

  def print_remote_connect(ip, port, payload) do
    {:ok, socket} = :gen_tcp.connect(ip, port, [:binary])
    :ok = :gen_tcp.send(socket, Poison.encode!(payload))
    :timer.sleep(1000)
    :ok = :gen_tcp.close(socket)
  end

  def connect() do
    {:ok, socket} = :gen_tcp.connect({192, 168, 8, 113}, 1302, [:binary])
    :timer.sleep(1000)
    :ok = :gen_tcp.close(socket)
  end

  def print_test() do
    {:ok, socket} = :gen_tcp.connect({192, 168, 8, 113}, 1302, [:binary])
    payload = %{"refNumber" => "12345","fName" => "BOB","sName" => "BANDA","from" => "LIVINGSTONE","to" => "LUSAKA","Price" => "300.00","ticketNumber" => "1234","items" => ["Luggage:300","TicketPrice:300"]}
    :ok = :gen_tcp.send(socket, Poison.encode!(payload))
    :ok = :gen_tcp.close(socket)
  end

end