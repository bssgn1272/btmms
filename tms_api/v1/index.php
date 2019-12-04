<?php
/**
 * @author: Chimuka Moonde
 * @mobile No: 0973297682
 * @email : chimukamoonde@gmail.com
 * Kindly note that this is a customized version of slim 2
 * @editor: Francis Chulu
 * @Date: 07/11/2019
 * Pointed all users table queries to traders table
 */

//including the required files
require_once '../include/Config.php';
include '../include/StatusCodes.php';
include '../include/Utils.php';
require_once '../include/Security.php';
require_once '../include/DbHandler.php';
require_once '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();
//Creating a slim instance
$app = new \Slim\Slim();
//For now we expect json request for POST and PUT
if ($_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'POST') {
    $decodedRequest = Utils::handleRequest();
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

    //check for required params
    verifyRequiredParams(array('application_name'));

    //reading post params
    $application_name = $app->request->post('application_name');

    $db = new DbHandler();

    $result = $db->createKey($application_name);

    echoResponse(200, $result);
});

$app->get('/users', function () use ($app) {


    $trader_id = $app->request->get('trader_id');
    $role = $app->request->get('role');
    $mobile_number = $app->request->get('mobile_number');

    $db = new DbHandler();

    $multiple_result = true;
    $db->getAllTraders($trader_id, $mobile_number, $role, $multiple_result);

});

$app->get('/balance', function () use ($app) {


    $trader_id = $app->request->get('trader_id');
    $mobile_number = $app->request->get('mobile_number');

    $db = new DbHandler();

    $multiple_result = true;
    $result =$db->checkTokenBalance($trader_id,$mobile_number);
    echoResponse(200,$result);

});

$app->post('/register', function () use ($app) {
    global $decodedRequest;
    //check for required params
    verifyRequiredParams(array('firstname', 'lastname', 'nrc', 'gender', 'mobile_number', 'password'));

    //reading post params
    //$role = $decodedRequest['role'];
    $firstname = $decodedRequest['firstname'];
    $lastname = $decodedRequest['lastname'];
    $nrc = $decodedRequest['nrc'];
    $gender = $decodedRequest['gender'];
    $dob = $decodedRequest['dob'];
    // $email = $app->request->post('email');
    $mobile_number = $decodedRequest['mobile_number'];
    //$account_number = $decodedRequest['account_number'];
    $password = $decodedRequest['password'];

    //validating email address
    //validateEmail($email);

    $db = new DbHandler();

    $db->createUser($firstname, $lastname, $nrc, $gender, $dob, $mobile_number, $password);

});

$app->put('/update_profile', function () use ($app) {
    global $decodedRequest;
    //check for required params
    verifyRequiredParams(array('firstname', 'lastname', 'nrc', 'gender', 'mobile_number'));


    //reading post params
    //$role = $decodedRequest['role'];
    $trader_id = $decodedRequest['trader_id'];
    $firstname = $decodedRequest['firstname'];
    $lastname = $decodedRequest['lastname'];
    $nrc = $decodedRequest['nrc'];
    $gender = $decodedRequest['gender'];
    $dob = $decodedRequest['dob'];
    // $email = $app->request->post('email');
    $mobile_number = $decodedRequest['mobile_number'];
    //$account_number = $decodedRequest['account_number'];
    //$password = $decodedRequest['password'];

    //$password = $app->request->post('password');

    $db = new DbHandler();

    $db->updateUser($trader_id, $firstname, $lastname, $nrc, $gender, $dob, $mobile_number);

});

$app->post('/login', function () use ($app) {
    global $decodedRequest;
    //check for required params
    //Email was removed so traders will be required to use their mobile number to login
    //verifyRequiredParams(array('emailorMobile', 'password'));
    verifyRequiredParams(array('mobile_number', 'password'));
    //reading post params
    //$role = $decodedRequest['role'];
    $mobile_number = $decodedRequest['mobile_number'];
    $password = $decodedRequest['password'];

    $db = new DbHandler();

    $db->checkLogin($mobile_number, $password);
});

