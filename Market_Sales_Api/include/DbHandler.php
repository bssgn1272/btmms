<?php
/**
 * @author: Chimuka Moonde
 * @mobile No: 0973297682
 * @email : chimukamoonde@gmail.com
 * Kindly note that this is a customized version of slim 2
 */


/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 */
require_once __DIR__ . '/Security.php';
require_once __DIR__ . '/DbConnect.php';
require_once __DIR__ . '/DbConnectLog.php';

class DbHandler
{
    private $conn;
    private $conn_log;
    private $security;

    function __construct()
    {
        $this->security = new Security();

        //Creating a DbConnect object to connect to the database
        $db = new DbConnect();
        $db_log = new DbConnectLog();

        //Initializing our connection link of this class by calling the method connect of DbConnect class
        $this->conn = $db->connect();
        $this->conn_log = $db_log->connect();
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

    public function write_Error_to_file($value)
    {
        echo $value;

        //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
        $file = __DIR__ . '/Error.txt';

        $date = 'Script was executed at ' . date('d-M-y H:i:s') . "\n" . json_encode($value) . "\n" . "\n";

        file_put_contents($file, $date, FILE_APPEND);
        //END OF WRITING TO FILE
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
    //END OF FUNCTIONS**************************************************************************************************


    //************************ENTITY***************************//
    public function createUser($firstname, $lastname, $nrc, $gender, $dob, $mobile_number, $password)
    {
        require_once 'PassHash.php';

        $response = array();

        if (!$this->doesUserExist($firstname, $lastname, $nrc, $mobile_number)) {

            //Generating API key
            $api_key = $this->generateApiKey();

            $sql = 'INSERT INTO unza_traders(role, firstname, lastname, nrc, gender, dob, mobile_number, password,auth_key) VALUES(?,?,?,?,?,?,?,?,?)';

            if (!($stmt = $this->conn->prepare($sql))) {
                $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
            }

            $firstname = $this->insert_name_prep($firstname);
            $lastname = $this->insert_name_prep($lastname);
            $dob = date('Y-m-d', strtotime($dob));
            // $email_checked = is_null($email) ? null : $email;
            //$password_hash = PassHash::hash($password);


            $auth_key = $this->security->generateRandomString();
            $password_hash = $this->security->generatePasswordHash($password . $auth_key);

            $role = 'marketeer';
            $isParamBound = $stmt->bind_param('sssssssss', $role, $firstname, $lastname, $nrc, $gender, $dob, $mobile_number, $password_hash, $auth_key);
            if (!$isParamBound) {
                $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
            }

            if ($stmt->execute()) {
                //CREATE SUCCESSFUL
                $stmt->close();

                //$customer_id = $this->conn->insert_id;

                //$this->generateToken($company_id, $customer_id);

                $res = $this->getUserByEmailOrMobile(null, $mobile_number);

                $response['error'] = false;
                $response['status'] = StatusCodes::SUCCESS_CODE;
                $response['message'] = 'You are successfully registered';
                $response['user'] = $res;

                $this->echoResponse(HTTP_status_201_Created, $response);
            } else {
                //IF QUERY FAILED TO EXECUTE
                $response['error'] = true;
                $response['status'] = StatusCodes::GENERIC_ERROR;
                $response['message'] = 'Oops! A database error occurred. Error is: (' . $stmt->errno . ')' . $stmt->error;
                //$response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;
                $stmt->close();

                $this->Execute_failed_to_file($response, $sql);

                $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
            }
        } else {
            //User with same details already existed in the db
            $response['error'] = true;
            $response['status'] = StatusCodes::TRADER_ALREADY_EXIST;
            $response['message'] = 'Sorry , credentials already in use';

            $this->echoResponse(HTTP_status_409_Conflict, $response);
        }

        return null;
    }


    public function updateUser($trader_id, $firstname, $lastname, $nrc, $gender, $dob, $mobile_number)
    {

        $response = array();

        $sql = 'UPDATE unza_traders SET
                      firstname = ?,
                      lastname = ?,
                      nrc = ?,
                      gender = ?,
                      dob = ?,
                      mobile_number = ?
                  WHERE trader_id = ? ';

        $isPrepared = $stmt = $this->conn->prepare($sql);


        $firstname = $this->insert_name_prep($firstname);
        $lastname = $this->insert_name_prep($lastname);
        $dob = date('Y-m-d', strtotime($dob));
        //$email_checked = is_null($email) ? null : $email;


        //$isParamBound = $stmt->bind_param('issssssssi', $role_id, $firstname, $lastname, $nrc, $gender, $dob, $email_checked, $mobile_number, $account_number, $trader_id);
        $isParamBound = $stmt->bind_param('ssssssi', $firstname, $lastname, $nrc, $gender, $dob, $mobile_number, $trader_id);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }


        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }


        if ($stmt->execute()) {

            $num_affected_rows = $stmt->affected_rows;

            if ($num_affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $res = $this->getUserByEmailOrMobile($trader_id, null);

                $response['error'] = false;
                $response['status'] = StatusCodes::SUCCESS_CODE;
                $response['message'] = 'Information updated successfully';
                $response['user'] = $res;

                $this->echoResponse(HTTP_status_200_OK, $response);
            } else {
                //FAILED TO UPDATE
                $response['error'] = true;
                $response['status'] = StatusCodes::FAILURE_CODE;
                $response['message'] = 'Failed to update information. Please try again!';

                $this->echoResponse(HTTP_status_422_Unprocessable_Entity, $response);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['status'] = StatusCodes::GENERIC_ERROR;
            $response['message'] = 'Oops! A database error occured. Error is: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

            $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }

        $stmt->close();
        return null;
    }


    public function checkLogin($mobile_number, $password)
    {

        $response = array();

        $sql = 'SELECT password,auth_key FROM unza_traders WHERE mobile_number = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('s', $mobile_number);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //PARENT FOUND BY EMAIL

                $stmt->bind_result($password_hash, $auth_key);

                $stmt->fetch();
                $stmt->close();

                //  if (PassHash::check_password($password_hash, $password)) {
                if ($this->security->validatePassword($password . $auth_key, $password_hash)) {
                    //PASSWORD MATCHES
                    //$this->generateToken($company_id,$resUser['customer_id']);
                    $response['error'] = false;
                    $response['status'] = StatusCodes::SUCCESS_CODE;
                    $response['message'] = 'Success. Credentials provided are valid';
                    $response['user'] = $this->getUserByEmailOrMobile(null, $mobile_number);

                    $this->echoResponse(HTTP_status_200_OK, $response);

                } else {
                    //INCORRECT PASSWORD
                    $response['error'] = true;
                    $response['status'] = StatusCodes::INCORRECT_CREDENTIALS;
                    $response['message'] = 'Incorrect credentials provided';

                    $this->echoResponse(HTTP_status_401_Unauthorized, $response);
                }

            } else {
                //PARENT NOT FOUND BY EMAIL
                $response['error'] = true;
                $response['status'] = StatusCodes::INCORRECT_CREDENTIALS;
                $response['message'] = 'Incorrect credentials provided';

                $this->echoResponse(HTTP_status_401_Unauthorized, $response);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['status'] = StatusCodes::GENERIC_ERROR;
            $response['message'] = 'Oops! A database error occurred. Error is: (' . $stmt->errno . ')' . $stmt->error;
            $stmt->close();

            $this->Execute_failed_to_file($response, $sql);

            $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }

        return null;
    }


