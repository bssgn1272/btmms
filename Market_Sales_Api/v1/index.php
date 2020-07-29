<?php

/**
 * @author: Chimuka Moonde
 * @mobile No: 0973297682
 * @email : chimukamoonde@gmail.com
 * Kindly note that this is a customized version of slim 2
 * @editor: Francis Chulu
 * @Date: 07/11/2019
 * Pointed all users table queries to traders table, added logging class
 */

//including the required files
require_once '../include/Config.php';
include '../include/StatusCodes.php';
include '../include/Utils.php';
require_once '../include/Security.php';
require_once '../include/DbHandler.php';
require_once '.././libs/Slim/Slim.php';
require_once  '../include/ApiLogger.php';

\Slim\Slim::registerAutoloader();//Creating a slim instance
$app = new \Slim\Slim();
$log = new ApiLogger();

//For now we expect json request for POST and PUT
if ($_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'POST') {
    $decodedRequest = Utils::handleRequest();
    //$log->logInfo(APP_INFO_LOG, 'index | [' . $decodedRequest['seller_mobile_number']. ']  Received request is ' . print_r($decodedRequest, TRUE));
    if (isset($decodedRequest['status']) && $decodedRequest['status'] == StatusCodes::GENERIC_ERROR) {
        echoResponse(200, $decodedRequest);
        exit();
    }
}
if ($app->request->isOptions()) {
    return true;
    //break;
}

if (ENVIRONMENT == 1) {
    error_reporting(E_ALL ^ (E_STRICT | E_DEPRECATED | E_NOTICE));
    ini_set('display_errors', FALSE); // Error display - FALSE only in production environment or real server
    ini_set('log_errors', TRUE); // Error logging engine
    ini_set('error_log', __DIR__ . '/php-errors/errors.log'); // Logging file path
    ini_set('log_errors_max_len', 1024); // Logging file size
} elseif (ENVIRONMENT == 0) {
    error_reporting(E_ALL); // Error engine - always TRUE!
    ini_set('display_errors', TRUE); // Error display - FALSE only in production environment or real server    ini_set('log_errors', TRUE); // Error logging engine
    ini_set('log_errors', TRUE); // Error logging engine
    ini_set('error_log', __DIR__ . '/php-errors/errors.log'); // Logging file path
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('log_errors', TRUE); // Error logging engine
    ini_set('error_log', __DIR__ . '/php-errors/errors.log'); // Logging file path
}


$app->post('/generateKey', function () use ($app) {
    global $decodedRequest;

    //check for required params
    verifyRequiredParams(array('application_name'));

    //reading post params
    //$application_name = $app->request->post('application_name');
    $application_name = $decodedRequest['application_name'];

    $db = new DbHandler();

    $result = $db->createKey($application_name);

    echoResponse(200, $result);
});


//************************ROLES***************************//
$app->get('/roles', function () use ($app) {

    $db = new DbHandler();

    $multiple_result = false;
    $db->getAllRoles();
});
//************************END OF ROLES***************************//

//************************PAYMENT_METHODS***************************//
$app->get('/payment_methods', function () use ($app) {

    $db = new DbHandler();

    $multiple_result = false;
    $db->getAllPaymentMethods();

});
//************************END OF PAYMENT_METHODS***************************//

//************************TRANSACTION TYPES METHODS***************************//
$app->get('/transaction_types', function () use ($app) {

    $db = new DbHandler();

    $multiple_result = false;
    $db->getAllTransactionTypes();

});
//************************END OF TRANSACTION TYPES METHODS***************************//

//************************PRODUCTS CATEGORIES***************************//
//************************END OF PRODUCTS CATEGORIES***************************//

//************************PRODUCTS***************************//
//************************END OF PRODUCTS***************************//

//************************MEASURES***************************//
//************************END OF MEASURES***************************//

//************************MARKETEER PRODUCTS***************************//
//************************END OF MARKETEER PRODUCTS***************************//


//************************MARKET FEES***************************//
$app->get('/market_fee', function () use ($app) {

    //verifyRequiredParams(array('seller_mobile_number'));

    $seller_mobile_number = $app->request->get('seller_mobile_number');
    //$buyer_mobile_number = $app->request->get('buyer_mobile_number');

    $db = new DbHandler();

    $db->getAllPendingMarketCharges($seller_mobile_number );

});
//************************END OF MARKET FEES***************************//

