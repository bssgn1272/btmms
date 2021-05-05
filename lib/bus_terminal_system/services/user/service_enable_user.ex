defmodule BusTerminalSystem.Service.User.EnableUser do
  use BusTerminalSystemWeb, :universal

  def index(conn, params) do
    multiple = Ecto.Multi.new()
    current_user = conn.assigns.user
    map = (fn x ->
      Enum.reduce(x, %{}, fn {k,v}, map ->
        Map.merge(map, %{"#{k}": v})
      end)
           end)

    try do
      multiple
      |> Ecto.Multi.run(:validation, fn _repo, _changes ->
        User.find_by(username: params["username"], id: params["sendId"])
        |> case do
             nil -> {:error, %{message: "User Name not found", log: "#{current_user.username} tried to enable user and provided an unknown username: #{params["username"]}"}}
             user -> {:ok, user}
           end
      end)
      |> Ecto.Multi.update(:user, fn %{validation: validation} ->
        Ecto.Changeset.change(validation, %{account_status: "ACTIVE"})
      end)
#      |> Ecto.Multi.insert(:sms, fn %{validation: validation, user: user} ->
#        Ecto.Changeset.change(PbsPaymentGateway.Utility.AuditTrail.multi_sms(user.mobile,
#          "Hello #{user.last_name} #{user.first_name},\n Your user account is has been disabled"))
#      end)
#      |> Ecto.Multi.insert(:log, PbsPaymentGateway.Utility.AuditTrail.multi_logs(
#        current_user.username, "SUCCESSFULLY TO ENABLED USER BY: #{current_user.username}", "ENABLE", current_user.id,
#        "enabled username: #{params["username"]} account", "#{params["username"]}",
#        "#{current_user.username} successfully enabled user #{params["username"]}", AuditTrail.ip_address(conn)))
      |> Repo.transaction()
      |> case do
           {:ok, %{user: user}} -> %{status: 0, message: "Successfully enabled #{params["username"]}'s account"}
           {:error, _, error, _} ->
#             Ecto.Multi.insert(multiple, :log, PbsPaymentGateway.Utility.AuditTrail.multi_logs(
#               current_user.username, "FAILED TO ENABLED USER BY: #{current_user.username}", "ENABLE", current_user.id,
#               "enabled username: #{params["username"]} account", "#{params["username"]}. date: #{PbsPaymentGateway.GlobalFunctions.Time.local_time()}",
#               error.log, AuditTrail.ip_address(conn)))
             %{status: 1, message: error.message}
         end
    rescue
      _ ->
#        Ecto.Multi.insert(multiple, :log, PbsPaymentGateway.Utility.AuditTrail.multi_logs(
#          current_user.username, "FAILED TO ENABLED USER BY: #{current_user.username}", "ENABLE", current_user.id,
#          "enabled username: #{params["username"]} account", "#{params["username"]}. date: #{PbsPaymentGateway.GlobalFunctions.Time.local_time()}",
#          "An Exception error occurred when #{current_user.username} was enabling user #{params["username"]} account at #{PbsPaymentGateway.GlobalFunctions.Time.local_time()}", AuditTrail.ip_address(conn)))
        %{status: 1, message: "Enable user account failed: Please contact system administrator."}
    end
  end
end