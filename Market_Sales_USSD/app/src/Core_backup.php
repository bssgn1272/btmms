<?php

namespace App\Src;

//use App\Lib\Config;
use App\Lib\StatusCodes;
use App\Lib\SharedUtils;
use App\Lib\Config;

//use App\Lib\Logger\USSDLogger;

date_default_timezone_set("Africa/Lusaka");
ini_set('error_log', 'ussd-app-error.log'); //Error log for general php (application) errors.
/**
 * The USSD Main class
 *
 * @author Francis Chulu
 */

class Core {

    /**
     * Logging class instance
     * @var type object
     */
    private $log;

    /**
     * Config class instance
     * @var type object
     */
    private $config;

    /**
     * Utilities class instance
     * @var type object
     */
    private $utils;

    /**
     * Status codes class instance
     * @var type object
     */
    private $statusCodes;

    /**
     * Holds the request payload array
     * @var type array
     */
    private $payload;

    /**
     * Request mobile number
     * @var string 
     */
    private $msisdn;

    /**
     * Request sequence
     * @var string 
     */
    private $sequence;

    /**
     * Request end of session
     * @var boolean 
     */
    private $end_of_session;

    /**
     * Request language
     * @var string 
     */
    private $language;

    /**
     * session id 
     * @var string 
     */
    private $session_id;

    /**
     * Request msisdn imsi
     * @var string 
     */
    private $imsi;

    /**
     * Request service key
     * @var string 
     */
    private $service_key;

    /**
     * Request body
     * @var string 
     */
    private $body;

    /**
     * Gateway response
     * @var array 
     */
    private $gw_response;

    /**
     * Menus file
     * @param type JSON
     */
    private $menus;

    /**
     * Request session id
     * @param type string
     */
    private $request_session_id;

    /**
     * Traders names
     * @param type string
     */
    private $names;

    /**
     * 
     * @param type $msisdn
     * @param type $log
     */
    public function __construct($msisdn, $log) {
        $log->logInfo(Config::APP_INFO_LOG, $msisdn, '| Initializing....');
        $this->msisdn = $msisdn;
        $this->names = "";
        $this->sequence = "";
        $this->end_of_session = FALSE;
        $this->language = "";
        $this->session_id = "";
        $this->request_session_id = "";
        $this->imsi = "";
        $this->service_key = "";
        $this->body = "";
        $this->gw_response = "";
        $this->log = $log;
        $this->statusCodes = new StatusCodes();
        $log->logInfo(Config::APP_INFO_LOG, $msisdn, ' Done Initializing');
    }

