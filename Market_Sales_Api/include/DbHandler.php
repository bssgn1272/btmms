<?php
/**
 * @author: Chimuka Moonde
 * @mobile No: 0973297682
 * @email : chimukamoonde@gmail.com
 * Kindly note that this is a customized version of slim 2
 */


require_once __DIR__ . '/Security.php';
require_once __DIR__ . '/DbConnect.php';
require_once __DIR__ . '/DbConnectLog.php';
require_once '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 */
class DbHandler
{
    private $conn;
    private $conn_log;
    private $security;
    private $logWriter;

    function __construct()
    {
        //Creating a DbConnect object to connect to the database
        $db = new DbConnect();
        $db_log = new DbConnectLog();

        //Initializing our connection link of this class by calling the method connect of DbConnect class
        $this->conn = $db->connect();
        $this->conn_log = $db_log->connect();

        $this->security = new Security();
        $this->logWriter = new ApiLogger();
    }


    //FUNCTIONS*********************************************************************************************************
    public function insert_name_prep($value)
    {
        $value = strip_tags($value); //Strips HTML and PHP tags from a string
        //$value = str_replace(' ', '', $value); //remove spaces
        $value = trim($value);
        $value = ucfirst(strtolower($value)); //Uppercase first letter
        return $value;
    }

    public function insert_word_prep($value)
    {
        $value = strip_tags($value); //Remove html tags
        $value = ucwords(strtolower($value)); //Uppercase first letter of word
        return $value;
    }

    public function insert_prep($value)
    {
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        $value = trim($value);
        return $value;
    }

    public function email_prep($value)
    {
        $value = stripslashes($value);    //Strips HTML and PHP tags from a string
        $value = filter_var($value, FILTER_SANITIZE_EMAIL);
        $value = strtolower($value);
        $value = trim($value);
        return $value;
    }


    public function Prepare_failed_to_file($value, $sql_query)
    {
        echo $value . "\n" . $sql_query;

        //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
        $file = __DIR__ . '/Prepare_failed.txt';
        $info = 'Script was executed at ' . date('d-M-y H:i:s') . "\n" . json_encode($value) . "\n" . $sql_query . "\n" . "\n";
        file_put_contents($file, $info, FILE_APPEND);
        //END OF WRITING TO FILE
    }

    public function Binding_parameters_failed_to_file($value, $sql_query)
    {
        echo $value . "\n" . $sql_query;

        //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
        $file = __DIR__ . '/Binding_parameters_failed.txt';
        $info = 'Script was executed at ' . date('d-M-y H:i:s') . "\n" . json_encode($value) . "\n" . $sql_query . "\n" . "\n";
        file_put_contents($file, $info, FILE_APPEND);
        //END OF WRITING TO FILE
    }

    public function Execute_failed_to_file($value, $sql_query)
    {
        //echo $value ."\n". $sql_query;

        //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
        $file = __DIR__ . '/Execute_failed.txt';
        $info = 'Script was executed at ' . date('d-M-y H:i:s') . "\n" . json_encode($value) . "\n" . $sql_query . "\n" . "\n";
        file_put_contents($file, $info, FILE_APPEND);
        //END OF WRITING TO FILE
    }

    public function Affect_Rows_failed_to_file($value, $sql_query)
    {
        //echo $value ."\n". $sql_query;

        //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
        $file = __DIR__ . '/Affect_Rows_failed.txt';
        $info = 'Script was executed at ' . date('d-M-y H:i:s') . "\n" . json_encode($value) . "\n" . $sql_query . "\n" . "\n";
        file_put_contents($file, $info, FILE_APPEND);
        //END OF WRITING TO FILE
    }

    public function write_Error_to_file($value)
    {
        echo $value;

        //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
        $file = __DIR__ . '/Error.txt';
        $date = 'Script was executed at ' . date('d-M-y H:i:s') . "\n" . json_encode($value) . "\n" . "\n";
        file_put_contents($file, $date, FILE_APPEND);
        //END OF WRITING TO FILE
    }

    public function updateTransactionFinalStatus($status,$cart_id)
    {
        $response = array();

        $StatusDesc = $this->getStatusDescription($status);

        $sql = 'UPDATE unza_transactions 
                SET final_StatusCode = ?, 
                    final_StatusDesc = ? 
                WHERE cart_id  = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('sss', $status, $StatusDesc, $cart_id);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $response['error'] = false;
                $response['message'] = 'updateTransactionFinalStatus successfully';

                //$this->echoResponse(HTTP_status_200_OK,$response);
            } else {
                //FAILED TO UPDATE
                $response['error'] = true;
                $response['message'] = 'Failed updateTransactionFinalStatus. Please try again!';

                //$this->echoResponse(HTTP_status_422_Unprocessable_Entity,$response);
                $this->Affect_Rows_failed_to_file($response,$sql);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            //$this->echoResponse(HTTP_status_500_Internal_Server_Error,$response);
        }