$app->post('/change_password', function () use ($app) {
    //check for required params
    global $decodedRequest;
    verifyRequiredParams(array('mobile_number', 'old_password', 'password'));

    //$role = $decodedRequest['role'];
    $mobile_number = $decodedRequest['mobile_number'];
    $old_password = $decodedRequest['old_password'];
    $password = $decodedRequest['password'];

    $db = new DbHandler();

    $db->changePassword($mobile_number, $old_password, $password);

});

$app->post('/pin_request', function () use ($app) {
    //check for required params
    global $decodedRequest;
    verifyRequiredParams(array('mobile_number'));

    //$role = $decodedRequest['role'];
    $mobile_number = $decodedRequest['mobile_number'];

    $db = new DbHandler();

    $db->resetPasswordRequest($mobile_number);

});

$app->post('/reset_password', function () use ($app) {
    //check for required params
    global $decodedRequest;
    verifyRequiredParams(array('mobile_number', 'pin', 'new_password'));

    //reading post params
    //$role = $decodedRequest['role'];
    $mobile_number = $decodedRequest['mobile_number'];
    $pin = $decodedRequest['pin'];
    $password = $decodedRequest['new_password'];

    $db = new DbHandler();

    $db->resetPassword($mobile_number, $pin, $password);

});


//************************ROLES***************************//
$app->get('/roles', function () use ($app) {

    $pharmacy_id = $app->request->get('pharmacy_id');
    $pharmacy_name = $app->request->get('pharmacy_name');

    $db = new DbHandler();

    $multiple_result = false;
    $db->getAllRoles();
});
//************************END OF ROLES***************************//

//************************ROLES***************************//
$app->get('/payment_methods', function () use ($app) {

    $pharmacy_id = $app->request->get('pharmacy_id');
    $pharmacy_name = $app->request->get('pharmacy_name');

    $db = new DbHandler();

    $multiple_result = false;
    $db->getAllPaymentMethods();

});
//************************END OF ROLES***************************//

//************************PRODUCTS CATEGORIES***************************//
$app->get('/product_categories', function () use ($app) {

    $pharmacy_id = $app->request->get('pharmacy_id');
    $pharmacy_name = $app->request->get('pharmacy_name');

    $db = new DbHandler();

    $multiple_result = false;
    $db->getAllProductCategory();

});

$app->post('/product_categories', function () use ($app) {

    //check for required params
    global $decodedRequest;
    verifyRequiredParams(array('category_name'));

    $category_name = $decodedRequest['category_name'];
    $category_description = $decodedRequest['category_description'];

    $db = new DbHandler();

    $db->createProductsCategory($category_name, $category_description);

});

$app->put('/product_categories', function () use ($app) {

    //check for required params
    global $decodedRequest;
    verifyRequiredParams(array('category_name', 'product_category_id'));

    $category_name = $decodedRequest['category_name'];
    $category_description = $decodedRequest['category_description'];
    $product_category_id = $decodedRequest['product_category_id'];

    $db = new DbHandler();

    $db->updateProductCategory($category_name, $category_description, $product_category_id);

});
//************************END OF PRODUCTS CATEGORIES***************************//

//************************PRODUCTS***************************//
$app->get('/products', function () use ($app) {

    $pharmacy_id = $app->request->get('pharmacy_id');
    $pharmacy_name = $app->request->get('pharmacy_name');

    $db = new DbHandler();

    $multiple_result = false;
    $db->getAllProducts();

});

$app->post('/products', function () use ($app) {

    //check for required params
    global $decodedRequest;
    verifyRequiredParams(array('product_category_id', 'product_name'));

    $product_category_id = $decodedRequest['product_category_id'];
    $product_name = $decodedRequest['product_name'];
    $product_description = $decodedRequest['product_description'];

    $db = new DbHandler();

    $db->createProduct($product_category_id, $product_image = null, $product_name, $product_description);

});

$app->put('/products', function () use ($app) {

    //check for required params
    global $decodedRequest;
    verifyRequiredParams(array('product_category_id', 'product_name', 'product_id'));

    $product_category_id = $decodedRequest['product_category_id'];
    $product_name = $decodedRequest['product_name'];
    $product_description = $decodedRequest['product_description'];
    $product_id = $decodedRequest['product_id'];

    $db = new DbHandler();

    $db->updateProduct($product_category_id, $product_image = null, $product_name, $product_description, $product_id);

});
//************************END OF PRODUCTS***************************//

