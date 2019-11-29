<?php

/*
 * @author Francis Chulu <chulu1francis@gmail.com>
 * @since 2015
 * Class for common functions across the app
 */

namespace App\Lib;

use App\Lib\Config;

class SharedUtils {

    /**
     * Does page to page redirection
     * @param type $location
     */
    public static function redirect($location) {
        header("Location:" . $location);
        exit();
    }

    /**
     * Gets a url based on the shortcode
     * @param type $shortCode
     * @return type string
     */
    public static function getURL($shortCode) {
        $URL = "";
        foreach (Config::short_code_url_map as $key => $value) {
            if ($shortCode == $key) {
                $URL = $value;
            }
        }
        return $URL;
    }

    /**
     * Generates a random string
     * @param type $digits
     * @return type string
     */
    public static function generateSessionID($digits) {
        $i = 0; //counter
        $sessionid = ""; //our default id is blank.
        while ($i < $digits) {
            $sessionid .= mt_rand(0, 9);
            $i++;
        }
        return $sessionid;
    }

    /**
     * Makes call to ussd menu
     * @param type $request
     * @param type $url
     * @return type
     */
    public static function httpPost($request, $url, $requestType) {
        $ch = curl_init($url);
        if (!empty($request)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestType);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        return $result;
    }

    /**
     * Builds the menu call request atleast for airtel :-
     * @param type $ussd_body
     * @param type $session_id
     * @param type $mobile_no
     * @return type array
     */
    public static function buildAirtelRequest($ussd_body, $session_id, $mobile_no) {
        $request = [
            "SEQUENCE" => "1",
            "END_OF_SESSION" => "FALSE",
            "LANGUAGE" => "ENG",
            "SESSION_ID" => $session_id,
            "IMSI" => "11111110000",
            "SERVICE_KEY" => "",
            "MOBILE_NUMBER" => $mobile_no,
            "USSD_BODY" => $ussd_body
        ];
        return xmlrpc_encode_request("USSD_MESSAGE", $request);
    }

    /**
     * Builds the menu call request atleast for mtn :-
     * @param type $ussd_body
     * @param type $session_id
     * @param type $mobile_no
     * @return type array
     */
    public static function buildMTNRequest($ussd_body, $session_id, $mobile_no) {
        $request = [
            //  "SEQUENCE" => "1",
            //  "END_OF_SESSION" => "FALSE",
            //   "LANGUAGE" => "ENG",
            "SESSION_ID" => $session_id,
            "IMSI" => "11111110000",
            "SHORT_CODE" => "",
            "MSISDN" => $mobile_no,
            "INPUT" => $ussd_body
        ];
        return $request;
    }

    /**
     * Builds the menu call request atleast for airtel :-
     * @param type $ussd_body
     * @param type $session_id
     * @param type $mobile_no
     * @return type array
     */
    public static function buildZamtelRequest($url, $ussd_body, $session_id, $mobile_no, $requestType, $short_code = "") {
        $url .= "?AppId=1&TransId=$session_id&RequestType=$requestType&SHORTCODE=$short_code&MSISDN=$mobile_no&USSDString=$ussd_body";
        return $url;
    }

    public function getZamtelUSSDBody($response_arr) {
        $response_ussd_body="";
        foreach ($response_arr as $value) {
            if (!empty($value)) {
                $arr = explode("=", $value);
                if ($arr[0] == "USSDString") {
                    $response_ussd_body = $arr[1];
                    break;
                }
            }
        }
        return $response_ussd_body;
    }

    /**
     * Checks the msisdn for each network
     * @param type $subscriber_input
     * @param type $mno
     * @return boolean
     */
    public static function validateMsisdn($subscriber_input, $mno) {
        $response = false;
        if ($mno == Config::mno_airtel) {
            if (preg_match('/^[0-9]*$/', $subscriber_input) && substr($subscriber_input, 0, 5) == "26097" && strlen($subscriber_input) == 12) {
                $response = TRUE;
            }
        } if ($mno == Config::mno_mtn) {
            if (preg_match('/^[0-9]*$/', $subscriber_input) && substr($subscriber_input, 0, 5) == "26096" && strlen($subscriber_input) == 12) {
                $response = TRUE;
            }
        } if ($mno == Config::mno_zamtel) {
            if (preg_match('/^[0-9]*$/', $subscriber_input) && substr($subscriber_input, 0, 5) == "26095" && strlen($subscriber_input) == 12) {
                $response = TRUE;
            }
        }
        return $response;
    }

}