        $stmt->close();
        return null;
    }

    public function echoResponse($status_code, $response)
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

    public function formatNumber($number)
    {
        //The default country code
        $default_country_code = '260';

        //Remove any parentheses and the numbers they contain:
        $number = preg_replace("/\([0-9]+?\)/", '', $number);

        //Strip spaces and non-numeric characters:
        $number = preg_replace("/[^0-9]/", '', $number);

        //Strip out leading zeros:
        $number = ltrim($number, '0');

        $pfx = $default_country_code;

        //Check if the number doesn't already start with the correct dialling code:
        if (!preg_match('/^' . $pfx . '/', $number)) {
            $number = $pfx . $number;
        }

        return $number;
    }

    public function generateToken($company_id, $customer_id)
    {
        $token = md5(microtime(true) . mt_Rand());

        $response = array();

        $isPrepared = $stmt = $this->conn->prepare('UPDATE customer SET token = ?
                                                          WHERE company_id = ? AND customer_id = ? ');
        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error);
        }

        $isParamBound = $stmt->bind_param('sii', $token, $company_id, $customer_id);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error);
        }


        if ($stmt->execute()) {

            $num_affected_rows = $stmt->affected_rows;

            if ($num_affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $response['error'] = false;
                $response['message'] = 'Token updated successfully';

            } else {
                //FAILED TO UPDATE
                $response['error'] = true;
                $response['message'] = 'Failed to update token. Please try again!';
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response);
        }

        $stmt->close();
        return $response;
    }

    public function generateRandomNumber($digits = 4)
    {
        $i = 0;
        $pin = '';
        while ($i < $digits) {
            //generate a random number between 0 and 9.
            $pin .= mt_rand(0, 9);
            $i++;
        }
        return $pin;
    }

    public function getCurrentDateTime()
    {
        return date('Y-m-d H:i:s');
    }

    public function generateExtTransactionID()
    {
        return date("YmdHis");
    }

    public function updateCreditExtTransactionID($extTransactionID)
    {
        $response = array();

        $credit_ExtTransactionID = $this->generateExtTransactionID();

        $sql = 'UPDATE unza_transactions 
                SET 
                    credit_ExtTransactionID = ?
                WHERE debit_ExtTransactionID  = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('ss', $credit_ExtTransactionID,$extTransactionID);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $response['error'] = false;
                $response['message'] = 'updateCreditExtTransactionID successfully';

                //$this->echoResponse(HTTP_status_200_OK,$response);
            } else {
                //FAILED TO UPDATE
                $response['error'] = true;
                $response['message'] = 'Failed updateCreditExtTransactionID. Please try again!';

                //$this->echoResponse(HTTP_status_422_Unprocessable_Entity,$response);
                $this->Affect_Rows_failed_to_file($response,$sql);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            //$this->echoResponse(HTTP_status_500_Internal_Server_Error,$response);
        }

        $stmt->close();
        return $credit_ExtTransactionID;
    }

    public function getStatusDescription($code){
        $msg = null;

        switch ($code) {
            case 299:
                $msg = "Transaction pending processing";
                break;
            case 301:
                $msg = "Sorry transaction has failed";
                break;
            case 302:
                $msg = "Sorry transaction final status unknown";
                break;
            case 303:
                $msg = "Client is not active or does not exist";
                break;
            case 304:
                $msg = "ServiceID is not active or does not exist";
                break;
            case 305:
                $msg = "Mobile number is invalid";
                break;
            case 306:
                $msg = "Client is not allowed to access the service";
                break;
            case 307:
                $msg = "Third party transaction id is a duplicate";
                break;
            case 308:
                $msg = "Third party system was not authenticated ";
                break;
            case 309:
                $msg = "A request mandatory failed is missing";
                break;
            case 310:
                $msg = "Requested method is not allowed!";
                break;
            case 311:
                $msg = "TransactionID does not exist!";
                break;
            case 312:
                $msg = "Transaction with TransactionID has already been updated!";
                break;
            case 313:
                $msg = "Transaction was successfully updated";
                break;
            case 500:
                $msg = "Generic system error occurred";
                break;

            default:
                $msg = "Unknown error occurred, please contact support";
        }

        return $msg;
    }

    public function getMNO($mobile_number)
    {

        $telco = substr(trim($mobile_number), 0, 5);

        switch ($telco) {
            case '26097'://AIRTELZM
                $msg = "AIRTELZM";
                break;
            case '26077'://AIRTELZM
                $msg = "AIRTELZM";
                break;
            case '26096'://MTNZM
                $msg = "MTNZM";
                break;
            case '26076'://MTNZM
                $msg = "MTNZM";
                break;
            case '26095'://ZAMTEL
                $msg = "ZAMTEL";
                break;
            default:
                $msg = "Failed to identify MNO";

        }

        return $msg;
    }
    //END OF FUNCTIONS**************************************************************************************************


    //************************ROLES***************************//
    public function getAllRoles()
    {
        $response = array();

        $sql = 'SELECT role_id, name, description
                FROM unza_roles
                ';

        $isPrepared = $stmt = $this->conn->prepare($sql);

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

//        $isParamBound = $stmt->bind_param('i', $role_id);
//        if (!$isParamBound) {
//            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
//        }


        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //IF FOUND

                $stmt->bind_result($role_id, $role_name, $role_description);

                $response['error'] = false;
                $response['roles'] = array();

                while ($stmt->fetch()) {
                    $tmp = array();
                    $tmp['role_id'] = $role_id;
                    $tmp['role_name'] = $role_name;
                    $tmp['role_description'] = $role_description;

                    $response['roles'][] = $tmp;
                }


                $this->echoResponse(HTTP_status_200_OK, $response);

            } else {
                //IF NOT FOUND
                $response['error'] = false;
                $response['roles'] = array();
                $response['message'] = 'No results found';

                $this->echoResponse(HTTP_status_200_OK, $response);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }

        return null;
    }
    //************************END OF ROLES***************************//


    //************************TRANSACTION TYPES METHODS***************************//
    public function getAllTransactionTypes()
    {
        $response = array();

        $sql = 'SELECT transaction_type_id, name, description
                FROM unza_transaction_types';

        $isPrepared = $stmt = $this->conn->prepare($sql);

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

//        $isParamBound = $stmt->bind_param('i', $role_id);
//        if (!$isParamBound) {
//            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
//        }


        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //IF FOUND

                $stmt->bind_result($transaction_type_id, $name, $description);

                $response['error'] = false;
                $response['transaction_types'] = array();

                while ($stmt->fetch()) {
                    $tmp = array();
                    $tmp['transaction_type_id'] = $transaction_type_id;
                    $tmp['transaction_type_name'] = $name;
                    $tmp['transaction_type_description'] = $description;

                    $response['transaction_types'][] = $tmp;
                }

                $this->echoResponse(HTTP_status_200_OK, $response);

            } else {
                //IF NOT FOUND
                $response['error'] = false;
                $response['transaction_types'] = array();
                $response['message'] = 'No results found';

                $this->echoResponse(HTTP_status_200_OK, $response);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }

        return null;
    }
    //************************END OF TRANSACTION TYPES METHODS***************************//

    //************************TRANSACTION TYPES***************************//
    public function getAllTransactionType()
    {
        $response = array();

        $sql = 'SELECT transaction_type_id, name, description
                FROM unza_transaction_types
                ';

        $isPrepared = $stmt = $this->conn->prepare($sql);

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

//        $isParamBound = $stmt->bind_param('i', $role_id);
//        if (!$isParamBound) {
//            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
//        }


        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //IF FOUND

                $stmt->bind_result($transaction_type_id, $name, $description);

                $response['error'] = false;
                $response['transaction_types'] = array();

                while ($stmt->fetch()) {
                    $tmp = array();
                    $tmp['transaction_type_id'] = $transaction_type_id;
                    $tmp['name'] = $name;
                    $tmp['description'] = $description;

                    $response['transaction_types'][] = $tmp;
                }

                $this->echoResponse(HTTP_status_200_OK, $response);

            } else {
                //IF NOT FOUND
                $response['error'] = false;
                $response['transaction_types'] = array();
                $response['message'] = 'No results found';

                $this->echoResponse(HTTP_status_200_OK, $response);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }

        return null;
    }
    //************************END OF TRANSACTION TYPES***************************//

    //************************ENTITY***************************//
    public function getAllTraders($trader_id = null, $mobile_number = null, $role = null, $multiple_result)
    {

        $response = array();

        if ($role !== null) {

            $sql = 'SELECT trader_id,role,firstname, lastname, nrc, gender, dob, mobile_number, status                                                                                       
                    FROM unza_traders
                    WHERE role = ?
                    ORDER BY role,firstname,lastname';

            $isPrepared = $stmt = $this->conn->prepare($sql);
            $isParamBound = $stmt->bind_param('s', $role);
            if (!$isParamBound) {
                $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
            }


        } elseif ($mobile_number !== null) {

            $sql = 'SELECT trader_id,role,firstname, lastname, nrc, gender, dob, mobile_number, status                                                                                       
                    FROM unza_traders
                    WHERE mobile_number = ?
                    ORDER BY firstname,lastname';

            $isPrepared = $stmt = $this->conn->prepare($sql);
            $isParamBound = $stmt->bind_param('s', $mobile_number);
            if (!$isParamBound) {
                $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
            }

        } else {

            $sql = 'SELECT trader_id, role,firstname, lastname, nrc, gender, dob, mobile_number,status                                                                                       
                    FROM unza_traders
                    ORDER BY role,firstname,lastname';

            $isPrepared = $stmt = $this->conn->prepare($sql);

            //$stmt->bind_param('i', $user_level_id);
        }

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //IF FOUND

                $stmt->bind_result($trader_id, $role, $firstname, $lastname, $nrc, $gender, $dob, $mobile_number, $status);


                $response['error'] = false;
                $response['users'] = array();
                $response['status'] = StatusCodes::SUCCESS_CODE;


                $count = 0;
                while ($stmt->fetch()) {
                    $count++;
                    $tmp = array();
                    $tmp['trader_id'] = $trader_id;
                    //$tmp['$image'] = $image;
                    $tmp['role'] = $role;
                    $tmp['firstname'] = $firstname;
                    $tmp['lastname'] = $lastname;
                    $tmp['nrc'] = $nrc;
                    $tmp['gender'] = $gender;
                    $tmp['dob'] = $dob;
                    $tmp['mobile_number'] = $mobile_number;
                    $tmp['status'] = $status;
                    // $tmp['email'] = $email;
//                    $tmp['token_balance'] = $token_balance;
//                    $tmp['account_number'] = $account_number;
//                    $tmp['QR_code'] = $QR_code;

                    if ($multiple_result) {
                        if ($mobile_number !== null) {
                            $response['users'] = $tmp;
                        } else {
                            $response['users'][] = $tmp;
                        }
                    } else {
                        $response['users'] = $tmp;
                    }
                }

                $response['message'] = 'Found ' . $count . ' records.';


                $this->echoResponse(HTTP_status_200_OK, $response);


            } else {
                //IF NOT FOUND
                $response['error'] = false;
                $response['status'] = StatusCodes::FAILURE_CODE;
                $response['users'] = null;
                $response['message'] = 'No results found';

                $this->echoResponse(HTTP_status_200_OK, $response);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['status'] = StatusCodes::GENERIC_ERROR;
            $response['message'] = 'Oops! A database error occurred. Error is: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }

        return null;
    }
    //************************END OF ENTITY***************************//

    //************************PRODUCTS CATEGORIES***************************//
    //************************END OF PRODUCTS CATEGORIES***************************//


    //************************PRODUCTS***************************//
    //************************END OF PRODUCTS***************************//


    //************************MEASURES***************************//
    //************************END OF MEASURES***************************//


    //************************MARKETEER PRODUCTS***************************//
    //************************END OF MARKETEER PRODUCTS***************************//

    //************************MARKETEER KYC***************************//
    //simulation
    public function fetchMarketeerKYC()
    {
        $response = array();

        $tmp = array();
//        "response": {
//        "QUERY": {
//            "data": {
//                "account_status": "INACTIVE",
//                "email": null,
//                "first_name": null,
//                "last_name": null,
//                "mobile": "0973279867",
//                "nrc": "102030/10/7",
//                "operator_role": "MARKETER",
//                "ssn": "12345678911",
//                "username": "unzatesttwo",
//                "uuid": "2020-7174453813-1-0283-21"
//            },
//            "operation": "QUERY",
//            "operation_status": "SUCCESS",
//            "status": 0
//        }
//    }

        $response['response'] = array();
        $stands = array();
        $stands[] = array(
            'stand_number' => 'A70',
            'stand_price' => '10.00'
        );

        $first = array(
            'account_status' => 'ACTIVE',
            'email' => null,
            'first_name' => 'Francis',
            'last_name' => 'Marketeer',
            'mobile' => '260969240309',
            'nrc' => null,
            'operator_role' => 'MARKETER',
            'ssn' => null,
            'username' => null,
            'uuid' => '2020-0812387178-1-1287-28',
            'stands' => $stands
        );

        $stands = array();
        $stands[] = array(
            'stand_number' => 'A26',
            'stand_price' => '10.00'
        );
        $stands[] = array(
            'stand_number' => 'B40',
            'stand_price' => '10.00'
        );
        $second = array(
            'account_status' => 'ACTIVE',
            'email' => null,
            'first_name' => 'Simon',
            'last_name' => 'Marketeer',
            'mobile' => '260967485331',
            'nrc' => null,
            'operator_role' => 'MARKETER',
            'ssn' => null,
            'username' => null,
            'uuid' => '2020-3676257032-2-8166-20',
            'stands' => $stands

        );


        $tmp['QUERY']['data'][] = $first;
        $tmp['QUERY']['data'][] = $second;
        $tmp['QUERY']['operation'] = "QUERY";
        $tmp['QUERY']['operation_status'] = "SUCCESS";
        $tmp['QUERY']['status'] = 0;

        $response['response'] = $tmp;

        return $response;
    }

    public function fetchMarketeerKYCSingle()
    {
        $response = array();

        $tmp = array();
//        "response": {
//        "QUERY": {
//            "data": {
//                "account_status": "INACTIVE",
//                "email": null,
//                "first_name": null,
//                "last_name": null,
//                "mobile": "0973279867",
//                "nrc": "102030/10/7",
//                "operator_role": "MARKETER",
//                "ssn": "12345678911",
//                "username": "unzatesttwo",
//                "uuid": "2020-7174453813-1-0283-21"
//            },
//            "operation": "QUERY",
//            "operation_status": "SUCCESS",
//            "status": 0
//        }
//    }

        $response['response'] = array();
        $stands = array();
        $stands[] = array(
            'stand_number' => 'A70',
            'stand_price' => '10.00'
        );

        $first = array(
            'account_status' => 'ACTIVE',
            'email' => null,
            'first_name' => 'Francis',
            'last_name' => 'Marketeer',
            'mobile' => '260969240309',
            'nrc' => null,
            'operator_role' => 'MARKETER',
            'ssn' => null,
            'username' => null,
            'uuid' => '2020-0812387178-1-1287-28',
            'stands' => $stands
        );

        $stands = array();
        $stands[] = array(
            'stand_number' => 'A26',
            'stand_price' => '10.00'
        );
        $stands[] = array(
            'stand_number' => 'B40',
            'stand_price' => '10.00'
        );
        $second = array(
            'account_status' => 'ACTIVE',
            'email' => null,
            'first_name' => 'Simon',
            'last_name' => 'Marketeer',
            'mobile' => '260967485331',
            'nrc' => null,
            'operator_role' => 'MARKETER',
            'ssn' => null,
            'username' => null,
            'uuid' => '2020-3676257032-2-8166-20',
            'stands' => $stands

        );


        //$tmp['QUERY']['data'][] = $first;
        $tmp['QUERY']['data'] = $second;
        $tmp['QUERY']['operation'] = "QUERY";
        $tmp['QUERY']['operation_status'] = "SUCCESS";
        $tmp['QUERY']['status'] = 0;

        $response['response'] = $tmp;

        return $response;
    }
    //end of simulation
    //************************END OF MARKETEER KYC***************************//


    //************************MARKET FEES***************************//
    public function getAllPendingMarketCharges($seller_mobile_number)
    {

        $response = array();

//        $sql = 'SELECT id, msisdn, SUM(amount), stand_number, date_created
//                FROM unza_market_charge_payments
//                WHERE msisdn = ? AND status = 0
//                GROUP BY stand_number
//                ';

        $sql = 'SELECT id, msisdn, SUM(amount), stand_number, date_created                                                                                       
                FROM unza_market_charge_payments
                WHERE msisdn = ? AND status = 0 AND DATE(date_created) = DATE(?)                                                  
                GROUP BY stand_number
                ';

        $isPrepared = $stmt = $this->conn->prepare($sql);

        $today = $this->getCurrentDateTime();

        $isParamBound = $stmt->bind_param('ss', $seller_mobile_number,$today);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //IF FOUND

                $stmt->bind_result($id, $msisdn, $amount, $stand_number, $date_created);

                $response['error'] = false;
                $response['found'] = true;
                $response['market_fee'] = array();

                while ($stmt->fetch()) {
                    $tmp = array();
                    $tmp['marketeer_msisdn'] = $msisdn;
                    $tmp['stand_number'] = $stand_number;
                    $tmp['stand_price'] = abs($amount);
                    //$tmp['transaction_date'] = $transaction_date;

                    $response['market_fee'][] = $tmp;
                }

                $this->echoResponse(HTTP_status_200_OK, $response);


            } else {
                //IF NOT FOUND
                $response['error'] = false;
                $response['found'] = false;
                $response['market_fee'] = array(
                    'marketeer_msisdn' => $seller_mobile_number,
                    'stand_price' => 0.0
                );

                $this->echoResponse(HTTP_status_200_OK, $response);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }

        return null;
    }

    public function createPaidMarketChargesRecord($mobile_number, $amount, $stand_number)
    {

        $response = array();

        $sql = 'INSERT INTO unza_market_charge_collections(marketeer_msisdn,amount,stand_number,transaction_details,transaction_date) VALUES(?,?,?,?,?)';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }


        $transaction_details = "Market Fees Payments";
        $transaction_date = $this->getCurrentDateTime();

        $isParamBound = $stmt->bind_param('sdsss', $mobile_number, $amount, $stand_number, $transaction_details, $transaction_date);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {
            //CREATED
            $new_id = $this->conn->insert_id;

            $response['error'] = false;
            $response['message'] = 'Market fee payment created successfully';

            //$this->echoResponse(HTTP_status_201_Created,$response);
            $this->updatePaidMarketCharges($mobile_number, $stand_number);

        } else {
            //FAILED TO CREATE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }

        $stmt->close();


        return null;
    }

    public function updatePaidMarketCharges($seller_mobile_number, $stand_number)
    {
        $response = array();

        $sql = 'UPDATE unza_market_charge_payments 
                SET status = 1 
                WHERE msisdn = ? AND stand_number = ? AND status = 0';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('ss', $seller_mobile_number, $stand_number);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $response['error'] = false;
                $response['message'] = 'Market fees information updated successfully';

                //$this->echoResponse(HTTP_status_200_OK,$response);
            } else {
                //FAILED TO UPDATE
                $response['error'] = true;
                $response['message'] = 'Failed to update market fees information. Please try again!';

                //$this->echoResponse(HTTP_status_422_Unprocessable_Entity,$response);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            //$this->echoResponse(HTTP_status_500_Internal_Server_Error,$response);
        }

        $stmt->close();
        //return $response;
        return null;
    }
    //************************END OF MARKET FEES***************************//


    //************************TRANSACTIONS***************************//
    public function getAllTransactions($cart_id = null, $seller_id = null, $buyer_id = null, $seller_mobile_number = null, $buyer_mobile_number = null, $multiple_result)
    {
        $response = array();

        if ($cart_id !== null || $seller_id !== null || $buyer_id !== null) {

            $sql = 'SELECT cart_id, seller_id,s.firstname,s.lastname,seller_mobile_number, buyer_id,b.firstname,b.lastname,buyer_mobile_number, amount, device_serial, transaction_date 
                    FROM unza_transactions
                    JOIN unza_traders b ON b.trader_id = unza_transactions.buyer_id
                    JOIN unza_traders s ON s.trader_id = unza_transactions.seller_id
                    WHERE cart_id = ? OR seller_id = ? OR buyer_id = ?
                    ';

            $isPrepared = $stmt = $this->conn->prepare($sql);

            $isParamBound = $stmt->bind_param('iii', $cart_id, $seller_id, $buyer_id);
            if (!$isParamBound) {
                $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
            }


        } else if ($seller_mobile_number !== null || $buyer_mobile_number !== null) {

            $sql = 'SELECT cart_id, seller_id,s.firstname,s.lastname,seller_mobile_number, buyer_id,b.firstname,b.lastname,buyer_mobile_number, amount, device_serial, transaction_date 
                    FROM unza_transactions
                    JOIN unza_traders b ON b.trader_id = unza_transactions.buyer_id
                    JOIN unza_traders s ON s.trader_id = unza_transactions.seller_id
                    WHERE seller_mobile_number = ? OR buyer_mobile_number  = ?
            ';

            $isPrepared = $stmt = $this->conn->prepare($sql);

            $isParamBound = $stmt->bind_param('ss', $seller_mobile_number, $buyer_mobile_number);
            if (!$isParamBound) {
                $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
            }

        } else {

            $sql = 'SELECT cart_id, seller_id,s.firstname,s.lastname,seller_mobile_number, buyer_id,b.firstname,b.lastname,buyer_mobile_number, amount, device_serial, transaction_date 
                    FROM unza_transactions
                    JOIN unza_traders b ON b.trader_id = unza_transactions.buyer_id
                    JOIN unza_traders s ON s.trader_id = unza_transactions.seller_id
                    ';

            $isPrepared = $stmt = $this->conn->prepare($sql);

            //$stmt->bind_param('i', $user_level_id);
        }

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //IF FOUND

                $stmt->bind_result($cart_id, $seller_id, $s_firstname, $s_lastname, $seller_mobile_number, $buyer_id, $b_firstname, $b_lastname, $buyer_mobile_number, $amount_due, $device_serial, $transaction_date);

                $response['error'] = false;
                $response['transaction_summaries'] = array();

                while ($stmt->fetch()) {
                    $tmp = array();
                    $tmp['cart_id'] = $cart_id;
                    $tmp['seller_id'] = $seller_id;
                    $tmp['seller_firstname'] = $s_firstname;
                    $tmp['seller_lastname'] = $s_lastname;
                    $tmp['seller_mobile_number'] = $seller_mobile_number;
                    $tmp['buyer_id'] = $buyer_id;
                    $tmp['buyer_firstname'] = $b_firstname;
                    $tmp['buyer_lastname'] = $b_lastname;
                    $tmp['buyer_mobile_number'] = $buyer_mobile_number;
                    $tmp['amount'] = $amount_due;
                    $tmp['device_serial'] = $device_serial;
                    $tmp['transaction_date'] = $transaction_date;

                    //$tmp['transaction_details'] = $this->getAllTransactionDetails($cart_id, false);

                    if ($multiple_result) {
                        $response['transaction_summaries'][] = $tmp;
                    } else {
                        $response['transaction_summaries'] = $tmp;
                    }

                }

                $this->echoResponse(HTTP_status_200_OK, $response);


            } else {
                //IF NOT FOUND
                $response['error'] = false;
                $response['transaction_summary'] = array();
                $response['message'] = 'No results found';

                $this->echoResponse(HTTP_status_200_OK, $response);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }

        return null;
    }

    public function getAllTransactionsSummary($period, $seller_mobile_number = null)
    {
        $today = date('Y-m-d');
        $response = array();
        $response['marketeer'] = array();
        $response['today'] = array();
        $response['week'] = array();
        $response['month'] = array();

        $sql = 'SELECT seller_id, seller_firstname, seller_lastname, seller_mobile_number
                    FROM unza_transactions
                    WHERE seller_mobile_number = ?      
            ';

        $isPrepared = $stmt = $this->conn->prepare($sql);

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('s', $seller_mobile_number);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }


        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //IF FOUND

                $stmt->bind_result($seller_id, $seller_firstname, $seller_lastname, $seller_mobile_number);

                //$response['error'] = false;

                while ($stmt->fetch()) {
                    $tmp = array();
                    $tmp['seller_id'] = $seller_id;
                    $tmp['seller_firstname'] = $seller_firstname;
                    $tmp['seller_lastname'] = $seller_lastname;
                    $tmp['seller_mobile_number'] = $seller_mobile_number;

                    $response['marketeer'] = $tmp;
                }

            } else {
                //IF NOT FOUND
                $tmp = array();
                $tmp['seller_id'] = null;
                $tmp['seller_firstname'] = null;
                $tmp['seller_lastname'] = null;
                $tmp['seller_mobile_number'] = null;
                //$tmp['error'] = false;
                //$tmp['marketeer'] = array();
                $tmp['message'] = 'No results found';

                $response['marketeer'] = $tmp;

            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            //$this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }


        $stmt->close();

//            $sql = 'SELECT LEFT(transaction_date,10) AS day,seller_id, seller_firstname, seller_lastname, seller_mobile_number,COUNT(cart_id) AS num_of_sales,SUM(amount) AS revenue
//                    FROM transaction_summaries
//                    WHERE seller_mobile_number = ? AND  DATE(transaction_date) >= ?
//                    GROUP BY 1
//                    ORDER BY 1 DESC
//                    LIMIT 7
//            ';

        $sql = 'SELECT LEFT(transaction_date,10) AS day,seller_id, seller_firstname, seller_lastname, seller_mobile_number,COUNT(cart_id) AS num_of_sales,SUM(amount) AS revenue
                FROM unza_transactions
                WHERE DATE(transaction_date) >= DATE_SUB(CURDATE(), INTERVAL 1 Day) AND transaction_type_id = 1 AND seller_mobile_number = ?
                GROUP BY seller_mobile_number
            ';

        $isPrepared = $stmt = $this->conn->prepare($sql);

        $isParamBound = $stmt->bind_param('s', $seller_mobile_number);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //IF FOUND

                $stmt->bind_result($day, $seller_id, $seller_firstname, $seller_lastname, $seller_mobile_number, $num_of_sales, $revenue);


                while ($stmt->fetch()) {
                    $tmp = array();

                    //$tmp['$day'] = $day;
//                    $tmp['seller_id'] = $seller_id;
//                    $tmp['seller_firstname'] = $seller_firstname;
//                    $tmp['seller_lastname'] = $seller_lastname;
//                    $tmp['seller_mobile_number'] = $seller_mobile_number;
                    $tmp['num_of_sales'] = $num_of_sales;
                    $tmp['revenue'] = "ZMW " . $revenue;

                    $response['today'] = $tmp;
                }

            } else {

                $tmp = array();
                $tmp['num_of_sales'] = 0;
                $tmp['revenue'] = "ZMW " . 0.0;

                $response['today'] = $tmp;

            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

        }

        $stmt->close();