//************************MEASURES***************************//
$app->get('/measures', function () use ($app) {

    $pharmacy_id = $app->request->get('pharmacy_id');
    $pharmacy_name = $app->request->get('pharmacy_name');

    $db = new DbHandler();

    $multiple_result = false;
    $db->getAllMeasures();

});

$app->post('/measures', function () use ($app) {

    //check for required params
    global $decodedRequest;
    verifyRequiredParams(array('unit_name'));

    $unit_name = $decodedRequest['unit_name'];
    $unit_description = $decodedRequest['unit_description'];

    $db = new DbHandler();

    $db->createMeasure($unit_name, $unit_description);

});

$app->put('/measures', function () use ($app) {

    //check for required params
    global $decodedRequest;
    verifyRequiredParams(array('unit_name', 'unit_of_measure_id'));

    $unit_name = $decodedRequest['unit_name'];
    $unit_description = $decodedRequest['unit_description'];
    $unit_of_measure_id = $decodedRequest['unit_of_measure_id'];

    $db = new DbHandler();

    $db->updateMeasure($unit_name, $unit_description, $unit_of_measure_id);

});
//************************END OF MEASURES***************************//

//************************MARKETEER PRODUCTS***************************//
$app->get('/marketeer_products', function () use ($app) {

    $trader_id = $app->request->get('trader_id');
    $pharmacy_name = $app->request->get('pharmacy_name');

    $db = new DbHandler();

    $multiple_result = false;
    $db->getAllMarketeerProducts($trader_id);

});

$app->post('/marketeer_products', function () use ($app) {

    //check for required params
    global $decodedRequest;
    verifyRequiredParams(array('trader_id', 'product_id'));

    $trader_id = $decodedRequest['trader_id'];
    $product_id = $decodedRequest['product_id'];
    $unit_of_measure_id = $decodedRequest['unit_of_measure_id'];
    $price = $decodedRequest['price'];

    $db = new DbHandler();

    $db->createMarketeerProduct($trader_id, $product_id, $unit_of_measure_id, $price);

});

$app->put('/marketeer_products', function () use ($app) {

    //check for required params
    global $decodedRequest;
    verifyRequiredParams(array('trader_id', 'product_id', 'marketeer_products_id'));

    $trader_id = $decodedRequest['trader_id'];
    $product_id = $decodedRequest['product_id'];
    $unit_of_measure_id = $decodedRequest['unit_of_measure_id'];
    $price = $decodedRequest['price'];
    $marketeer_products_id = $decodedRequest['marketeer_products_id'];

    $db = new DbHandler();

    $db->updateMarketeerProduct($trader_id, $product_id, $unit_of_measure_id, $price, $marketeer_products_id);

});
//************************END OF MARKETEER PRODUCTS***************************//


//************************TOKEN PROCUREMENT***************************//
$app->get('/token_procurement', function () use ($app) {

    $trader_id = $app->request->get('trader_id');
    $role = $app->request->get('role');

    $db = new DbHandler();

    $multiple_result = true;
    $db->getAllTokenProcurement($trader_id, $multiple_result);

});

$app->post('/token_procurement', function () use ($app) {
    //check for required params
    global $decodedRequest;
    verifyRequiredParams(array('trader_id', 'amount_tendered', 'reference_number', 'agent_id', 'payment_method_id', 'procuring_msisdn', 'device_serial', 'transaction_date'));

    $trader_id = $decodedRequest['trader_id'];
    $amount_tendered = $decodedRequest['amount_tendered'];
    $reference_number = $decodedRequest['reference_number'];
    $agent_id = $decodedRequest['agent_id'];
    $organisation_id = $decodedRequest['organisation_id'];
    $payment_method_id = $decodedRequest['payment_method_id'];
    $procuring_msisdn = $decodedRequest['procuring_msisdn'];
    $device_serial = $decodedRequest['device_serial'];
    $transaction_date = $decodedRequest['transaction_date'];

    $db = new DbHandler();

    $db->createToken($trader_id, $amount_tendered, $reference_number, $agent_id, $organisation_id, $payment_method_id, $procuring_msisdn, $device_serial, $transaction_date);

});
//************************END OF TOKEN PROCUREMENT***************************//