//************************TRANSACTION***************************//
$app->get('/transactions', function () use ($app) {

    $cart_id = $app->request->get('cart_id');
    $seller_id = $app->request->get('seller_id');
    $buyer_id = $app->request->get('buyer_id');
    $seller_mobile_number = $app->request->get('seller_mobile_number');
    $buyer_mobile_number = $app->request->get('buyer_mobile_number');

    $db = new DbHandler();

    $multiple_result = true;
    $db->getAllTransactions($cart_id ,$seller_id ,$buyer_id ,$seller_mobile_number ,$buyer_mobile_number ,$multiple_result);

});

$app->get('/summary_transactions', function () use ($app) {

    $cart_id = $app->request->get('cart_id');
    $seller_id = $app->request->get('seller_id');
    $period = $app->request->get('period');
    $seller_mobile_number = $app->request->get('seller_mobile_number');
    $buyer_mobile_number = $app->request->get('buyer_mobile_number');

    $db = new DbHandler();

    $multiple_result = true;
    $db->getAllTransactionsSummary($period ,$seller_mobile_number);

});

//NSANO Payment API
//now disabling Nsano
//to enable "transactions"
$app->post('/disable_transactions', function () use ($app) {

    //checking for required parameters
    //verifyRequiredParams(array('school_id','class_id'));
    global $log;

    if (isset($_POST['transactions'])) {

        $json_object = $_POST['transactions'];

    } else {

        $app->response()->header('Content-Type', 'application/json');
        $json_object = file_get_contents('php://input');

    }

    //Decode JSON into an Array
    $transactions_json_obj = json_decode($json_object, true);

    if (true) {
        //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
        $file = __DIR__ . '/transaction_capture.txt';
        $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($transactions_json_obj) . "\n" . "\n";
        file_put_contents($file, $date, FILE_APPEND);
        //END OF WRITING TO FILE
    }

    //FILE_NAME,SCRIPT_NAME,METHOD OR ENDPOINT,MESSAGE,TASK
    $log->logInfo(APP_INFO_LOG, 'Transaction Capture' ,json_encode($transactions_json_obj));

    $transaction_type_id = $transactions_json_obj['transaction_type_id'];
    $seller_id = isset($transactions_json_obj['seller_id']) ? $transactions_json_obj['seller_id'] : null;
    $seller_firstname = isset($transactions_json_obj['seller_firstname']) ? $transactions_json_obj['seller_firstname'] : null;
    $seller_lastname = isset($transactions_json_obj['seller_lastname']) ? $transactions_json_obj['seller_lastname'] : null;
    $seller_mobile_number = isset($transactions_json_obj['seller_mobile_number']) ? $transactions_json_obj['seller_mobile_number'] : null;
    $buyer_id = isset($transactions_json_obj['buyer_id']) ? $transactions_json_obj['buyer_id'] : null;
    $buyer_firstname = isset($transactions_json_obj['buyer_firstname']) ? $transactions_json_obj['buyer_firstname'] : null;
    $buyer_lastname = isset($transactions_json_obj['buyer_lastname']) ? $transactions_json_obj['buyer_lastname'] : null;
    $buyer_mobile_number = isset($transactions_json_obj['buyer_mobile_number']) ? $transactions_json_obj['buyer_mobile_number'] : null;
    $buyer_email = isset($transactions_json_obj['buyer_email']) ? $transactions_json_obj['buyer_email'] : null;
    $amount = $transactions_json_obj['amount_due'];
    $device_serial = $transactions_json_obj['device_serial'];
    $transaction_date = $transactions_json_obj['transaction_date'];

    $route_code = isset($transactions_json_obj['route_code']) ? $transactions_json_obj['route_code'] : null;
    $transaction_channel = isset($transactions_json_obj['transaction_channel']) ? $transactions_json_obj['transaction_channel'] : null;
    $id_type = isset($transactions_json_obj['id_type']) ? $transactions_json_obj['id_type'] : null;
    $passenger_id = isset($transactions_json_obj['passenger_id']) ? $transactions_json_obj['passenger_id'] : null;
    $travel_date = isset($transactions_json_obj['travel_date']) ? $transactions_json_obj['travel_date'] : null;
    $travel_time = isset($transactions_json_obj['travel_time']) ? $transactions_json_obj['travel_time'] : null;
    $bus_schedule_id = isset($transactions_json_obj['bus_schedule_id']) ? $transactions_json_obj['bus_schedule_id'] : null;

    $stand_number = isset($transactions_json_obj['stand_number']) ? $transactions_json_obj['stand_number'] : null;
    //$transaction_details = isset($transactions_json_obj['transaction_details']) ? $transactions_json_obj['transaction_details'] : null;

    $seller = $seller_mobile_number;
    $buyer = $buyer_mobile_number;

    if(empty(trim($seller_mobile_number))){
        $seller = null;
    }

    if (empty(trim($buyer_mobile_number))){
        $buyer = null;
    }

    $db = new DbHandler();

    $db->createNsanoTransactionsSummaries($transaction_type_id,$stand_number,
        $route_code,$transaction_channel,$id_type,$passenger_id,$bus_schedule_id,$travel_date,$travel_time,
        $seller_id, $seller_firstname,$seller_lastname, $seller,
        $buyer_id,  $buyer_firstname,$buyer_lastname,$buyer, $buyer_email,
        $amount, $device_serial, $transaction_date);

});