//            $sql = 'SELECT  day,seller_id, seller_firstname, seller_lastname, seller_mobile_number, SUM(num_of_sales), SUM(revenue)
//                    FROM  (
//                    SELECT LEFT(transaction_date,10) AS day,seller_id, seller_firstname, seller_lastname, seller_mobile_number,COUNT(cart_id) AS num_of_sales,SUM(amount) AS revenue
//                    FROM transaction_summaries
//                    WHERE seller_mobile_number = ?
//                    GROUP BY 1
//                    ORDER BY 1 DESC
//                    LIMIT 7) as ts
//            ';

        $sql = 'SELECT LEFT(transaction_date,10) AS day,seller_id, seller_firstname, seller_lastname, seller_mobile_number,COUNT(cart_id) AS num_of_sales,SUM(amount) AS revenue
                FROM unza_transactions
                WHERE DATE(transaction_date) >= DATE_SUB(CURDATE(), INTERVAL 7 Day) AND transaction_type_id = 1  AND seller_mobile_number = ?
                GROUP BY seller_mobile_number
            ';

        $isPrepared = $stmt = $this->conn->prepare($sql);

        $isParamBound = $stmt->bind_param('s', $seller_mobile_number);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //IF FOUND

                $stmt->bind_result($day, $seller_id, $seller_firstname, $seller_lastname, $seller_mobile_number, $num_of_sales, $revenue);

                while ($stmt->fetch()) {
                    $tmp = array();

                    //$tmp['$day'] = $day;
                    $tmp['num_of_sales'] = $num_of_sales;
                    $tmp['revenue'] = "ZMW " . $revenue;

                    $response['week'] = $tmp;

                }

            } else {

                $tmp = array();
                $tmp['num_of_sales'] = 0;
                $tmp['revenue'] = "ZMW " . 0.0;

                $response['week'] = $tmp;
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }


        $stmt->close();


//            $sql = 'SELECT LEFT(transaction_date,7) AS day,seller_id, seller_firstname, seller_lastname, seller_mobile_number,COUNT(cart_id) AS num_of_sales,SUM(amount) AS revenue
//                    FROM transaction_summaries
//                    WHERE seller_mobile_number = ?
//                    GROUP BY 1
//                    ORDER BY 1 DESC
//                    LIMIT 1
//            ';

        $sql = 'SELECT LEFT(transaction_date,10) AS day,seller_id, seller_firstname, seller_lastname, seller_mobile_number,COUNT(cart_id) AS num_of_sales,SUM(amount) AS revenue
                FROM unza_transactions
                WHERE DATE(transaction_date) >= DATE_SUB(CURDATE(), INTERVAL 1 Month) AND transaction_type_id = 1  AND seller_mobile_number = ?
                GROUP BY seller_mobile_number
            ';

        $isPrepared = $stmt = $this->conn->prepare($sql);

        $isParamBound = $stmt->bind_param('s', $seller_mobile_number);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //IF FOUND

                $stmt->bind_result($day, $seller_id, $seller_firstname, $seller_lastname, $seller_mobile_number, $num_of_sales, $revenue);

                while ($stmt->fetch()) {
                    $tmp = array();

                    //$tmp['$day'] = $day;
                    $tmp['num_of_sales'] = $num_of_sales;
                    $tmp['revenue'] = "ZMW " . $revenue;

                    $response['month'] = $tmp;

                }

            } else {

                $tmp = array();
                $tmp['num_of_sales'] = 0;
                $tmp['revenue'] = "ZMW " . 0.0;

                $response['month'] = $tmp;
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }


        $this->echoResponse(HTTP_status_200_OK, $response);
        return null;
    }

    public function getAllTransactionDetails($cart_id = null, $endpoint)
    {
        $response = array();

        if ($cart_id !== null) {

            $sql = 'SELECT transaction_details_id, cart_id, 
                            products.product_category_id,category_name,products.product_id,product_name, 
                            purchase_price                                                                                        
                    FROM transaction_details
                    JOIN products ON products.product_id = transaction_details.product_id
                    JOIN product_categories ON product_categories.product_category_id = products.product_category_id
                    where cart_id = ?
                    ORDER BY category_name,product_name,purchase_price';

            $isPrepared = $stmt = $this->conn->prepare($sql);

            $isParamBound = $stmt->bind_param('i', $cart_id);
            if (!$isParamBound) {
                $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
            }


        } else {

            $sql = 'SELECT transaction_details_id, cart_id, 
                            products.product_category_id,category_name,products.product_id,product_name, 
                            purchase_price                                                                                        
                    FROM transaction_details
                    JOIN products ON products.product_id = transaction_details.product_id
                    JOIN product_categories ON product_categories.product_category_id = products.product_category_id
                    ORDER BY category_name,product_name,purchase_price';

            $isPrepared = $stmt = $this->conn->prepare($sql);

            //$stmt->bind_param('i', $user_level_id);
        }

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }


        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //IF FOUND

                $stmt->bind_result($transaction_details_id, $cart_id,
                    $product_category_id, $category_name, $product_id, $product_name, $purchase_price);

                if ($endpoint) {
                    $response['error'] = false;
                    $response['transaction_details'] = array();
                }

                while ($stmt->fetch()) {
                    $tmp = array();
                    $tmp['transaction_details_id'] = $transaction_details_id;
                    $tmp['cart_id'] = $cart_id;
                    $tmp['product_category_id'] = $product_category_id;
                    $tmp['category_name'] = $category_name;
                    $tmp['product_id'] = $product_id;
                    $tmp['product_name'] = $product_name;
                    $tmp['purchase_price'] = $purchase_price;


                    if ($endpoint) {
                        $response['transaction_details'][] = $tmp;
                    } else {
                        $response[] = $tmp;
                    }
                }


            } else {
                //IF NOT FOUND

                if ($endpoint) {
                    $response['error'] = false;
                    $response['transaction_details'] = array();
                    $response['message'] = 'No results found';
                } else {
                    $response = array();
                }

            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);
        }

        return $response;
    }

    public function createNsanoTransactionsSummaries($transaction_type_id, $stand_number,
                                                     $route_code, $transaction_channel, $id_type, $passenger_id, $bus_schedule_id, $travel_date, $travel_time,
                                                     $seller_id, $seller_firstname, $seller_lastname, $seller_mobile_number,
                                                     $buyer_id, $buyer_firstname, $buyer_lastname, $buyer_mobile_number, $buyer_email,
                                                     $amount, $device_serial, $transaction_date)
    {

        $response = array();

        //CHECK BUYER'S CURRENT BALANCE
        //$res = $this->checkTokenBalance($buyer_id, $buyer_mobile_number);

        //if ($res[TOKEN_BALANCE] >= $amount) {

        //LOG ATTEMPTED TRANSACTION
        $sql = 'INSERT INTO unza_transactions(transaction_type_id,stand_number,
                              route_code,transaction_channel,id_type,passenger_id,bus_schedule_id,travel_date,travel_time, 
                              seller_id,seller_firstname,seller_lastname, seller_mobile_number, 
                              buyer_id, buyer_firstname,buyer_lastname,buyer_mobile_number, buyer_email,
                              amount, device_serial, transaction_date) 
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('isssssssssssssssssdss', $transaction_type_id, $stand_number,
            $route_code, $transaction_channel, $id_type, $passenger_id, $bus_schedule_id, $travel_date, $travel_time,
            $seller_id, $seller_firstname, $seller_lastname, $seller_mobile_number,
            $buyer_id, $buyer_firstname, $buyer_lastname, $buyer_mobile_number, $buyer_email,
            $amount, $device_serial, $transaction_date);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {
            //CREATED
            $cart_id = $this->conn->insert_id;

            $response['error'] = false;
            $response['message'] = 'Transaction is being processed,you will soon receive an SMS confirmation';
            $stmt->close();

            $this->echoResponse(HTTP_status_202_Accepted, $response);

            if ($transaction_type_id == 3) {//TICKET PURCHASE
                if ($seller_mobile_number !== null) {
                    $this->makeNsanoDebitRequest($seller_mobile_number, $amount, $cart_id, 1);
                } else {
                    $this->makeNsanoDebitRequest($buyer_mobile_number, $amount, $cart_id, 1);
                }
            } else {//NON TICKET PURCHASE
                $this->makeNsanoDebitRequest($buyer_mobile_number, $amount, $cart_id, 1);
            }


        } else {
            //FAILED TO CREATE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;
            $stmt->close();

            $this->Execute_failed_to_file($response, $sql);

            $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }

        return null;
    }

    public function createUNZATransactionsSummaries($transaction_type_id, $stand_number,
                                                    $route_code, $transaction_channel, $id_type, $passenger_id, $bus_schedule_id, $travel_date, $travel_time,
                                                    $seller_id, $seller_firstname, $seller_lastname, $seller_mobile_number,
                                                    $buyer_id, $buyer_firstname, $buyer_lastname, $buyer_mobile_number, $buyer_email,
                                                    $amount, $device_serial, $transaction_date)
    {

        $response = array();

        //LOG ATTEMPTED TRANSACTION
        $sql = 'INSERT INTO unza_transactions(transaction_type_id,stand_number,
                              route_code,transaction_channel,id_type,passenger_id,bus_schedule_id,travel_date,travel_time, 
                              seller_id,seller_firstname,seller_lastname, seller_mobile_number, 
                              buyer_id, buyer_firstname,buyer_lastname,buyer_mobile_number, buyer_email,
                              amount, device_serial, transaction_date,
                              debit_ExtTransactionID
                              ) 
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }


        $transaction_date_formatted = date('Y-m-d H:i:s', strtotime($transaction_date));
        $travel_date_formatted = date('Y-m-d', strtotime($travel_date));
        $travel_time_formatted = date('H:i:s', strtotime($travel_time));
        $extTransactionID = $this->generateExtTransactionID();

        $isParamBound = $stmt->bind_param('isssssssssssssssssdsss', $transaction_type_id, $stand_number,
            $route_code, $transaction_channel, $id_type, $passenger_id, $bus_schedule_id, $travel_date_formatted, $travel_time_formatted,
            $seller_id, $seller_firstname, $seller_lastname, $seller_mobile_number,
            $buyer_id, $buyer_firstname, $buyer_lastname, $buyer_mobile_number, $buyer_email,
            $amount, $device_serial, $transaction_date_formatted,
            $extTransactionID);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {
            //CREATED
            $cart_id = $this->conn->insert_id;


            $response['error'] = false;
            $response['message'] = 'Transaction is being processed,you will soon receive an SMS message';
            $stmt->close();

            $this->echoResponse(HTTP_status_202_Accepted, $response);

            if ($transaction_type_id == 3) {//TICKET PURCHASE
                if ($seller_mobile_number !== null) {
                    $this->makeUNZADebitRequest($seller_mobile_number, $amount, $extTransactionID, 1);
                } else {
                    $this->makeUNZADebitRequest($buyer_mobile_number, $amount, $extTransactionID, 1);
                }
            } else {//NON TICKET PURCHASE
                $this->makeUNZADebitRequest($buyer_mobile_number, $amount, $extTransactionID, 1);
            }


        } else {
            //FAILED TO CREATE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;
            $stmt->close();

            $this->Execute_failed_to_file($response, $sql);

            $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }

        return null;
    }

    //************************REQUESTS***************************//
    public function makeNsanoDebitRequest($mobile_number, $amount, $cart_id, $leg){

        $telco = substr(trim($mobile_number), 0, 5);

        switch ($telco) {
            case '26097'://AIRTELZM
                $this->nsano_API('AIRTELZM', 'malipo', $mobile_number, $amount, $cart_id, $leg);
                break;
            case '26096'://MTNZM
                $this->nsano_API('MTNZM', 'malipo', $mobile_number, $amount, $cart_id, $leg);
                break;
            case '26076'://MTNZM
                $this->nsano_API('MTNZM', 'malipo', $mobile_number, $amount, $cart_id, $leg);
                break;
            case '26095'://ZAMTEL
                $this->nsano_API('ZAMTEL', 'malipo', $mobile_number, $amount, $cart_id, $leg);
                //$this->makeUNZADebitRequest($mobile_number,$amount,$cart_id,1);
                break;
            default:
                $response['error'] = True;
                $response['message'] = "Telco not identified";
                $this->echoResponse(HTTP_status_200_OK, $response);
        }
    }

    public function makeUNZADebitRequest($mobile_number, $amount, $extTransactionID, $leg)
    {

        $telco = substr(trim($mobile_number), 0, 5);

        switch ($telco) {
            case '26097'://AIRTELZM
                $this->unza_API(0, $mobile_number, $amount, $extTransactionID, $leg);
                break;
            case '26077'://AIRTELZM
                $this->unza_API(0, $mobile_number, $amount, $extTransactionID, $leg);
                break;
            case '26096'://MTNZM
                $this->unza_API(3, $mobile_number, $amount, $extTransactionID, $leg);
                break;
            case '26076'://MTNZM
                $this->unza_API(3, $mobile_number, $amount, $extTransactionID, $leg);
                break;
            case '26095'://ZAMTEL
                $this->unza_API(2, $mobile_number, $amount, $extTransactionID, $leg);
                break;
            default:
                $response['error'] = True;
                $response['message'] = "Telco not identified";
                $this->echoResponse(HTTP_status_200_OK, $response);
        }
    }

    public function makeNsanoCreditRequest($mobile_number, $amount, $reference, $leg)
    {

        $telco = substr(trim($mobile_number), 0, 5);

        switch ($telco) {
            case '26097'://AIRTELZM
                $this->nsano_API('AIRTELZM', 'mikopo', $mobile_number, $amount, $reference, $leg);
                break;
            case '26096'://MTNZM
                $this->nsano_API('MTNZM', 'mikopo', $mobile_number, $amount, $reference, $leg);
                break;
            case '26076'://MTNZM
                $this->nsano_API('MTNZM', 'mikopo', $mobile_number, $amount, $reference, $leg);
                break;
            case '26095'://ZAMTEL
                $this->nsano_API('ZAMTEL', 'mikopo', $mobile_number, $amount, $reference, $leg);
                //$this->makeUNZACreditRequest($mobile_number, $amount, $reference, $leg);
                break;
            default:
                $response['error'] = True;
                $response['message'] = "Telco not identified";
                $this->echoResponse(HTTP_status_200_OK, $response);
        }

    }

    public function makeUNZACreditRequest($mobile_number, $amount, $extTransactionID, $leg)
    {

        $telco = substr(trim($mobile_number), 0, 5);

        switch ($telco) {
            case '26097'://AIRTELZM
                $this->unza_API(0, $mobile_number, $amount, $extTransactionID, $leg);
                break;
            case '26077'://AIRTELZM
                $this->unza_API(0, $mobile_number, $amount, $extTransactionID, $leg);
                break;
            case '26096'://MTNZM
                $this->unza_API(4, $mobile_number, $amount, $extTransactionID, $leg);
                //$this->makeNsanoCreditRequest($mobile_number, $amount, $reference, $leg);
                break;
            case '26076'://MTNZM
                $this->unza_API(4, $mobile_number, $amount, $extTransactionID, $leg);
                //$this->makeNsanoCreditRequest($mobile_number, $amount, $reference, $leg);
                break;
            case '26095'://ZAMTEL
                $this->unza_API(1, $mobile_number, $amount, $extTransactionID, $leg);
                break;
            default:
                $response['error'] = True;
                $response['message'] = "Telco not identified";
                $this->echoResponse(HTTP_status_200_OK, $response);
        }

    }
    //************************END OF REQUESTS***************************//

    //************************PAYMENT API***************************//
    public function nsano_API($mno, $kuwaita, $msisdn, $amount, $refID, $leg)
    {

        $url = "https://" . NSANO_URL . ":" . NSANO_PORT . "/api/fusion/tp/" . NSANO_API_KEY;
        $port = NSANO_PORT;

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json'
        ];

        $post_fields = array();

        $post_fields = [
            'mno' => $mno,
            'kuwaita' => $kuwaita,
            'msisdn' => $msisdn,
            'amount' => $amount,
            'refID' => $refID,
        ];

        $total_transaction_feel = 0.0;
        //loop through all transactions