//************************TOKEN REDEMPTION**************************//
$app->get('/token_redemption', function () use ($app) {

    $trader_id = $app->request->get('trader_id');
    $role = $app->request->get('role');

    $db = new DbHandler();

    $multiple_result = true;
    $db->getAllTokenRedemption($trader_id, $multiple_result);

});

$app->post('/token_redemption', function () use ($app) {
    //check for required params
    global $decodedRequest;
    verifyRequiredParams(array('trader_id', 'token_value_tendered', 'reference_number',  'payment_method_id', 'recipient_msisdn', 'device_serial', 'transaction_date'));

    $trader_id = $decodedRequest['trader_id'];
    $token_value_tendered = $decodedRequest['token_value_tendered'];
    $reference_number = $decodedRequest['reference_number'];
    //$agent_id = $decodedRequest['agent_id'];
    //$organisation_id = $decodedRequest['organisation_id'];
    $payment_method_id = $decodedRequest['payment_method_id'];
    $recipient_msisdn = $decodedRequest['recipient_msisdn'];
    $device_serial = $decodedRequest['device_serial'];
    $transaction_date = $decodedRequest['transaction_date'];

    $db = new DbHandler();

    $db->createTokenRedemption($trader_id, $token_value_tendered, $reference_number, null, null, $payment_method_id, $recipient_msisdn, $device_serial, $transaction_date);

});
//************************END OF TOKEN REDEMPTION***************************//

//************************REWARD CAMPAIGNS***************************//
$app->get('/reward_campaigns', function () use ($app) {

    $reward_campaign_id = $app->request->get('reward_campaign_id');
    $pharmacy_name = $app->request->get('pharmacy_name');

    $db = new DbHandler();

    $multiple_result = false;
    $db->getAllRewardCampaigns($reward_campaign_id);

});

$app->post('/reward_campaigns', function () use ($app) {

    //check for required params
    global $decodedRequest;
    verifyRequiredParams(array('campaign_name', 'marketeer_points_required', 'buyer_points_required', 'marketeer_points_multiplier', 'buyer_points_multiplier', 'active_from', 'active_to'));

    $campaign_name = $decodedRequest['campaign_name'];
    $campaign_description = $decodedRequest['campaign_description'];
    $marketeer_points_required = $decodedRequest['marketeer_points_required'];
    $buyer_points_required = $decodedRequest['buyer_points_required'];
    $marketeer_points_multiplier = $decodedRequest['marketeer_points_multiplier'];
    $buyer_points_multiplier = $decodedRequest['buyer_points_multiplier'];
    $active_from = $decodedRequest['active_from'];
    $active_to = $decodedRequest['active_to'];

    $db = new DbHandler();

    $db->createRewardCampaign($campaign_name, $campaign_description, $marketeer_points_required, $buyer_points_required, $marketeer_points_multiplier, $buyer_points_multiplier, $active_from, $active_to);

});

$app->put('/reward_campaigns', function () use ($app) {

    //check for required params
    global $decodedRequest;
    verifyRequiredParams(array('campaign_name', 'marketeer_points_required', 'buyer_points_required', 'marketeer_points_multiplier', 'buyer_points_multiplier', 'active_from', 'active_to', 'reward_campaign_id'));

    $campaign_name = $decodedRequest['campaign_name'];
    $campaign_description = $decodedRequest['campaign_description'];
    $marketeer_points_required = $decodedRequest['marketeer_points_required'];
    $buyer_points_required = $decodedRequest['buyer_points_required'];
    $marketeer_points_multiplier = $decodedRequest['marketeer_points_multiplier'];
    $buyer_points_multiplier = $decodedRequest['buyer_points_multiplier'];
    $active_from = $decodedRequest['active_from'];
    $active_to = $decodedRequest['active_to'];
    $reward_campaign_id = $decodedRequest['reward_campaign_id'];

    $db = new DbHandler();

    $db->updateRewardCampaign($campaign_name, $campaign_description, $marketeer_points_required, $buyer_points_required, $marketeer_points_multiplier, $buyer_points_multiplier, $active_from, $active_to, $reward_campaign_id);

});
//************************END OF REWARD CAMPAIGNS***************************//