//UNZA Payment API
$app->post('/transactions', function () use ($app) {

    //checking for required parameters
    //verifyRequiredParams(array('school_id','class_id'));

    if (isset($_POST['transactions'])) {

        $json_object = $_POST['transactions'];

    } else {

        $app->response()->header('Content-Type', 'application/json');
        $json_object = file_get_contents('php://input');

    }

    //Decode JSON into an Array
    $transactions_json_obj = json_decode($json_object, true);

    if (true) {
        //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
        $file = __DIR__ . '/Transaction-Capture.txt';
        $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($transactions_json_obj) . "\n" . "\n";
        file_put_contents($file, $date, FILE_APPEND);
        //END OF WRITING TO FILE
    }

    //FILE_NAME,SCRIPT_NAME,METHOD OR ENDPOINT,MESSAGE,TASK
    global $log;
    $log->logInfo(APP_INFO_LOG, 'Transaction Capture',json_encode($transactions_json_obj));

    $transaction_type_id = $transactions_json_obj['transaction_type_id'];
    $seller_id = !empty($transactions_json_obj['seller_id']) ? $transactions_json_obj['seller_id'] : null;
    $seller_firstname = !empty($transactions_json_obj['seller_firstname']) ? $transactions_json_obj['seller_firstname'] : null;
    $seller_lastname = !empty($transactions_json_obj['seller_lastname']) ? $transactions_json_obj['seller_lastname'] : null;
    $seller_mobile_number = !empty(trim($transactions_json_obj['seller_mobile_number'])) ? startsWith(trim($transactions_json_obj['seller_mobile_number']),'260') : null;
    $buyer_id = !empty($transactions_json_obj['buyer_id']) ? $transactions_json_obj['buyer_id'] : null;
    $buyer_firstname = !empty($transactions_json_obj['buyer_firstname']) ? $transactions_json_obj['buyer_firstname'] : null;
    $buyer_lastname = !empty($transactions_json_obj['buyer_lastname']) ? $transactions_json_obj['buyer_lastname'] : null;
    $buyer_mobile_number = !empty(trim($transactions_json_obj['buyer_mobile_number'])) ? startsWith(trim($transactions_json_obj['buyer_mobile_number']),'260') : null;
    $buyer_email = !empty($transactions_json_obj['buyer_email']) ? $transactions_json_obj['buyer_email'] : null;
    $amount = $transactions_json_obj['amount_due'];
    $device_serial = $transactions_json_obj['device_serial'];
    $transaction_date = $transactions_json_obj['transaction_date'];

    $route_code = !empty($transactions_json_obj['route_code']) ? $transactions_json_obj['route_code'] : null;
    $transaction_channel = !empty($transactions_json_obj['transaction_channel']) ? $transactions_json_obj['transaction_channel'] : null;
    $id_type = !empty($transactions_json_obj['id_type']) ? $transactions_json_obj['id_type'] : null;
    $passenger_id = !empty($transactions_json_obj['passenger_id']) ? $transactions_json_obj['passenger_id'] : null;
    $travel_date = !empty($transactions_json_obj['travel_date']) ? $transactions_json_obj['travel_date'] : null;
    $travel_time = !empty($transactions_json_obj['travel_time']) ? $transactions_json_obj['travel_time'] : null;
    $bus_schedule_id = !empty($transactions_json_obj['bus_schedule_id']) ? $transactions_json_obj['bus_schedule_id'] : null;

    $stand_number = !empty($transactions_json_obj['stand_number']) ? $transactions_json_obj['stand_number'] : null;
    //$transaction_details = empty($transactions_json_obj['transaction_details']) ? $transactions_json_obj['transaction_details'] : null;

    $db = new DbHandler();

    $db->createUNZATransactionsSummaries($transaction_type_id,$stand_number,
        $route_code,$transaction_channel,$id_type,$passenger_id,$bus_schedule_id,$travel_date,$travel_time,
        $seller_id, $seller_firstname,$seller_lastname, $seller_mobile_number,
        $buyer_id,  $buyer_firstname,$buyer_lastname,$buyer_mobile_number, $buyer_email,
        $amount, $device_serial, $transaction_date);

});

