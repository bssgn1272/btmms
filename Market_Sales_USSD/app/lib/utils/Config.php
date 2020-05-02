<?php

namespace App\Lib;

class Config {

    /**
     * USSD info log file path
     * 
     * @var string
     */
    CONST APP_INFO_LOG = "/var/log/applications/ussd/INFO";
    //CONST APP_INFO_LOG = "C:/xampp/logs/INFO";

    /**
     * USSD error log file path
     * 
     * @var string
     */
    CONST APP_ERROR_LOG = "/var/log/applications/ussd/ERROR";
    //CONST APP_ERROR_LOG = "C:/xampp/logs/ERROR";

    /**
     * USSD deburg log file path
     * 
     * @var string
     */
     CONST APP_DEBURG_LOG = "/var/log/applications/ussd/DEBURG";
    //CONST APP_DEBURG_LOG = "C:/xampp/logs/DEBURG";

    /**
     * System busy message
     * @var string
     */
    CONST SYSTEM_BUSY_MESSAGE = "System is currently busy, please try again later.";

    /**
     * http OK status code
     * @var type int
     */
    CONST HTTP_SUCCESS_STATUS_CODE = 200;

    /**
     * Zamtel end session request type 3
     * @var type int
     */
    CONST ZAMTEL_END_SESSION_REQUEST_TYPE = 3;

    /**
     * Zamtel continue session request type 2
     * @var type int
     */
    CONST ZAMTEL_CONTINUE_SESSION_REQUEST_TYPE = 2;

    /**
     * Zamtel new session request type 1
     * @var type int
     */
    CONST ZAMTEL_NEW_SESSION_REQUEST_TYPE = 1;

    /**
     * Default ussd language
     * @var type string
     */
    CONST USSD_DEFAULT_LANGUAGE = "ENG";

    /**
     * Default imsi
     * @var type int
     */
    CONST USSD_DEFAULT_IMSI = 111111111111;

    /**
     * Market sales endpoint url
     * @var type string
     */
    //CONST API_URL = "http://localhost/tms_api/v1/";
    CONST API_URL = "http://18.188.249.56/MarketSalesAPI/v1/";
    //PROBASE END POINTS
    CONST VERSION_MARKET = "http://{HOST}:{PORT}/api/v1/btms/market/secured/";
    CONST VERSION_TRAVEL = "http://{HOST}:{PORT}/api/v1/btms/travel/secured/";
    CONST VERSION_TICKETS = "http://{HOST}:{PORT}/api/v1/btms/tickets/secured/";
    CONST MARKETER_KYC = "marketer_kyc";
    CONST AUTH_MARKETEER = "authenticate_marketer";
    CONST UPDATE_PIN = "update_pin";
    CONST RESET_PIN = "reset_pin";
    CONST REGISTER_MARKETEER = "register_market";
    CONST ROUTES = "routes";
    CONST SCHEDULED_ROUTES = "internal/locations/destinations";
    CONST PURCHASE_TICKET = "purchase";
    //NAPSA DETAILS
   CONST NAPSA_IP = "10.10.1.57";
   // CONST NAPSA_IP = "192.168.8.253";
    CONST NAPSA_PORT = "4000";
    //PROBASE API CREDENTIALS
    CONST PROBASE_API_USERNAME = "admin";
    CONST PROBASE_API_SERVICE_TOKEN = "JJ8DJ7S66DMA5";
    //PROBASE STATUS CODES
    CONST OPERATION_SUCCESS = 0;
    CONST OPERATION_FAILED = 1;

    /**
     * Curl calls connection timeout in seconds
     * @var type int
     */
    CONST CONNECTION_TIMEOUT = 20;

    /**
     * Curl calls read timeout in seconds
     * @var type int
     */
    CONST READ_TIMEOUT = 30;

    /**
     * Marketeer account is blocked
     * @var type int
     */
    CONST ACC_BLOCKED_STATUS = "INACTIVE";

    /**
     * Marketeer account is active
     * @var type int
     */
    CONST ACC_ACTIVE_STATUS = "ACTIVE";

    /**
     * Marketeer account is in otp
     * @var type int
     */
    CONST ACC_OTP_STATUS = "OTP";

    /**
     * SUCCESS STATUS
     */
    CONST SUCCESS_STATUS_CODE = 100;

    /**
     * Invalid message starting str
     */
    CONST INVALID_STR = "Invalid {val}.";

    /**
     * MSISDN Length
     */
    CONST MSISDN_LEN = 10;

    /**
     * MOMO Pin length
     */
    CONST MOMO_PIN_LEN = 5;

    /**
     * Country code
     */
    CONST country_code = 26;

    /**
     * Transaction types
     */
    CONST MAKE_SALE = 1;
    CONST ORDER = 2;
    CONST TICKET_PURCHASE = 3;
    CONST PAY_MARKET_FEE = 4;
    CONST MAX_OPTIONS_PER_PAGE=3;

}