//************************REDEEMED REWARDS***************************//
$app->get('/redeemed_rewards', function () use ($app) {

    $trader_id = $app->request->get('trader_id');
    $pharmacy_name = $app->request->get('pharmacy_name');

    $db = new DbHandler();

    $multiple_result = false;
    $db->getAllRedeemedRewards($trader_id);

});
//************************END OF REDEEMED REWARDS***************************//

//************************TRANSACTION***************************//
$app->get('/transactions', function () use ($app) {

    $cart_id = $app->request->get('cart_id');
    $pharmacy_name = $app->request->get('pharmacy_name');

    $db = new DbHandler();

    $multiple_result = false;
    $db->getAllCarts($cart_id, $multiple_result);

});

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

    $marketeer_id = $transactions_json_obj['marketeer_id'];
    $buyer_id = isset($transactions_json_obj['buyer_id']) ? $transactions_json_obj['buyer_id'] : null;
    $buyer_mobile_number = isset($transactions_json_obj['buyer_mobile_number']) ? $transactions_json_obj['buyer_mobile_number'] : null;
    $amount_due = $transactions_json_obj['amount_due'];
    $token_tendered = $transactions_json_obj['token_tendered'];
    $device_serial = $transactions_json_obj['device_serial'];
    $transaction_date = $transactions_json_obj['transaction_date'];
    $transaction_details = isset($transactions_json_obj['transaction_details']) ? $transactions_json_obj['transaction_details'] : null;

    $db = new DbHandler();

    $db->createTransactionsSummaries($marketeer_id, $buyer_id, $buyer_mobile_number,$amount_due, $token_tendered, $device_serial, $transaction_date, $transaction_details);

});

$app->post('/credit_wallet', function () use ($app) {

    //check for required params
    verifyRequiredParams(array('mno','msisdn','amount'));

    //reading post params
    $mno = $app->request->post('mno');
    $msisdn = $app->request()->post('msisdn');
    $amount = $app->request()->post('amount');
    $refID = $app->request()->post('refID');

    $db = new DbHandler();

    $result = $db->wallet_API($mno,'mikopo',$msisdn,$amount,$refID);

    echoResponse(200, $result);
});

$app->post('/debit_wallet', function () use ($app) {

    //check for required params
    verifyRequiredParams(array('mno','msisdn','amount'));

    //reading post params
    $mno = $app->request->post('mno');
    $msisdn = $app->request()->post('msisdn');
    $amount = $app->request()->post('amount');
    $refID = $app->request()->post('refID');

    $db = new DbHandler();

    $result = $db->wallet_API($mno,'malipo',$msisdn,$amount,$refID);

    echoResponse(200, $result);
});


$app->get('/check_transaction_status', function () use ($app) {

    $metadata = $app->request->get('metadata');

    $db = new DbHandler();

    $db->check_status_API($metadata);

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

//************************ROUTES***************************//
$app->get('/routes', function () use ($app) {

    $route_id = $app->request->get('route_id');

    $db = new DbHandler();

    $multiple_result = false;
    $db->getAllRoutes();

});
//************************END OF ROUTES***************************//

//************************ROUTES***************************//
$app->get('/departure_times', function () use ($app) {

    $route_id = $app->request->get('route_id');

    $db = new DbHandler();

    $multiple_result = false;
    $db->getAllRoutesTimes($route_id);

});
//************************END OF ROUTES***************************//

//************************ DEPARTURE TIMES***************************//
$app->get('/departure_times', function () use ($app) {

    $route_id = $app->request->get('route_id');

    $db = new DbHandler();

    $multiple_result = false;
    $db->getAllRoutesTimes($route_id);

});
//************************END OF DEPARTURE TIMES***************************//

//************************AVAILABLE BUS***************************//
$app->get('/available_buses', function () use ($app) {

    $route_id = $app->request->get('route_id');

    $db = new DbHandler();

    $multiple_result = false;
    $db->getAllAvailableBuses($route_id);

});
//************************END OF AVAILABLE BUS***************************//

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


//Verifying required params posted or not
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

        $db = new DbOperation();

        //Getting api key from database
        $api_key = $headers['Authorization'];

        //Validating api key from database
        if (!$db->isValidApiKey($api_key)) {
            //api key is not present
            $response['error'] = true;
            $response['message'] = 'Access Denied. Invalid API key';
            echoResponse(401, $response);
            $app->stop();
        }
    } else {
        //api key is missing in header
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