$app->post('/credit_wallet', function () use ($app) {
    global $decodedRequest;
    global $log;

    //check for required params
    //verifyRequiredParams(array('mno','msisdn','amount'));

    //reading post params
    //$mno = $app->request->post('mno');
    $mno = $decodedRequest['mno'];
    //$msisdn = $app->request()->post('msisdn');
    $msisdn = $decodedRequest['msisdn'];
    //$amount = $app->request()->post('amount');
    $amount = $decodedRequest['amount'];
    //$refID = $app->request()->post('refID');
    $refID = $decodedRequest['refID'];

    $json = array(
        "mno" => $mno,
        "msisdn" => $msisdn,
        "amount" => $amount,
        "refID" => $refID
    );

    //FILE_NAME,SCRIPT_NAME,METHOD OR ENDPOINT,MESSAGE,TASK
    $log->logInfo(APP_INFO_LOG, 'Credit Wallet Capture' ,json_encode($json));

    $db = new DbHandler();

    $app->contentType('application/json');
    echo $result = $db->nsano_API($mno,'mikopo',$msisdn,$amount,$refID,1);

});

$app->post('/debit_wallet', function () use ($app) {
    global $decodedRequest;
    global $log;

    //check for required params
    verifyRequiredParams(array('mno','msisdn','amount'));

    //reading post params
    //$mno = $app->request->post('mno');
    $mno = $decodedRequest['mno'];
    //$msisdn = $app->request()->post('msisdn');
    $msisdn = $decodedRequest['msisdn'];
    //$amount = $app->request()->post('amount');
    $amount = $decodedRequest['amount'];
    //$refID = $app->request()->post('refID');
    $refID = $decodedRequest['refID'];

    $json = array(
        "mno" => $mno,
        "msisdn" => $msisdn,
        "amount" => $amount,
        "refID" => $refID
    );

    //FILE_NAME,SCRIPT_NAME,METHOD OR ENDPOINT,MESSAGE,TASK
    $log->logInfo(APP_INFO_LOG, 'Debit Wallet Capture' ,json_encode($json));

    $db = new DbHandler();

    $app->contentType('application/json');
    echo $result = $db->nsano_API($mno,'malipo',$msisdn,$amount,$refID,1);

});

$app->post('/notify_fusion', function () use ($app) {
    global $decodedRequest;
    global $log;

    //check for required params
    verifyRequiredParams(array('code','refID'));

    //reading post params
    $code = $decodedRequest['code'];
    //$refID = $app->request()->post('refID');
    $refID = $decodedRequest['refID'];

    $json = array(
        "code" => $code,
        "refID" => $refID
    );

    //FILE_NAME,SCRIPT_NAME,METHOD OR ENDPOINT,MESSAGE,TASK
    $log->logInfo(APP_INFO_LOG, 'notify_fusion Capture' ,json_encode($json));

    $db = new DbHandler();

    $app->contentType('application/json');
    echo $result = $db->notify_API('kusubiri_mikopo',$refID,$code);

    //echoResponse(200, $result);
});

