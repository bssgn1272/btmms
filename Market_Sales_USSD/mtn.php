<?php

//Load the classes in advance
require_once __DIR__ . '/vendor/autoload.php';

//Use classes in the namespace
use App\Lib\SharedUtils;
use App\Src\Core;
use App\Lib\Logger\USSDLogger;
use App\Lib\Config;

$log = new USSDLogger();
$log->logInfo(Config::APP_INFO_LOG, $_POST['MSISDN'], "Gateway request is : \n" . print_r($_POST, true));

//Lets return a response to the Conviva gateway
if (!empty($_POST['SESSION_ID']) && !empty($_POST['MSISDN'])) {
    echo callMenu($_POST['SESSION_ID'], $_POST['MSISDN'], $_POST['INPUT'], $_POST['SHORT_CODE']);
} else {
    //Aha! some fields are missing we tell the customer system is busy
    $log->logInfo(Config::APP_INFO_LOG, $_POST['MSISDN'], "Some fields from mtn gateway are missing, I do not work like this!. Am aborting this request.");
    echo getErrorResponse();
}

/**
 * 
 * @param type $sessionID
 * @param type $msisdn
 * @param type $ussd_body
 * @return type
 */
function callMenu($sessionID, $msisdn, $ussd_body, $short_code) {
    global $log;
    //We convert the Conviva ussd gateway request to the request that the menu will understand
    $payload = buildMenuRequestPayload($sessionID, $msisdn, $ussd_body);
    $log->logInfo(Config::APP_INFO_LOG, $_POST['MSISDN'], "Request to the menu is:" . print_r($payload, true));
    try {
        $core = new Core($_POST['MSISDN'], $log);
    } catch (Exception $ex) {
        $log->logError(Config::APP_INFO_LOG, $_POST['MSISDN'], '|Exception was thrown during core Initialization. Error: ' . $ex->getMessage());
        unset($_SESSION);
        exit;
    }
    return buildGatewayResponse($core->handle($payload));
}

/**
 * 
 * @param type $sessionID
 * @param type $msisdn
 * @param type $ussd_body
 */
function buildMenuRequestPayload($sessionID, $msisdn, $ussd_body) {
    $params = array();
    $params['SERVICE_KEY'] = "2";
    $params['MOBILE_NUMBER'] = $msisdn;
    $params['SESSION_ID'] = $sessionID;
    $params['SEQUENCE'] = "0";
    $params['END_OF_SESSION'] = "FALSE";
    $params['USSD_BODY'] = $ussd_body;
    $params['IMSI'] = Config::USSD_DEFAULT_IMSI;
    $params['LANGUAGE'] = Config::USSD_DEFAULT_LANGUAGE;
    return $params;
}

/**
 * Builds the Conviva Gateway response
 * @param type $responseStr
 */
function buildGatewayResponse($responseStr) {
    getHeaders($responseStr['END_OF_SESSION']);
    $response = $responseStr['USSD_BODY'];
    return $response;
}

/**
 * We generate a customized response error message in case menu returns an error
 * @param type $request_payload
 * @return string
 */
function getErrorResponse() {
    getHeaders("TRUE");
    $response = Config::SYSTEM_BUSY_MESSAGE;
    return $response;
}

/**
 * We set the response headers
 * @param type $freeFlowStatus
 */
function getHeaders($freeFlowStatus) {
    if ($freeFlowStatus == 'TRUE') {
        header('Freeflow: FB');
    } else {
        header('Freeflow: FC');
    }
    header('charge: N');
    header('amount: 0');
    header("cpRefId: " . rand(1000, 1000000));
    header('Expires: -1');
    header('Pragma: no-cache');
    header('Cache-Control: max-age=0');
    header('Content-Type: UTF-8');
}