//        if ($leg == 2) {
//
//            $result = $this->getTransactionFees();
//
//            foreach ($result as $key => $val) {
//
//                if ($val['charge_type'] == "Percentage") {//PERCENTAGE
//
//                    $total_transaction_feel = $total_transaction_feel + ($amount * ($val['value'] / 100));
//
//                } else {//NONE PERCENTAGE
//
//                    $total_transaction_feel = $total_transaction_feel + ($amount - $val['value']);
//
//                }
//
//            }
//
//        }
//
//        if ($leg == 1) {
//
//            $post_fields = [
//                'mno' => $mno,
//                'kuwaita' => $kuwaita,
//                'msisdn' => $msisdn,
//                'amount' => $amount,
//                'refID' => $refID,
//            ];
//
//        } elseif ($leg == 2) {
//
//            $post_fields = [
//                'mno' => $mno,
//                'kuwaita' => $kuwaita,
//                'msisdn' => $msisdn,
//                'amount' => $amount - $total_transaction_feel,
//                'refID' => $refID,
//            ];
//
//        }


        //Initializing curl to open a connection
        $curl_handler = curl_init();
        curl_setopt($curl_handler, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_handler, CURLOPT_URL, $url);
        curl_setopt($curl_handler, CURLOPT_PORT, $port);
        curl_setopt($curl_handler, CURLOPT_POST, true);
        curl_setopt($curl_handler, CURLOPT_POSTFIELDS, http_build_query($post_fields));
        curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, false);//set to true in production
        curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, false);//set to true in production
        curl_setopt($curl_handler, CURLOPT_CONNECTTIMEOUT, 0);// 100; // set to zero for no timeout
        $result = curl_exec($curl_handler);

        if ($kuwaita == "mikopo") {//CREDIT
            if ($leg == 1) {
                $this->createCreditRequestLog($refID, http_build_query($post_fields));
            } else if ($leg == 2) {
                $this->updateCreditRequestLog($refID, http_build_query($post_fields));
            }
        } else if ($kuwaita == "malipo") {//DEBIT
            $this->createDebitRequestLog($refID, http_build_query($post_fields));
        }


        if ($result === FALSE) {//cURL REQUEST ERROR OCCURRED

            $file = null;
            if ($kuwaita == "mikopo") {//DURING CREDIT CUSTOMER REQUEST
                $file = __DIR__ . '/NSANO-Credit_Request_Errors.txt';
            } else if ($kuwaita == "malipo") {//DURING DEBIT CUSTOMER REQUEST
                $file = __DIR__ . '/NSANO-Debit_Request_Errors.txt';
            } else {
                $file = __DIR__ . '/NSANO-Request_Errors.txt';//DURING REQUEST
            }

            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode(curl_error($curl_handler)) . "\n" . "\n";
            file_put_contents($file, $date, FILE_APPEND);

            die('Curl failed: ' . curl_error($curl_handler));

        } else {//cURL REQUEST SUCCESSFUL

            if ($kuwaita == "malipo") {//DEBIT RESPONSE

                //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
                $file = __DIR__ . '/Nsano-Debit_Response.txt';
                $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $result . "\n" . "\n";
                file_put_contents($file, $date, FILE_APPEND);
                //END OF WRITING TO FILE

                $json_obj = json_decode($result, true);
                $code = isset($json_obj['code']) ? $json_obj['code'] : null;
                $msg = isset($json_obj['msg']) ? $json_obj['msg'] : null;
                $reference = isset($json_obj['reference']) ? $json_obj['reference'] : null;


                if ($code != 00) {
                    $buyer_msg = $msg . " .During Debit buyer's wallet";
                    $this->pushSMS($buyer_msg, $msisdn);
                }

                $this->updateDebitResponseLog($refID, $reference, $result);
                $this->updateDebitResponseDetails($msg, $reference, $code, $refID);

            } elseif ($kuwaita == "mikopo") {//CREDIT RESPONSE

                //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
                $file = __DIR__ . '/Nsano-Credit_Response.txt';
                $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $result . "\n" . "\n";
                file_put_contents($file, $date, FILE_APPEND);
                //END OF WRITING TO FILE

                $json_obj = json_decode($result, true);
                $code = isset($json_obj['code']) ? $json_obj['code'] : null;
                $msg = isset($json_obj['msg']) ? $json_obj['msg'] : null;
                $reference = isset($json_obj['reference']) ? $json_obj['reference'] : null;
                $system_code = isset($json_obj['system_code']) ? $json_obj['system_code'] : null;
                $transactionID = isset($json_obj['transactionID']) ? $json_obj['transactionID'] : null;

                if ($leg == 1) {//NOTIFY ONLY THE RECIPIENT OF THE MONEY

                    //$this->pushSMS($msg, $msisdn);
                    $this->saveSMS($msg, $msisdn);
                    $this->pushSMS($msg, "260973297682");//test

                    $this->updateCreditResponseDetails($msg, $reference, $code, $system_code, $transactionID, $refID);

                } elseif ($leg == 2) {//NOTIFY BOTH THE SELLER AND BUYER

                    $resTraderInformation = $this->getTraderInformation($refID);

                    if ($code == 00) {//Transaction successful

                        $seller_msg = "Transaction successful. You have received ZMW " . $amount . ". Transaction REF: " . strtoupper($refID);
                        $buyer_msg = "Transaction successful. You have sent ZMW " . $amount . ". Transaction REF: " . strtoupper($refID);

                        //NOTIFY THE SELLER
                        //$this->pushSMS($seller_msg, $msisdn);
                        $this->saveSMS($seller_msg, $msisdn);
                        $this->pushSMS($seller_msg, "260973297682");//test

                        //NOTIFY THE BUYER
                        //$this->pushSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                        $this->saveSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                        $this->pushSMS($buyer_msg, "260973297682");//test

                        $response['code'] = '00';
                        $response['msg'] = 'Transaction successful';

                        $this->echoResponse(HTTP_status_200_OK, $response);//NOTIFY NSANO

                    } else if ($code == 01) {//Transaction failed

                        //NOTIFY THE SELLER
                        $seller_msg = $msg . " during credit seller's wallet";
                        //$this->pushSMS($seller_msg, $resTraderInformation['seller_mobile_number']);
                        $this->saveSMS($seller_msg, $resTraderInformation['seller_mobile_number']);
                        $this->pushSMS($seller_msg, "260973297682");//test

                        //NOTIFY THE BUYER
                        $buyer_msg = $msg . " during credit seller's wallet. Transaction will be reversed";
                        //$this->pushSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                        $this->saveSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                        $this->pushSMS($buyer_msg, "260973297682");//test

                        $response['code'] = '01';
                        $response['msg'] = "Failure to complete transaction";

                        $this->echoResponse(HTTP_status_200_OK, $response);//NOTIFY NSANO

                    } else {

                        //NOTIFY THE SELLER
                        $seller_msg = $msg . " During credit seller's wallet";
                        //$this->pushSMS($seller_msg, $resTraderInformation['seller_mobile_number']);
                        $this->saveSMS($seller_msg, $resTraderInformation['seller_mobile_number']);
                        $this->pushSMS($seller_msg, "260973297682");//test

                        //NOTIFY THE BUYER
                        $buyer_msg = $msg . " During credit seller's wallet. Transaction will be reversed";
                        //$this->pushSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                        $this->saveSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                        $this->pushSMS($buyer_msg, "260973297682");//test

                        $response['code'] = '01';
                        $response['msg'] = "Failure to complete transaction";

                        $this->echoResponse(HTTP_status_200_OK, $response);//NOTIFY NSANO
                    }

                    $this->updateCreditResponseLog($refID, $reference, $result);
                    $this->updateTransactionCreditResponseLegTwo($msg, $reference, $code, $system_code, $transactionID, $refID);
                }

            } else {

                //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
                $file = __DIR__ . '/Nsano-error_wallet-transaction.txt';
                $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $result . "\n" . "\n";
                file_put_contents($file, $date, FILE_APPEND);
                //END OF WRITING TO FILE

            }
        }

        curl_close($curl_handler);

        return $result;
    }

    public function unza_API($service, $msisdn, $amount, $extTransactionID, $leg)
    {

        $url = "http://" . UNZA_API_IP . ":" . UNZA_API_PORT . "/core/v1/pushPayment";
        $port = UNZA_API_PORT;

        $headers = [];
        $post_fields = [];


        if ($service == 1 || $service == 4) {//CREDIT CUSTOMER

            $headers = [
                'apiKey:' .UNZA_API_KEY,
                'clientCode:' .UNZA_CLIENT_CODE,
                'callbackUrl:' .NAPSA_CREDIT_CALLBACK_URL,
                'Content-Type: application/json',
                'Accept: application/json'
            ];

            if (TRANSACTION_FEE_ON_CREDIT) {
                if ($leg == 2) {

                    $post_fields = [
                        'serviceID' => $service,
                        'accountNumber' => $msisdn,
                        'amount' => ($amount - $this->getTotalTransactionFee($amount)),
                        'extTransactionID' => $extTransactionID,
                        'extNarration' => 'Credit customer ' . $msisdn . ' with amount - active transaction fee',
                    ];

                }else{

                    $post_fields = [
                        'serviceID' => $service,
                        'accountNumber' => $msisdn,
                        'amount' => $amount,
                        'extTransactionID' => $extTransactionID,
                        'extNarration' => 'Credit customer ' . $msisdn,
                    ];

                }

            } else {
                $post_fields = [
                    'serviceID' => $service,
                    'accountNumber' => $msisdn,
                    'amount' => $amount,
                    'extTransactionID' => $extTransactionID,
                    'extNarration' => 'Credit customer ' . $msisdn,
                ];
            }

        } else if ($service == 2 || $service == 3) {//DEBIT CUSTOMER

            $headers = [
                'apiKey:' .UNZA_API_KEY,
                'clientCode:' .UNZA_CLIENT_CODE,
                'callbackUrl:' .NAPSA_DEBIT_CALLBACK_URL,
                'Content-Type: application/json',
                'Accept: application/json'
            ];

            $post_fields = TRANSACTION_FEE_ON_DEBIT ? [
                'serviceID' => $service,
                'accountNumber' => $msisdn,
                'amount' => $amount + $this->getTotalTransactionFee($amount),
                'extTransactionID' => $extTransactionID,
                'extNarration' => 'Debit customer ' . $msisdn . ' with amount + active transaction fee',
            ] : [
                'serviceID' => $service,
                'accountNumber' => $msisdn,
                'amount' => $amount,
                'extTransactionID' => $extTransactionID,
                'extNarration' => 'Debit customer ' . $msisdn,
            ];

        }


        $curl_handler = curl_init();
        curl_setopt($curl_handler, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_handler, CURLOPT_URL, $url);
        curl_setopt($curl_handler, CURLOPT_PORT, $port);
        curl_setopt($curl_handler, CURLOPT_POST, true);
        curl_setopt($curl_handler, CURLOPT_POSTFIELDS, json_encode($post_fields));
        curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, false);//set to true in production
        curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, false);//set to true in production
        curl_setopt($curl_handler, CURLOPT_CONNECTTIMEOUT, 0);// 100; // set to zero for no timeout
        $result = curl_exec($curl_handler);

        if ($service == 1 || $service == 4) {//CREDIT CUSTOMER

            if($leg == 1){
                //create a new credit request log by providing ref_id
                $this->createCreditRequestLog($extTransactionID, json_encode($post_fields));
            }else if ($leg == 2){
                //update credit request log by the ref_id created during debit
                $this->updateCreditRequestLog($extTransactionID,json_encode($post_fields));
            }

            $file = __DIR__ . '/UNZA-Credit-Customer-Request.txt';
            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($post_fields) . "\n" . "\n";
            file_put_contents($file, $date, FILE_APPEND);

            $this->logWriter->logInfo(APP_INFO_LOG, 'UNZA Credit Request', json_encode($post_fields));

        } else if ($service == 2 || $service == 3) {//DEBIT CUSTOMER

            $this->createDebitRequestLog($extTransactionID, json_encode($post_fields));

            $file = __DIR__ . '/UNZA-Debit-Customer-Request.txt';
            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($post_fields) . "\n" . "\n";
            file_put_contents($file, $date, FILE_APPEND);

            $this->logWriter->logInfo(APP_INFO_LOG, 'UNZA Debit Request', json_encode($post_fields));
        }


        if ($result === FALSE) {//cURL REQUEST ERROR OCCURRED

            $file = null;
            if ($service == 1 || $service == 4) {//DURING CREDIT CUSTOMER REQUEST
                $file = __DIR__ . '/UNZA-Credit-Request-Errors.txt';
                $this->logWriter->logError(APP_ERROR_LOG, 'UNZA Credit Request Error', json_encode(curl_error($curl_handler)));
            } else if ($service == 2 || $service == 3) {//DURING DEBIT CUSTOMER REQUEST
                $file = __DIR__ . '/UNZA-Debit-Request-Errors.txt';
                $this->logWriter->logError(APP_ERROR_LOG, 'UNZA Debit Request Error', json_encode(curl_error($curl_handler)));
            } else {
                $file = __DIR__ . '/UNZA-Request-Errors.txt';//DURING REQUEST
                $this->logWriter->logError(APP_ERROR_LOG, 'UNZA Request Error', json_encode(curl_error($curl_handler)));
            }

            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode(curl_error($curl_handler)) . "\n" . "\n";
            file_put_contents($file, $date, FILE_APPEND);

            die('Curl failed: ' . curl_error($curl_handler));

        } else {//cURL REQUEST SUCCESSFUL

            if ($service == 2 || $service == 3) {//DEBIT RESPONSE

                //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
                $file = __DIR__ . '/UNZA-Debit-Response.txt';
                $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $result . "\n" . "\n";
                file_put_contents($file, $date, FILE_APPEND);
                $this->logWriter->logInfo(APP_INFO_LOG, 'UNZA Debit Response', $result);
                //END OF WRITING TO FILE

                $json_obj = json_decode($result, true);
                $statusCode = isset($json_obj['statusCode']) ? $json_obj['statusCode'] : null;
                $statusDesc = isset($json_obj['statusDesc']) ? $json_obj['statusDesc'] : null;
                $coreTransactionID = isset($json_obj['query']['CoreTransactionID']) ? $json_obj['query']['CoreTransactionID'] : null;

                $this->updateDebitResponseLog($extTransactionID,  $result);
                $this->updateDebitResponseDetails($statusCode,$statusDesc, $coreTransactionID,  $extTransactionID);

            } elseif ($service == 1 || $service == 4) {//CREDIT RESPONSE

                //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
                $file = __DIR__ . '/UNZA-Credit-Response.txt';
                $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $result . "\n" . "\n";
                file_put_contents($file, $date, FILE_APPEND);
                $this->logWriter->logInfo(APP_INFO_LOG, 'UNZA Credit Response', $result);
                //END OF WRITING TO FILE

                $json_obj = json_decode($result, true);
                $statusCode = isset($json_obj['statusCode']) ? $json_obj['statusCode'] : null;
                $statusDesc = isset($json_obj['statusDesc']) ? $json_obj['statusDesc'] : null;
                $coreTransactionID = isset($json_obj['query']['CoreTransactionID']) ? $json_obj['query']['CoreTransactionID'] : null;

                $this->updateCreditResponseLog($extTransactionID,  $result);
                $this->updateCreditResponseDetails($statusCode, $statusDesc, $coreTransactionID, $extTransactionID);


            } else {//cURL ERROR

                //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
                $file = __DIR__ . '/UNZA-Error-Wallet-Transaction.txt';
                $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $result . "\n" . "\n";
                file_put_contents($file, $date, FILE_APPEND);
                $this->logWriter->logError(APP_ERROR_LOG, 'UNZA Error Wallet Transaction', $result);
                //END OF WRITING TO FILE

            }
        }

        curl_close($curl_handler);

        return $result;
    }
    //************************END OF PAYMENT API***************************//

    public function updateByProbase($external_trans_id, $probase_status_code, $probase_status_description, $cart_id)
    {

        $response = array();

        $sql = 'UPDATE unza_transactions SET
                                 external_trans_id = ?,
                                 probase_status_code =  ?,
                                 probase_status_description = ?
                WHERE  cart_id = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }


        $isParamBound = $stmt->bind_param('iisi', $external_trans_id, $probase_status_code, $probase_status_description, $cart_id);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $response['error'] = false;
                $response['message'] = 'Transaction updated successfully';
            } else {
                //FAILED TO UPDATE
                $response['error'] = true;
                $response['message'] = 'Failed to update transaction. Please try again!';
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);
        }

        $stmt->close();
        return $response;
    }

    public function createTransactionsDetails($cart_id, $transaction_details)
    {

        $response = array();

        foreach ($transaction_details as $key => $val) {

            $tmp = array();

            $sql = 'INSERT INTO transaction_details(cart_id, product_id, purchase_price) VALUES(?,?,?)';

            if (!($stmt = $this->conn->prepare($sql))) {
                $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
            }

            $isParamBound = $stmt->bind_param('iid', $cart_id, $val['product_id'], $val['purchase_price']);
            if (!$isParamBound) {
                $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
            }

            if ($stmt->execute()) {
                //CREATED
                //$new_id = $this->conn->insert_id;

                $tmp['error'] = false;
                $tmp['message'] = 'Transaction details created successfully';
                $response[] = $tmp;
                $stmt->close();
            } else {
                //FAILED TO CREATE
                $tmp['error'] = true;
                $tmp['message'] = 'Oops! An error occurred';
                $tmp['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;
                $response[] = $tmp;
                $stmt->close();
                $this->Execute_failed_to_file($response, $sql);
            }
        }

        return $response;
    }

    public function updateNsanoDebitCallbackDetails($msg, $reference, $code, $system_code = null, $transactionID = null)
    {
        $result = array();

        $sql = 'UPDATE unza_transactions 
                SET debit_Callback_StatusDesc = ?, debit_Callback_CoreTransactionID = ?, debit_Callback_StatusCode = ?,callback_system_code = ?, callback_transactionID = ? 
                WHERE debit_CoreTransactionID  = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('ssssss', $msg, $reference, $code, $system_code, $transactionID, $reference);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            $num_affected_rows = $stmt->affected_rows;

            if ($num_affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $result['error'] = false;
                $result['message'] = 'debit callback information updated successfully';
            } else {
                //FAILED TO UPDATE
                $result['error'] = true;
                $result['message'] = 'Failed to update debit callback information. Please try again!';
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $result['error'] = true;
            $result['message'] = 'Oops! An error occurred';
            $result['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($result, $sql);

        }

        if ($code == 00) {

            $resTraderInformation = $this->getTraderInformation($reference);

            if ($resTraderInformation != null) {

                if ($resTraderInformation['transaction_type_id'] == 1 || $resTraderInformation['transaction_type_id'] == 2) {//MAKE SELL OR MAKE ORDER

                    //PROCEED TO CREDIT THE SELLER
                    $this->makeNsanoCreditRequest($resTraderInformation['seller_mobile_number'], $resTraderInformation['amount'], $reference, 2);

                } else if ($resTraderInformation['transaction_type_id'] == 3) {//TICKET PURCHASE

                    //NOTIFY THE BUYER
                    $buyer_msg = $msg . " The ticket number is ticket " . $resTraderInformation['cart_id'] . ". Transaction REF: " . strtoupper($reference);
                    if ($resTraderInformation['seller_mobile_number'] !== null) {
//                        $this->pushSMS($buyer_msg, $resTraderInformation['seller_mobile_number']);
                        $this->saveSMS($buyer_msg, $resTraderInformation['seller_mobile_number']);
                        $this->pushSMS($buyer_msg, "260973297682");//test
                    }
                    //$this->pushSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                    $this->saveSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                    $this->pushSMS($buyer_msg, "260973297682");//test

                    $response['code'] = '00';
                    $response['msg'] = 'Transaction successful';

                    $this->echoResponse(HTTP_status_200_OK, $response);//NOTIFY NSANO

                    $this->pushToProbasePurchaseTicketAPI($reference);

                } else if ($resTraderInformation['transaction_type_id'] == 4) {//MARKET FEE PAYMENT

                    //NOTIFY THE BUYER
                    $buyer_msg = $msg . ". Transaction REF: " . strtoupper($reference);
                    //$this->pushSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                    $this->saveSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                    $this->pushSMS($buyer_msg, "260973297682");//test

                    $response['code'] = '00';
                    $response['msg'] = 'Transaction successful';

                    $this->echoResponse(HTTP_status_200_OK, $response);//NOTIFY NSANO

                    $this->createPaidMarketChargesRecord($resTraderInformation['buyer_mobile_number'], $resTraderInformation['amount'], $resTraderInformation['stand_number']);

                    $resCollectionAccounts = $this->getCollectionAccounts();

                    if ($resCollectionAccounts != null) {

                        foreach ($resCollectionAccounts as $key => $val) {

                            //$refID = $resTraderInformation['cart_id'] . date("YmdHis");
                            $refID = date("YmdHis");

                            $this->makeNsanoCreditRequest($val['account'], ($resTraderInformation['amount'] * ($val['percentage'] / 100)), $refID, 1);

                        }

                    }

                }

            }

        } else { //TRANSACTION FAILED AT NSANO OR TELCO

            $resTraderInformation = $this->getTraderInformation($reference);

            if ($resTraderInformation != null) {

                if ($resTraderInformation['transaction_type_id'] == 1 || $resTraderInformation['transaction_type_id'] == 2) {//MAKE SELL OR MAKE ORDER

                    //BUYER
                    //$buyer_msg = $msg . " at Nsano or Telco";
                    $buyer_msg = $msg;
                    //$this->pushSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                    $this->saveSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                    $this->pushSMS($buyer_msg, "260973297682");//test
                    //BUYER

                    //SELLER
                    //$seller_msg = "Transaction for " . $resTraderInformation['buyer_mobile_number'] . " has failed at Nsano or Telco";
                    $seller_msg = "Payment transaction for " . $resTraderInformation['buyer_mobile_number'] . " has failed.";
//                    $this->pushSMS($seller_msg, $resTraderInformation['seller_mobile_number']);
                    $this->saveSMS($seller_msg, $resTraderInformation['seller_mobile_number']);
                    $this->pushSMS($seller_msg, "260973297682");//test
                    //SELLER

                } else if ($resTraderInformation['transaction_type_id'] == 3) {//TICKET PURCHASE

                    //NOTIFY THE BUYER
                    if ($resTraderInformation['seller_mobile_number'] !== null) {
                        //$this->pushSMS($msg, $resTraderInformation['seller_mobile_number']);
                        $this->saveSMS($msg, $resTraderInformation['seller_mobile_number']);
                        $this->pushSMS($msg, "260973297682");//test
                    }
                    //$this->pushSMS($msg, $resTraderInformation['buyer_mobile_number']);
                    $this->saveSMS($msg, $resTraderInformation['buyer_mobile_number']);
                    $this->pushSMS($msg, "260973297682");//test

                } else if ($resTraderInformation['transaction_type_id'] == 4) {//MARKET FEE PAYMENT
                    //$this->pushSMS($msg, $resTraderInformation['buyer_mobile_number']);
                    $this->saveSMS($msg, $resTraderInformation['buyer_mobile_number']);
                    $this->pushSMS($msg, "260973297682");//test
                }


            } else {
                $result['error'] = true;
                $result['message'] = 'Oops! An error occurred when fetching trader information';
            }

            $response['code'] = '01';
            $response['msg'] = "Failure to complete transaction";

            $this->echoResponse(HTTP_status_200_OK, $response);//NOTIFY NSANO
        }

        $stmt->close();
        //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
        $file = __DIR__ . '/4-updateTransactionDebitCallbackDetails.txt';
        $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($result) . "\n" . "\n";
        file_put_contents($file, $date, FILE_APPEND);
        //END OF WRITING TO FILE
        return null;
    }

    public function getTraderInformation($extTransactionID)
    {
        $response = array();

        $sql = 'SELECT cart_id,seller_mobile_number,buyer_mobile_number,amount,transaction_type_id,stand_number
                FROM unza_transactions
                WHERE debit_ExtTransactionID = ? OR credit_ExtTransactionID = ?';

        $isPrepared = $stmt = $this->conn->prepare($sql);

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('ss', $extTransactionID,$extTransactionID);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //IF FOUND

                $stmt->bind_result($cart_id_db, $seller_mobile_number, $buyer_mobile_number, $amount, $transaction_type_id, $stand_number);

                while ($stmt->fetch()) {
                    $tmp = array();

                    $tmp['cart_id'] = $cart_id_db;
                    $tmp['seller_mobile_number'] = $seller_mobile_number;
                    $tmp['buyer_mobile_number'] = $buyer_mobile_number;
                    $tmp['amount'] = $amount;
                    $tmp['transaction_type_id'] = $transaction_type_id;
                    $tmp['stand_number'] = $stand_number;

                    $response = $tmp;
                }


            } else {
                //IF NOT FOUND
                $response = null;
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);
        }

        $stmt->close();
        return $response;
    }

    public function getTransactionFees()
    {
        $response = array();

        $sql = 'SELECT id, name, value, status, charge_type
                FROM unza_transaction_charges
                WHERE status = 1
                ';

        $isPrepared = $stmt = $this->conn->prepare($sql);

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

//        $isParamBound = $stmt->bind_param('s', $debit_reference);
//        if (!$isParamBound) {
//            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
//        }

        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //IF FOUND

                $stmt->bind_result($id, $name, $value, $status, $charge_type);

                while ($stmt->fetch()) {
                    $tmp = array();

                    $tmp['id'] = $id;
                    $tmp['name'] = $name;
                    $tmp['value'] = $value;
                    $tmp['status'] = $status;
                    $tmp['charge_type'] = $charge_type;

                    $response[] = $tmp;
                }

                $file = __DIR__ . '/getTransactionFees(Found).txt';
                $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $response . "\n" . "\n";
                file_put_contents($file, $date, FILE_APPEND);

            } else {
                //IF NOT FOUND
                $response = null;
                $file = __DIR__ . '/getTransactionFees(NotFound).txt';
                $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $response . "\n" . "\n";
                file_put_contents($file, $date, FILE_APPEND);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);
        }

        $stmt->close();

        $file = __DIR__ . '/getTransactionFees(return).txt';
        $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $response . "\n" . "\n";
        file_put_contents($file, $date, FILE_APPEND);
        return $response;
    }

    public function getTotalTransactionFee($amount){

        $total_transaction_fee = 0;

        //retrieve possible transaction fees
        $resTransactionFee = $this->getTransactionFees();

        if($resTransactionFee != null) {//CALCULATE TOTAL ACTIVE TRANSACTION FEE

            //loop through all active transaction fees
            foreach ($resTransactionFee as $key => $val) {

                if ($val['charge_type'] == "Percentage") {//PERCENTAGE

                    $total_transaction_fee = $total_transaction_fee + ($amount * ($val['value'] / 100));

                } else {//NONE PERCENTAGE

                    $total_transaction_fee = $total_transaction_fee + ($amount - $val['value']);

                }

            }

        }else{//NO ACTIVE TRANSACTION FEE
            $total_transaction_fee = 0;
        }

        $file = __DIR__ . '/getTotalTransactionFee(return).txt';
        $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $total_transaction_fee . "\n" . "\n";
        file_put_contents($file, $date, FILE_APPEND);
        return $total_transaction_fee;

    }

    public function getCollectionAccounts()
    {
        $response = array();

        $sql = 'SELECT id, code, name, account, type, status, percentage
                FROM unza_market_charge_collection_accounts
                WHERE status = 1
                ';

        $isPrepared = $stmt = $this->conn->prepare($sql);

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

//        $isParamBound = $stmt->bind_param('s', $debit_reference);
//        if (!$isParamBound) {
//            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
//        }

        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //IF FOUND

                $stmt->bind_result($id, $code, $name, $account, $type, $status, $percentage);

                while ($stmt->fetch()) {
                    $tmp = array();

                    $tmp['id'] = $id;
                    $tmp['code'] = $code;
                    $tmp['name'] = $name;
                    $tmp['account'] = $account;
                    $tmp['type'] = $type;
                    $tmp['status'] = $status;
                    $tmp['percentage'] = $percentage;

                    $response[] = $tmp;
                }

                $file = __DIR__ . '/getCollectionAccounts(Found).txt';
                $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($response) . "\n" . "\n";
                file_put_contents($file, $date, FILE_APPEND);

            } else {
                //IF NOT FOUND
                $response = null;

                $file = __DIR__ . '/getCollectionAccounts(NotFound).txt';
                $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($response) . "\n" . "\n";
                file_put_contents($file, $date, FILE_APPEND);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);
        }

        $stmt->close();

        $file = __DIR__ . '/getCollectionAccounts(return).txt';
        $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($response) . "\n" . "\n";
        file_put_contents($file, $date, FILE_APPEND);
        return $response;
    }

    public function updateDebitResponseDetails($debit_StatusCode, $debit_StatusDesc, $debit_CoreTransactionID, $extTransactionID)
    {
        $response = array();

        $sql = 'UPDATE unza_transactions 
                SET 
                    debit_StatusCode = ?, 
                    debit_StatusDesc = ?, 
                    debit_CoreTransactionID = ? 
                WHERE debit_ExtTransactionID  = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('ssss', $debit_StatusCode, $debit_StatusDesc, $debit_CoreTransactionID,$extTransactionID);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $response['error'] = false;
                $response['message'] = 'updateDebitResponseDetails updated successfully';

                //$this->echoResponse(HTTP_status_200_OK,$response);
            } else {
                //FAILED TO UPDATE
                $response['error'] = true;
                $response['message'] = 'Failed updateDebitResponseDetails. Please try again!';

                //$this->echoResponse(HTTP_status_422_Unprocessable_Entity,$response);
                $this->Affect_Rows_failed_to_file($response,$sql);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            //$this->echoResponse(HTTP_status_500_Internal_Server_Error,$response);
        }

        $stmt->close();
        return null;
    }

    public function updateCreditResponseDetails($credit_StatusCode, $credit_StatusDesc, $credit_CoreTransactionID, $extTransactionID)
    {
        $response = array();

        $sql = 'UPDATE unza_transactions 
                SET 
                    credit_StatusCode = ?, 
                    credit_StatusDesc = ?,
                    credit_CoreTransactionID = ? 
                WHERE credit_ExtTransactionID  = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('ssss', $credit_StatusCode, $credit_StatusDesc, $credit_CoreTransactionID, $extTransactionID);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $response['error'] = false;
                $response['message'] = 'updateCreditResponseDetails successfully';

                //$this->echoResponse(HTTP_status_200_OK,$response);
            } else {
                //FAILED TO UPDATE
                $response['error'] = true;
                $response['message'] = 'Failed updateCreditResponseDetails. Please try again!';

                //$this->echoResponse(HTTP_status_422_Unprocessable_Entity,$response);
                $this->Affect_Rows_failed_to_file($response,$sql);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            //$this->echoResponse(HTTP_status_500_Internal_Server_Error,$response);
        }

        $stmt->close();
        return null;
    }

    public function updateDebitCallbackResponseDetails($debit_Callback_StatusCode,$debit_Callback_StatusDesc,$debit_Callback_CoreTransactionID, $extTransactionID,$accountNumber,$amount)
    {
        $result = array();

        $sql = 'UPDATE unza_transactions 
                SET 
                    debit_Callback_StatusCode = ?,
                    debit_Callback_StatusDesc = ?, 
                    debit_MobileNumber = ?,
                    debit_Callback_CoreTransactionID = ? 
                WHERE debit_ExtTransactionID = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('sssss', $debit_Callback_StatusCode,$debit_Callback_StatusDesc,$accountNumber,$debit_Callback_CoreTransactionID,$extTransactionID);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            $num_affected_rows = $stmt->affected_rows;

            if ($num_affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $result['error'] = false;
                $result['message'] = 'updateDebitCallbackResponseDetails successfully';
            } else {
                //FAILED TO UPDATE
                $result['error'] = true;
                $result['message'] = 'Failed to updateDebitCallbackResponseDetails. Please try again!';

                $this->Affect_Rows_failed_to_file($result,$sql);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $result['error'] = true;
            $result['message'] = 'Oops! An error occurred';
            $result['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($result, $sql);
        }


        $this->echoResponse(HTTP_status_200_OK, ['status' => 1,'description' => 'Callback received successfully']);//NOTIFY


        if ($debit_Callback_StatusCode == 300) {//SUCCESSFUL

            $resTraderInformation = $this->getTraderInformation($extTransactionID);

            if ($resTraderInformation != null) {

                if ($resTraderInformation['transaction_type_id'] == 1 || $resTraderInformation['transaction_type_id'] == 2) {//MAKE SELL OR MAKE ORDER

                    //PROCEED TO CREDIT THE SELLER
                    $credit_extTransactionID = $this->updateCreditExtTransactionID($extTransactionID);

                    $this->makeUNZACreditRequest($resTraderInformation['seller_mobile_number'], $resTraderInformation['amount'], $credit_extTransactionID, 2);

                } else if ($resTraderInformation['transaction_type_id'] == 3) {//TICKET PURCHASE

                    $this->pushToProbasePurchaseTicketAPI($debit_Callback_CoreTransactionID, $resTraderInformation['cart_id'], $resTraderInformation['seller_mobile_number'], $resTraderInformation['buyer_mobile_number']);

                } else if ($resTraderInformation['transaction_type_id'] == 4) {//MARKET FEE PAYMENT

                    //NOTIFY THE BUYER
                    $buyer_msg = $debit_Callback_StatusDesc . ". Thank you for paying market fee. Transaction REF: " . strtoupper($debit_Callback_CoreTransactionID);
                    //$this->pushSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                    $this->saveSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                    $this->pushSMS($buyer_msg, "260973297682");//test


                    //$this->createPaidMarketChargesRecord($resTraderInformation['buyer_mobile_number'], $resTraderInformation['amount'],$resTraderInformation['stand_number']);
                    $this->updatePaidMarketCharges($resTraderInformation['buyer_mobile_number'], $resTraderInformation['stand_number']);


                    $resCollectionAccounts = $this->getCollectionAccounts();

                    if ($resCollectionAccounts != null) {

                        foreach ($resCollectionAccounts as $key => $val) {

                            $this->makeUNZACreditRequest($val['account'], ($resTraderInformation['amount'] * ($val['percentage'] / 100)), $this->generateExtTransactionID(), 1);

                        }

                    }

                }

            } else {
                $result['error'] = true;
                $result['message'] = 'Oops! An error occurred when fetching trader information';
                $this->write_Error_to_file($result);
            }

        } else { //TRANSACTION FAILED

            $resTraderInformation = $this->getTraderInformation($extTransactionID);

            if ($resTraderInformation != null) {

                if ($resTraderInformation['transaction_type_id'] == 1 || $resTraderInformation['transaction_type_id'] == 2) {//MAKE SELL OR MAKE ORDER

                    //BUYER
                    $buyer_msg = $this->getStatusDescription($debit_Callback_StatusCode);
                    //$this->pushSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                    $this->saveSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                    $this->pushSMS($buyer_msg, "260973297682");//test
                    //BUYER

                    //SELLER
                    $seller_msg = "Payment transaction for " . $resTraderInformation['buyer_mobile_number'] . " has failed.";
                    //$this->pushSMS($seller_msg, $resTraderInformation['seller_mobile_number']);
                    $this->saveSMS($seller_msg, $resTraderInformation['seller_mobile_number']);
                    $this->pushSMS($seller_msg, "260973297682");//test
                    //SELLER

                } else if ($resTraderInformation['transaction_type_id'] == 3) {//TICKET PURCHASE

                    //NOTIFY THE BUYER
                    if ($resTraderInformation['seller_mobile_number'] !== null) {
                        //$this->pushSMS($msg, $resTraderInformation['seller_mobile_number']);
                        $this->saveSMS($this->getStatusDescription($debit_Callback_StatusCode), $resTraderInformation['seller_mobile_number']);
                        $this->pushSMS($this->getStatusDescription($debit_Callback_StatusCode), "260973297682");//test
                    }
                    //$this->pushSMS($msg, $resTraderInformation['buyer_mobile_number']);
                    $this->saveSMS($this->getStatusDescription($debit_Callback_StatusCode), $resTraderInformation['buyer_mobile_number']);
                    $this->pushSMS($this->getStatusDescription($debit_Callback_StatusCode), "260973297682");//test

                } else if ($resTraderInformation['transaction_type_id'] == 4) {//MARKET FEE PAYMENT
                    //$this->pushSMS($msg, $resTraderInformation['buyer_mobile_number']);
                    $this->saveSMS($this->getStatusDescription($debit_Callback_StatusCode), $resTraderInformation['buyer_mobile_number']);
                    $this->pushSMS($this->getStatusDescription($debit_Callback_StatusCode), "260973297682");//test
                }


            } else {
                $result['error'] = true;
                $result['message'] = 'Oops! An error occurred when fetching trader information';
                $this->write_Error_to_file($result);
            }
        }

        $stmt->close();
        return null;
    }

    public function updateCreditCallbackResponseDetails($credit_Callback_StatusCode,$credit_Callback_StatusDesc,$credit_Callback_CoreTransactionID, $extTransactionID,$accountNumber,$amount)
    {
        $result = array();

        $sql = 'UPDATE unza_transactions 
                SET 
                    credit_Callback_StatusCode = ?,
                    credit_Callback_StatusDesc = ?, 
                    credit_MobileNumber = ?,
                    credit_Callback_CoreTransactionID = ? 
                WHERE credit_ExtTransactionID  = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('sssss', $credit_Callback_StatusCode,$credit_Callback_StatusDesc,$accountNumber,$credit_Callback_CoreTransactionID,$extTransactionID);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            $num_affected_rows = $stmt->affected_rows;

            if ($num_affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $result['error'] = false;
                $result['message'] = 'updateCreditCallbackResponseDetails successfully';
            } else {
                //FAILED TO UPDATE
                $result['error'] = true;
                $result['message'] = 'Failed to updateCreditCallbackResponseDetails. Please try again!';

                $this->Affect_Rows_failed_to_file($result,$sql);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $result['error'] = true;
            $result['message'] = 'Oops! An error occurred';
            $result['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($result, $sql);
        }


        $this->echoResponse(HTTP_status_200_OK, ['status' => 1,'description' => 'Callback received successfully']);//NOTIFY


        if ($credit_Callback_StatusCode == 300) {//SUCCESSFUL

            $resTraderInformation = $this->getTraderInformation($extTransactionID);

            if ($resTraderInformation != null) {

                if ($resTraderInformation['transaction_type_id'] == 1 || $resTraderInformation['transaction_type_id'] == 2) {//MAKE SELL OR MAKE ORDER

//                    if($resTraderInformation['transaction_type_id'] == 1){
//
//                    }elseif ($resTraderInformation['transaction_type_id'] == 2){
//
//                    }
                    $seller_msg = "Transaction successful. You have received ZMW " . $amount . ". Transaction REF: " . strtoupper($credit_Callback_CoreTransactionID);
                    $buyer_msg = "Transaction successful. You have purchased goods worth ZMW " . $amount . ". Transaction REF: " . strtoupper($credit_Callback_CoreTransactionID);

                    //NOTIFY THE SELLER
                    //$this->pushSMS($seller_msg, $msisdn);
                    $this->saveSMS($seller_msg, $resTraderInformation['seller_mobile_number']);
                    $this->pushSMS($seller_msg, "260973297682");//test

                    //NOTIFY THE BUYER
                    //$this->pushSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                    $this->saveSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                    $this->pushSMS($buyer_msg, "260973297682");//test


                } else if ($resTraderInformation['transaction_type_id'] == 3) {//TICKET PURCHASE



                } else if ($resTraderInformation['transaction_type_id'] == 4) {//MARKET FEE PAYMENT

                    //$this->pushSMS($statusDesc, $msisdn);
//                    $this->saveSMS($statusDesc, $resTraderInformation['buyer_mobile_number']);
//                    $this->pushSMS($statusDesc, "260973297682");//test


                }

            } else {
                $result['error'] = true;
                $result['message'] = 'Oops! An error occurred when fetching trader information';
                $this->write_Error_to_file($result);
            }

        } else { //TRANSACTION FAILED

            $resTraderInformation = $this->getTraderInformation($extTransactionID);

            if ($resTraderInformation != null) {

                if ($resTraderInformation['transaction_type_id'] == 1 || $resTraderInformation['transaction_type_id'] == 2) {//MAKE SELL OR MAKE ORDER

                    //BUYER
                    $buyer_msg = $this->getStatusDescription($credit_Callback_StatusCode);
                    //$this->pushSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                    $this->saveSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                    $this->pushSMS($buyer_msg, "260973297682");//test
                    //BUYER

                    //SELLER
                    $seller_msg = "Payment transaction for " . $resTraderInformation['buyer_mobile_number'] . " has failed.";
                    //$this->pushSMS($seller_msg, $resTraderInformation['seller_mobile_number']);
                    $this->saveSMS($seller_msg, $resTraderInformation['seller_mobile_number']);
                    $this->pushSMS($seller_msg, "260973297682");//test
                    //SELLER

                } else if ($resTraderInformation['transaction_type_id'] == 3) {//TICKET PURCHASE


                } else if ($resTraderInformation['transaction_type_id'] == 4) {//MARKET FEE PAYMENT

                    $msg = $this->getStatusDescription($credit_Callback_StatusCode) . " .Error during crediting wallet";
                    //$this->pushSMS($seller_msg, $resTraderInformation['seller_mobile_number']);
                    $this->saveSMS($msg, $resTraderInformation['seller_mobile_number']);
                    $this->pushSMS($msg . " receiver msg", "260973297682");//test


                }


            } else {
                $result['error'] = true;
                $result['message'] = 'Oops! An error occurred when fetching trader information';
                $this->write_Error_to_file($result);
            }
        }

        $stmt->close();
        return null;
    }

    //sample request
    public function connectToProbaseAPI()
    {
        $url = 'http://api.domain.com';

        $headers = [
            'apiKey:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json'
        ];

        $post_fields = [
            'key' => 'value',
        ];


        //Initializing curl to open a connection
        $curl_handler = curl_init();

        curl_setopt($curl_handler, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_handler, CURLOPT_URL, $url);
        curl_setopt($curl_handler, CURLOPT_POST, true);
        curl_setopt($curl_handler, CURLOPT_POSTFIELDS, http_build_query($post_fields));
        curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, false);//set to true in production
        curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, false);//set to true in production
        curl_setopt($curl_handler, CURLOPT_CONNECTTIMEOUT, 0);// 100; // set to zero for no timeout

        $result = curl_exec($curl_handler);
        if ($result === FALSE) {

            $file = __DIR__ . '/SMS_Send_Errors.txt';
            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode(curl_error($curl_handler)) . "\n" . "\n";
            file_put_contents($file, $date, FILE_APPEND);

            die('Curl failed: ' . curl_error($curl_handler));

        } else {

        }

        curl_close($curl_handler);

        return $result;
    }

    public function pushToProbaseMakeSalesAPI($debit_reference)
    {
        $url = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';

        $headers = [
            'x-request-id:Fe2lL9okYV7WkCEAAAeB',
            'Content-Type: application/json',
            'Accept: application/json'
        ];


        $salesRes = $this->getTransactionDetails($debit_reference);

        $auth = [
            "username" => "admin",
            "service_token" => "JJ8DJ7S66DMA5"
        ];
        $payload = [
            'cart_id' => $salesRes['cart_id'],
            'transaction_type' => $salesRes['transaction_type'],

            'seller_uuid' => $salesRes['seller_id'],
            'seller_firstname' => $salesRes['seller_firstname'],
            'seller_lastname' => $salesRes['seller_lastname'],
            'seller_mobile_number' => $salesRes['seller_mobile_number'],

            'buyer_uuid' => $salesRes['buyer_id'],
            'buyer_firstname' => $salesRes['buyer_firstname'],
            'buyer_lastname' => $salesRes['buyer_lastname'],
            'buyer_mobile_number' => $salesRes['buyer_mobile_number'],

            'amount' => $salesRes['amount'],
            'transaction_date' => $salesRes['transaction_date']
        ];

        $json = [
            "auth" => $auth,
            "payload" => $payload
        ];

        //Initializing curl to open a connection
        $curl_handler = curl_init();

        curl_setopt($curl_handler, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_handler, CURLOPT_URL, $url);
        curl_setopt($curl_handler, CURLOPT_POST, true);
        curl_setopt($curl_handler, CURLOPT_POSTFIELDS, json_encode($json));
        curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, false);//set to true in production
        curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, false);//set to true in production
        curl_setopt($curl_handler, CURLOPT_CONNECTTIMEOUT, 0);// 100; // set to zero for no timeout

        $result = curl_exec($curl_handler);
        if ($result === FALSE) {

            $file = __DIR__ . '/MarketSales_Errors.txt';

            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode(curl_error($curl_handler)) . "\n" . "\n";

            file_put_contents($file, $date, FILE_APPEND);

            die('Curl failed: ' . curl_error($curl_handler));

        } else {

            //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
            $file = __DIR__ . '/Probase-Market-Sales';
            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $result . "\n" . "\n";
            file_put_contents($file, $date, FILE_APPEND);
            //END OF WRITING TO FILE

            $json_obj = json_decode($result, true);
//            $msg = isset($json_obj['msg']) ? $json_obj['msg'] : null;
//            $reference = isset($json_obj['reference']) ? $json_obj['reference'] : null;
//            $code = isset($json_obj['code']) ? $json_obj['code'] : null;
//            $system_code = isset($json_obj['system_code']) ? $json_obj['system_code'] : null;
//            $transactionID = isset($json_obj['transactionID']) ? $json_obj['transactionID'] : null;
        }

        curl_close($curl_handler);

        return $result;
    }

    public function pushToProbasePurchaseTicketAPI($debit_CoreTransactionID, $external_reference, $seller_mobile_number, $buyer_mobile_number)
    {
        $url = "http://" . PROBASE_API_IP . ":" . PROBASE_API_PORT . "/api/v1/btms/tickets/secured/purchase";

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        $received = [
            "debit_CoreTransactionID" =>$debit_CoreTransactionID,
            "external_reference" => $external_reference,
            "seller mobile number" => $seller_mobile_number,
            "buyer mobile number" => $buyer_mobile_number
        ];

        //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
        $file = __DIR__ . '/pushToProbasePurchaseTicketAPI-Parameters.txt';
        $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($received) . "\n" . "\n";
        file_put_contents($file, $date, FILE_APPEND);
        //END OF WRITING TO FILE


        $ticketRes = $this->getTransactionDetails($debit_CoreTransactionID);

        $auth = [
            "username" => "manager",
            "service_token" => "JJ8DJ7S66DMA5"
        ];
        $payload = [
            'external_ref' => 'ticket' . $ticketRes['cart_id'],
            'route_code' => $ticketRes['route_code'],
            'first_name' => $ticketRes['buyer_firstname'],
            'other_name' => '',
            'last_name' => $ticketRes['buyer_lastname'],
            'email' => $ticketRes['buyer_email'],
            'transaction_channel' => $ticketRes['transaction_channel'],
            'id_type' => $ticketRes['id_type'],
            'passenger_id' => $ticketRes['passenger_id'],
            'bus_schedule_id' => $ticketRes['bus_schedule_id'],
            'travel_date' => $ticketRes['travel_date'],
            'mobile_number' => $ticketRes['buyer_mobile_number'],
            'payment_mode' => !empty($ticketRes['seller_mobile_number']) ? $this->getMNO($ticketRes['seller_mobile_number']) : $this->getMNO($ticketRes['buyer_mobile_number'])
        ];

        $json = [
            "auth" => $auth,
            "payload" => $payload
        ];

        //Initializing curl to open a connection
        $curl_handler = curl_init();
        curl_setopt($curl_handler, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_handler, CURLOPT_URL, $url);
        curl_setopt($curl_handler, CURLOPT_POST, true);
        curl_setopt($curl_handler, CURLOPT_POSTFIELDS, json_encode($json));
        curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, false);//set to true in production
        curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, false);//set to true in production
        //curl_setopt($curl_handler, CURLOPT_CONNECTTIMEOUT, 0);// 100; // set to zero for no timeout

        //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
        $file = __DIR__ . '/Probase-TicketPurchase-Request.txt';
        $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($json) . "\n" . "\n";
        file_put_contents($file, $date, FILE_APPEND);
        //END OF WRITING TO FILE

        $this->logWriter->logInfo(APP_INFO_LOG, 'Probase TicketPurchase Request', json_encode($json));

        $msg = null;
        $result = curl_exec($curl_handler);
        if ($result === FALSE) {

            //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
            $file = __DIR__ . '/Probase-TicketPurchase-Errors.txt';
            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode(curl_error($curl_handler)) . "\n" . "\n";
            file_put_contents($file, $date, FILE_APPEND);
            //END OF WRITING TO FILE

            $this->logWriter->logError(APP_ERROR_LOG, 'Probase TicketPurchase Error', json_encode(curl_error($curl_handler)));

            $msg = "Debit transaction successful. Thank you for purchasing ticket. Error occurred when attempting to connect to ticket system.External REF: ticket" . $external_reference . " .Transaction REF: " . strtoupper($debit_CoreTransactionID);
            if ($seller_mobile_number != null || $seller_mobile_number != "") {
                //$this->pushSMS($msg, $seller_mobile_number);
                $this->saveSMS($msg, $seller_mobile_number);
                $this->pushSMS($msg . " seller msg(".$seller_mobile_number.")", "260973297682");//test
            }
            //$this->pushSMS($msg, $buyer_mobile_number);
            $this->saveSMS($msg, $buyer_mobile_number);
            $this->pushSMS($msg . " buyer msg(".$buyer_mobile_number.")", "260973297682");//test

            die('Curl failed: ' . curl_error($curl_handler));

        } else {

            //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
            $file = __DIR__ . '/Probase-TicketPurchase-Response.txt';
            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $result . "\n" . "\n";
            file_put_contents($file, $date, FILE_APPEND);
            //END OF WRITING TO FILE

            $this->logWriter->logInfo(APP_INFO_LOG, 'Probase Ticket Purchase Response', $result);

            $json_obj = json_decode($result, true);
            $response_json_object = $json_obj['response'];
            $purchase_json_object = $response_json_object['PURCHASE'];
            $data_json_object = $purchase_json_object['data'];
            $activation_status = isset($data_json_object['activation_status']) ? $data_json_object['activation_status'] : null;
            $bus_schedule_id = isset($data_json_object['bus_schedule_id']) ? $data_json_object['bus_schedule_id'] : null;
            $currency = isset($data_json_object['currency']) ? $data_json_object['currency'] : null;
            $email = isset($data_json_object['email']) ? $data_json_object['email'] : null;
            $end_route = isset($data_json_object['end_route']) ? $data_json_object['end_route'] : null;
            $external_reference = isset($data_json_object['external_reference']) ? $data_json_object['external_reference'] : null;
            $first_name = isset($data_json_object['first_name']) ? $data_json_object['first_name'] : null;
            $id_type = isset($data_json_object['id_type']) ? $data_json_object['id_type'] : null;
            $last_name = isset($data_json_object['last_name']) ? $data_json_object['last_name'] : null;
            $mobile_number = isset($data_json_object['mobile_number']) ? $data_json_object['mobile_number'] : null;
            $other_name = isset($data_json_object['other_name']) ? $data_json_object['other_name'] : null;
            $passenger_id = isset($data_json_object['passenger_id']) ? $data_json_object['passenger_id'] : null;
            $reference_number = isset($data_json_object['reference_number']) ? $data_json_object['reference_number'] : null;
            $route_code = isset($data_json_object['route_code']) ? $data_json_object['route_code'] : null;
            $serial_number = isset($data_json_object['serial_number']) ? $data_json_object['serial_number'] : null;
            $start_route = isset($data_json_object['start_route']) ? $data_json_object['start_route'] : null;
            $ticket_id = isset($data_json_object['ticket_id']) ? $data_json_object['ticket_id'] : null;
            $travel_date = isset($data_json_object['travel_date']) ? $data_json_object['travel_date'] : null;

            if($activation_status != null AND $activation_status == 'VALID'){

                //$msg = "Debit transaction successful. Thank you for purchasing ticket " .$ticket_id. ". Travel date: " . $travel_date . ", Start destination: " . $this->insert_name_prep($start_route) . ", End destination: " . $this->insert_name_prep($end_route) . " . REF NUM: " . $reference_number . ". Transaction REF: " . strtoupper($debit_CoreTransactionID);
                $msg = "Debit transaction successful. Thank you for purchasing ticket " .$ticket_id. ". Travel date: " . $travel_date. ", Departure time: ".$ticketRes['travel_time']. ", Start destination: " .$this->insert_name_prep($start_route). ", End destination: " .$this->insert_name_prep($end_route). ". REF NUM: " .$reference_number. ". Transaction REF: " .strtoupper($debit_CoreTransactionID);
                if ($seller_mobile_number !== null || $seller_mobile_number !== "") {
                    //$this->pushSMS($msg, $seller_mobile_number);
                    $this->saveSMS($msg, $seller_mobile_number);
                    $this->pushSMS($msg . " seller msg(".$seller_mobile_number.")", "260973297682");//test
                }
                //$this->pushSMS($msg, $buyer_mobile_number);
                $this->saveSMS($msg, $buyer_mobile_number);
                $this->pushSMS($msg ." buyer msg(".$buyer_mobile_number.")", "260973297682");//test

            }else{

                //call check status
                $msg = "Debit transaction successful. Thank you for purchasing ticket. Error occurred when retrieving ticket details. Transaction REF: " . strtoupper($debit_CoreTransactionID);
                if ($seller_mobile_number !== null || $seller_mobile_number !== "") {
                    //$this->pushSMS($msg, $seller_mobile_number);
                    $this->saveSMS($msg, $seller_mobile_number);
                    $this->pushSMS($msg . " seller msg(".$seller_mobile_number.")", "260973297682");//test
                }
                //$this->pushSMS($msg, $buyer_mobile_number);
                $this->saveSMS($msg, $buyer_mobile_number);
                $this->pushSMS($msg . " buyer msg(".$buyer_mobile_number.")", "260973297682");//test
            }

        }

        curl_close($curl_handler);

        return $result;
    }

    public function getTransactionDetails($debit_CoreTransactionID)
    {
        $response = array();

        $sql = 'SELECT cart_id, transaction_type_id, route_code, transaction_channel, id_type, passenger_id, bus_schedule_id,travel_date, travel_time, 
                      seller_id, seller_firstname, seller_lastname, seller_mobile_number, 
                      buyer_id, buyer_firstname, buyer_lastname, buyer_mobile_number, buyer_email, 
                      amount, device_serial, transaction_date
                FROM unza_transactions
                WHERE debit_CoreTransactionID = ?';

        $isPrepared = $stmt = $this->conn->prepare($sql);

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('s', $debit_CoreTransactionID);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //IF FOUND

                $stmt->bind_result($cart_id, $transaction_type_id, $route_code, $transaction_channel, $id_type, $passenger_id, $bus_schedule_id, $travel_date, $travel_time,
                    $seller_id, $seller_firstname, $seller_lastname, $seller_mobile_number,
                    $buyer_id, $buyer_firstname, $buyer_lastname, $buyer_mobile_number, $buyer_email,
                    $amount, $device_serial, $transaction_date);

                while ($stmt->fetch()) {
                    $tmp = array();

                    $tmp['cart_id'] = $cart_id;
                    $tmp['transaction_type_id'] = $transaction_type_id;

                    $tmp['seller_id'] = $seller_id;
                    $tmp['seller_firstname'] = $seller_firstname;
                    $tmp['seller_lastname'] = $seller_lastname;
                    $tmp['seller_mobile_number'] = $seller_mobile_number;

                    $tmp['buyer_id'] = $buyer_id;
                    $tmp['buyer_firstname'] = $buyer_firstname;
                    $tmp['buyer_lastname'] = $buyer_lastname;
                    $tmp['buyer_mobile_number'] = $buyer_mobile_number;
                    $tmp['buyer_email'] = $buyer_email;

                    $tmp['amount'] = $amount;
                    $tmp['device_serial'] = $device_serial;
                    $tmp['transaction_date'] = $transaction_date;

                    $tmp['route_code'] = $route_code;
                    $tmp['transaction_channel'] = $transaction_channel;
                    $tmp['id_type'] = $id_type;
                    $tmp['passenger_id'] = $passenger_id;
                    $tmp['bus_schedule_id'] = $bus_schedule_id;
                    $tmp['travel_date'] = $travel_date;
                    $tmp['travel_time'] = $travel_time;

                    $response = $tmp;
                }


            } else {
                //IF NOT FOUND
                $response = null;
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);
        }

        $stmt->close();
        return $response;
    }

    public function pushSMS($msg, $destination)
    {
        $source = SMS_Sender_ID;
        $SMS = urlencode($msg);

        $url = "http://" . KANNEL_IP . ":" . KANNEL_PORT . "/napsamobile/pushsms?smsc=zamtelsmsc&username=" . KANNEL_USER . "&password=" . KANNEL_PASSWORD . "&from=" . $source . "&to=" . $destination . "&text=" . $SMS;
        try {

            $curl_handler = curl_init();
            curl_setopt($curl_handler, CURLOPT_URL, $url);
            curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, PaEaseConnectionTimeout);
//            curl_setopt($ch, CURLOPT_TIMEOUT, PaEaseReadTimeout);
            $response = curl_exec($curl_handler);

            if ($response === FALSE) {//cURL REQUEST ERROR OCCURRED

                $file = __DIR__ . '/Kannel-Request_Errors.txt';
                $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode(curl_error($curl_handler)) . "\n" . "\n";
                file_put_contents($file, $date, FILE_APPEND);

                die('Curl failed: ' . curl_error($curl_handler));

            }else{

                //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
                $file = __DIR__ . '/Kannel-Request_Response.txt';
                $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $response . " mobile number: " . $destination . "\n" . "\n";
                file_put_contents($file, $date, FILE_APPEND);
                //END OF WRITING TO FILE
            }

            curl_close($curl_handler);

        } catch (Exception $ex) {

            //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
            $file = __DIR__ . '/Kannel-Request_Exception.txt';
            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $ex . " mobile number: " . $destination . "\n" . "\n";
            file_put_contents($file, $date, FILE_APPEND);
            //END OF WRITING TO FILE
        }
    }

    public function saveSMS($message, $mobile_number)
    {
        $response = array();

        $sender_id = SMS_Sender_ID;
        $status = 0;

        $received = [
            "msg" => $message,
            "mobile number" => $mobile_number
        ];
        $file = __DIR__ . '/saveSMS.txt';
        $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($received) . "\n" . "\n";
        file_put_contents($file, $date, FILE_APPEND);

        $sql = 'INSERT INTO unza_sms_logs(sender_id, mobile_number, message, status) VALUES(?,?,?,?)';

        if (!($stmt = $this->conn_log->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn_log->errno . ') ' . $this->conn_log->error, $sql);
        }

        $isParamBound = $stmt->bind_param('ssss', $sender_id, $mobile_number, $message, $status);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {
            //CREATED
            $id = $this->conn_log->insert_id;

            $response['error'] = false;
            $response['message'] = 'SMS log captured';

            //$this->echoResponse(HTTP_status_200_OK, $response);

            //Create connection to rabbitmq server
            $connection = new AMQPStreamConnection(RABBIT_MQ_HOST, RABBIT_MQ_PORT, RABBIT_MQ_USER, RABBIT_MQ_PASSWORD);
            $channel = $connection->channel();

            //Get details of the message after insert in the db and build a payload array
            $json_array = [
                "id" => $id,
                "sender_id" => $sender_id,
                "mobile_number" => $mobile_number,
                "message" => $message,
                "attempts" => 0,
            ];
            //we declare a queue for us to send
            $channel->queue_declare('napsa_sms', false, false, false, false);

            //Publish a message to the queue
            $msg = new AMQPMessage(json_encode($json_array));
            $channel->basic_publish($msg, '', 'napsa_sms');


        } else {
            //FAILED TO CREATE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
            $file = __DIR__ . '/SMS-DBInsert-Errors.txt';
            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($response) . "\n" . "\n";
            file_put_contents($file, $date, FILE_APPEND);
            //END OF WRITING TO FILE
        }

        $stmt->close();
        return null;
    }

    //************************LOGS***************************//
    public function createDebitRequestLog($extTransactionID, $debit_request)
    {

        $response = array();

        $date_time = $this->getCurrentDateTime();

        $sql = 'INSERT INTO unza_transaction_logs(debit_extTransactionID,debit_request,debit_request_time) VALUES(?,?,?)';

        if (!($stmt = $this->conn_log->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn_log->errno . ') ' . $this->conn_log->error, $sql);
        }

        $isParamBound = $stmt->bind_param('sss', $extTransactionID, $debit_request, $date_time);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {
            //CREATED
            $response['error'] = false;
            $response['message'] = 'createDebitRequestLog captured';
            $stmt->close();

            //$this->echoResponse(HTTP_status_200_OK, $response);

        } else {
            //FAILED TO CREATE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;
            $stmt->close();

            $this->Execute_failed_to_file($response, $sql);

            //$this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }

        return null;
    }

    public function updateDebitResponseLog($extTransactionID, $debit_response)
    {
        $response = array();

        $date_time = $this->getCurrentDateTime();

        $sql = 'UPDATE unza_transaction_logs 
                SET 
                    debit_response = ?, 
                    debit_response_time = ? 
                WHERE debit_extTransactionID  = ?';

        if (!($stmt = $this->conn_log->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn_log->errno . ') ' . $this->conn_log->error, $sql);
        }

        $isParamBound = $stmt->bind_param('sss', $debit_response, $date_time, $extTransactionID);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $response['error'] = false;
                $response['message'] = 'updateDebitResponseLog successfully';

                //$this->echoResponse(HTTP_status_200_OK,$response);
            } else {
                //FAILED TO UPDATE
                $response['error'] = true;
                $response['message'] = 'Failed updateDebitResponseLog. Please try again!';

                //$this->echoResponse(HTTP_status_422_Unprocessable_Entity,$response);
                $this->Affect_Rows_failed_to_file($response,$sql);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            //$this->echoResponse(HTTP_status_500_Internal_Server_Error,$response);
        }

        $stmt->close();

        return null;
    }

    public function createCreditRequestLog($extTransactionID, $credit_request)
    {

        $response = array();

        $date_time = $this->getCurrentDateTime();

        $sql = 'INSERT INTO unza_transaction_logs(credit_extTransactionID,credit_request,credit_request_time) VALUES(?,?,?)';

        if (!($stmt = $this->conn_log->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn_log->errno . ') ' . $this->conn_log->error, $sql);
        }

        $isParamBound = $stmt->bind_param('sss', $extTransactionID, $credit_request, $date_time);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {
            //CREATED
            $response['error'] = false;
            $response['message'] = 'createCreditRequestLog captured';
            $stmt->close();

            //$this->echoResponse(HTTP_status_200_OK, $response);

        } else {
            //FAILED TO CREATE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;
            $stmt->close();

            $this->Execute_failed_to_file($response, $sql);

            //$this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }

        return null;
    }

    public function updateCreditRequestLog($extTransactionID, $credit_request)
    {
        $response = array();

        $date_time = $this->getCurrentDateTime();

        $sql = 'UPDATE unza_transaction_logs 
                SET 
                    credit_request = ?, 
                    credit_request_time = ? 
                WHERE credit_extTransactionID = ?';

        if (!($stmt = $this->conn_log->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn_log->errno . ') ' . $this->conn_log->error, $sql);
        }

        $isParamBound = $stmt->bind_param('ssi', $credit_request, $date_time, $extTransactionID);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $response['error'] = false;
                $response['message'] = 'Credit information updated successfully';

                //$this->echoResponse(HTTP_status_200_OK,$response);
            } else {
                //FAILED TO UPDATE
                $response['error'] = true;
                $response['message'] = 'Failed to update credit information. Please try again!';

                //$this->echoResponse(HTTP_status_422_Unprocessable_Entity,$response);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            //$this->echoResponse(HTTP_status_500_Internal_Server_Error,$response);
        }

        $stmt->close();

        return null;
    }

    public function updateDebitCallbackResponseLog($debit_callback_response,$extTransactionID)
    {
        $result = array();

        $date_time = $this->getCurrentDateTime();

        $sql = 'UPDATE unza_transaction_logs 
                SET 
                    debit_callback_response = ?, 
                    debit_callback_response_time = ? 
                WHERE debit_extTransactionID  = ?';

        if (!($stmt = $this->conn_log->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn_log->errno . ') ' . $this->conn_log->error, $sql);
        }

        $isParamBound = $stmt->bind_param('sss', $debit_callback_response, $date_time, $extTransactionID);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $result['error'] = false;
                $result['message'] = 'updateDebitCallbackResponseLog successfully';

                //$this->echoResponse(HTTP_status_200_OK,$response);
            } else {
                //FAILED TO UPDATE
                $result['error'] = true;
                $result['message'] = 'Failed updateDebitCallbackResponseLog. Please try again!';

                //$this->echoResponse(HTTP_status_422_Unprocessable_Entity,$response);
                $this->Affect_Rows_failed_to_file($result,$sql);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $result['error'] = true;
            $result['message'] = 'Oops! An error occurred';
            $result['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($result, $sql);

            //$this->echoResponse(HTTP_status_500_Internal_Server_Error,$response);
        }

        $stmt->close();

        return null;
    }

    public function updateCreditCallbackResponseLog($credit_callback_response,$extTransactionID)
    {
        $result = array();

        $date_time = $this->getCurrentDateTime();

        $sql = 'UPDATE unza_transaction_logs 
                SET 
                    credit_callback_response = ?, 
                    credit_callback_response_time = ? 
                WHERE credit_extTransactionID  = ?';

        if (!($stmt = $this->conn_log->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn_log->errno . ') ' . $this->conn_log->error, $sql);
        }

        $isParamBound = $stmt->bind_param('sss', $credit_callback_response, $date_time, $extTransactionID);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $result['error'] = false;
                $result['message'] = 'updateCreditCallbackResponseLog successfully';

                //$this->echoResponse(HTTP_status_200_OK,$response);
            } else {
                //FAILED TO UPDATE
                $result['error'] = true;
                $result['message'] = 'Failed updateCreditCallbackResponseLog. Please try again!';

                //$this->echoResponse(HTTP_status_422_Unprocessable_Entity,$response);
                $this->Affect_Rows_failed_to_file($result,$sql);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $result['error'] = true;
            $result['message'] = 'Oops! An error occurred';
            $result['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($result, $sql);

            //$this->echoResponse(HTTP_status_500_Internal_Server_Error,$response);
        }

        $stmt->close();

        return null;
    }

    public function updateCreditResponseLog($extTransactionID,  $credit_response)
    {
        $response = array();

        $date_time = $this->getCurrentDateTime();

        $sql = 'UPDATE unza_transaction_logs 
                SET 
                    credit_response = ?, 
                    credit_response_time = ? 
                WHERE credit_extTransactionID  = ?';

        if (!($stmt = $this->conn_log->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn_log->errno . ') ' . $this->conn_log->error, $sql);
        }

        $isParamBound = $stmt->bind_param('sss',  $credit_response, $date_time, $extTransactionID);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $response['error'] = false;
                $response['message'] = 'Credit information updated successfully';

                //$this->echoResponse(HTTP_status_200_OK,$response);
            } else {
                //FAILED TO UPDATE
                $response['error'] = true;
                $response['message'] = 'Failed to update credit information. Please try again!';

                //$this->echoResponse(HTTP_status_422_Unprocessable_Entity,$response);
                $this->Affect_Rows_failed_to_file($response,$sql);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            //$this->echoResponse(HTTP_status_500_Internal_Server_Error,$response);
        }

        $stmt->close();

        return null;
    }
    //************************END OF LOGS***************************//