    public function changePassword($mobile, $old_password, $new_password)
    {

        $response = array();

        //FETCH PARENT BY MOBILE NUMBER
        $sql = 'SELECT password,auth_key 
                FROM unza_traders
                WHERE mobile_number = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('s', $mobile);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //PARENT FOUND BY MOBILE NUMBER
                $stmt->bind_result($password_hash, $auth_key);

                $stmt->fetch();
                $stmt->close();

                if ($this->security->validatePassword($old_password . $auth_key, $password_hash)) {
                    //PASSWORD MATCH
                    $sql = 'UPDATE unza_traders 
                            SET password = ?, auth_key=?
                            WHERE mobile_number = ? ';

                    if (!($stmt = $this->conn->prepare($sql))) {
                        $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
                    }

                    //Generating password hash with a new auth_key
                    $auth_key = $this->security->generateRandomString();
                    $password_hash = $this->security->generatePasswordHash($new_password . $auth_key);

                    $isParamBound = $stmt->bind_param('sss', $password_hash, $auth_key, $mobile);
                    if (!$isParamBound) {
                        $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
                    }

                    if ($stmt->execute()) {

                        $num_affected_rows = $stmt->affected_rows;

                        $stmt->close();//Closing the statement

                        if ($num_affected_rows > 0) {
                            //PASSWORD UPDATED
                            $response['error'] = false;
                            $response['status'] = StatusCodes::SUCCESS_CODE;
                            $response['message'] = 'You have successfully changed your password';

                            $this->echoResponse(HTTP_status_200_OK, $response);

                        } else {
                            //FAILED TO UPDATE PASSWORD
                            $response['error'] = true;
                            $response['status'] = StatusCodes::FAILURE_CODE;
                            $response['message'] = 'Failed to update Password. Please try again!';

                            $this->echoResponse(HTTP_status_422_Unprocessable_Entity, $response);
                        }
                    } else {
                        //IF QUERY FAILED TO EXECUTE
                        $response['error'] = true;
                        $response['status'] = StatusCodes::GENERIC_ERROR;
                        $response['message'] = 'Oops! A database error occurred. Error is: (' . $stmt->errno . ')' . $stmt->error;
                        $stmt->close();

                        $this->Execute_failed_to_file($response, $sql);

                        $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
                    }

                } else {
                    //PASSWORD MISMATCH
                    $response['error'] = true;
                    $response['status'] = StatusCodes::FAILURE_CODE;
                    $response['message'] = 'Incorrect mobile number or old password';

                    $this->echoResponse(HTTP_status_403_Forbidden, $response);
                }
            } else {
                //PARENT NOT FOUND BY EMAIL/MOBILE
                $response['error'] = true;
                $response['status'] = StatusCodes::FAILURE_CODE;
                $response['message'] = 'Incorrect mobile number or old password';

                $this->echoResponse(HTTP_status_403_Forbidden, $response);
            }
        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['status'] = StatusCodes::GENERIC_ERROR;
            $response['message'] = 'Oops! A database error occurred. Error is: (' . $stmt->errno . ')' . $stmt->error;
            $stmt->close();

            $this->Execute_failed_to_file($response, $sql);

