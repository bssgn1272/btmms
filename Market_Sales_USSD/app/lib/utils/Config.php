<?php

namespace App\Lib;

class Config {

    /**
     * USSD info log file path
     * 
     * @var string
     */
    CONST APP_INFO_LOG = "/var/log/applications/ussd/INFO";

    /**
     * USSD error log file path
     * 
     * @var string
     */
    CONST APP_ERROR_LOG = "/var/log/applications/ussd/ERROR";

    /**
     * USSD deburg log file path
     * 
     * @var string
     */
    CONST APP_DEBURG_LOG = "/var/log/applications/ussd/DEBURG";

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
    CONST API_URL = "http://localhost/tms_api/v1/";

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
    CONST ACC_BLOCKED_STATUS = 0;

    /**
     * Marketeer account is active
     * @var type int
     */
    CONST ACC_ACTIVE_STATUS = 1;

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
    CONST MOMO_PIN_LEN = 4;
    /**
     * Country code
     */
    CONST country_code = 26;

}
