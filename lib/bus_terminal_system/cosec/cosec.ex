defmodule BusTerminalSystem.Cosec do

  import Ecto.Query, warn: false
  alias BusTerminalSystem.Settings
  alias BusTerminalSystem.BusManagement.Bus

  def get_devices() do
    cred = [{"Authorization", "Basic #{Settings.find_by(key: "COSEC_CRED").value}"}]
    HTTPoison.get!("#{Settings.find_by(key: "COSEC_GD").value}?action=get;format=json",cred).body |> Poison.decode!()
  end

#  1
  def add_user(id, short_name) do
    cred = [{"Authorization", "Basic #{Settings.find_by(key: "COSEC_CRED").value}"}]
    HTTPoison.get!("#{Settings.find_by(key: "COSEC_AD").value}?action=set;id=#{id};name=#{short_name};short-name=#{short_name};active=1;module=U",cred)
  end

#  2
  def add_device_to_user(bus_id, device) do
    cred = [{"Authorization", "Basic #{Settings.find_by(key: "COSEC_CRED").value}"}]
    HTTPoison.get!("#{Settings.find_by(key: "COSEC_AD2U").value}?action=assign;device=#{device};id=#{bus_id}",cred)
  end

# 3
  def add_credentials(bus_id, card_id) do
    cred = [{"Authorization", "Basic #{Settings.find_by(key: "COSEC_CRED").value}"}]
    HTTPoison.get!("#{Settings.find_by(key: "COSEC_ADC").value}?action=set-credential;id=#{bus_id};credential-type=card;data=#{card_id}",cred)
  end

  def run() do
    if Settings.find_by(key: "COSEC_ENABLE_BUS_REGISTRATION").value == "TRUE" do
      query = from u in Bus, where: is_nil(u.cosec) and not is_nil(u.card) and u.auth_status == true
      Bus.where(query)
      |> Enum.each(fn bus ->
        register_to_cosec(bus)
      end)
    end

  end

  def register_to_cosec(bus) do

#    bus = BusTerminalSystem.BusManagement.Bus.first

    card = (fn length -> BusTerminalSystem.Randomizer.randomizer(length, :numeric) end)

    add_user_result = add_user(bus.id, bus.license_plate |> String.replace(" ", "") |> String.trim)
    devices = get_devices()["device"]

    if add_user_result.status_code != 200 do
      add_user_result
    else
      result = devices |> Enum.map(fn device ->
        add_device_to_user(bus.id, device["id"]).body
        %{result: add_device_to_user(bus.id, device["id"]).body, device: device["id"]}
      end) |> Enum.filter(fn r -> r.result |> String.contains?("successful") end)

#      card_length = Settings.find_by(key: "COSEC_CARD_LENGTH").value |> Decimal.new() |> Decimal.to_integer

      if Enum.count(devices) == Enum.count(result) do
        response = add_credentials(bus.id, bus.card).body
        BusTerminalSystem.BusManagement.Bus.update(bus,[cosec: "Bus Registration Complete, Response: #{response}"])
      else
        response = add_credentials(bus.id, bus.card).body
        BusTerminalSystem.BusManagement.Bus.update(bus,[cosec: "Bus Registration Incomplete, Response: #{response}"])
      end
    end


  end

end