$app->get('/check_transaction_status', function () use ($app) {

    $metadata = $app->request->get('metadata');

    $db = new DbHandler();

    $app->contentType('application/json');
    echo $result =  $db->check_status_API($metadata);

});

$app->post('/credit_callback', function () use ($app) {

    $json_object = json_decode(file_get_contents('php://input'), true);

    //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
    $file = __DIR__ . '/credit_callback_capture.txt';

    $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($json_object) . "\n" . "\n";

    file_put_contents($file, $date, FILE_APPEND);
    //END OF WRITING TO FILE

    echoResponse(201, $json_object);
});

$app->post('/debit_callback', function () use ($app) {

    $json_object = json_decode(file_get_contents('php://input'), true);

    //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
    $file = __DIR__ . '/NSANO-Debit_Callback_Capture.txt';
    $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($json_object) . "\n" . "\n";
    file_put_contents($file, $date, FILE_APPEND);
    //END OF WRITING TO FILE

    $debt_callback_response = file_get_contents('php://input');
    $json_object = json_decode($debt_callback_response, true);

    $code = isset($json_object['code']) ? $json_object['code']: null;
    $msg = isset($json_object['msg']) ? $json_object['msg']: null;
    $reference = isset($json_object['reference']) ? $json_object['reference']: null;
    $system_code = isset($json_object['system_code']) ? $json_object['system_code']: null;
    $transactionID = isset($json_object['transactionID']) ? $json_object['transactionID']: null;

    $db = new DbHandler();

    $db->updateDebitCallbackResponseLog($reference,$debt_callback_response);
    $db->updateNsanoDebitCallbackDetails($msg,$reference,$code,$system_code,$transactionID);

});

$app->post('/napsa_debit_callback', function () use ($app) {

    $json_object = json_decode(file_get_contents('php://input'), true);

    //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
    $file = __DIR__ . '/Debit-Callback-Capture.txt';
    $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($json_object) . "\n" . "\n";
    file_put_contents($file, $date, FILE_APPEND);
    //END OF WRITING TO FILE

    global $log;
    $log->logInfo(APP_INFO_LOG,'Debit Callback Capture' ,json_encode($json_object));

    $nested_json_object = $json_object['query'];
    $statusCode = isset($json_object['statusCode']) ? $json_object['statusCode']: null;
    $statusDesc = isset($json_object['statusDesc']) ? $json_object['statusDesc']: null;
    $coreTransactionID = isset($nested_json_object['coreTransactionID']) ? $nested_json_object['coreTransactionID']: null;
    $extTransactionID = isset($nested_json_object['extTransactionID']) ? $nested_json_object['extTransactionID']: null;
    $extNarration = isset($nested_json_object['extNarration']) ? $nested_json_object['extNarration']: null;
    $serviceID = isset($nested_json_object['serviceID']) ? $nested_json_object['serviceID']: null;
    $accountNumber = isset($nested_json_object['accountNumber']) ? $nested_json_object['accountNumber']: null;
    $amount = isset($nested_json_object['amount']) ? $nested_json_object['amount']: null;

    $db = new DbHandler();

    $db->updateDebitCallbackResponseLog($json_object,$extTransactionID);
    $db->updateDebitCallbackResponseDetails($statusCode,$statusDesc,$coreTransactionID,$extTransactionID,$accountNumber,$amount);

});

