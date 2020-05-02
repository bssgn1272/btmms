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
    private $probase_url_market;
    private $probase_url_travel;
    private $probase_url_tickets;

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
        // $this->probase_url_market = "http://728c5b51.ngrok.io";
        $this->probase_url_market = str_replace("{PORT}", Config::NAPSA_PORT, str_replace("{HOST}", Config::NAPSA_IP, Config::VERSION_MARKET));
        // $this->probase_url_tickets = "http://728c5b51.ngrok.io";
        //$this->probase_url_travel ="http://728c5b51.ngrok.io";
        $this->probase_url_tickets = str_replace("{PORT}", Config::NAPSA_PORT, str_replace("{HOST}", Config::NAPSA_IP, Config::VERSION_TICKETS));
        $this->probase_url_travel = str_replace("{PORT}", Config::NAPSA_PORT, str_replace("{HOST}", Config::NAPSA_IP, Config::VERSION_TRAVEL));
        $log->logInfo(Config::APP_INFO_LOG, $msisdn, ' Done Initializing');
    }

    function handle($payload) {
        $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, '| The request received is ' . print_r($payload, TRUE));
        $this->setVariables($payload);
        $this->startSession();
        $this->loadMenus();
        // $_SESSION['menu-selection'] = "";
        if (!is_array($this->menus) || count($this->menus) <= 0) {
            $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, '| Menus cannot be loaded. ' . print_r($this->menus, TRUE));
            $this->end_of_session = TRUE;
            $this->body = Config::SYSTEM_BUSY_MESSAGE;
            $_SESSION['menu-selection'] = "";
            $this->gw_response = $this->get_response_array();
            return $this->gw_response;
        } else {
            //First check if trader is registered and account is active so that we pull their names
            return $this->processRequest();
            /* if ($this->checkRegistration()) {
              return $this->processRequest();
              } else {
              $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, '| Trader is not registered. They need to register first before accessing the system!');
              $this->end_of_session = TRUE;
              $_SESSION['menu-selection'] = "-1";
              $this->gw_response = $this->get_response_array();
              return $this->gw_response;
              } */
        }
    }

    private function checkRegistration() {
        $this->probase_url_market = Config::API_URL . "marketeer_kyc_single";
        $response = false;
        $payload = SharedUtils::BuildProbaseMarketRequest($this->msisdn, "", "", "", "");
        $result = SharedUtils::httpGet($this->probase_url_market, $payload, $this->msisdn, $this->log);
        //$result = SharedUtils::httpPostJson($this->probase_url_market . Config::MARKETER_KYC, $payload, $this->msisdn, $this->log);
        if (!empty($result['response'])) {
            $_SESSION['names'] = "";
            //Trader is registered in our system
            if (!empty($result['response']['QUERY'])) {
                if ($result['response']['QUERY']["status"] === Config::OPERATION_SUCCESS) {
                    if (!empty($result['response']['QUERY']["data"]['account_status'])) {
                        $_SESSION['trader_details'] = $result['response']['QUERY']["data"];
                        $_SESSION['names'] = $result['response']['QUERY']["data"]['first_name'] . " "
                                . $result['response']['QUERY']["data"]['last_name'];
                        $_SESSION['stands'] = $result['response']['QUERY']["data"]['stands'];
                        $response = TRUE;
                    }
                }
            }
        }
        return $response;
    }

    private function processRequest() {
        //First time dial in and is trader
        if (empty($_SESSION['menu-selection'])) {
            $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, '| Returning with login');
            // $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
            $this->body = $this->menus['LOGIN'];
            $_SESSION['menu-selection'] = "LOGIN";
            $this->gw_response = $this->get_response_array();
            return $this->gw_response;
        }

        //Lets proceed and check customer selections
        return $this->menuSelections();
    }

    private function menuSelections() {
        switch ($_SESSION['menu-selection']) {
            case "OTP_PIN":
                $this->gw_response = $this->otpPin();
                break;
            case "NEW_PIN":
                $this->gw_response = $this->newPin();
                break;
            case "CONFIRM_NEW_PIN":
                $this->gw_response = $this->confirmNewPin();
                break;
            case "LOGIN":
                $this->gw_response = $this->login();
                break;
            case "ENTER_PIN":
                $this->gw_response = $this->authenticate();
                break;
            case "BUS_ROUTES":
                $this->gw_response = $this->busRoutes();
                break;
            case "MARKET_FEES":
                $this->gw_response = $this->marketFees();
                break;
            case "MARKET_FEES_CONFIRM":
                $this->gw_response = $this->marketFeesConfirm();
                break;
            case "CHECK_SALES":
                $this->gw_response = $this->checkSales();
                break;
            case "CHECK_SALES_NO_SALES":
                $this->gw_response = $this->checkSalesNoSales();
                break;
            case "TRAVEL_DATE":
                $this->gw_response = $this->travelDates();
                break;
            case "BUS_SCHEDULES":
                $this->gw_response = $this->busSchedules();
                break;
            case "NO_SCHEDULED_ROUTES":
                $this->gw_response = $this->noScheduledRoutes();
                break;
            case "PASSENGER_NRC":
                $this->gw_response = $this->passengerNrc();
                break;
            case "PASSENGER_FNAME":
                $this->gw_response = $this->passengerFirstname();
                break;
            case "PASSENGER_LNAME":
                $this->gw_response = $this->passengerLastname();
                break;
            case "PASSENGER_CONFIRM":
                $this->gw_response = $this->passengerConfirm();
                break;
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
            case "TRX_PROCESSING1":
                $this->gw_response = $this->transactionProcessing1();
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

    private function authenticate() {
        switch ($this->body) {
            case "#":
            case "#":
                $_SESSION['menu-selection'] = "LOGIN";
                $this->body = $this->menus['LOGIN'];
                $this->gw_response = $this->get_response_array();
                break;
            default :
                if (SharedUtils::validatePin($this->msisdn, $this->body, $this->log)) {
                    $payload = SharedUtils::BuildProbaseMarketRequest($this->msisdn, "", $this->body, "", "");
                    $result = SharedUtils::httpPostJson($this->probase_url_market . Config::AUTH_MARKETEER, $payload, $this->msisdn, $this->log);
                    if (!empty($result['response'])) {
                        if (!empty($result['response']['AUTHENTICATION']) && $result['response']['AUTHENTICATION']["status"] === Config::OPERATION_SUCCESS) {
                            $_SESSION['menu-selection'] = "MAIN";
                            $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
                            $this->gw_response = $this->get_response_array();
                        } else {
                            $_SESSION['menu-selection'] = "ENTER_PIN";
                            $this->body = "Invalid pin provided." . $this->menus['ENTER_PIN'];
                            $this->gw_response = $this->get_response_array();
                        }
                    } else {
                        $_SESSION['menu-selection'] = "";
                        $this->end_of_session = TRUE;
                        $this->body = Config::SYSTEM_BUSY_MESSAGE;
                        $this->gw_response = $this->get_response_array();
                    }
                } else {
                    $_SESSION['menu-selection'] = "ENTER_PIN";
                    $this->body = "Invalid pin provided." . $this->menus['ENTER_PIN'];
                    $this->gw_response = $this->get_response_array();
                }
                break;
        }
        return $this->gw_response;
    }

    private function otpPin() {
        if (SharedUtils::validatePin($this->msisdn, $this->body, $this->log)) {
            $payload = SharedUtils::BuildProbaseMarketRequest($this->msisdn, "", $this->body, "", "");
            $result = SharedUtils::httpPostJson($this->probase_url_market . Config::AUTH_MARKETEER, $payload, $this->msisdn, $this->log);
            if (!empty($result['response'])) {
                if (!empty($result['response']['AUTHENTICATION']) && $result['response']['AUTHENTICATION']["status"] === Config::OPERATION_SUCCESS) {
                    $_SESSION['menu-selection'] = "NEW_PIN";
                    $this->body = $this->menus['NEW_PIN'];
                    $this->gw_response = $this->get_response_array();
                    return $this->gw_response;
                } else {
                    $_SESSION['menu-selection'] = "OTP_PIN";
                    $this->body = SharedUtils::strReplace("{val}", "OTP pin ", Config::INVALID_STR) . $this->menus['OTP_PIN'];
                    $this->gw_response = $this->get_response_array();
                    return $this->gw_response;
                }
            } else {
                $_SESSION['menu-selection'] = "";
                $this->end_of_session = TRUE;
                $this->body = Config::SYSTEM_BUSY_MESSAGE;
                $this->gw_response = $this->get_response_array();
            }
        } else {
            $_SESSION['menu-selection'] = "OTP_PIN";
            $this->body = SharedUtils::strReplace("{val}", "OTP pin ", Config::INVALID_STR) . $this->menus['OTP_PIN'];
            $this->gw_response = $this->get_response_array();
        }
        return $this->gw_response;
    }

    private function newPin() {
        if (SharedUtils::validatePin($this->msisdn, $this->body, $this->log)) {
            $_SESSION["New Pin"] = $this->body;
            $_SESSION['menu-selection'] = "CONFIRM_NEW_PIN";
            $this->body = $this->menus['CONFIRM_NEW_PIN'];
            $this->gw_response = $this->get_response_array();
            return $this->gw_response;
        } else {
            $_SESSION['menu-selection'] = "NEW_PIN";
            $this->body = SharedUtils::strReplace("{val}", "New PIN ", Config::INVALID_STR) . $this->menus['NEW_PIN'];
            $this->gw_response = $this->get_response_array();
        }
        return $this->gw_response;
    }

    private function confirmNewPin() {
        switch ($this->body) {
            case "#":
                $_SESSION['menu-selection'] = "NEW_PIN";
                $this->body = $this->menus['NEW_PIN'];
                $this->gw_response = $this->get_response_array();
                break;
            default :
                if (SharedUtils::validatePin($this->msisdn, $this->body, $this->log)) {
                    if ($_SESSION["New Pin"] === $this->body) {
                        $payload = SharedUtils::BuildProbaseMarketRequest($this->msisdn, "", $this->body, "", "");
                        $result = SharedUtils::httpPostJson($this->probase_url_market . Config::UPDATE_PIN, $payload, $this->msisdn, $this->log);

                        if (!empty($result['response'])) {
                            if (!empty($result['response']['AUTHENTICATION']) &&
                                    $result['response']['AUTHENTICATION']["status"] === Config::OPERATION_SUCCESS &&
                                    $result['response']['AUTHENTICATION']["data"]['account_status'] === Config::ACC_ACTIVE_STATUS) {
                                $_SESSION['menu-selection'] = "MAIN";
                                $this->body = "PIN was successfuly updated." . SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
                                $this->gw_response = $this->get_response_array();
                            } else {
                                $_SESSION['menu-selection'] = " ";
                                $this->end_of_session = TRUE;
                                $this->body = "PIN could not be updated. Please try again";
                                $this->gw_response = $this->get_response_array();
                            }
                        } else {
                            $_SESSION['menu-selection'] = "";
                            $this->end_of_session = TRUE;
                            $this->body = "PIN could not be updated." . Config::SYSTEM_BUSY_MESSAGE;
                            $this->gw_response = $this->get_response_array();
                        }
                    } else {
                        $_SESSION['menu-selection'] = "CONFIRM_NEW_PIN";
                        $this->body = "The two PINs do not match." . $this->menus['CONFIRM_NEW_PIN'];
                        $this->gw_response = $this->get_response_array();
                    }
                } else {
                    $_SESSION['menu-selection'] = "CONFIRM_NEW_PIN";
                    $this->body = SharedUtils::strReplace("{val}", "New PIN confirmation ", Config::INVALID_STR) . $this->menus['CONFIRM_NEW_PIN'];
                    $this->gw_response = $this->get_response_array();
                }
                break;
        }
        return $this->gw_response;
    }

    private function main() {
        switch ($this->body) {
            case "1":
                $_SESSION['menu-selection'] = "MAKE_SALE_AMOUNT";
                $this->body = $this->menus['MAKE_SALE_AMOUNT'];
                $this->gw_response = $this->get_response_array();
                break;
            case "2":
                $_SESSION['menu-selection'] = "ORDER_GOODS";
                $this->body = $this->menus['ORDER_GOODS'];
                $this->gw_response = $this->get_response_array();
                break;
            case "3":
                //Pay market fees
                $stand_menu = $this->formatStands($_SESSION['stands'], false, false);
                if (strlen($stand_menu) > 0) {
                    $_SESSION['menu-selection'] = "MARKET_FEES";
                    $this->body = SharedUtils::strReplace("{stands}", $stand_menu, $this->menus['MARKET_FEES']);
                    $this->gw_response = $this->get_response_array();
                } else {
                    $this->end_of_session = TRUE;
                    $_SESSION['menu-selection'] = "";
                    $this->body = Config::SYSTEM_BUSY_MESSAGE;
                    $this->gw_response = $this->get_response_array();
                }
                break;
            case "4":
                //Check Sales. We try to pull the sales first before we present menu to customer
                $url = Config::API_URL . "summary_transactions";
                $payload = ["seller_mobile_number" => $this->msisdn];
                $result = SharedUtils::httpGet($url, $payload, $this->msisdn, $this->log);

                if (empty($result["marketeer"]['message'])) {
                    $_SESSION['menu-selection'] = "CHECK_SALES";
                    $_SESSION["sales"] = $result; //Just in case marketeer does not select and option but submits
                    $this->body = SharedUtils::strReplace("{today}", $result["today"]['num_of_sales'] . ",K" . trim(str_replace("ZMW", "", $result["today"]['revenue'])), $this->menus['CHECK_SALES']);
                    $this->body = SharedUtils::strReplace("{weekly}", $result["week"]['num_of_sales'] . ",K" . trim(str_replace("ZMW", "", $result["week"]['revenue'])), $this->body);
                    $this->body = SharedUtils::strReplace("{monthly}", $result["month"]['num_of_sales'] . ",K" . trim(str_replace("ZMW", "", $result["month"]['revenue'])), $this->body);
                    $this->gw_response = $this->get_response_array();
                } else {
                    $_SESSION['menu-selection'] = "CHECK_SALES_NO_SALES";
                    $this->body = $this->menus['CHECK_SALES_NO_SALES'];
                    $this->gw_response = $this->get_response_array();
                }
                break;
            case "5":
                //Change pin
                //We just reuse the OTP menu since its doing the same thing as 
                $_SESSION['menu-selection'] = "NEW_PIN";
                $this->body = $this->menus['NEW_PIN'];
                $this->gw_response = $this->get_response_array();
                return $this->gw_response;
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

    private function checkSales() {
        switch ($this->body) {
            case "#":
                $_SESSION['menu-selection'] = "MAIN";
                $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
                $this->gw_response = $this->get_response_array();
                break;
            default :
                $_SESSION['menu-selection'] = "CHECK_SALES";
                $this->body = SharedUtils::strReplace("{today}", $_SESSION["sales"]["today"]['num_of_sales'] . ",K" . trim(str_replace("ZMW", "", $_SESSION["sales"]["today"]['revenue'])), $this->menus['CHECK_SALES']);
                $this->body = SharedUtils::strReplace("{weekly}", $_SESSION["sales"]["week"]['num_of_sales'] . ",K" . trim(str_replace("ZMW", "", $_SESSION["sales"]["week"]['revenue'])), $this->body);
                $this->body = "Invalid selection." . SharedUtils::strReplace("{monthly}", $_SESSION["sales"]["month"]['num_of_sales'] . ",K" . trim(str_replace("ZMW", "", $_SESSION["sales"]["month"]['revenue'])), $this->body);
                $this->gw_response = $this->get_response_array();
                break;
        }
        return $this->gw_response;
    }

    private function checkSalesNoSales() {
        switch ($this->body) {
            case "#":
                //The user selected to go to main page
                $_SESSION['menu-selection'] = "MAIN";
                $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
                $this->gw_response = $this->get_response_array();
                break;
            default :
                $_SESSION['menu-selection'] = "CHECK_SALES_NO_SALES";
                $this->body = "Invalid selection.".$this->menus['CHECK_SALES_NO_SALES'];
                $this->gw_response = $this->get_response_array();
                break;
        }
        return $this->gw_response;
    }

    private function noScheduledRoutes() {
        switch ($this->body) {
            case "00":
                $_SESSION['menu-selection'] = "TRAVEL_DATE";
                $this->body = $this->menus['TRAVEL_DATE'];
                $this->gw_response = $this->get_response_array();
                break;
            case "#":
                $_SESSION['menu-selection'] = "LOGIN";
                $this->body = $this->menus['LOGIN'];
                $this->gw_response = $this->get_response_array();
                break;
            default:
                $_SESSION['menu-selection'] = "NO_SCHEDULED_ROUTES";
                $this->body = "Invalid selection." . str_replace("{date}", $_SESSION["travel_date"], $this->menus['NO_SCHEDULED_ROUTES']);
                $this->gw_response = $this->get_response_array();
                break;
        }
        return $this->gw_response;
    }

    private function travelDates() {
        switch ($this->body) {
            case "1":
            case "2":
                $format = \DateTime::createFromFormat('d-m-Y', date('d-m-Y', strtotime(date("d-m-Y") . ' +1 day')));
                $tommorow = $format->format('d/m/Y');
                // $_SESSION["travel_date"] = "27/01/2020"; // SHOULD BE REMOVED. FOR TESTING ONLY!!
                $_SESSION["travel_date"] = ($this->body == "1") ? date("d/m/Y") : $tommorow;
                $_SESSION['menu-selection'] = "BUS_SCHEDULES";
                //$payload = SharedUtils::BuildProbaseMarketRequest($this->msisdn, "", $this->body, "", "", $_SESSION['start_route'], $_SESSION['end_route'], "27/01/2020");
                $payload = SharedUtils::BuildProbaseMarketRequest($this->msisdn, "", $this->body, "", "", $_SESSION['start_route'], $_SESSION['end_route'], $_SESSION["travel_date"]);
                $result = SharedUtils::httpPostJson($this->probase_url_travel . Config::SCHEDULED_ROUTES, $payload, $this->msisdn, $this->log);
                if (!empty($result) && sizeof($result) > 0) {
                    $routes_menu = $this->formatBusSchedules($result, false, false);
                    if (strlen($routes_menu) > 0) {
                        $_SESSION['menu-selection'] = "BUS_SCHEDULES";
                        $this->body = SharedUtils::strReplace("{buses}", $routes_menu, $this->menus['BUS_SCHEDULES']);
                        $this->body = str_replace("{travel_date}", $_SESSION["travel_date"], $this->body);
                        $this->gw_response = $this->get_response_array();
                    } else {
                        $this->end_of_session = TRUE;
                        $_SESSION['menu-selection'] = "";
                        $this->body = Config::SYSTEM_BUSY_MESSAGE;
                        $this->gw_response = $this->get_response_array();
                    }
                } else {
                    $_SESSION['menu-selection'] = "NO_SCHEDULED_ROUTES";
                    $this->body = str_replace("{date}", $_SESSION["travel_date"], $this->menus['NO_SCHEDULED_ROUTES']);
                    $this->gw_response = $this->get_response_array();
                }
                break;
            case "00":
                $payload = [];
                $result = SharedUtils::httpGet($this->probase_url_travel . Config::ROUTES, $payload, $this->msisdn, $this->log);
                if (!empty($result['travel_routes']) && sizeof($result['travel_routes']) > 0) {
                    $routes_menu = $this->formatRoutes($result['travel_routes'], false, false);
                    if (strlen($routes_menu) > 0) {
                        $_SESSION['menu-selection'] = "BUS_ROUTES";
                        $this->body = SharedUtils::strReplace("{routes}", $routes_menu, $this->menus['BUS_ROUTES']);
                        $this->gw_response = $this->get_response_array();
                    } else {
                        $this->end_of_session = TRUE;
                        $_SESSION['menu-selection'] = "";
                        $this->body = Config::SYSTEM_BUSY_MESSAGE;
                        $this->gw_response = $this->get_response_array();
                    }
                }
                break;
            case "#":
                $_SESSION['menu-selection'] = "LOGIN";
                $this->body = $this->menus['LOGIN'];
                $this->gw_response = $this->get_response_array();
                break;
            default:
                $_SESSION['menu-selection'] = "TRAVEL_DATE";
                $this->body = "Invalid selection." . $this->menus['TRAVEL_DATE'];
                $this->gw_response = $this->get_response_array();
                break;
        }
        return $this->gw_response;
    }

    private function busRoutes() {
        if ((isset($_SESSION['current_routes']) && isset($_SESSION['current_routes'][$this->body]))) {
            //The user selected a valid option.
            $route_details = explode(",", $_SESSION['current_routes'][$this->body]);
            $_SESSION['start_route'] = $route_details[1]; //We have to keep the selected start route.
            $_SESSION['end_route'] = $route_details[2]; //We have to keep the selected end route.
            $_SESSION['route_code'] = $route_details[3]; //We have to keep the selected route_code.
            $_SESSION['route_name'] = $route_details[4]; //We have to keep the selected route_name.
            $_SESSION['menu-selection'] = "TRAVEL_DATE";
            $this->body = $this->menus['TRAVEL_DATE'];
            $this->gw_response = $this->get_response_array();
        } elseif ($this->body == "00" && $_SESSION['routes_navigator']['last_first_opt'] == 0) {
            //The user selected to go to login page
            $_SESSION['menu-selection'] = "LOGIN";
            $this->body = $this->menus['LOGIN'];
            $this->gw_response = $this->get_response_array();
        } elseif ($this->body == "00" && $_SESSION['routes_navigator']['last_first_opt'] > 0) {
            //Previous menu...
            $routes_menu = $this->formatRoutes($_SESSION['routes_navigator']['routes'], false, true);
            if (strlen($routes_menu) > 0) {
                $_SESSION['menu-selection'] = "BUS_ROUTES";
                $this->body = SharedUtils::strReplace("{routes}", $routes_menu, $this->menus['BUS_ROUTES']);
                $this->gw_response = $this->get_response_array();
            } else {
                $this->end_of_session = TRUE;
                $_SESSION['menu-selection'] = "";
                $this->body = Config::SYSTEM_BUSY_MESSAGE;
                $this->gw_response = $this->get_response_array();
            }
        } elseif ($this->body == "99" && ($_SESSION['routes_navigator']['last_last_opt'] < count($_SESSION['routes_navigator']['routes']))) {
            //More routes...
            $routes_menu = $this->formatRoutes($_SESSION['routes_navigator']['routes'], true, false);
            if (strlen($routes_menu) > 0) {
                $_SESSION['menu-selection'] = "BUS_ROUTES";
                $this->body = SharedUtils::strReplace("{routes}", $routes_menu, $this->menus['BUS_ROUTES']);
                $this->gw_response = $this->get_response_array();
            } else {
                $this->end_of_session = TRUE;
                $_SESSION['menu-selection'] = "";
                $this->body = Config::SYSTEM_BUSY_MESSAGE;
                $this->gw_response = $this->get_response_array();
            }
        } else {
            $payload = [];
            $result = SharedUtils::httpGet($this->probase_url_travel . Config::ROUTES, $payload, $this->msisdn, $this->log);
            if (!empty($result['travel_routes']) && sizeof($result['travel_routes']) > 0) {
                $routes_menu = $this->formatRoutes($result['travel_routes'], false, false);
                if (strlen($routes_menu) > 0) {
                    $_SESSION['menu-selection'] = "BUS_ROUTES";
                    $this->body = "Invalid selection." . SharedUtils::strReplace("{routes}", $routes_menu, $this->menus['BUS_ROUTES']);
                    $this->gw_response = $this->get_response_array();
                } else {
                    $this->end_of_session = TRUE;
                    $_SESSION['menu-selection'] = "";
                    $this->body = Config::SYSTEM_BUSY_MESSAGE;
                    $this->gw_response = $this->get_response_array();
                }
            }
        }

        return $this->gw_response;
    }

    private function marketFees() {
        if ((isset($_SESSION['current_stands']) && isset($_SESSION['current_stands'][$this->body]))) {
            //The user selected a valid option.
            $stand_details = explode(",", $_SESSION['current_stands'][$this->body]);
            $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, '| SELECTED STAND IS:::' . print_r($stand_details, true));
            $_SESSION['stand_number'] = $stand_details[0]; //We have to keep the selected stand.
            $_SESSION['stand_price'] = $stand_details[1]; //We have to keep the selected stand price.
            $this->body = SharedUtils::strReplace("{amount}", $_SESSION['stand_price'], $this->menus['MARKET_FEES_CONFIRM']);
            $this->body = SharedUtils::strReplace("{stand}", $_SESSION['stand_number'], $this->body);
            $_SESSION['menu-selection'] = "MARKET_FEES_CONFIRM";
            $this->gw_response = $this->get_response_array();
        } elseif ($this->body == "00" && $_SESSION['stand_navigator']['last_first_opt'] == 0) {
            //The user selected to go to main page
            $_SESSION['menu-selection'] = "MAIN";
            $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
            $this->gw_response = $this->get_response_array();
        } elseif ($this->body == "00" && $_SESSION['stand_navigator']['last_first_opt'] > 0) {
            //Previous menu...
            $stand_menu = $this->formatStands($_SESSION['stand_navigator']['stands'], false, true);
            if (strlen($stand_menu) > 0) {
                $_SESSION['menu-selection'] = "MARKET_FEES";
                $this->body = SharedUtils::strReplace("{stands}", $stand_menu, $this->menus['MARKET_FEES']);
                $this->gw_response = $this->get_response_array();
            } else {
                $this->end_of_session = TRUE;
                $_SESSION['menu-selection'] = "";
                $this->body = Config::SYSTEM_BUSY_MESSAGE;
                $this->gw_response = $this->get_response_array();
            }
        } elseif ($this->body == "99" && ($_SESSION['stand_navigator']['last_last_opt'] < count($_SESSION['stand_navigator']['stands']))) {
            //More routes...
            $stand_menu = $this->formatStands($_SESSION['stand_navigator']['stands'], true, false);
            if (strlen($stand_menu) > 0) {
                $_SESSION['menu-selection'] = "MARKET_FEES";
                $this->body = SharedUtils::strReplace("{stands}", $stand_menu, $this->menus['MARKET_FEES']);
                $this->gw_response = $this->get_response_array();
            } else {
                $this->end_of_session = TRUE;
                $_SESSION['menu-selection'] = "";
                $this->body = Config::SYSTEM_BUSY_MESSAGE;
                $this->gw_response = $this->get_response_array();
            }
        } else {
            $stand_menu = $this->formatStands($_SESSION['stands'], false, false);
            if (strlen($stand_menu) > 0) {
                $_SESSION['menu-selection'] = "MARKET_FEES";
                $this->body = "Invalid selection." . SharedUtils::strReplace("{stands}", $stand_menu, $this->menus['MARKET_FEES']);
                $this->gw_response = $this->get_response_array();
            } else {
                $this->end_of_session = TRUE;
                $_SESSION['menu-selection'] = "";
                $this->body = Config::SYSTEM_BUSY_MESSAGE;
                $this->gw_response = $this->get_response_array();
            }
        }

        return $this->gw_response;
    }

    /**
     * 
     * @return type array
     */
    private function marketFeesConfirm() {
        switch ($this->body) {
            case "1":
                //Ok we push the request to the api
                $payload = SharedUtils::buildPushTransactionRequest($_SESSION['trader_details']['uuid'], $_SESSION['stand_price'], $this->msisdn, $_SESSION['trader_details']['first_name'], $_SESSION['trader_details']['last_name'], $_SESSION['buyer_msisdn'], Config::PAY_MARKET_FEE, "", "", "", "", "", "", "", "", $_SESSION['stand_number']);
                $result = SharedUtils::httpPostJson(Config::API_URL . "transactions", $payload, $this->msisdn, $this->log);
                if ($result['error'] == FALSE) {
                    $_SESSION['menu-selection'] = "TRX_PROCESSING";
                    $this->body = $this->menus['TRX_PROCESSING'];
                    $this->gw_response = $this->get_response_array();
                } else {
                    $_SESSION['menu-selection'] = "";
                    $this->end_of_session = TRUE;
                    $this->body = "Error occured while processing request. Please try again";
                    $this->gw_response = $this->get_response_array();
                }
                break;
            case "00":
                //Go to previous menu
                $stand_menu = $this->formatStands($_SESSION['stands'], false, false);
                if (strlen($stand_menu) > 0) {
                    $_SESSION['menu-selection'] = "MARKET_FEES";
                    $this->body = SharedUtils::strReplace("{stands}", $stand_menu, $this->menus['MARKET_FEES']);
                    $this->gw_response = $this->get_response_array();
                } else {
                    $this->end_of_session = TRUE;
                    $_SESSION['menu-selection'] = "";
                    $this->body = Config::SYSTEM_BUSY_MESSAGE;
                    $this->gw_response = $this->get_response_array();
                }
                break;
            case "#":
                //The user selected to go to main page
                $_SESSION['menu-selection'] = "MAIN";
                $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
                $this->gw_response = $this->get_response_array();
                break;
            default :
                //The user selected a valid option.
                $this->body = SharedUtils::strReplace("{amount}", $_SESSION['stand_price'], $this->menus['MARKET_FEES_CONFIRM']);
                $this->body = "Invalid selection." . SharedUtils::strReplace("{stand}", $_SESSION['stand_number'], $this->body);
                $_SESSION['menu-selection'] = "MARKET_FEES_CONFIRM";
                $this->gw_response = $this->get_response_array();
                break;
        }
        return $this->gw_response;
    }

    /**
     * Formats bus routes with next and previous selection options
     * @param type $routes
     * @param type $is_next
     * @param type $is_previous
     * @return string
     */
    private function formatRoutes($routes, $is_next = false, $is_previous = false) {
        $return_menu = "";
        $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, '| Collected routes are: ' . print_r($routes, true));
        $counter = 0;
        if (is_array($routes) && count($routes) > 0) {
            if (!isset($_SESSION['routes_navigator'])) {
                $_SESSION['routes_navigator']['routes'] = $routes;
                $_SESSION['routes_navigator']['page_id'] = 1;
                $_SESSION['routes_navigator']['max_entries_per_page'] = Config::MAX_OPTIONS_PER_PAGE;
                $_SESSION['routes_navigator']['max_entries'] = count($routes);
            } else {
                if ($is_next == true && $is_previous == false) {
                    ++$_SESSION['routes_navigator']['page_id'];
                } elseif ($is_previous == true && $is_next == false) {
                    --$_SESSION['routes_navigator']['page_id'];
                }
            }
            $_SESSION['routes_navigator']['last_first_opt'] = ($_SESSION['routes_navigator']['page_id'] - 1) * Config::MAX_OPTIONS_PER_PAGE;
            $_SESSION['routes_navigator']['last_last_opt'] = ($_SESSION['routes_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE) - 1;

            $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, "| Maximum number of menu options is : " . $_SESSION['routes_navigator']['max_entries']);
            $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, "| Array first selection on index : " . $_SESSION['routes_navigator']['last_first_opt']);
            $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, "| Array last selection on index : " . $_SESSION['routes_navigator']['last_last_opt']);

            unset($_SESSION['current_routes']); //Clean up the session subjects...IMPORNTANT!
            foreach ($routes as $key => $value) {
                if ($key >= $_SESSION['routes_navigator']['last_first_opt'] && $key <= $_SESSION['routes_navigator']['last_last_opt']) {
                    $return_menu .= "\n" . ($key + 1) . "." . $value['route_name'];
                    $_SESSION['current_routes'][($key + 1)] = $value['id'] . ',' . $value['start_route'] . ',' . $value['end_route'] . ',' . $value['route_code'] . "," . $value['route_name']; //Kepp the details in the session values.
                    $counter = $counter + 1;
                }
            }
            if ($_SESSION['routes_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE <= Config::MAX_OPTIONS_PER_PAGE && count($routes) <= Config::MAX_OPTIONS_PER_PAGE) {
                $return_menu .= "\n00.Back";
            } elseif ($_SESSION['routes_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE <= Config::MAX_OPTIONS_PER_PAGE && count($routes) > Config::MAX_OPTIONS_PER_PAGE) {
                $return_menu .= "\n00.Back";
                $return_menu .= "\n99.More";
            } elseif ($_SESSION['routes_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE > Config::MAX_OPTIONS_PER_PAGE && ($_SESSION['routes_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE < count($routes))) {
                $return_menu .= "\n00.Previous";
                $return_menu .= "\n99.More";
            } elseif ($_SESSION['routes_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE > Config::MAX_OPTIONS_PER_PAGE && ($_SESSION['routes_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE >= count($routes))) {
                $return_menu .= "\n00.Previous";
            }
        }
        $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, "| Prepared menus being returned are : " . $return_menu);
        return $return_menu;
    }

    /**
     * Formats the marketeer stands with next and previous selection options
     * @param type $routes
     * @param type $is_next
     * @param type $is_previous
     * @return string
     */
    private function formatStands($stands, $is_next = false, $is_previous = false) {
        $return_menu = "";
        $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, '| Collected stands are: ' . print_r($stands, true));
        $counter = 0;
        if (is_array($stands) && count($stands) > 0) {
            if (!isset($_SESSION['stand_navigator'])) {
                $_SESSION['stand_navigator']['stands'] = $routes;
                $_SESSION['stand_navigator']['page_id'] = 1;
                $_SESSION['stand_navigator']['max_entries_per_page'] = Config::MAX_OPTIONS_PER_PAGE;
                $_SESSION['stand_navigator']['max_entries'] = count($stands);
            } else {
                if ($is_next == true && $is_previous == false) {
                    ++$_SESSION['stand_navigator']['page_id'];
                } elseif ($is_previous == true && $is_next == false) {
                    --$_SESSION['stand_navigator']['page_id'];
                }
            }
            $_SESSION['stand_navigator']['last_first_opt'] = ($_SESSION['stand_navigator']['page_id'] - 1) * Config::MAX_OPTIONS_PER_PAGE;
            $_SESSION['stand_navigator']['last_last_opt'] = ($_SESSION['stand_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE) - 1;

            $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, "| Maximum number of menu options is : " . $_SESSION['stand_navigator']['max_entries']);
            $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, "| Array first selection on index : " . $_SESSION['stand_navigator']['last_first_opt']);
            $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, "| Array last selection on index : " . $_SESSION['stand_navigator']['last_last_opt']);

            unset($_SESSION['current_stands']); //Clean up the session subjects...IMPORNTANT!
            foreach ($stands as $key => $value) {
                if ($key >= $_SESSION['stand_navigator']['last_first_opt'] && $key <= $_SESSION['stand_navigator']['last_last_opt']) {
                    $return_menu .= "\n" . ($key + 1) . ".Stand " . $value['stand_number'] . " - K" . $value['stand_price'];
                    $_SESSION['current_stands'][($key + 1)] = $value['stand_number'] . ',' . $value['stand_price']; //Kepp the details in the session values.
                    $counter = $counter + 1;
                }
            }
            if ($_SESSION['stand_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE <= Config::MAX_OPTIONS_PER_PAGE && count($stands) <= Config::MAX_OPTIONS_PER_PAGE) {
                $return_menu .= "\n00.Back";
            } elseif ($_SESSION['stand_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE <= Config::MAX_OPTIONS_PER_PAGE && count($stands) > Config::MAX_OPTIONS_PER_PAGE) {
                $return_menu .= "\n00.Back";
                $return_menu .= "\n99.More";
            } elseif ($_SESSION['stand_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE > Config::MAX_OPTIONS_PER_PAGE && ($_SESSION['stand_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE < count($stands))) {
                $return_menu .= "\n00.Previous";
                $return_menu .= "\n99.More";
            } elseif ($_SESSION['stand_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE > Config::MAX_OPTIONS_PER_PAGE && ($_SESSION['stand_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE >= count($stands))) {
                $return_menu .= "\n00.Previous";
            }
        }
        $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, "| Prepared menus being returned are : " . $return_menu);
        return $return_menu;
    }

    private function busSchedules() {
        if ((isset($_SESSION['current_routes']) && isset($_SESSION['current_routes'][$this->body]))) {
            //The user selected a valid option.
            $schedule_details = explode(",", $_SESSION['current_routes'][$this->body]);
            $_SESSION['bus_schedule_id'] = $schedule_details[0]; //We have to keep the selected schedule.
            $_SESSION['bus_str'] = $schedule_details[2] . "-" . $_SESSION['route_name'] . "," . $_SESSION["travel_date"] . " " . $schedule_details[1] . "hrs - K" . $schedule_details[3]; //We have to keep the selected schedule.
            $_SESSION['bus_fare'] = $schedule_details[3]; //We have to keep the selected schedule.
            $_SESSION['menu-selection'] = "PASSENGER_NRC";
            $this->body = $this->menus['PASSENGER_NRC'];
            $this->gw_response = $this->get_response_array();
        } elseif ($this->body == "00" && $_SESSION['schedules_navigator']['last_first_opt'] == 0) {
            $_SESSION['menu-selection'] = "TRAVEL_DATE";
            $this->body = $this->menus['TRAVEL_DATE'];
            $this->gw_response = $this->get_response_array();
        } elseif ($this->body == "00" && $_SESSION['schedules_navigator']['last_first_opt'] > 0) {
            //Previous menu...
            $routes_menu = $this->formatBusSchedules($_SESSION['schedules_navigator']['schedules'], false, true);
            if (strlen($routes_menu) > 0) {
                $_SESSION['menu-selection'] = "BUS_SCHEDULES";
                $this->body = SharedUtils::strReplace("{buses}", $routes_menu, $this->menus['BUS_SCHEDULES']);
                $this->body = str_replace("{travel_date}", $_SESSION["travel_date"], $this->body);
                $this->gw_response = $this->get_response_array();
            } else {
                $this->end_of_session = TRUE;
                $_SESSION['menu-selection'] = "";
                $this->body = Config::SYSTEM_BUSY_MESSAGE;
                $this->gw_response = $this->get_response_array();
            }
        } elseif ($this->body == "99" && ($_SESSION['schedules_navigator']['last_last_opt'] < count($_SESSION['schedules_navigator']['routes']))) {
            //More routes...
            $routes_menu = $this->formatRoutes($_SESSION['schedules_navigator']['schedules'], true, false);
            if (strlen($routes_menu) > 0) {
                $_SESSION['menu-selection'] = "BUS_SCHEDULES";
                $this->body = SharedUtils::strReplace("{buses}", $routes_menu, $this->menus['BUS_SCHEDULES']);
                $this->body = str_replace("{travel_date}", $_SESSION["travel_date"], $this->body);
                $this->gw_response = $this->get_response_array();
            } else {
                $this->end_of_session = TRUE;
                $_SESSION['menu-selection'] = "";
                $this->body = Config::SYSTEM_BUSY_MESSAGE;
                $this->gw_response = $this->get_response_array();
            }
        } else {
            $format = \DateTime::createFromFormat('d-m-Y', date('d-m-Y', strtotime(date("d-m-Y") . ' +1 day')));
            $tommorow = $format->format('d/m/Y');
            $_SESSION["travel_date"] = ($this->body == "1") ? date("d/m/Y") : $tommorow;
            $_SESSION['menu-selection'] = "BUS_SCHEDULES";
            $payload = SharedUtils::BuildProbaseMarketRequest($this->msisdn, "", $this->body, "", "", $_SESSION['start_route'], $_SESSION['end_route'], $_SESSION["travel_date"]);
            $result = SharedUtils::httpPostJson($this->probase_url_travel . Config::SCHEDULED_ROUTES, $payload, $this->msisdn, $this->log);
            if (!empty($result) && sizeof($result) > 0) {
                $routes_menu = $this->formatBusSchedules($result, false, false);
                if (strlen($routes_menu) > 0) {
                    $_SESSION['menu-selection'] = "BUS_SCHEDULES";
                    $this->body = SharedUtils::strReplace("{buses}", $routes_menu, $this->menus['BUS_SCHEDULES']);
                    $this->body = str_replace("{travel_date}", $_SESSION["travel_date"], $this->body);
                    $this->gw_response = $this->get_response_array();
                } else {
                    $this->end_of_session = TRUE;
                    $_SESSION['menu-selection'] = "";
                    $this->body = Config::SYSTEM_BUSY_MESSAGE;
                    $this->gw_response = $this->get_response_array();
                }
            } else {
                $_SESSION['menu-selection'] = "NO_SCHEDULED_ROUTES";
                $this->body = str_replace("{date}", $_SESSION["travel_date"], $this->menus['NO_SCHEDULED_ROUTES']);
                $this->gw_response = $this->get_response_array();
            }
        }

        return $this->gw_response;
    }

    private function passengerNrc() {
        switch ($this->body) {
            case "#":
                $_SESSION['menu-selection'] = "LOGIN";
                $this->body = $this->menus['LOGIN'];
                $this->gw_response = $this->get_response_array();
                break;
            case "00":
                $format = \DateTime::createFromFormat('d-m-Y', date('d-m-Y', strtotime(date("d-m-Y") . ' +1 day')));
                $tommorow = $format->format('d/m/Y');
                $_SESSION["travel_date"] = ($this->body == "1") ? date("d/m/Y") : $tommorow;
                $_SESSION['menu-selection'] = "BUS_SCHEDULES";
                $payload = SharedUtils::BuildProbaseMarketRequest($this->msisdn, "", $this->body, "", "", $_SESSION['start_route'], $_SESSION['end_route'], $_SESSION["travel_date"]);
                $result = SharedUtils::httpPostJson($this->probase_url_travel . Config::SCHEDULED_ROUTES, $payload, $this->msisdn, $this->log);
                if (!empty($result) && sizeof($result) > 0) {
                    $routes_menu = $this->formatBusSchedules($result, false, false);
                    if (strlen($routes_menu) > 0) {
                        $_SESSION['menu-selection'] = "BUS_SCHEDULES";
                        $this->body = SharedUtils::strReplace("{buses}", $routes_menu, $this->menus['BUS_SCHEDULES']);
                        $this->body = str_replace("{travel_date}", $_SESSION["travel_date"], $this->body);
                        $this->gw_response = $this->get_response_array();
                    } else {
                        $this->end_of_session = TRUE;
                        $_SESSION['menu-selection'] = "";
                        $this->body = Config::SYSTEM_BUSY_MESSAGE;
                        $this->gw_response = $this->get_response_array();
                    }
                } else {
                    $_SESSION['menu-selection'] = "NO_SCHEDULED_ROUTES";
                    $this->body = str_replace("{date}", $_SESSION["travel_date"], $this->menus['NO_SCHEDULED_ROUTES']);
                    $this->gw_response = $this->get_response_array();
                }
                break;
            default :
                if (strlen($this->body) >= 10) {
                    $nrc_array = explode("/", $this->body);
                    if (!empty($nrc_array) &&
                            strlen($nrc_array[0]) == 6 &&
                            strlen($nrc_array[1]) >= 1 &&
                            strlen($nrc_array[2]) == 1) {
                        $_SESSION['NRC'] = $this->body;
                        $_SESSION['menu-selection'] = "PASSENGER_FNAME";
                        $this->body = $this->menus['PASSENGER_FNAME'];
                        $this->gw_response = $this->get_response_array();
                    } else {
                        $_SESSION['menu-selection'] = "PASSENGER_NRC";
                        $this->body = "Invalid NRC." . $this->menus['PASSENGER_NRC'];
                        $this->gw_response = $this->get_response_array();
                    }
                } else {
                    $_SESSION['menu-selection'] = "PASSENGER_NRC";
                    $this->body = "Invalid NRC." . $this->menus['PASSENGER_NRC'];
                    $this->gw_response = $this->get_response_array();
                }
                break;
        }
        return $this->gw_response;
    }

    private function passengerFirstname() {
        switch ($this->body) {
            case "#":
                $_SESSION['menu-selection'] = "LOGIN";
                $this->body = $this->menus['LOGIN'];
                $this->gw_response = $this->get_response_array();
                break;
            case "00":
                $_SESSION['menu-selection'] = "PASSENGER_NRC";
                $this->body = $this->menus['PASSENGER_NRC'];
                $this->gw_response = $this->get_response_array();
                break;
            default :
                if (!is_numeric($this->body)) {
                    $_SESSION['FIRSTNAME'] = $this->body;
                    $_SESSION['menu-selection'] = "PASSENGER_LNAME";
                    $this->body = $this->menus['PASSENGER_LNAME'];
                    $this->gw_response = $this->get_response_array();
                } else {
                    $_SESSION['menu-selection'] = "PASSENGER_FNAME";
                    $this->body = "First name cannot be a number." . $this->menus['PASSENGER_FNAME'];
                    $this->gw_response = $this->get_response_array();
                }
                break;
        }
        return $this->gw_response;
    }

    private function passengerLastname() {
        switch ($this->body) {
            case "#":
                $_SESSION['menu-selection'] = "LOGIN";
                $this->body = $this->menus['LOGIN'];
                $this->gw_response = $this->get_response_array();
                break;
            case "00":
                $_SESSION['menu-selection'] = "PASSENGER_FNAME";
                $this->body = $this->menus['PASSENGER_FNAME'];
                $this->gw_response = $this->get_response_array();
                break;
            default :
                if (!is_numeric($this->body)) {
                    $_SESSION['LASTNAME'] = $this->body;
                    $_SESSION['menu-selection'] = "PASSENGER_CONFIRM";
                    $this->body = str_replace("{names}", $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'], $this->menus['PASSENGER_CONFIRM']);
                    $this->body = str_replace("{bus_str}", $_SESSION['bus_str'], $this->body);
                    $this->gw_response = $this->get_response_array();
                } else {
                    $_SESSION['menu-selection'] = "PASSENGER_LNAME";
                    $this->body = "Last name cannot be a number." . $this->menus['PASSENGER_LNAME'];
                    $this->gw_response = $this->get_response_array();
                }
                break;
        }
        return $this->gw_response;
    }

    private function passengerConfirm() {
        switch ($this->body) {
            case "#":
                $_SESSION['menu-selection'] = "LOGIN";
                $this->body = $this->menus['LOGIN'];
                $this->gw_response = $this->get_response_array();
                break;
            case "00":
                $_SESSION['menu-selection'] = "PASSENGER_LNAME";
                $this->body = $this->menus['PASSENGER_LNAME'];
                $this->gw_response = $this->get_response_array();
                break;
            case "1":
                //Lets push the transaction to the api
                $payload = SharedUtils::buildPushTransactionRequest("", $_SESSION['bus_fare'], "", "", "", $this->msisdn, Config::TICKET_PURCHASE, "", $_SESSION['FIRSTNAME'], $_SESSION['LASTNAME'], "", $_SESSION['route_code'], $_SESSION['NRC'], $_SESSION["travel_date"], $_SESSION['bus_schedule_id'], "");
                $result = SharedUtils::httpPostJson(Config::API_URL . "transactions", $payload, $this->msisdn, $this->log);
                if ($result['error'] == FALSE) {
                    $_SESSION['menu-selection'] = "TRX_PROCESSING1";
                    $this->body = $this->menus['TRX_PROCESSING1'];
                    $this->gw_response = $this->get_response_array();
                } else {
                    $_SESSION['menu-selection'] = "";
                    $this->end_of_session = TRUE;
                    $this->body = "Error occured while processing request. Please try again";
                    $this->gw_response = $this->get_response_array();
                }
                break;
            default :
                $_SESSION['menu-selection'] = "PASSENGER_CONFIRM";
                $this->body = str_replace("{names}", $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME'], $this->menus['PASSENGER_CONFIRM']);
                $this->body = "Invalid selection." . str_replace("{bus_str}", $_SESSION['bus_str'], $this->body);
                $this->gw_response = $this->get_response_array();
                break;
        }
        return $this->gw_response;
    }

    private function formatBusSchedules($Schedules, $is_next = false, $is_previous = false) {
        $return_menu = "";
        $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, '| Collected Schdules are: ' . print_r($Schedules, true));
        $counter = 1;
        if (is_array($Schedules) && count($Schedules) > 0) {
            if (!isset($_SESSION['schedules_navigator'])) {
                $_SESSION['schedules_navigator']['schedules'] = $Schedules;
                $_SESSION['schedules_navigator']['page_id'] = 1;
                $_SESSION['schedules_navigator']['max_entries_per_page'] = Config::MAX_OPTIONS_PER_PAGE;
                $_SESSION['schedules_navigator']['max_entries'] = count($Schedules);
            } else {
                if ($is_next == true && $is_previous == false) {
                    ++$_SESSION['schedules_navigator']['page_id'];
                } elseif ($is_previous == true && $is_next == false) {
                    --$_SESSION['schedules_navigator']['page_id'];
                }
            }
            $_SESSION['schedules_navigator']['last_first_opt'] = ($_SESSION['schedules_navigator']['page_id'] - 1) * Config::MAX_OPTIONS_PER_PAGE;
            $_SESSION['schedules_navigator']['last_last_opt'] = ($_SESSION['schedules_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE) - 1;

            $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, "| Maximum number of menu options is : " . $_SESSION['schedules_navigator']['max_entries']);
            $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, "| Array first selection on index : " . $_SESSION['schedules_navigator']['last_first_opt']);
            $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, "| Array last selection on index : " . $_SESSION['schedules_navigator']['last_last_opt']);

            unset($_SESSION['current_routes']); //Clean up the session subjects...IMPORNTANT!
            foreach ($Schedules as $key => $value) {
                if ($key >= $_SESSION['schedules_navigator']['last_first_opt'] && $key <= $_SESSION['schedules_navigator']['last_last_opt']) {
                    if ($value['available_seats'] > 0) {
                        $return_menu .= "\n" . ($key + 1) . "." . $value['bus']['company'] . "-" . $value['departure_time'] . "hrs, K" . $value['fare'];
                        $_SESSION['current_routes'][($key + 1)] = $value['bus_schedule_id'] . ',' . $value['departure_time'] . ',' . $value['bus']['company'] . "," . $value['fare']; //Keep the details in the session values.
                        $counter = $counter + 1;
                    }
                }
            }
            if ($_SESSION['schedules_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE <= Config::MAX_OPTIONS_PER_PAGE && count($Schedules) <= Config::MAX_OPTIONS_PER_PAGE) {
                $return_menu .= "\n00.Back";
            } elseif ($_SESSION['schedules_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE <= Config::MAX_OPTIONS_PER_PAGE && count($Schedules) > Config::MAX_OPTIONS_PER_PAGE) {
                $return_menu .= "\n00.Back";
                $return_menu .= "\n99.More";
            } elseif ($_SESSION['schedules_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE > Config::MAX_OPTIONS_PER_PAGE && ($_SESSION['schedules_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE < count($Schedules))) {
                $return_menu .= "\n00.Previous";
                $return_menu .= "\n99.More";
            } elseif ($_SESSION['schedules_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE > Config::MAX_OPTIONS_PER_PAGE && ($_SESSION['schedules_navigator']['page_id'] * Config::MAX_OPTIONS_PER_PAGE >= count($Schedules))) {
                $return_menu .= "\n00.Previous";
            }
        }
        $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, "| Prepared menus being returned are : " . $return_menu);
        return $return_menu;
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

    //Transaction processing STARTS
    private function transactionProcessing1() {
        switch ($this->body) {
            case "1":
                $_SESSION['menu-selection'] = "LOGIN";
                $this->body = $this->menus['LOGIN'];
                $this->gw_response = $this->get_response_array();
                break;
            default:
                $_SESSION['menu-selection'] = "TRX_PROCESSING1";
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
                $_SESSION['menu-selection'] = "MAIN";
                $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
                $this->gw_response = $this->get_response_array();
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
                $_SESSION['menu-selection'] = "MAIN";
                $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
                $this->gw_response = $this->get_response_array();
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
                $payload = SharedUtils::buildPushTransactionRequest($_SESSION['trader_details']['uuid'], $_SESSION['Sale_amount'], $this->msisdn, $_SESSION['trader_details']['first_name'], $_SESSION['trader_details']['last_name'], $_SESSION['buyer_msisdn'], Config::MAKE_SALE, "", "", "", "", "", "", "", "", "");
                $result = SharedUtils::httpPostJson(Config::API_URL . "transactions", $payload, $this->msisdn, $this->log);
                if ($result['error'] == FALSE) {
                    $_SESSION['menu-selection'] = "TRX_PROCESSING";
                    $this->body = $this->menus['TRX_PROCESSING'];
                    $this->gw_response = $this->get_response_array();
                } else {
                    $_SESSION['menu-selection'] = "";
                    $this->end_of_session = TRUE;
                    $this->body = "Error occured while processing request. Please try again";
                    $this->gw_response = $this->get_response_array();
                }
                break;
            case "#":
                $_SESSION['menu-selection'] = "MAIN";
                $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
                $this->gw_response = $this->get_response_array();
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
                $_SESSION['menu-selection'] = "MAIN";
                $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
                $this->gw_response = $this->get_response_array();
                break;
            default:
                $payload = SharedUtils::BuildProbaseMarketRequest(Config::country_code . $this->body, "", "", "", "");
                $result = SharedUtils::httpPostJson($this->probase_url_market . Config::MARKETER_KYC, $payload, $this->msisdn, $this->log);
                if (SharedUtils::validateMsisdn($this->body, $this->log, $this->msisdn) && !empty($result['response'])) {
                    //Trader cannot buy from themselves
                    if ($this->msisdn != Config::country_code . $this->body) {
                        if (!empty($result['response']['QUERY']["data"]['account_status']) &&
                                ($result['response']['QUERY']["data"]['account_status'] == Config::ACC_ACTIVE_STATUS ||
                                $result['response']['QUERY']["data"]['account_status'] == Config::ACC_OTP_STATUS)) {
                            $_SESSION['seller_details'] = $result['response']['QUERY']["data"];
                            $_SESSION['seller_names'] = $result['response']['QUERY']["data"]['first_name'] . " "
                                    . $result['response']['QUERY']["data"]['last_name'];
                            $_SESSION['seller_mobile'] = $this->body;
                            $_SESSION['menu-selection'] = "ORDER_GOODS_AMOUNT";
                            $this->body = SharedUtils::strReplace("{trader}", $_SESSION['seller_names'], $this->menus['ORDER_GOODS_AMOUNT']);
                            $this->body = SharedUtils::strReplace("{mobile}", $_SESSION['seller_mobile'], $this->body);
                            $this->gw_response = $this->get_response_array();
                        } elseif (!empty($result['response']['QUERY']["data"]['account_status']) &&
                                $result['response']['QUERY']["data"]['account_status'] == Config::ACC_BLOCKED_STATUS) {
                            $this->end_of_session = TRUE;
                            $this->body = $this->menus['TRADER_BLOCKED1'];
                            $this->gw_response = $this->get_response_array();
                        } else {
                            $_SESSION['menu-selection'] = "ORDER_GOODS";
                            $this->body = SharedUtils::strReplace("{val}", "traders mobile number", Config::INVALID_STR) . "\n" . $this->menus['ORDER_GOODS'];
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
                $_SESSION['menu-selection'] = "MAIN";
                $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
                $this->gw_response = $this->get_response_array();
                break;
            case "0":
                $this->body = 2;
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
                $payload = SharedUtils::buildPushTransactionRequest($_SESSION['seller_details']['uuid'], $_SESSION['Sale_amount'], "26" . $_SESSION['seller_mobile'], $_SESSION['seller_details']['first_name'], $_SESSION['seller_details']['last_name'], $this->msisdn, Config::ORDER, $_SESSION['trader_details']['uuid'], $_SESSION['trader_details']['first_name'], $_SESSION['trader_details']['last_name'], "", "", "", "", "", "");
                $result = SharedUtils::httpPostJson(Config::API_URL . "transactions", $payload, $this->msisdn, $this->log);
                if ($result['error'] == FALSE) {
                    $_SESSION['menu-selection'] = "TRX_PROCESSING";
                    $this->body = $this->menus['TRX_PROCESSING'];
                    $this->gw_response = $this->get_response_array();
                } else {
                    $_SESSION['menu-selection'] = "";
                    $this->end_of_session = TRUE;
                    $this->body = "Error occured while processing request. Please try again";
                    $this->gw_response = $this->get_response_array();
                }
                break;
            case "#":
                $_SESSION['menu-selection'] = "MAIN";
                $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
                $this->gw_response = $this->get_response_array();
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
        switch ($this->body) {
            case "1":
                $payload = [];
                $result = SharedUtils::httpGet($this->probase_url_travel . Config::ROUTES, $payload, $this->msisdn, $this->log);
                if (!empty($result['travel_routes']) && sizeof($result['travel_routes']) > 0) {
                    $routes_menu = $this->formatRoutes($result['travel_routes'], false, false);
                    if (strlen($routes_menu) > 0) {
                        $_SESSION['menu-selection'] = "BUS_ROUTES";
                        $this->body = SharedUtils::strReplace("{routes}", $routes_menu, $this->menus['BUS_ROUTES']);
                        $this->gw_response = $this->get_response_array();
                    } else {
                        $this->end_of_session = TRUE;
                        $_SESSION['menu-selection'] = "";
                        $this->body = Config::SYSTEM_BUSY_MESSAGE;
                        $this->gw_response = $this->get_response_array();
                    }
                } else {
                    $this->end_of_session = TRUE;
                    $_SESSION['menu-selection'] = "";
                    $this->body = $this->menus['NO_ROUTES'];
                    $this->gw_response = $this->get_response_array();
                }
                break;
            case "2":
                if ($this->checkRegistration()) {
                    if (!empty($_SESSION['trader_details']) && $_SESSION['trader_details']['account_status'] === Config::ACC_BLOCKED_STATUS) {
                        $this->end_of_session = TRUE;
                        $_SESSION['menu-selection'] = "";
                        $this->body = $this->menus['ACC_BLOCKED'];
                        $this->gw_response = $this->get_response_array();
                        // return $this->gw_response;
                    }
                    if (!empty($_SESSION['trader_details']) && $_SESSION['trader_details']['account_status'] === Config::ACC_OTP_STATUS) {
                        //  if (empty($_SESSION['menu-selection'])) {
                        $_SESSION['menu-selection'] = "OTP_PIN";
                        $this->body = $this->menus['OTP_PIN'];
                        $this->gw_response = $this->get_response_array();
                        //return $this->gw_response;
                        //}
                    }
                    if (!empty($_SESSION['trader_details']) && $_SESSION['trader_details']['account_status'] === Config::ACC_ACTIVE_STATUS) {
                        /* $_SESSION['menu-selection'] = "ENTER_PIN";
                          $this->body = $this->menus['ENTER_PIN'];
                          $this->gw_response = $this->get_response_array(); */
                        $_SESSION['menu-selection'] = "MAIN";
                        $this->body = SharedUtils::strReplace("{Marketeer}", $_SESSION['names'], $this->menus['MAIN']);
                        $this->gw_response = $this->get_response_array();
                    }
                } else {
                    $this->log->logInfo(Config::APP_INFO_LOG, $this->msisdn, '| Trader is not registered. They need to register first before accessing the system!');
                    $this->end_of_session = TRUE;
                    $_SESSION['menu-selection'] = "-1";
                    $this->body = str_replace("{msisdn}", $this->msisdn, $this->menus['NOT_REGISTERED']);
                    $this->gw_response = $this->get_response_array();
                    return $this->gw_response;
                }
                break;
            case "3":
                $this->end_of_session = TRUE;
                $_SESSION['menu-selection'] = "TODO";
                $this->body = $this->menus['TODO'];
                $this->gw_response = $this->get_response_array();
                break;
            default :
                $_SESSION['menu-selection'] = "LOGIN";
                $this->body = "Invalid selection." . $this->menus['LOGIN'];
                $this->gw_response = $this->get_response_array();
                break;
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
