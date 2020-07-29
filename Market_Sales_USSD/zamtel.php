<?php

//Load the classes in advance
require_once __DIR__ . '/vendor/autoload.php';

//Use classes in the namespace
use App\Src\Core;
use App\Lib\Logger\USSDLogger;
use App\Lib\Config;

$log = new USSDLogger();
$log->logInfo(Config::APP_INFO_LOG, $_GET['MSISDN'], "Gateway request is : \n" . print_r($_GET,TRUE));

//Just to be sure Zamtel is not sending a wrong request
if (!empty($_GET['TransId']) && !empty($_GET['RequestType']) && !empty($_GET['MSISDN']) && !empty($_GET['AppId']) && !empty($_GET['SHORTCODE'])) {
    //Ok we are safe to proceed now
    echo callMenu($_GET['TransId'], $_GET['MSISDN'], $_GET['AppId'], urldecode($_GET['USSDString']), $_GET['RequestType'], $_GET['SHORTCODE']);
} else {
    //Aha! some fields are missing we tell the customer system is busy
    $log->logInfo(Config::APP_INFO_LOG, $_GET['MSISDN'], "Some fields from zamtel are missing, I do not work like this!. Am aborting this request....");
    echo '&TransId=' . $_GET['TransId'] . '&RequestType=' . Config::ZAMTEL_END_SESSION_REQUEST_TYPE . '&MSISDN=' . $msisdn . '&AppId=' . $appId . '&USSDString=' . Config::SYSTEM_BUSY_MESSAGE;
}

/**
 * 
 * @param type $method_name
 * @param type $params
 * @param type $user_data
 * @return type
 */
function callMenu($transID, $msisdn, $appID, $ussdStr, $requestType, $shortCode) {
    $response = "";
    global $log;
    $payload = buildMenuRequest($transID, $msisdn, $appID, $ussdStr, $requestType, $shortCode);
    $log->logInfo(Config::APP_INFO_LOG, $_GET['MSISDN'], "Request to the menu is:" . print_r($payload, true));
    try {
        $core = new Core($_GET['MSISDN'], $log);
    } catch (Exception $ex) {
        $log->logError(Config::APP_INFO_LOG, $_GET['MSISDN'], '|Exception was thrown during core Initialization. Error: ' . $ex->getMessage());
        unset($_SESSION);
        exit;
    }

    $result = $core->handle($payload);
    //You can never be sure, we just check if its empty
    if (!empty($result)) {
        if ($result['END_OF_SESSION'] == "TRUE") {
            //we end the session
            $response = '&TransId=' . $transID . '&RequestType=' . Config::ZAMTEL_END_SESSION_REQUEST_TYPE . '&MSISDN=' . $msisdn . '&AppId=' . $appID . '&USSDString=' . $result['USSD_BODY'];
        } else {
            //we continue the session
            $response = '&TransId=' . $transID . '&RequestType=' . Config::ZAMTEL_CONTINUE_SESSION_REQUEST_TYPE . '&MSISDN=' . $msisdn . '&AppId=' . $appID . '&USSDString=' . $result['USSD_BODY'];
        }
    } else {
        $response = '&TransId=' . $transID . '&RequestType=' . Config::ZAMTEL_END_SESSION_REQUEST_TYPE . '&MSISDN=' . $msisdn . '&AppId=' . $appID . '&USSDString=' . Config::SYSTEM_BUSY_MESSAGE;
    }
    return $response;
}


/**
 * 
 * @param type $transID
 * @param type $msisdn
 * @param type $appID
 * @param type $ussdStr
 * @param type $requestType
 * @param type $shortCode
 * @return string
 */
function buildMenuRequest($transID, $msisdn, $appID, $ussdStr, $requestType, $shortCode) {
     global $log;
    $log->logInfo(Config::APP_INFO_LOG, $_GET['MSISDN'], "STRING IS: $ussdStr");
    $response = array();
    $response['SEQUENCE'] = "0";
    $response['END_OF_SESSION'] = "";
    $response['LANGUAGE'] = Config::USSD_DEFAULT_LANGUAGE;
    $response['SESSION_ID'] = $transID;
    $response['IMSI'] = Config::USSD_DEFAULT_IMSI;
    $response['SERVICE_KEY'] = $appID;
    $response['MOBILE_NUMBER'] = $msisdn;
    if ($requestType == Config::ZAMTEL_NEW_SESSION_REQUEST_TYPE) {
        //Its a new session no need to provide the ussd body
        $response['USSD_BODY'] = "";
    } else {
        //We get the ussd body. the string is always *shortCode*body e.g *123*9, 
        //so we get the second array index
        $ussd_body = explode("*", $ussdStr)[2];
        $response['USSD_BODY'] = $ussd_body;
    }
    return $response;
}

/**
 * 
 * @param type $request_payload
 * @return string
 */
function getErrorResponse($request_payload) {
    $response = array();
    $response['RESPONSE_CODE'] = '0';
    $response['SESSION_ID'] = $request_payload['SESSION_ID'];
    $response['SEQUENCE'] = $request_payload['SEQUENCE'];
    $response['USSD_BODY'] = Config::SYSTEM_BUSY_MESSAGE;
    $response['END_OF_SESSION'] = "TRUE";
    $response['REQUEST_TYPE'] = 'RESPONSE';
    return $response;
}