$app->post('/napsa_credit_callback', function () use ($app) {

    $json_object = json_decode(file_get_contents('php://input'), true);

    //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
    $file = __DIR__ . '/Credit-Callback-Capture.txt';
    $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($json_object) . "\n" . "\n";
    file_put_contents($file, $date, FILE_APPEND);
    //END OF WRITING TO FILE

    global $log;
    $log->logInfo(APP_INFO_LOG,'Credit Callback Capture' ,json_encode($json_object));

    $nested_json_object = $json_object['query'];
    $statusCode = isset($json_object['statusCode']) ? $json_object['statusCode']: null;
    $statusDesc = isset($json_object['statusDesc']) ? $json_object['statusDesc']: null;
    $coreTransactionID = isset($nested_json_object['coreTransactionID']) ? $nested_json_object['coreTransactionID']: null;
    $extTransactionID = isset($nested_json_object['extTransactionID']) ? $nested_json_object['extTransactionID']: null;
    $extNarration = isset($nested_json_object['extNarration']) ? $nested_json_object['extNarration']: null;
    $serviceID = isset($nested_json_object['serviceID']) ? $nested_json_object['serviceID']: null;
    $accountNumber = isset($nested_json_object['accountNumber']) ? $nested_json_object['accountNumber']: null;
    $amount = isset($nested_json_object['amount']) ? $nested_json_object['amount']: null;

    $db = new DbHandler();

    $db->updateCreditCallbackResponseLog($json_object,$extTransactionID);
    $db->updateCreditCallbackResponseDetails($statusCode,$statusDesc,$coreTransactionID,$extTransactionID,$accountNumber,$amount);

});



$app->post('/SMS', function () use ($app) {

    global $decodedRequest;
    global $log;

    //check for required params
    verifyRequiredParams(array('msg','recipient'));

    //reading post params
    $msg = $decodedRequest['msg'];
    //$refID = $app->request()->post('refID');
    $recipient = $decodedRequest['recipient'];

    $json = array(
        "msg" => $msg,
        "recipient" => $recipient
    );

    //FILE_NAME,SCRIPT_NAME,METHOD OR ENDPOINT,MESSAGE,TASK
    $log->logInfo(APP_INFO_LOG, 'SMS Capture' ,json_encode($json));

    $db = new DbHandler();

    $app->contentType('application/json');

    $db->pushSMS($msg,$recipient);

    //echoResponse(201, $json_object);
});

$app->get('/fee', function () use ($app) {

    $db = new DbHandler();

    $result = $db->getTransactionFees();

    echoResponse(200, $result);

});

$app->get('/transaction_fee', function () use ($app) {

    $db = new DbHandler();

    $result = $db->getTotalTransactionFee(1);

    echoResponse(200, $result);

});

$app->get('/marketeer_kyc', function () use ($app) {

    $db = new DbHandler();

    $result = $db->fetchMarketeerKYC();

    echoResponse(200, $result);

});

$app->get('/marketeer_kyc_single', function () use ($app) {

    $db = new DbHandler();

    $result = $db->fetchMarketeerKYCSingle();

    echoResponse(200, $result);

});

$app->get('/test', function () use ($app) {

    parse_str($_SERVER["QUERY_STRING"], $params);

    //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
    $file = __DIR__ . '/get_parameters_capture.txt';

    $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($params) . "\n" . "\n";

    file_put_contents($file, $date, FILE_APPEND);
    //END OF WRITING TO FILE

    echoResponse(200, $params);
});
$app->post('/test', function () use ($app) {

    $json_object = json_decode(file_get_contents('php://input'), true);

    //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
    $file = __DIR__ . '/post_parameters_capture.txt';

    $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($json_object) . "\n" . "\n";

    file_put_contents($file, $date, FILE_APPEND);
    //END OF WRITING TO FILE

    echoResponse(201, $json_object);
});
$app->put('/test', function () use ($app) {

    $json_object = json_decode(file_get_contents('php://input'), true);

    //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
    $file = __DIR__ . '/put_parameters_capture.txt';

    $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($json_object) . "\n" . "\n";

    file_put_contents($file, $date, FILE_APPEND);
    //END OF WRITING TO FILE

    echoResponse(200, $json_object);
});
//************************END OF TRANSACTION***************************//



//METHODS ARE RECURRING CALLED DEPENDING ON REQUIRED USAGE
//BEGINNING OF FUNCTIONS---------------------------------------------------------------------------------------------------

//Method to display
//Echoing json response to client
function echoResponse($status_code, $response)
{
    //Getting app instance
    $app = \Slim\Slim::getInstance();

    //Setting Http response code
    $app->status($status_code);

    //setting response content type to json
    $app->contentType('application/json');

//    header('Access-Control-Allow-Origin: *');
//    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
//    header('Access-Control-Allow-Headers: Authorization, Content-Type');
    header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');
    header_remove('X-Powered-By');
    echo json_encode($response);//displaying the response in json format


//    header('Cache-Control: no-cache, no-store, must-revalidate');
//    header('Pragma: no-cache');
//    header('Expires: 0');
}


