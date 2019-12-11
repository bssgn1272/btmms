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
     * @var type double
     */
    private $balance;

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
        $this->balance = 0;
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
            $_SESSION['menu-selection'] = "";
            $this->gw_response = $this->get_response_array();
            return $this->gw_response;
        } else {
            //First check if trader is registered and account is active so that we pull their names
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
        //First time dial in and is trader
        if (empty($_SESSION['menu-selection']) && !empty($_SESSION['trader_details'])) {
            $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, '| Returning with Trader Main Menu');
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
            case "MAIN_BUYERS":
                $this->gw_response = $this->mainBuyer();
                break;
            case "MAKE_SALE_AMOUNT":
                $this->gw_response = $this->makeSaleAmount();
                break;
            case "MAKE_SALE_BUYER_MOBILE":
                $this->gw_response = $this->makeSaleBuyerMobile();
                break;
            case "MAKE_SALE_CONFIRM":
                $this->gw_response = $this->makeSaleConfirm();
                break;
            case "ORDER_GOODS":
                $this->gw_response = $this->orderGoods();
                break;
            case "ORDER_GOODS_AMOUNT":
                $this->gw_response = $this->orderGoodsAmount();
                break;
            case "ORDER_GOODS_CONFIRM":
                $this->gw_response = $this->orderGoodsConfirm();
                break;
            case "CHECK_BALANCE":
                $this->gw_response = $this->checkBalance();
                break;
            case "TRX_PROCESSING":
                $this->gw_response = $this->transactionProcessing();
                break;
            case "LOGIN":
                $this->gw_response = $this->login();
                break;
            case "LOGIN_MAIN":
                $this->gw_response = $this->loginMain();
                break;
            case "LOGIN_MAIN_SALES":
                $this->gw_response = $this->loginMainSales();
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
                $this->end_of_session = TRUE;
                $_SESSION['menu-selection'] = "TODO";
                $this->body = $this->menus['TODO'];
                $this->gw_response = $this->get_response_array();
                break;
            case "2":
                $_SESSION['menu-selection'] = "MAKE_SALE_AMOUNT";
                $this->body = $this->menus['MAKE_SALE_AMOUNT'];
                $this->gw_response = $this->get_response_array();
                break;
            case "3":
                $_SESSION['menu-selection'] = "ORDER_GOODS";
                $this->body = $this->menus['ORDER_GOODS'];
                $this->gw_response = $this->get_response_array();
                break;
            case "4":
                //TODO: Call API and Pull customers balance
                $_SESSION['menu-selection'] = "CHECK_BALANCE";
                $payload = SharedUtils::buildAPIRequest($_SESSION['trader_details']['trader_id'], "", "", "", "", "", $this->msisdn, "", "");
                $result = SharedUtils::httpGet("balance", $payload, $this->msisdn, $this->log);
                $_SESSION['balance'] = $result['token_balance'];
                $this->body = SharedUtils::strReplace("{balance}", $_SESSION['balance'], $this->menus['CHECK_BALANCE']);
                $this->gw_response = $this->get_response_array();
                break;
            case "5":
                // $_SESSION['menu-selection'] = "LOGIN";
                // $this->body = $this->menus['LOGIN'];
                // $this->gw_response = $this->get_response_array();
                $this->end_of_session = TRUE;
                $_SESSION['menu-selection'] = "TODO";
                $this->body = $this->menus['TODO'];
                $this->gw_response = $this->get_response_array();
                break;
            default:
                $_SESSION['menu-selection'] = "MAIN";
                $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
                $this->body = SharedUtils::strReplace("{val}", "selection", Config::INVALID_STR) . "\n" . $this->body;
                $this->gw_response = $this->get_response_array();
                break;
        }
        return $this->gw_response;
    }

    private function mainBuyer() {
        switch ($this->body) {
            case "1":
                $_SESSION['menu-selection'] = "RESETPIN_NRC";
                $this->body = $this->menus['RESETPIN_NRC'];
                $this->gw_response = $this->get_response_array();
                break;
            case "2":
                $this->end_of_session = TRUE;
                $_SESSION['menu-selection'] = "EXIT";
                $this->body = $this->menus['EXIT'];
                $this->gw_response = $this->get_response_array();
                break;
            default:
                $_SESSION['menu-selection'] = "MAIN_BUYERS";
                $this->body = $this->menus['MAIN_BUYERS'];
                $this->gw_response = $this->get_response_array();
                break;
        }
        return $this->gw_response;
    }

    //CHECK BALANCE STARTS
    private function checkBalance() {
        switch ($this->body) {
            case "1":
                $_SESSION['menu-selection'] = "MAIN";
                $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
                $this->gw_response = $this->get_response_array();
                break;
            default:
                $_SESSION['menu-selection'] = "CHECK_BALANCE";
                $this->body = SharedUtils::strReplace("{balance}", $_SESSION['balance'], $this->menus['CHECK_BALANCE']);
                $this->body = SharedUtils::strReplace("{val}", "selection", Config::INVALID_STR) . "\n" . $this->body;
                $this->gw_response = $this->get_response_array();
                break;
        }
        return $this->gw_response;
    }

    //Transaction processing STARTS
    private function transactionProcessing() {
        switch ($this->body) {
            case "1":
                $_SESSION['menu-selection'] = "MAIN";
                $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
                $this->gw_response = $this->get_response_array();
                break;
            default:
                $_SESSION['menu-selection'] = "TRX_PROCESSING";
                $this->body = SharedUtils::strReplace("{val}", "selection", Config::INVALID_STR) . "\n" . $this->menus['TRX_PROCESSING'];
                $this->gw_response = $this->get_response_array();
                break;
        }
        return $this->gw_response;
    }

    //Transaction processing ENDS
    //CHECK BALANCE ENDS
    //--------------------------------
    //MAKE SALE FUNCTIONS STARTS HERE
    /**
     *  Make sale process amount
     * @return type array
     */
    private function makeSaleAmount() {
        switch ($this->body) {
            case "#":
                $_SESSION['menu-selection'] = "";
                $this->gw_response = $this->processRequest();
                break;
            default:
                if (SharedUtils::validateAmount($this->body, $this->log, $this->msisdn)) {
                    $_SESSION['Sale_amount'] = $this->body;
                    $_SESSION['menu-selection'] = "MAKE_SALE_BUYER_MOBILE";
                    $this->body = $this->menus['MAKE_SALE_BUYER_MOBILE'];
                    $this->gw_response = $this->get_response_array();
                } else {
                    $_SESSION['menu-selection'] = "MAKE_SALE_AMOUNT";
                    $this->body = SharedUtils::strReplace("{val}", "amount", Config::INVALID_STR) . "\n" . $this->menus['MAKE_SALE_AMOUNT'];
                    $this->gw_response = $this->get_response_array();
                }
                break;
        }
        return $this->gw_response;
    }

    /**
     * Make sale process buyers mobile number
     * @return type array
     */
    private function makeSaleBuyerMobile() {
        switch ($this->body) {
            case "#":
                $_SESSION['menu-selection'] = "";
                $this->gw_response = $this->processRequest();
                break;
            case "0":
                $this->body = 2;
                $this->gw_response = $this->main();
                break;
            default:
                if ($this->msisdn != Config::country_code . $this->body) {
                    if (SharedUtils::validateMsisdn($this->body, $this->log, $this->msisdn)) {
                        $_SESSION['menu-selection'] = "MAKE_SALE_CONFIRM";
                        $_SESSION['buyer_msisdn'] = Config::country_code . $this->body;
                        $this->body = SharedUtils::strReplace("{msisdn}", $this->body, $this->menus['MAKE_SALE_CONFIRM']);
                        $this->body = SharedUtils::strReplace("{amount}", $_SESSION['Sale_amount'], $this->body);
                        $this->gw_response = $this->get_response_array();
                    } else {
                        $_SESSION['menu-selection'] = "MAKE_SALE_BUYER_MOBILE";
                        $this->body = SharedUtils::strReplace("{val}", "mobile number", Config::INVALID_STR) . "\n" . $this->menus['MAKE_SALE_BUYER_MOBILE'];
                        $this->gw_response = $this->get_response_array();
                    }
                } else {
                    $_SESSION['menu-selection'] = "MAKE_SALE_BUYER_MOBILE";
                    $this->body = SharedUtils::strReplace("{val}", "mobile number. You cannot sale to yourself", Config::INVALID_STR) . "\n" . $this->menus['MAKE_SALE_BUYER_MOBILE'];
                    $this->gw_response = $this->get_response_array();
                }
                break;
        }
        return $this->gw_response;
    }

    /**
     * confirm sale
     * @return type array
     */
    private function makeSaleConfirm() {
        switch ($this->body) {
            case "1":
                //Lets push the transaction to the api
                $payload = SharedUtils::buildPushTransactionRequest($_SESSION['trader_details']['trader_id'], $_SESSION['Sale_amount'], $_SESSION['buyer_msisdn']);
                $result = SharedUtils::httpPostJson("transactions", $payload, $this->msisdn, $this->log);
                $_SESSION['menu-selection'] = "TRX_PROCESSING";
                $this->body = $this->menus['TRX_PROCESSING'];
                $this->gw_response = $this->get_response_array();
                break;
            case "#":
                $_SESSION['menu-selection'] = "";
                $this->gw_response = $this->processRequest();
                break;
            case "0":
                $this->body = $_SESSION['Sale_amount'];
                $this->gw_response = $this->makeSaleAmount();
                break;
            default :
                $_SESSION['menu-selection'] = "MAKE_SALE_CONFIRM";
                $this->body = SharedUtils::strReplace("{msisdn}", $_SESSION['buyer_msisdn'], $this->menus['MAKE_SALE_CONFIRM']);
                $this->body = SharedUtils::strReplace("{amount}", $_SESSION['Sale_amount'], $this->body);
                $this->body = SharedUtils::strReplace("{val}", "selection", Config::INVALID_STR) . "\n" . $this->body;
                $this->gw_response = $this->get_response_array();
                break;
        }
        return $this->gw_response;
    }

    //MAKE SALE FUNCTIONS END HERE
    //----------------------------------------
    //ORDER GOODS FUNCTIONS START HERE
    private function orderGoods() {
        switch ($this->body) {
            case "#":
                $_SESSION['menu-selection'] = "";
                $this->gw_response = $this->processRequest();
                break;
            default:
                $payload = SharedUtils::buildAPIRequest("", "", "", "", "", "", Config::country_code . $this->body, "", "");
                $result = SharedUtils::httpGet("users", $payload, $this->msisdn, $this->log);
                if (SharedUtils::validateMsisdn($this->body, $this->log, $this->msisdn) && !empty($result['users'])) {
                    //Trader cannot buy from themselves
                    if ($this->msisdn != Config::country_code . $this->body) {
                        if ($result['users']['status'] === Config::ACC_ACTIVE_STATUS) {
                            $_SESSION['seller_details'] = $result['users'];
                            $_SESSION['seller_names'] = $result['users']['firstname'] . " " . $result['users']['lastname'];
                            $_SESSION['menu-selection'] = "ORDER_GOODS_AMOUNT";
                            $_SESSION['seller_mobile'] = $this->body;
                            $this->body = SharedUtils::strReplace("{trader}", $_SESSION['seller_names'], $this->menus['ORDER_GOODS_AMOUNT']);
                            $this->body = SharedUtils::strReplace("{mobile}", $_SESSION['seller_mobile'], $this->body);
                            $this->gw_response = $this->get_response_array();
                        } else {
                            $this->end_of_session = TRUE;
                            $this->body = $this->menus['TRADER_BLOCKED1'];
                            $this->gw_response = $this->get_response_array();
                        }
                    } else {
                        $_SESSION['menu-selection'] = "ORDER_GOODS";
                        $this->body = SharedUtils::strReplace("{val}", "mobile number. You cannot order from yourself", Config::INVALID_STR) . "\n" . $this->menus['ORDER_GOODS'];
                        $this->gw_response = $this->get_response_array();
                    }
                } else {
                    $_SESSION['menu-selection'] = "ORDER_GOODS";
                    $this->body = SharedUtils::strReplace("{val}", "traders mobile number", Config::INVALID_STR) . "\n" . $this->menus['ORDER_GOODS'];
                    $this->gw_response = $this->get_response_array();
                }
                break;
        }
        return $this->gw_response;
    }

    private function orderGoodsAmount() {
        switch ($this->body) {
            case "#":
                $_SESSION['menu-selection'] = "";
                $this->gw_response = $this->processRequest();
                break;
            case "0":
                $this->body = 3;
                $this->gw_response = $this->main();
                break;
            default:
                if (SharedUtils::validateAmount($this->body, $this->log, $this->msisdn)) {
                    $_SESSION['Sale_amount'] = $this->body;
                    $_SESSION['menu-selection'] = "ORDER_GOODS_CONFIRM";
                    $this->body = SharedUtils::strReplace("{trader}", $_SESSION['seller_names'], $this->menus['ORDER_GOODS_CONFIRM']);
                    $this->body = SharedUtils::strReplace("{mobile}", $_SESSION['seller_mobile'], $this->body);
                    $this->body = SharedUtils::strReplace("{amount}", $_SESSION['Sale_amount'], $this->body);
                    $this->gw_response = $this->get_response_array();
                } else {
                    $_SESSION['menu-selection'] = "ORDER_GOODS_AMOUNT";
                    $this->body = SharedUtils::strReplace("{trader}", $_SESSION['seller_names'], $this->menus['ORDER_GOODS_AMOUNT']);
                    $this->body = SharedUtils::strReplace("{mobile}", $_SESSION['seller_mobile'], $this->body);
                    $this->body = SharedUtils::strReplace("{val}", "amount", Config::INVALID_STR) . "\n" . $this->body;
                    $this->gw_response = $this->get_response_array();
                }
                break;
        }
        return $this->gw_response;
    }

    /**
     * confirm order goods
     * @return type array
     */
    private function orderGoodsConfirm() {
        switch ($this->body) {
            case "1":
                //TODO: Post the transaction to the API for processing
                //Lets push the transaction to the api
                $payload = SharedUtils::buildPushTransactionRequest($_SESSION['seller_details']['trader_id'], $_SESSION['Sale_amount'], $this->msisdn);
                $result = SharedUtils::httpPostJson("transactions", $payload, $this->msisdn, $this->log);
                $_SESSION['menu-selection'] = "TRX_PROCESSING";
                $this->body = $this->menus['TRX_PROCESSING'];
                $this->gw_response = $this->get_response_array();
                break;
            case "#":
                $_SESSION['menu-selection'] = "";
                $this->gw_response = $this->processRequest();
                break;
            case "0":
                $this->body = $_SESSION['seller_mobile'];
                $this->gw_response = $this->orderGoods();
                break;
            default :
                $_SESSION['menu-selection'] = "ORDER_GOODS_CONFIRM";
                $this->body = SharedUtils::strReplace("{trader}", $_SESSION['seller_names'], $this->menus['ORDER_GOODS_CONFIRM']);
                $this->body = SharedUtils::strReplace("{mobile}", $_SESSION['seller_mobile'], $this->body);
                $this->body = SharedUtils::strReplace("{amount}", $_SESSION['Sale_amount'], $this->body);
                $this->body = SharedUtils::strReplace("{val}", "selection", Config::INVALID_STR) . "\n" . $this->body;
                $this->gw_response = $this->get_response_array();
                break;
        }
        return $this->gw_response;
    }

    //ORDER GOODS FUNCTIONS END HERE
    //----------------------------------------
    //LOGIN FUNCTIONS STARTS HERE
    /**
     * Process login selection
     * @return type array
     */
    private function login() {
        //TODO: Validate MoMo Pin using MoMo systems
        if (SharedUtils::validateMomoPin($this->msisdn, $this->msisdn, $this->body, $this->log)) {
            $_SESSION['menu-selection'] = "LOGIN_MAIN";
            $this->body = $this->menus['LOGIN_MAIN'];
            $this->gw_response = $this->get_response_array();
        } else {
            $_SESSION['menu-selection'] = "LOGIN";
            $this->body = SharedUtils::strReplace("{val}", "MOMO pin", Config::INVALID_STR) . $this->menus['LOGIN'];
            $this->gw_response = $this->get_response_array();
        }
        return $this->gw_response;
    }

    private function loginMain() {
        switch ($this->body) {
            case "1":
                //TODO: Post the transaction to the API
                $_SESSION['menu-selection'] = "LOGIN_MAIN_SALES";
                $this->body = $this->menus['LOGIN_MAIN_SALES'];
                $this->gw_response = $this->get_response_array();
                break;
            case "2":
                $this->end_of_session = TRUE;
                $_SESSION['menu-selection'] = "TODO";
                $this->body = $this->menus['TODO'];
                $this->gw_response = $this->get_response_array();
                break;
            case "3":
                $this->end_of_session = TRUE;
                $_SESSION['menu-selection'] = "TODO";
                $this->body = $this->menus['TODO'];
                $this->gw_response = $this->get_response_array();
                break;
            default :
                $_SESSION['menu-selection'] = "LOGIN_MAIN";
                $this->body = SharedUtils::strReplace("{val}", "selection", Config::INVALID_STR) . $this->menus['LOGIN_MAIN'];
                $this->gw_response = $this->get_response_array();
                break;
        }
        return $this->gw_response;
    }

    private function loginMainSales() {
        switch ($this->body) {
            case "1":
                //TODO: Call API to send an SMS with todays transactions
                $this->end_of_session = TRUE;
                $_SESSION['menu-selection'] = "LOGIN_MAIN_SALES_SMS_MSG";
                $this->body = $this->menus['LOGIN_MAIN_SALES_SMS_MSG'];
                $this->gw_response = $this->get_response_array();
                break;
            case "2":
                $this->end_of_session = TRUE;
                $_SESSION['menu-selection'] = "TODO";
                $this->body = $this->menus['TODO'];
                $this->gw_response = $this->get_response_array();
                break;
            case "#":
                $_SESSION['menu-selection'] = "LOGIN_MAIN";
                $this->body = $this->menus['LOGIN_MAIN'];
                $this->gw_response = $this->get_response_array();
                break;
            default :
                $_SESSION['menu-selection'] = "LOGIN_MAIN_SALES";
                $this->body = SharedUtils::strReplace("{val}", "selection", Config::INVALID_STR) . $this->menus['LOGIN_MAIN_SALES'];
                $this->gw_response = $this->get_response_array();
                break;
        }
        return $this->gw_response;
    }

    //LOGIN FUNCTIONS ENDS HERE
    private function resetPin() {
        if (!empty($this->body)) {
            $payload = SharedUtils::buildAPIRequest("", "", "", "", $this->body, "", $this->msisdn, "", "");
            $result = SharedUtils::httpGet("nrc", $payload, $this->msisdn, $this->log);
            if ($result['status'] == Config::SUCCESS_STATUS_CODE) {
                $this->end_of_session = TRUE;
                $_SESSION['menu-selection'] = "";
                $this->body = $this->menus['RESETPIN_NRC_OTP_MSG'];
                $this->gw_response = $this->get_response_array();
            } else {
                $_SESSION['menu-selection'] = "RESETPIN_NRC";
                $this->body = $this->menus['RESETPIN_NRC_INVALID'];
                $this->gw_response = $this->get_response_array();
            }
        } else {
            $_SESSION['menu-selection'] = "RESETPIN_NRC";
            $this->body = $this->menus['RESETPIN_NRC'];
            $this->gw_response = $this->get_response_array();
        }
        return $this->gw_response;
    }

    private function checkRegistration() {
        $response = false;
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