//************************END OF ENTITY***************************//
    /*
    Fetching user api key
    @param String $trader_id user id primary key in user table
    */
    public function getApiKeyById($trader_id)
    {
        $stmt = $this->conn->prepare('SELECT api_key FROM unza_users WHERE id = ?');
        $stmt->bind_param('i', $trader_id);
        if ($stmt->execute()) {
            # code...
            $api_key = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $api_key;
        } else {
            return NULL;
        }
    }

    /*
    Fetching user id by api key
    @param String $api_key user api key
    */
    public function getUserId($api_key)
    {
        # code...
        $stmt = $this->conn->prepare('SELECT id FROM unza_users WHERE api_key = ?');
        $stmt->bind_param('s', $api_key);
        if ($stmt->execute()) {
            # code...
            $trader_id = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $trader_id;
        } else {
            return NULL;
        }
    }


    public function createKey($application_name)
    {

        $response = array();

        if (!$this->doesApplicationExist($application_name)) {

            $sql = 'INSERT INTO unza_api_config(application_name,api_key) VALUES(?,?)';

            $isPrepared = $stmt = $this->conn->prepare($sql);
            if (!$isPrepared) {
                $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
            }

            //Generating API key
            $api_key = $this->generateApiKey();

            $isParamBound = $stmt->bind_param('ss', $application_name, $api_key);
            if (!$isParamBound) {
                $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
            }

            $result = $stmt->execute();

            $stmt->close();

            if ($result) {
                //CREATED
                $response['error'] = false;
                $response['message'] = 'API Key successfully created';
                $response['API_KEY'] = $api_key;
            } else {
                //FAILED TO CREATE
                $response['error'] = true;
                $response['message'] = 'Oops! An error occurred';
                $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

                $this->Execute_failed_to_file($response, $sql
                );
            }

        } else {
            //ALREADY EXISTS
            $response['error'] = true;
            $response['message'] = 'Already exists';
        }

        return $response;
    }

    private function doesApplicationExist($application_name)
    {
        $stmt = $this->conn->prepare('SELECT api_config_id FROM unza_api_config WHERE application_name = ? ');
        $stmt->bind_param('s', $application_name);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }


    //validating application api key
    //if the api key is there in db, it is valid key
    public function isValidApiKey($api_key)
    {
        $stmt = $this->conn->prepare('SELECT api_config_id FROM unza_api_config WHERE api_key = ?');
        $stmt->bind_param('s', $api_key);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    //This method will generate a unique api key
    private function generateApiKey()
    {
        //Generating random Unique MD5 String for user Api Key
        return md5(uniqid(rand(), true));
    }


}

/**
 * @author: Chimuka Moonde
 * @mobile No: 0973297682
 * @email : chimukamoonde@gmail.com
 * Kindly note that this is a customized version of slim 2
 */