    function handle($payload) {
        $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, '| The request received is ' . print_r($payload, TRUE));
        $this->setVariables($payload);
        $this->startSession();
        $this->loadMenus();
        if (!is_array($this->menus) || count($this->menus) <= 0) {
            $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, '| Menus cannot be loaded. ' . print_r($this->menus, TRUE));
            $this->end_of_session = TRUE;
            $this->body = Config::SYSTEM_BUSY_MESSAGE;
            $_SESSION['menu-selection'] = "-1";
            $this->gw_response = $this->get_response_array();
            return $this->gw_response;
        } else {
            //First check if trader is registered and account is active
            //$this->checkRegistration();
            //return $this->processRequest();
            if ($this->checkRegistration()) {
                return $this->processRequest();
            } else {
                $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, '| Trader is not registered. They need to register first before accessing the system!');
                $this->end_of_session = TRUE;
                $_SESSION['menu-selection'] = "-1";
                $this->gw_response = $this->get_response_array();
                return $this->gw_response;
            }
        }
    }

    private function processRequest() {
        //First time dial in
        if (empty($_SESSION['menu-selection'])) {
            $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, '| Returning with Main Menu');
            $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
            $_SESSION['menu-selection'] = "MAIN";
            $this->gw_response = $this->get_response_array();
            return $this->gw_response;
        }
        //Lets proceed and check customer selections
        return $this->menuSelections();
    }

    private function menuSelections() {
        switch ($_SESSION['menu-selection']) {
            case "MAIN":
                $this->gw_response = $this->main();
                break;
            case "RESETPIN_NRC":
                $this->gw_response = $this->resetPin();
                break;
        }
        return $this->gw_response;
    }

    private function main() {
        switch ($this->body) {
            case "1":
                break;
            case "2":
                break;
            case "3":
                break;
            case "4":
                $_SESSION['menu-selection'] = "RESETPIN_NRC";
                $this->body = $this->menus['RESETPIN_NRC'];
                $this->gw_response = $this->get_response_array();
                break;
            default:
                $_SESSION['menu-selection'] = "MAIN";
                $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
                $this->gw_response = $this->get_response_array();
                break;
        }

        return $this->gw_response;
    }

    private function resetPin() {
        if (!empty($this->body)) {
            
        } else {
            $_SESSION['menu-selection'] = "RESETPIN_NRC";
            $this->body = $this->menus['RESETPIN_NRC'];
            $this->gw_response = $this->get_response_array();
        }
        return $this->gw_response;
    }

    private function checkRegistration() {
        $response = TRUE;
        $payload = SharedUtils::buildAPIRequest("", "", "", "", "", "", $this->msisdn, "", "");
        $result = SharedUtils::httpGet("users", $payload, $this->msisdn, $this->log);
        if (!empty($result['users'])) {
            $_SESSION['names'] = "";
            //Check if Trader is blocked or not
            if ($result['users']['status'] === Config::ACC_ACTIVE_STATUS) {
                //Store this traders details in a session so that we use them when want to
                $_SESSION['trader_details'] = $result['users'];
                $_SESSION['names'] = $result['users']['firstname'] . " " . $result['users']['lastname'];
                $response = TRUE;
            } else {
                $this->body = $this->menus['TRADER_BLOCKED'];
            }
        } else {
            $this->body = SharedUtils::strReplace("{msisdn}", $this->msisdn, $this->menus['NOT_REGISTERED']);
        }
        return $response;
    }

    private function checkStatus($result) {
        $response = false;
        foreach ($result as $value) {
            if ($value['status'] === Config::ACC_ACTIVE_STATUS) {
                $response = TRUE;
            }
        }
        return $response;
    }

    private function setVariables($payload) {
        $this->sequence = $payload['SEQUENCE'];
        $this->end_of_session = $payload['END_OF_SESSION'];
        $this->language = $payload['LANGUAGE'];
        $this->request_session_id = $payload['SESSION_ID'];
        $this->imsi = $payload['IMSI'];
        $this->service_key = $payload['SERVICE_KEY'];
        $this->msisdn = $payload['MOBILE_NUMBER'];
        $this->body = isset($payload['USSD_BODY']) ? $payload['USSD_BODY'] : "1";
        $this->session_id = $this->msisdn . $this->request_session_id;
    }

    private function startSession() {
        session_id($this->session_id);
        session_start();
    }

    private function loadMenus() {
//We load the menus json file
        $this->menus = SharedUtils::JsonToArray(file_get_contents(__DIR__ . "/../lib/utils/Menus.json"), true);
    }

    /**
     * Function for preparing the response payload - reSponse to the USSD Gateway.
     *
     * @param ses_id
     * @param seq
     * @param body
     * @param is_end_of_ses
     *
     */
    private function get_response_array() {
        $response = [];
        $response['RESPONSE_CODE'] = '0';
        $response['SESSION_ID'] = $this->request_session_id;
        $response['SEQUENCE'] = $this->sequence;
        $response['USSD_BODY'] = $this->body;
        $response['END_OF_SESSION'] = $this->end_of_session;
        $response['REQUEST_TYPE'] = 'RESPONSE';

        if ($this->end_of_session == 'TRUE' && isset($_SESSION['menu-selection'])) {
            $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, '| Cleaning up the session values....');
            session_unset();
            $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, ' Session values cleaned');
        }
        $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, 'Array respponse::' . print_r($response, TRUE));
        return $response;
    }

}
