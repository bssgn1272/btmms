<?php

//Load the classes in advance
require_once __DIR__ . '/vendor/autoload.php';

//Use classes in the namespace
use App\Lib\SharedUtils;
use App\Src\Core;
use App\Lib\Logger\USSDLogger;
use App\Lib\Config;

date_default_timezone_set("Africa/Lusaka");

$log = new USSDLogger();
$input = SharedUtils::JsonToArray(file_get_contents("php://input"));
//handle($input);

function handle($payload) {
    $log->logInfo(Config::APP_INFO_LOG, -1, 'index | handle() [' . $payload['MOBILE_NUMBER'] . ']  equest from the gateway is ' . print_r($payload, TRUE));
    try {
        $core = new Core($payload['MOBILE_NUMBER'], $log);
    } catch (Exception $ex) {
        $log->logError(Config::APP_INFO_LOG, -1, 'index | handle() [' . $payload['MOBILE_NUMBER'] . ']  Exception was thrown during core Initialization. Error: ' . $ex->getMessage());
        unset($_SESSION);
        exit;
    }
    echo SharedUtils::arrayToJson($core->handle($payload));
}

?>
