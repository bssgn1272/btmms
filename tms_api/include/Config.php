<?php
/*
DATABASE CONFIGURATION
*/
define('ENVIRONMENT', 3);
define('SIMULATION', 1);

switch (ENVIRONMENT) {
    case 1://PRODUCTION
        define('DB_USERNAME', 'napsa_user');
        define('DB_PASSWORD', 'napsa12345');
        define('DB_HOST', 'mysql-server-test.cgbpeu9hf7kv.us-east-2.rds.amazonaws.com');
        define('DB_NAME','napsa_market_sales');
        break;
    case 2://TEST
        define('DB_USERNAME', 'napsa_user');
        define('DB_PASSWORD', 'napsa12345');
        define('DB_HOST', 'mysql-server-test.cgbpeu9hf7kv.us-east-2.rds.amazonaws.com');
        define('DB_NAME','napsa_market_sales');
        break;
    case 3://LOCAL
        define('DB_USERNAME', 'root');
        define('DB_PASSWORD', '');
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'napsa_market_sales');
        break;
    default:
        define('DB_USERNAME', 'root');
        define('DB_PASSWORD', '');
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'napsa_market_sales');
        echo 'environment has not been chosen';
}

/*
 * if you need the time to be correct according to a specific location, you can set a timezone to use
 * */
date_default_timezone_set('Africa/Lusaka');

define('USER_ID', 'user_id');
define('TOKEN_BALANCE', 'token_balance');

//LIST OF HTTP STATUS CODES
/*
1xx informational response – the request was received, continuing process
2xx successful – the request was successfully received, understood and accepted
3xx redirection – further action needs to be taken in order to complete the request
4xx client error – the request contains bad syntax or cannot be fulfilled
5xx server error – the server failed to fulfill an apparently valid request
 */
//Successful responses
define('HTTP_status_200_OK', '200');
define('HTTP_status_201_Created', '201');
define('HTTP_status_202_Accepted', '202');
define('HTTP_status_204_No_Content', '204');

//Client error responses
define('HTTP_status_400_Bad Request', '400');
define('HTTP_status_401_Unauthorized','401');
define('HTTP_status_403_Forbidden','403');
define('HTTP_status_404_Not_Found','404');
define('HTTP_status_409_Conflict','409');
define('HTTP_status_422_Unprocessable_Entity','422');

//Server error responses
define('HTTP_status_500_Internal_Server_Error','500');
define('HTTP_status_501_Not_Implemented','501');
//END OF LIST OF HTTP STATUS CODES