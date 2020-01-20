<?php

/*
 * @author Francis Chulu <chulu1francis@gmail.com>
 * @since 2019
 * Class for common functions across the app
 */

namespace App\Lib;

use App\Lib\Config;

class SharedUtils {

    /**
     *  Converts a json object to an array
     * @param type $json
     * @return array
     */
    public static function JsonToArray($json) {
        return json_decode($json, TRUE);
    }

    /**
     * Convert array to json
     * @param type $array
     * @return json
     */
    public static function arrayToJson($array) {
        return json_encode($array, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Make http GET requests
     * @param type $method
     * @param type $payload
     * @param type $msisdn
     * @param type $log
     * @return boolean
     */
    public static function httpGet($method, $payload, $msisdn, $log) {
        $log->logInfo(Config::APP_INFO_LOG, $msisdn, '| Sending API request data::' . print_r($payload, TRUE));
        $ch = curl_init(Config::API_URL . $method . '?' . http_build_query($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Spend n seconds trying to connect to.
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, Config::CONNECTION_TIMEOUT);
        //Take n seconds to complete operations.
        curl_setopt($ch, CURLOPT_TIMEOUT, Config::READ_TIMEOUT);
        $result = SharedUtils::JsonToArray(curl_exec($ch));
        $log->logInfo(Config::APP_INFO_LOG, $msisdn, '| API response is::' . print_r($result, TRUE));
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode == 404) {
            $result = "";
        }
        curl_close($ch);
        return $result;
    }

    /**
     * Call the API through curl
     * @author
     * @param String url, String params
     * @return output of curl
     */
    public static function httpPostJson($method, $params, $msisdn, $log) {
        $log->logInfo(Config::APP_INFO_LOG, $msisdn, '| Sending API request data::' . print_r($params, TRUE));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Config::API_URL . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, SharedUtils::arrayToJson($params));
        //Spend n seconds trying to connect to.
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, Config::CONNECTION_TIMEOUT);
        //Take n seconds to complete operations.
        curl_setopt($ch, CURLOPT_TIMEOUT, Config::READ_TIMEOUT);
        $result = SharedUtils::JsonToArray(curl_exec($ch));
        $log->logInfo(Config::APP_INFO_LOG, $msisdn, '| API response is::' . print_r($result, TRUE));
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode == 404) {
            $result = "";
        }

        curl_close($ch);
        return $result;
    }

    public static function buildAPIRequest($trader_id = "", $firstname = "", $lastname = "", $gender = "", $nrc = "", $dob = "", $mobile_number = "", $old_password = "", $password = "") {
        # Packet
        $payload = [
            'trader_id' => $trader_id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'gender' => $gender,
            'nrc' => $nrc,
            'dob' => $dob,
            'mobile_number' => $mobile_number,
            'old_password' => $old_password,
            'password' => $password,
        ];
        return $payload;
    }

    public static function buildPushTransactionRequest($trader_id, $amount,$seller_mobile,$seller_names, $mobile_number, $transaction_type_id,$buyer_id,$buyer_names) {
        # Packet
        $payload = [
            'transaction_type_id' => $transaction_type_id,
            'seller_id' => $trader_id,
            'seller_mobile_number' => $seller_mobile,
            'seller_name' => $seller_names,
            'transaction_date' => date('Y-m-d'),
            'device_serial' => "1111111",
            'amount_due' => $amount,
            'buyer_mobile_number' => $mobile_number,
            'buyer_id' => $buyer_id,
            'buyer_name' => $buyer_names,
        ];
        return $payload;
    }

    public static function strReplace($search, $replace, $subject) {
        return str_replace($search, $replace, $subject);
    }

    public static function validateMsisdn($input, $log, $msisdn) {
        $log->logInfo(Config::APP_INFO_LOG, $msisdn, '| Validating entered buyers mobile number:' . $input);
        $response = false;
        if (is_numeric($input) && substr($input, 0, 2) == "09" && preg_match('/^[0-9]*$/', $input) && strlen($input) == Config::MSISDN_LEN) {
            $log->logInfo(Config::APP_INFO_LOG, $msisdn, '| The entered buyers mobile number:' . $input . ' is valid');
            $response = TRUE;
        }
        return $response;
    }

    public static function validateAmount($input, $log, $msisdn) {
        $log->logInfo(Config::APP_INFO_LOG, $msisdn, '| Validating sale amount:K' . $input);
        $response = false;
        if (!empty($input) && $input > 0 && (is_float($input) || is_numeric($input))) {
            $log->logInfo(Config::APP_INFO_LOG, $msisdn, '| Sale amount:K' . $input . ' is valid amount');
            $response = TRUE;
        }
        return $response;
    }

    public static function validateMomoPin($msisdn, $mobile, $pin, $log) {
        //For now we just check the length and if its numeric.
        //TODO: We need to make a call to a MoMo System based on msisdn to authenticate buyer
        $log->logInfo(Config::APP_INFO_LOG, $msisdn, '| Validating MoMo pin xxxx');
        $response = false;
        if (!empty($pin) && is_numeric($pin) && strlen($pin) == Config::MOMO_PIN_LEN) {
            $log->logInfo(Config::APP_INFO_LOG, $msisdn, '| MoMo pin xxxx is valid');
            $response = TRUE;
        }
        return $response;
    }

}
