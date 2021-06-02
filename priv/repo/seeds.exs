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
alias BusTerminalSystem.Permissions

permission = (fn name, code -> if Permissions.find_by(name: name) == nil, do: Permissions.create(name: name, code: code) end)
save = (fn key, value -> if Settings.find_by(key: key) == nil, do: Settings.create(key: key, value: value, status: true) end)

# SETTINGS
if BusTerminalSystem.UserRoles.find_by(role: "DEFAULT") == nil, do: BusTerminalSystem.UserRoles.create([permissions: "[]", role: "DEFAULT", auth_status: 1, maker_id: 1])
save.("APPLICATION_NAME", "BTMMS")
save.("BANK_URL", "http://41.175.13.198:7664/api/json/commercials/probase/zicb/fundsTransfer")
save.("BANK_SECONDARY_URL", "http://41.175.13.198:7664//api/json/commercials/zicb/banking")
save.("BANK_AUTH_KEY", "NOT SET")
save.("BANK_ENABLE_TICKET_POSTING", "FALSE")
save.("BANK_ENABLE_ACCOUNT_OPENING_TASK", "TRUE")
save.("BANK_AUTH_SERVICE_KEY", "NOT SET")
save.("BANK_ACCOUNT_OPENING_TYPE", "WA")
save.("BANK_ACCOUNT_OPENING_CURRENCY", "ZMW")
save.("BANK_ACCOUNT_OPENING_UNIQUE_TYPE", "NRC")
save.("BANK_PROXY_ACCOUNT_OPENING_SERVICE_CODE", "ZB0631")
save.("EYED_BUS_ROUTES_URL", "NOT SET")
save.("SMS_ENABLE", "FALSE")
save.("SMS_GATEWAY", "NOT SET")
save.("SMS_GATEWAY_USERNAME", "NOT SET")
save.("SMS_GATEWAY_PASSWORD", "NOT SET")
save.("SMS_GATEWAY_SMSC", "NOT SET")
save.("SMS_GATEWAY_SENDER", "NOT SET")
save.("COSEC_ENABLE_BUS_REGISTRATION", "TRUE")
save.("COSEC_GD", "http://10.70.1.1/cosec/api.svc/v2/device") # GET DEVICES
save.("COSEC_AD", "http://10.70.1.1/cosec/api.svc/v2/user") # ADD DEVICE
save.("COSEC_AD2U", "http://10.70.1.1/cosec/api.svc/v2/device") # ADD DEVICE TO USER
save.("COSEC_ADC", "http://10.70.1.1/cosec/api.svc/v2/user") # ADD CREDENTIALS
save.("COSEC_CRED", "c2E6MTIzNDU=") # COSEC CREDENTIALS
save.("COSEC_CARD_LENGTH", "10") # COSEC CREDENTIALS
save.("COSEC_TURNSTILE_ENABLE_TID_IP", "http://10.70.3.55:5000/enable/")
save.("COSEC_TURNSTILE_DISABLE_TID_IP", "http://10.70.3.55:5000/disable/")
save.("NAPSA_MEMBER_VALIDATION_URL", "http://10.10.1.114:8092/apis/external/v1/validatessnnrc")
save.("NAPSA_SOAP_URL", "http://napsa-enapsauatsvr:8738/eNAPSAExternalAPI/2018/04/NPSService")
save.("NAPSA_CONTRIBUTION_URL", "http://enapsa.napsa.co.zm/eNAPSAServicesLibrary/2016/11/IeNAPSAExternalAPI/ReturnUpload")
save.("NAPSA_COMPLIANCE_SERVICE", "TRUE")
save.("NAPSA_USER_UPDATE_SERVICE", "TRUE")
save.("EMAIL_SERVICE", "TRUE")

# PERMISSIONS

permission.("USERS - VIEW USERS", "100")
permission.("USERS - EDIT USERS", "101")
permission.("USERS - REGISTER USERS", "102")


permission.("ROUTES - VIEW TRAVEL ROUTES", "104")
permission.("ROUTES - CREATE TRAVEL ROUTES", "105")
permission.("ROUTES - EDIT TRAVEL ROUTES", "106")
permission.("ROUTES - DELETE TRAVEL ROUTES", "107")

permission.("REPORTS - VIEW REPORTS", "108")

permission.("BUS - REGISTER BUS", "103")
permission.("BUS - VIEW BUSES", "109")
permission.("BUS - UPDATE BUSES", "111")
permission.("BUS - DELETE BUSES", "112")

permission.("MARKET - VIEW MARKET", "113")
permission.("MARKET - CREATE MARKET", "114")
permission.("MARKET - CREATE SECTION", "115")
permission.("MARKET - MANAGE SHOP", "116")
permission.("MARKET - MANAGE MARKET", "117")

permission.("AUTHORIZATION - VIEW AUTHORIZATION", "118")
permission.("AUTHORIZATION - MAKER/CHECKER", "119")
permission.("AUTHORIZATION - AUTHORISE REQUEST", "122")
permission.("AUTHORIZATION - REJECT REQUEST", "123")

permission.("SECURITY - VIEW SECURITY", "120")
permission.("SECURITY - ROLES/PERMISSIONS", "121")
permission.("SECURITY - CREATE USER ROLE", "124")
permission.("SECURITY - UPDATE USER ROLE", "125")
permission.("SECURITY - DELETE USER ROLE", "126")
permission.("SECURITY - ADD PERMISSION", "127")
permission.("SECURITY - REMOVE PERMISSION", "128")

permission.("TERMINUS MANAGEMENT - TERMINUS MANAGEMENT VIEW", "129")
permission.("TERMINUS MANAGEMENT - SPV STAFF REGISTRATION", "130")
permission.("TERMINUS MANAGEMENT - NONE SPV STAFF REGISTRATION", "131")
permission.("TERMINUS MANAGEMENT - VIEW TELLERS", "132")