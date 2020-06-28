defmodule BusTerminalSystem.Cosec do

  def get_devices(host_ip, credentials) do
    cred = [{"Authorization", "Basic #{credentials}"}]
    HTTPoison.get!("#{host_ip}/cosec/api.svc/v2/device?action=get;format=json",cred)
  end

  def add_user(host_ip, credentials,bus) do
    cred = [{"Authorization", "Basic #{credentials}"}]
    HTTPoison.get!("#{host_ip}/cosec/api.svc/v2/user?action=set;id=#{bus};name=#{bus};short-name=#{bus};active=1;module=U",cred)
  end

  def add_device_to_user(host_ip, credentials, bus, device) do
    cred = [{"Authorization", "Basic #{credentials}"}]
    HTTPoison.get!("#{host_ip}/cosec/api.svc/v2/device?action=assign;device=#{device};id=#{bus}",cred)
  end

  def add_device_to_user(host_ip, credentials, bus, device) do
    cred = [{"Authorization", "Basic #{credentials}"}]
    HTTPoison.get!("#{host_ip}/cosec/api.svc/v2/device?action=assign;device=#{device};id=#{bus}",cred)
  end


  def add_credentials(host_ip, credentials, bus, card_id) do
    cred = [{"Authorization", "Basic #{credentials}"}]
    HTTPoison.get!("#{host_ip}/cosec/api.svc/v2/user?action=set-credential;id=#{bus};credentialtype=card;data=#{card_id}",cred)
  end

end