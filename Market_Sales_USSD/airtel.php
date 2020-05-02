<?php

//Load the classes in advance
require_once __DIR__ . '/vendor/autoload.php';

//Use classes in the namespace
use App\Lib\SharedUtils;
use App\Src\Core;
use App\Lib\Logger\USSDLogger;
use App\Lib\Config;

//get XMLRPC request from USSD gateway
$request_xml = file_get_contents("php://input");

//create the XMLRPC server
$xmlrpc_server = xmlrpc_server_create();

//register the method to the XMLRPC server
@xmlrpc_server_register_method($xmlrpc_server, "USSD_MESSAGE", "callMenu");

//start the server listener
header('Content-Type: text/xml');
$log = new USSDLogger();
$resp = xmlrpc_server_call_method($xmlrpc_server, $request_xml, array());
$log->logInfo(Config::APP_INFO_LOG, -1, "Responding to the Gateway With : \n" . print_r($resp, TRUE));
echo $resp;

/**
 * 
 * @param type $method_name
 * @param type $params
 * @param type $user_data
 * @return type
 */
function callMenu($method_name, $params, $user_data) {
    global $log;
    $payload = $params[0];
    $log->logInfo(Config::APP_INFO_LOG, $payload['MOBILE_NUMBER'], "|Request from the GW is:" . print_r($payload, true));
    try {
        $core = new Core($payload['MOBILE_NUMBER'], $log);
    } catch (Exception $ex) {
        $log->logError(Config::APP_INFO_LOG, $payload['MOBILE_NUMBER'], '|Exception was thrown during core Initialization. Error: ' . $ex->getMessage());
        unset($_SESSION);
        exit;
    }
    return $core->handle($payload);
}