            $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }

        return null;
    }


    private function doesUserExist($firstname, $lastname, $nrc, $mobile_number)
    {
        $stmt = $this->conn->prepare('SELECT trader_id FROM unza_traders WHERE ((firstname = ? AND lastname = ?) OR (nrc = ? OR mobile_number = ?))');
        $stmt->bind_param('ssss', $firstname, $lastname, $nrc, $mobile_number);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    public function getUserByEmailOrMobile($trader_id = null, $mobile_number = null)
    {
        $response = array();

        $sql = 'SELECT trader_id,role, image, firstname, lastname, nrc, gender, dob, mobile_number, account_number ,status
                FROM unza_traders
                WHERE  trader_id = ? OR mobile_number = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('is', $trader_id, $mobile_number);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //IF FOUND

                $stmt->bind_result($trader_id, $role, $image, $firstname, $lastname, $nrc, $gender, $dob, $mobile_number_db, $account_number, $status);


                //$response['error'] = false;
                //$response['parent'] = array();

                while ($stmt->fetch()) {
                    $tmp = array();
                    $tmp['trader_id'] = $trader_id;
                    //$tmp['role_id'] = $role_id;
                    $tmp['image'] = $image;
                    $tmp['firstname'] = $firstname;
                    $tmp['lastname'] = $lastname;
                    $tmp['nrc'] = $nrc;
                    $tmp['gender'] = $gender;
                    $tmp['dob'] = $dob;
                    $tmp['status'] = $status;
                    $tmp['role'] = $role;
                    $tmp['account_number'] = $account_number;
                    //$tmp['email'] = $email_db;
                    $tmp['mobile_number'] = $mobile_number_db;

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


    public function resetPasswordRequest($mobile_number)
    {

        $response = array();

        $sql = 'SELECT firstname,lastname,mobile_number,password_reset_token 
                FROM unza_traders 
                WHERE mobile_number = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('s', $mobile_number);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {

                $stmt->bind_result($firstname, $lastname, $phone, $password_reset_token);


                function generatePIN($digits = 4)
                {
                    $i = 0; //counter
                    $pin = ''; //our default pin is blank.
                    while ($i < $digits) {
                        //generate a random number between 0 and 9.
                        $pin .= mt_rand(0, 9);
                        $i++;
                    }
                    return $pin;
                }

                //If I want a 4-digit PIN code.
                $pin = generatePIN();

                # code...
                $stmt->fetch();
                $stmt->close();

                //---------------------------------------------

                $sql = 'UPDATE unza_traders 
                        SET password_reset_token = ? 
                        WHERE mobile_number = ? ';

                if (!($stmt = $this->conn->prepare($sql))) {
                    $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
                }

                $isParamBound = $stmt->bind_param('ss', $pin, $phone);
                if (!$isParamBound) {
                    $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
                }

                if ($stmt->execute()) {

                    $num_affected_rows = $stmt->affected_rows;

                    $stmt->close();//Closing the statement

                    if ($num_affected_rows > 0) {
                        //UPDATE SUCCESSFUL

                        //PREPARE TO SEND SMS===============================================================================
                        $message = 'Hi ' . $firstname . ' ' . $lastname . ', you requested for a password reset. Kindly use the PIN number ' . $pin . ' to reset your forgotten password.';

                        //$response['sms'] = $this->sendSMS(null, $phone, 'DCZRewards', $message);
                        //PREPARE TO SEND SMS===============================================================================

                        $response['error'] = false;
                        $response['status'] = StatusCodes::SUCCESS_CODE;
                        $response['message'] = 'A reset PIN will be sent to your mobile number,please check your SMS for the PIN';

                        $this->echoResponse(HTTP_status_200_OK, $response);

                    } else {
                        //UPDATE FAILED
                        $response['error'] = true;
                        $response['status'] = StatusCodes::FAILURE_CODE;
                        $response['message'] = 'Failed to generate PIN. Please try again!';

                        $this->echoResponse(HTTP_status_422_Unprocessable_Entity, $response);
                    }
                } else {
                    //IF QUERY FAILED TO EXECUTE
                    $response['error'] = true;
                    $response['status'] = StatusCodes::GENERIC_ERROR;
                    $response['message'] = 'Oops! A database error occured. Error is: (' . $stmt->errno . ')' . $stmt->error;
                    $stmt->close();

                    $this->Execute_failed_to_file($response, $sql);

                    $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
                }


                //---------------------------------------------
            } else {
                $response['error'] = true;
                $response['status'] = StatusCodes::FAILURE_CODE;
                $response['message'] = 'system does not recognize the submitted mobile number';

                $this->echoResponse(HTTP_status_401_Unauthorized, $response);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['status'] = StatusCodes::GENERIC_ERROR;
            $response['message'] = 'Oops! A database error occurred. Error is: (' . $stmt->errno . ')' . $stmt->error;
            $stmt->close();

            $this->Execute_failed_to_file($response, $sql);

            $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }

        return null;
    }

    public function resetPassword($mobile_number, $pin, $password)
    {
        require_once 'PassHash.php';

        $sql = 'SELECT trader_id 
                FROM unza_traders
                WHERE  mobile_number = ? AND password_reset_token = ?';

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param('ss', $mobile_number, $pin);

        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {

                $stmt->close();

                //Generating password hash
                //$password_hash = PassHash::hash($password);
                $auth_key = $this->security->generateRandomString();
                $password_hash = $this->security->generatePasswordHash($password . $auth_key);

                $sql = 'UPDATE unza_traders 
                        SET password = ?, 
                            password_reset_token = ? 
                        WHERE  mobile_number = ?';

                if (!($stmt = $this->conn->prepare($sql))) {
                    $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
                }

                $reset_token = null;

                $isParamBound = $stmt->bind_param('sss', $password_hash, $reset_token, $mobile);
                if (!$isParamBound) {
                    $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
                }

                if ($stmt->execute()) {

                    if ($stmt->affected_rows > 0) {
                        //PASSWORD UPDATED
                        $response['error'] = false;
                        $response['message'] = 'You have successfully changed your password';

                        $this->echoResponse(HTTP_status_200_OK, $response);
                    } else {
                        //FAILED TO UPDATE PASSWORD
                        $response['error'] = true;
                        $response['message'] = 'Failed to update Password. Please try again!';

                        $this->echoResponse(HTTP_status_422_Unprocessable_Entity, $response);
                    }

                    $stmt->close();

                } else {
                    //IF QUERY FAILED TO EXECUTE
                    $response['error'] = true;
                    $response['message'] = 'Oops! An error occurred';
                    $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;
                    $stmt->close();

                    $this->Execute_failed_to_file($response, $sql);

                    $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
                }


            } else {
                //NOT FOUND
                $response['error'] = true;
                $response['message'] = 'Incorrect mobile number or PIN';

                $this->echoResponse(HTTP_status_401_Unauthorized, $response);
            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred';
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;
            $stmt->close();

            $this->Execute_failed_to_file($response, $sql);

            $this->echoResponse(HTTP_status_500_Internal_Server_Error, $response);
        }

        return null;
    }

    public function getCurrentDateTime()
    {
        return date('Y-m-d H:i:s');
    }
    //************************ENTITY***************************//

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

    //************************PAYMENT METHODS***************************//
    public function getAllPaymentMethods()
    {
        $response = array();

        $sql = 'SELECT payment_method_id, payment_method_name
                FROM payment_methods
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

                $stmt->bind_result($payment_method_id, $payment_method_name);

                $response['error'] = false;
                $response['payment_methods'] = array();

                while ($stmt->fetch()) {
                    $tmp = array();
                    $tmp['payment_method_id'] = $payment_method_id;
                    $tmp['payment_method_name'] = $payment_method_name;

                    $response['payment_methods'][] = $tmp;
                }

                $this->echoResponse(HTTP_status_200_OK, $response);

            } else {
                //IF NOT FOUND
                $response['error'] = false;
                $response['payment_methods'] = array();
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
    //************************END OF PAYMENT METHODS***************************//

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
    //************************END OF MARKETEER KYC***************************//




    //************************MARKET FEES***************************//
    public function getAllPendingMarketCharges($seller_mobile_number)
    {

        $response = array();

        $sql = 'SELECT id, marketeer_msisdn, collection_msisdn, SUM(amount), transaction_type, status, transaction_details, transaction_date                                                                                       
                    FROM unza_market_charge_collections
                    WHERE marketeer_msisdn = ?
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

                $stmt->bind_result($id, $marketeer_msisdn, $collection_msisdn, $amount, $transaction_type, $status, $transaction_details, $transaction_date);

                $response['error'] = false;
                $response['market_fee'] = array();

                while ($stmt->fetch()) {
                    $tmp = array();
                    $tmp['marketeer_msisdn'] = $marketeer_msisdn;
                    $tmp['sum_amount'] = abs($amount);
                    //$tmp['transaction_date'] = $transaction_date;

                    $response['market_fee'] = $tmp;
                }

                $this->echoResponse(HTTP_status_200_OK, $response);


            } else {
                //IF NOT FOUND
                $response['error'] = false;
                $response['market_fee'] = array(
                    'marketeer_msisdn' => $seller_mobile_number,
                    'sum_amount' => 0.0
                );
                //$response['message'] = 'No results found';

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

    public function createPaidMarketChargesRecord($seller_mobile_number, $amount)
    {

        $response = array();

        $sql = 'INSERT INTO unza_market_charge_collections(marketeer_msisdn,amount,transaction_type,transaction_details,transaction_date) VALUES(?,?,?,?,?)';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $transaction_type = "CR";
        $transaction_details = "Market Fees Payments";
        $transaction_date = $this->getCurrentDateTime();

        $isParamBound = $stmt->bind_param('sdsss', $seller_mobile_number, $amount, $transaction_type, $transaction_details, $transaction_date);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {
            //CREATED
            $new_id = $this->conn->insert_id;

            $response['error'] = false;
            $response['message'] = 'Market fee payment created successfully';

            //$this->echoResponse(HTTP_status_201_Created,$response);
            $this->updatePaidMarketCharges($seller_mobile_number);

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

    public function updatePaidMarketCharges($seller_mobile_number)
    {
        $response = array();

        $sql = 'UPDATE unza_market_charge_collections SET status = 1 WHERE marketeer_msisdn = ? AND status = 0';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('s', $seller_mobile_number);
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
                    WHERE transaction_date >= DATE_SUB(CURDATE(), INTERVAL 1 Day)  AND seller_mobile_number = ?
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

            }else{

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
                    WHERE transaction_date >= DATE_SUB(CURDATE(), INTERVAL 7 Day)  AND seller_mobile_number = ?
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

            }else{

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
                    WHERE transaction_date >= DATE_SUB(CURDATE(), INTERVAL 1 Month)  AND seller_mobile_number = ?
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

            }else{

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

    public function createTransactionsSummaries($transaction_type_id, $route_code, $transaction_channel, $id_type, $passenger_id, $bus_schedule_id, $travel_date, $travel_time, $seller_id, $seller_firstname, $seller_lastname, $seller_mobile_number, $buyer_id, $buyer_firstname, $buyer_lastname, $buyer_mobile_number, $buyer_email, $amount, $device_serial, $transaction_date)
    {

        $response = array();

        //CHECK BUYER'S CURRENT BALANCE
        //$res = $this->checkTokenBalance($buyer_id, $buyer_mobile_number);

        //if ($res[TOKEN_BALANCE] >= $amount) {

        //LOG ATTEMPTED TRANSACTION
        $sql = 'INSERT INTO unza_transactions(transaction_type_id,route_code,transaction_channel,id_type,passenger_id,bus_schedule_id,travel_date,travel_time, seller_id,seller_firstname,seller_lastname, seller_mobile_number, buyer_id, buyer_firstname,buyer_lastname,buyer_mobile_number, buyer_email,amount, device_serial, transaction_date) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('issssssssssssssssdss', $transaction_type_id, $route_code, $transaction_channel, $id_type, $passenger_id, $bus_schedule_id, $travel_date, $travel_time, $seller_id, $seller_firstname, $seller_lastname, $seller_mobile_number, $buyer_id, $buyer_firstname, $buyer_lastname, $buyer_mobile_number, $buyer_email, $amount, $device_serial, $transaction_date);
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

            $telco = substr(trim($buyer_mobile_number), 0, 5);

            switch ($telco) {
                case '26097':
                    $res = $this->wallet_API('AIRTELZM', 'malipo', $buyer_mobile_number, $amount, $cart_id, 1);
                    break;
                case '26096':
                    $res = $this->wallet_API('MTNZM', 'malipo', $buyer_mobile_number, $amount, $cart_id, 1);
                    break;
                case '26076':
                    $res = $this->wallet_API('MTNZM', 'malipo', $buyer_mobile_number, $amount, $cart_id, 1);
                    break;
                case '26095':
                    $res = $this->wallet_API('ZAMTEL ', 'malipo', $buyer_mobile_number, $amount, $cart_id, 1);
                    break;
                default:
                    $res = "Telco not identified";
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


//        } else {
//            $response['error'] = true;
//            $response['message'] = 'Insufficient balance';
//
//            $this->echoResponse(HTTP_status_200_OK, $response);
//        }

        return null;
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

    public function decrementBuyerTokenBalance($token_value, $trader_id = null, $mobile_number = null)
    {

        $response = array();

        $res = $this->checkTokenBalance($trader_id, $mobile_number);

        if ($res[TOKEN_BALANCE] >= $token_value) {

            $sql = "UPDATE unza_traders SET token_balance = (token_balance - ?) WHERE  trader_id = ? OR mobile_number = ?";

            if (!($stmt = $this->conn->prepare($sql))) {
                $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
            }

            $isParamBound = $stmt->bind_param('dis', $token_value, $trader_id, $mobile_number);
            if (!$isParamBound) {
                $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
            }

            if ($stmt->execute()) {

                if ($stmt->affected_rows > 0) {
                    //UPDATE SUCCESSFUL

//                    $telco = substr(trim($mobile_number), 0, 3);
//
//                    $resFloat = null;
//                    switch ($telco) {
//                        case '097':
//                            $resFloat = $this->incrementFloatBalance($token_value, 1);
//                            break;
//                        case '096':
//                            $resFloat = $this->incrementFloatBalance($token_value, 2);
//                            break;
//                        case '076':
//                            $resFloat = $this->incrementFloatBalance($token_value, 2);
//                            break;
//                        case '095':
//                            $resFloat = $this->incrementFloatBalance($token_value, 3);
//                            break;
//                        default:
//                            $resFloat = "Telco not identified";
//                    }


                    $response['error'] = false;
                    $response['message'] = 'Balance updated successfully';
                    //$response['float_balance'] = $resFloat;
                } else {
                    //FAILED TO UPDATE
                    $response['error'] = true;
                    $response['message'] = 'Failed to update token balance. Please try again!';
                }

            } else {
                //IF QUERY FAILED TO EXECUTE
                $response['error'] = true;
                $response['message'] = 'Oops! An error occurred';
                $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

                $this->Execute_failed_to_file($response, $sql);
            }

            $stmt->close();

        } else {
            $response['error'] = false;
            //$response['message'] = 'Submitted token value is greater than available token value';
            $response['message'] = 'Insufficient balance';
        }


        return $response;
    }

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

    //************************END OF TRANSACTIONS***************************//


    //************************LOGS***************************//
    public function createTransactionDebitRequestLog($ref_id, $debit_request)
    {

        $response = array();

        $date_time = $this->getCurrentDateTime();

        //LOG ATTEMPTED TRANSACTION
        $sql = 'INSERT INTO unza_transaction_logs(ref_id,debit_request,debit_request_time) VALUES(?,?,?)';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('iss', $ref_id, $debit_request, $date_time);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {
            //CREATED

            $response['error'] = false;
            $response['message'] = 'Debit Request captured';
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

    public function createTransactionCreditRequestLog($ref_id, $credit_request)
    {

        $response = array();

        $date_time = $this->getCurrentDateTime();

        //LOG ATTEMPTED TRANSACTION
        $sql = 'INSERT INTO unza_transaction_logs(ref_id,credit_request,credit_request_time) VALUES(?,?,?)';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('iss', $ref_id, $credit_request, $date_time);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {
            //CREATED

            $response['error'] = false;
            $response['message'] = 'Debit Request captured';
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

    public function updateTransactionDebitResponseLog($ref_id, $reference, $debit_response)
    {
        $response = array();

        $date_time = $this->getCurrentDateTime();

        $sql = 'UPDATE unza_transaction_logs SET debit_reference = ?, debit_response = ?, debit_response_time = ? WHERE ref_id  = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('sssi', $reference, $debit_response, $date_time, $ref_id);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $response['error'] = false;
                $response['message'] = 'Debit information updated successfully';

                //$this->echoResponse(HTTP_status_200_OK,$response);
            } else {
                //FAILED TO UPDATE
                $response['error'] = true;
                $response['message'] = 'Failed to update debit information. Please try again!';

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

    public function updateTransactionDebitCallbackLog($reference, $debit_callback_response)
    {
        $response = array();

        $date_time = $this->getCurrentDateTime();

        $sql = 'UPDATE unza_transaction_logs SET debit_callback_response = ?, debit_callback_response_time = ? WHERE debit_reference  = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('sss', $debit_callback_response, $date_time, $reference);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $response['error'] = false;
                $response['message'] = 'Debit callback information updated successfully';

                //$this->echoResponse(HTTP_status_200_OK,$response);
            } else {
                //FAILED TO UPDATE
                $response['error'] = true;
                $response['message'] = 'Failed to update debit callback information. Please try again!';

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

    public function updateTransactionCreditRequestLog($reference, $credit_request)
    {
        $response = array();

        $date_time = $this->getCurrentDateTime();

        $sql = 'UPDATE unza_transaction_logs SET credit_request = ?, credit_request_time = ? WHERE debit_reference  = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('sss', $credit_request, $date_time, $reference);
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

    public function updateTransactionCreditResponseLog($ref_id, $reference, $credit_response)
    {
        $response = array();

        $date_time = $this->getCurrentDateTime();

        $sql = 'UPDATE unza_transaction_logs SET credit_reference = ?,credit_response = ?, credit_response_time = ? WHERE ref_id  = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('ssss', $reference, $credit_response, $date_time, $ref_id);
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
    //************************END OF LOGS***************************//


    public function wallet_API($mno, $kuwaita, $msisdn, $amount, $refID, $leg)
    {

        $url = "https://" . NSANO_URL . ":" . NSANO_PORT . "/api/fusion/tp/" . NSANO_API_KEY;
        $port = NSANO_PORT;

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json'
        ];


        //loop through all transactions
        $result = $this->getTransactionFees();

        $seller_amount_due = $amount;
        $total_transaction_feel = 0.0;

        foreach ($result as $key => $val) {

            if ($val['status'] == 1)

                //percentage
                if ($val['charge_type'] == "Percentage") {

                    $total_transaction_feel = $total_transaction_feel + ($amount * ($val['value'] / 100));

                    //none percentage
                } else {

                    $total_transaction_feel = $total_transaction_feel + ($amount - $val['value']);

                }

            //INSERT TRANSACTION FEE INTO TRANSACTION TABLE


        }


        $post_fields = [
            'mno' => $mno,
            'kuwaita' => $kuwaita,
            'msisdn' => $msisdn,
            'amount' => $amount,
            'refID' => $refID,
        ];

//        if($leg == 2){
//            $post_fields = [
//                'mno' => $mno,
//                'kuwaita' => $kuwaita,
//                'msisdn' => $msisdn,
//                'amount' => $amount,
//                'refID' => date("YmdHis")
//            ];
//        }else{
//            $post_fields = [
//                'mno' => $mno,
//                'kuwaita' => $kuwaita,
//                'msisdn' => $msisdn,
//                'amount' => $amount,
//                'refID' => $refID ,
//            ];
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
                $this->createTransactionCreditRequestLog($refID, http_build_query($post_fields));
            } else if ($leg == 2) {
                $this->updateTransactionCreditRequestLog($refID, http_build_query($post_fields));
            }
        } else if ($kuwaita == "malipo") {//DEBIT
            $this->createTransactionDebitRequestLog($refID, http_build_query($post_fields));
        }


        if ($result === FALSE) {//AN ERROR OCCURRED

            $file = null;
            if ($kuwaita == "mikopo") {//credit
                $file = __DIR__ . '/Credit_Wallet_Errors.txt';
            } else if ($kuwaita == "malipo") {//debit
                $file = __DIR__ . '/Debit_Wallet_Errors.txt';
            } else {
                $file = __DIR__ . '/Errors.txt';
            }

            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode(curl_error($curl_handler)) . "\n" . "\n";
            file_put_contents($file, $date, FILE_APPEND);

            die('Curl failed: ' . curl_error($curl_handler));

        } else {// REQUEST SUCCESSFUL

            if ($kuwaita == "malipo") {//debit

                //$this->notify_API($kuwaita, $refID, $code);

                //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
                $file = __DIR__ . '/1-Nsano-debit_wallet-transaction.txt';
                $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $result . "\n" . "\n";
                file_put_contents($file, $date, FILE_APPEND);
                //END OF WRITING TO FILE

                $json_obj = json_decode($result, true);
                $msg = isset($json_obj['msg']) ? $json_obj['msg'] : null;
                $reference = isset($json_obj['reference']) ? $json_obj['reference'] : null;
                $code = isset($json_obj['code']) ? $json_obj['code'] : null;


                if ($code != 00) {
                    $buyer_msg = $msg . " .During Debit buyer's wallet";
                    $this->pushSMS($buyer_msg, $msisdn);
                }

                $this->updateTransactionDebitResponseLog($refID, $reference, $result);
                $this->updateTransactionDebitDetails($msg, $reference, $code, $refID);

            } elseif ($kuwaita == "mikopo") {//credit

                //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
                $file = __DIR__ . '/5-Nsano-credit_wallet-transaction.txt';
                $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $result . "\n" . "\n";
                file_put_contents($file, $date, FILE_APPEND);
                //END OF WRITING TO FILE

                $json_obj = json_decode($result, true);
                $msg = isset($json_obj['msg']) ? $json_obj['msg'] : null;
                $reference = isset($json_obj['reference']) ? $json_obj['reference'] : null;
                $code = isset($json_obj['code']) ? $json_obj['code'] : null;
                $system_code = isset($json_obj['system_code']) ? $json_obj['system_code'] : null;
                $transactionID = isset($json_obj['transactionID']) ? $json_obj['transactionID'] : null;

                if ($leg == 1) {

                    $this->saveSMS($msg, $msisdn);
                    $this->pushSMS($msg, "260973297682");

                    $this->updateTransactionCreditDetails($msg, $reference, $code, $system_code, $transactionID, $refID);

                } elseif ($leg == 2) {

                    $resTraderInformation = $this->getTraderInformation($refID);

                    if ($code == 00) {//Transaction successful

                        $seller_msg = "Transaction successful. You have received ZMW " . $amount . ". Transaction REF: " . strtoupper($refID);
                        $buyer_msg = "Transaction successful. You have sent ZMW " . $amount . ". Transaction REF: " . strtoupper($refID);

                        //NOTIFY THE SELLER
                        $this->saveSMS($seller_msg, $msisdn);
                        $this->pushSMS($seller_msg, "260973297682");

                        //NOTIFY THE BUYER
                        $this->saveSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                        $this->pushSMS($buyer_msg, "260973297682");

                        $response['code'] = '00';
                        $response['msg'] = 'Transaction successful';

                        $this->echoResponse(HTTP_status_200_OK, $response);//NOTIFY NSANO

                    } else if ($code == 01) {//Transaction failed

                        //NOTIFY THE SELLER
                        $seller_msg = $msg . " during credit seller's wallet";
                        $this->saveSMS($seller_msg, $resTraderInformation['seller_mobile_number']);
                        $this->pushSMS($seller_msg, "260973297682");

                        //NOTIFY THE BUYER
                        $buyer_msg = $msg . " during credit seller's wallet. Transaction will be reversed";
                        $this->saveSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                        $this->pushSMS($buyer_msg, "260973297682");

                        $response['code'] = '01';
                        $response['msg'] = "Failure to complete transaction";

                        $this->echoResponse(HTTP_status_200_OK, $response);//NOTIFY NSANO

                    } else {

                        //NOTIFY THE SELLER
                        $seller_msg = $msg . " During credit seller's wallet";
                        $this->saveSMS($seller_msg, $resTraderInformation['seller_mobile_number']);
                        $this->pushSMS($seller_msg, "260973297682");

                        //NOTIFY THE BUYER
                        $buyer_msg = $msg . " During credit seller's wallet. Transaction will be reversed";
                        $this->saveSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                        $this->pushSMS($buyer_msg, "260973297682");

                        $response['code'] = '01';
                        $response['msg'] = "Failure to complete transaction";

                        $this->echoResponse(HTTP_status_200_OK, $response);//NOTIFY NSANO
                    }

                    $this->updateTransactionCreditResponseLog($refID, $reference, $result);
                    $this->updateTransactionSellerBalanceAfterDebitDetails($msg, $reference, $code, $system_code, $transactionID, $refID);
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

    public function updateTransactionDebitDetails($msg, $reference, $code, $refID)
    {
        $response = array();

        $sql = 'UPDATE unza_transactions SET debit_msg = ?, debit_reference = ?, debit_code = ? WHERE cart_id  = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('sssi', $msg, $reference, $code, $refID);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $response['error'] = false;
                $response['message'] = 'Debit information updated successfully';

                //$this->echoResponse(HTTP_status_200_OK,$response);
            } else {
                //FAILED TO UPDATE
                $response['error'] = true;
                $response['message'] = 'Failed to update debit information. Please try again!';

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
        //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
        $file = __DIR__ . '/2-updateTransactionDebitDetails.txt';

        $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($response) . "\n" . "\n";

        file_put_contents($file, $date, FILE_APPEND);
        //END OF WRITING TO FILE
        return $response;
    }

    public function updateTransactionDebitCallbackDetails($msg, $reference, $code, $system_code = null, $transactionID = null)
    {
        $response = array();

        $sql = 'UPDATE unza_transactions 
                SET callback_msg = ?, callback_reference = ?, callback_code = ?,callback_system_code = ?, callback_transactionID = ? 
                WHERE debit_reference  = ?';

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
                $response['error'] = false;
                $response['message'] = 'debit callback information updated successfully';

                if ($code == 00) {

                    $resTraderInformation = $this->getTraderInformation($reference);

                    if ($resTraderInformation != null) {

                        if ($resTraderInformation['transaction_type_id'] == 1 || $resTraderInformation['transaction_type_id'] == 2) {//MAKE SELL OR MAKE ORDER

                            //PROCEED TO CREDIT THE SELLER
                            $this->creditSeller($reference);

                        } else if ($resTraderInformation['transaction_type_id'] == 3) {//TICKET PURCHASE

                            //NOTIFY THE BUYER
                            $buyer_msg = $msg . " The ticket number is ticket" . $resTraderInformation['cart_id'] . ". Transaction REF: " . strtoupper($reference);
                            $this->saveSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                            $this->pushSMS($buyer_msg, "260973297682");

                            $response['code'] = '00';
                            $response['msg'] = 'Transaction successful';

                            $this->echoResponse(HTTP_status_200_OK, $response);//NOTIFY NSANO

                            $this->pushToProbasePurchaseTicketAPI($reference);

                        } else if ($resTraderInformation['transaction_type_id'] == 4) {//MARKET FEE PAYMENT

                            //NOTIFY THE BUYER
                            $buyer_msg = $msg . ". Transaction REF: " . strtoupper($reference);
                            $this->saveSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                            $this->pushSMS($buyer_msg, "260973297682");

                            $response['code'] = '00';
                            $response['msg'] = 'Transaction successful';

                            $this->echoResponse(HTTP_status_200_OK, $response);//NOTIFY NSANO

                            $this->createPaidMarketChargesRecord($resTraderInformation['buyer_mobile_number'], $resTraderInformation['amount']);

                        }

                    }

                } else { //TRANSACTION FAILED AT NSANO OR TELCO

                    $resTraderInformation = $this->getTraderInformation($reference);

                    if ($resTraderInformation != null) {

                        //BUYER
                        $buyer_msg = $msg . " at Nsano or Telco";
                        $this->pushSMS($buyer_msg, $resTraderInformation['buyer_mobile_number']);
                        $this->pushSMS($buyer_msg, "260973297682");
                        //BUYER

                        //SELLER
                        $seller_msg = "Transaction for " . $resTraderInformation['buyer_mobile_number'] . " has failed at Nsano or Telco";
                        $this->pushSMS($seller_msg, $resTraderInformation['seller_mobile_number']);
                        $this->pushSMS($seller_msg, "260973297682");
                        //SELLER

                        $response['code'] = '01';
                        $response['msg'] = "Failure to complete transaction";

                        $this->echoResponse(HTTP_status_200_OK, $response);//NOTIFY NSANO

                    } else {

                        $response['code'] = '01';
                        $response['msg'] = "Failure to complete transaction";

                        $this->echoResponse(HTTP_status_200_OK, $response);//NOTIFY NSANO

                        $response['error'] = true;
                        $response['message'] = 'Oops! An error occurred when fetching trader information';

                    }
                }

                //$this->echoResponse(HTTP_status_200_OK,$response);
            } else {
                //FAILED TO UPDATE
                $response['error'] = true;
                $response['message'] = 'Failed to update debit callback information. Please try again!';

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
        //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
        $file = __DIR__ . '/4-updateTransactionDebitCallbackDetails.txt';

        $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($response) . "\n" . "\n";

        file_put_contents($file, $date, FILE_APPEND);
        //END OF WRITING TO FILE
        return $response;
    }

    public function creditSeller($debit_reference)
    {
        $response = array();

        $resTraderInformation = $this->getTraderInformation($debit_reference);

        if ($resTraderInformation != null) {

            $telco = substr(trim($resTraderInformation['seller_mobile_number']), 0, 5);

            //$refID = $resTraderInformation['cart_id'] . date("YmdHis");

            switch ($telco) {
                case '26097':
                    $res = $this->wallet_API('AIRTELZM', 'mikopo', $resTraderInformation['seller_mobile_number'], $resTraderInformation['amount'], $debit_reference, 2);
                    break;
                case '26096':
                    $res = $this->wallet_API('MTNZM', 'mikopo', $resTraderInformation['seller_mobile_number'], $resTraderInformation['amount'], $debit_reference, 2);
                    break;
                case '26076':
                    $res = $this->wallet_API('MTNZM', 'mikopo', $resTraderInformation['seller_mobile_number'], $resTraderInformation['amount'], $debit_reference, 2);
                    break;
                case '26095':
                    $res = $this->wallet_API('ZAMTEL ', 'mikopo', $resTraderInformation['seller_mobile_number'], $resTraderInformation['amount'], $debit_reference, 2);
                    break;
                default:
                    $res = "Telco not identified";
            }


        } else {
            $response['error'] = true;
            $response['message'] = 'Oops! An error occurred when fetching trader information';
        }

        return $response;
    }

    public function updateTransactionSellerBalanceAfterDebitDetails($msg, $reference, $code, $system_code = null, $transactionID = null, $refID)
    {
        $response = array();

        $sql = 'UPDATE unza_transactions 
                SET credit_msg = ?, credit_reference = ?, credit_code = ?,credit_system_code = ?, credit_transactionID = ? 
                WHERE debit_reference  = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('ssssss', $msg, $reference, $code, $system_code, $transactionID, $refID);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            $num_affected_rows = $stmt->affected_rows;

            if ($num_affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $response['error'] = false;
                $response['message'] = 'credit information updated successfully';

            } else {
                //FAILED TO UPDATE
                $response['error'] = true;
                $response['message'] = 'Failed to update credit information. Please try again!';

            }

        } else {
            //IF QUERY FAILED TO EXECUTE
            $response['error'] = true;
            $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

            $this->Execute_failed_to_file($response, $sql);

        }

        $stmt->close();
        //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
        $file = __DIR__ . '/6-updateTransactionSellerBalanceAfterDebitDetails.txt';
        $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($response) . "\n" . "\n";
        file_put_contents($file, $date, FILE_APPEND);
        //END OF WRITING TO FILE
        return $response;
    }


    public function getTraderInformation($debit_reference)
    {
        $response = array();

        $sql = 'SELECT cart_id,seller_mobile_number,buyer_mobile_number,amount,transaction_type_id
                FROM unza_transactions
                WHERE debit_reference = ?';

        $isPrepared = $stmt = $this->conn->prepare($sql);

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('s', $debit_reference);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                //IF FOUND

                $stmt->bind_result($cart_id, $seller_mobile_number, $buyer_mobile_number, $amount, $transaction_type_id);

                while ($stmt->fetch()) {
                    $tmp = array();

                    $tmp['cart_id'] = $cart_id;
                    $tmp['seller_mobile_number'] = $seller_mobile_number;
                    $tmp['buyer_mobile_number'] = $buyer_mobile_number;
                    $tmp['amount'] = $amount;
                    $tmp['transaction_type_id'] = $transaction_type_id;

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

        $sql = 'SELECT id, name, "value", status, charge_type
                FROM unza_transaction_fees
                WHERE status = 1
                ';

        $isPrepared = $stmt = $this->conn->prepare($sql);

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('s', $debit_reference);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

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


            } else {
                //IF NOT FOUND
                $tmp['id'] = 0;
                $tmp['name'] = 0;
                $tmp['value'] = 0;
                $tmp['status'] = 0;
                $tmp['charge_type'] = 0;

                $response[] = $tmp;
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

    public function updateTransactionCreditDetails($msg, $reference, $code, $system_code, $transactionID, $cart_id)
    {
        $response = array();

        $sql = 'UPDATE unza_transactions SET credit_msg = ?, credit_reference = ?, credit_code = ?,credit_system_code = ?, credit_transactionID = ? WHERE cart_id  = ?';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('sssssi', $msg, $reference, $code, $system_code, $transactionID, $cart_id);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                //UPDATE SUCCESSFUL
                $response['error'] = false;
                $response['message'] = 'credit information updated successfully';

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
        return $response;
    }

    public function notify_API($kuwaita = "kusubiri_mikopo", $refID, $code)
    {
        $apiKey = NSANO_API_KEY;
        $url = "https://sandbox.nsano.com:7003/api/fusion/tp/" . $apiKey;
        $port = NSANO_PORT;

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json'
        ];

        $msg = null;
        switch ($code) {
            case 00://for success-transaction ends,

                $msg = "Credit Successful";
                break;
            case 01://for failure-transaction is reversed

                $msg = "Failure transaction";
                break;
            case 02:// for system errors-transaction is reversed
                $msg = "System errors transaction";
                break;

            default:
                $msg = "Error occurred";
        }

        $post_fields = [
            'kuwaita' => $kuwaita,
            'metadataID' => $refID,
            'code' => $code,
            'msg' => $msg
        ];


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
        if ($result === FALSE) {

            $file = __DIR__ . '/Notify_Fusion_Errors.txt';

            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode(curl_error($curl_handler)) . "\n" . "\n";

            file_put_contents($file, $date, FILE_APPEND);

            die('Curl failed: ' . curl_error($curl_handler));

        } else {

        }

        curl_close($curl_handler);

        return $result;
    }

    public function check_status_API($refID = null)
    {
        $apiKey = NSANO_API_KEY;
        $url = "https://sandbox.nsano.com:7003/api/fusion/tp/metadata/" . $refID . "/" . $apiKey;
        $port = NSANO_PORT;

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json'
        ];


        //Initializing curl to open a connection
        $curl_handler = curl_init();

        curl_setopt($curl_handler, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_handler, CURLOPT_URL, $url);
        curl_setopt($curl_handler, CURLOPT_PORT, $port);
        curl_setopt($curl_handler, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, false);//set to true in production
        curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, false);//set to true in production
        curl_setopt($curl_handler, CURLOPT_CONNECTTIMEOUT, 0);// 100; // set to zero for no timeout

        $result = curl_exec($curl_handler);
        if ($result === FALSE) {

            $file = __DIR__ . '/Check_Transaction_Errors.txt';

            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode(curl_error($curl_handler)) . "\n" . "\n";

            file_put_contents($file, $date, FILE_APPEND);

            die('Curl failed: ' . curl_error($curl_handler));

        } else {

        }

        curl_close($curl_handler);

        return $result;
    }

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

    public function pushToProbasePurchaseTicketAPI($debit_reference)
    {
        $url = '10.10.1.57:4000/api/v1/btms/tickets/secured/purchase';

        $headers = [
            'x-request-id:Fe2lL9okYV7WkCEAAAeB',
            'Content-Type: application/json',
            'Accept: application/json'
        ];


        $ticketRes = $this->getTransactionDetails($debit_reference);

        $auth = [
            "username" => "admin",
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
            'mobile_number' => $ticketRes['buyer_mobile_number']
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

            $file = __DIR__ . '/TicketPurchase_Errors.txt';

            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode(curl_error($curl_handler)) . "\n" . "\n";

            file_put_contents($file, $date, FILE_APPEND);

            die('Curl failed: ' . curl_error($curl_handler));

        } else {

            //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
            $file = __DIR__ . '/Probase-Ticket-Purchase';
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

    public function getTransactionDetails($debit_reference)
    {
        $response = array();

        $sql = 'SELECT cart_id, transaction_type_id, route_code, transaction_channel, id_type, passenger_id, bus_schedule_id,travel_date, travel_time, 
                      seller_id, seller_firstname, seller_lastname, seller_mobile_number, 
                      buyer_id, buyer_firstname, buyer_lastname, buyer_mobile_number, buyer_email, 
                      amount, device_serial, transaction_date
                FROM unza_transactions
                WHERE debit_reference = ?';

        $isPrepared = $stmt = $this->conn->prepare($sql);

        if (!$isPrepared) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('s', $debit_reference);
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
        $source = "Sales";
        $SMS = urlencode($msg);

        $url = "http://" . KANNEL_IP . ":" . KANNEL_PORT . "/napsamobile/pushsms?smsc=zamtelsmsc&username=" . KANNEL_USER . "&password=" . KANNEL_PASSWORD . "&from=" . $source . "&to=" . $destination . "&text=" . $SMS;
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, PaEaseConnectionTimeout);
//            curl_setopt($ch, CURLOPT_TIMEOUT, PaEaseReadTimeout);
            $response = curl_exec($ch);

            //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
            $file = __DIR__ . '/SMS-report.txt';
            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $response . " mobile number: " . $destination . "\n" . "\n";
            file_put_contents($file, $date, FILE_APPEND);
            //END OF WRITING TO FILE

            curl_close($ch);
        } catch (Exception $ex) {

            //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
            $file = __DIR__ . '/SMS-exception-report.txt';
            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . $ex . " mobile number: " . $destination . "\n" . "\n";
            file_put_contents($file, $date, FILE_APPEND);
            //END OF WRITING TO FILE

            throw new Exception("Error occurred while sending sms");
        }
    }

    public function saveSMS($message, $mobile_number)
    {
        $response = array();

        $sender_id = "Sales";
        $status = 0;

        $sql = 'INSERT INTO unza_sms_logs(sender_id, mobile_number, message, status) VALUES(?,?,?,?)';

        if (!($stmt = $this->conn->prepare($sql))) {
            $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
        }

        $isParamBound = $stmt->bind_param('ssss', $sender_id, $mobile_number, $message, $status);
        if (!$isParamBound) {
            $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
        }

        if ($stmt->execute()) {
            //CREATED

            $response['error'] = false;
            $response['message'] = 'Debit Request captured';
            $stmt->close();

            $this->echoResponse(HTTP_status_200_OK, $response);

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


//************************SEND SMS***************************//

    public function recordSMSHistory($from, $result)
    {
        $response = array();

        $json_obj = json_decode($result, true);
        $recipients = $json_obj['SMSMessageData']['Recipients'];


        foreach ($recipients AS $key => $val) {

            $sql = 'INSERT INTO sms_history(alphanumeric, recipient_number, cost, messageId, messageParts, status, statusCode) VALUES(?,?,?,?,?,?,?)';

            $isPrepared = $stmt = $this->conn->prepare($sql);
            if (!$isPrepared) {
                $this->Prepare_failed_to_file('Prepare failed: (' . $this->conn->errno . ') ' . $this->conn->error, $sql);
            }

            $isParamBound = $stmt->bind_param('ssssisi', $from, $val['number'], $val['cost'], $val['messageId'], $val['messageParts'], $val['status'], $val['statusCode']);
            if (!$isParamBound) {
                $this->Binding_parameters_failed_to_file('Binding parameters failed: (' . $stmt->errno . ') ' . $stmt->error, $sql);
            }


            if ($stmt->execute()) {
                //CREATED
                $response['error'] = false;
                $response['message'] = 'SMS history captured successfully';

                $stmt->close();

            } else {
                //FAILED TO CREATE
                $response['error'] = true;
                $response['message'] = 'Oops! An error occurred';
                $response['error_message'] = 'Execute failed: (' . $stmt->errno . ')' . $stmt->error;

                $this->Execute_failed_to_file($response, $sql);

                $stmt->close();
            }

        }
        return $response;
    }

//************************END OF SMS***************************//


}

/**
 * @author: Chimuka Moonde
 * @mobile No: 0973297682
 * @email : chimukamoonde@gmail.com
 * Kindly note that this is a customized version of slim 2
 */