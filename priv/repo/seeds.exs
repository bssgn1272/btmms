# Script for populating the database. You can run it as:
#
#     mix run priv/repo/seeds.exs
#
# Inside the script, you can read and write to any of your
# repositories directly:
#
#     BusTerminalSystem.Repo.insert!(%BusTerminalSystem.SomeSchema{})
#
# We recommend using the bang functions (`insert!`, `update!`
# and so on) as they will fail if something goes wrong.


alias BusTerminalSystem.Settings

save = (fn key, value -> if Settings.find_by(key: key) == nil, do: Settings.create(key: key, value: value, status: true) end)

save.("APPLICATION_NAME","BTMMS")
save.("BANK_URL","http://41.175.13.198:7664//api/json/commercials/probase/zicb/fundsTransfer")
save.("BANK_AUTH_KEY","NOT SET")
save.("BANK_AUTH_SERVICE_KEY","NOT SET")
save.("EYED_BUS_ROUTES_URL","NOT SET")
save.("SMS_GATEWAY","NOT SET")
save.("SMS_GATEWAY_USERNAME","NOT SET")
save.("SMS_GATEWAY_PASSWORD","NOT SET")
save.("SMS_GATEWAY_SMSC","NOT SET")
save.("SMS_GATEWAY_SENDER","NOT SET")