//Verifying required params posted or notgit
function verifyRequiredParams($required_fields)
{
    global $decodedRequest;
    //Assuming there is no error
    $error = false;

    //Error fields are blank
    $error_fields = '';

    //$request_params = array();

    //Getting the request parameters
    $request_params = $decodedRequest;

    //Handling PUT request params
    /*if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

        //Getting the app instance
        $app = \Slim\Slim::getInstance();

        //Getting put parameters in request params variable
        parse_str($decodedRequest, $request_params);
    }*/

    //Looping through all the parameters
    foreach ($required_fields as $field) {
        //if any required parameter is missing
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {

            $error = true;

            //Concatenating the missing parameters in error fields
            $error_fields .= $field . ', ';
        }
    }

    //if there is a parameter missing then error is true
    if ($error) {
        //Required field(s) are missing or empty
        //echo error json and stop the app

        //Creating response array
        $response = array();

        //Getting app instance
        $app = \Slim\Slim::getInstance();

        //Adding values to response array
        $response['error'] = true;
        $response['message'] = 'Require field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';

        //Displaying response with error code 400
        echoResponse(400, $response);

        //Stopping the app
        $app->stop();
    }
}

//verifying the labels in the excel
function verifyRequiredLabels($required_fields)
{
    //Assuming there is no error
    $error = false;

    //Error fields are blank
    $error_fields = '';

    //$request_params = array();

    //Getting the request parameters
    $request_params = $_REQUEST;


    //Looping through all the parameters
    foreach ($required_fields as $field) {
        # code...
        //if any required parameter is missing
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            # code...

            //error is true
            $error = true;

            //Concatnating the missing parameters in error fields
            $error_fields .= $field . ', ';
        }
    }

    //if there is a parameter missing then error is true
    if ($error) {
        # code...

        //Creating response array
        $response = array();

        //Getting app instance
        $app = \Slim\Slim::getInstance();

        //Adding values to response array
        $response['error'] = true;
        $response['message'] = 'Require field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';

        //Displaying response with error code 400
        echoResponse(400, $response);

        //Stopping the app
        $app->stop();
    }
}

// Function for basic field validation (present and neither empty nor only white space
function isNullOrEmptyString($str){
    return (!isset($str) || trim($str) === '');
}

// Function to check string starting with given substring
function startsWith ($string, $startString)
{
    $len = strlen($startString);

    if(substr($string, 0, $len) === $startString) {//true
        return $string;
    }else {//false
        return "260". $string;
    }

}

//ADDING MIDDLE LAYER TO AUTHENTICATE EVERY REQUEST
//Checking if the request has valid api key in the 'Authorization' header
function authenticateAPIKEY(\Slim\Route $route)
{
    //Getting request headers
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();

    //Verifying the headers
    if (isset($headers['API_KEY'])) {

        $db = new DbHandler();

        //Getting api key from database
        $api_key = $headers['API_KEY'];

        //Validating api key from database
        if (!$db->isValidApiKey($api_key)) {
            //API KEY IS NOT PRESENT
            $response['error'] = true;
            $response['message'] = 'Access Denied. Invalid API key';
            echoResponse(401, $response);
            $app->stop();
        }
    } else {
        //API KEY IS MISSING IN HEADER
        $response['error'] = true;
        $response['message'] = 'API key is missing';
        echoResponse(400, $response);
        $app->stop();
    }
}

//Method to authenticate the source IP Address
function authenticateIPAddress(\Slim\Route $route)
{
    $response = array();
    $app = \Slim\Slim::getInstance();

    $whitelist = array('123.456.789', '456.789.123', '789.123.456');

    if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {

        //Request for API key

    } else {
        // Deny connection
        $response['error'] = true;
        $response['message'] = 'Access Denied. Your IP Address is Blacklisted';
        echoResponse(401, $response);
        $app->stop();
    }
}

//END OF FUNCTIONS---------------------------------------------------------------------------------------------------
$app->run();
