<?php
/**
 * Utilities class
 *
 * @author Francis Chulu
 */

use Libs\Logger\ApiLogger;

header('Content-Type: application/json;charset=utf-8');
date_default_timezone_set("Africa/Lusaka");

class Utils
{

    function __construct()
    {

    }

    /**
     *  Converts a json object to an array
     * @param type $json
     * @return array
     */
    public static function JsonToArray($json)
    {
        return json_decode($json, TRUE);
    }

    /**
     * Convert array to json
     * @param type $array
     * @return json
     */
    public static function arrayToJson($array)
    {
        return json_encode($array, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Function receives any request and checks if its a json request
     * @return array
     */
    public static function handleRequest()
    {

        $request = file_get_contents('php://input');

        if (empty($request)) {
            $response['error'] = True;
            $response['message'] = StatusCodes::GENERIC_ERROR_MSG;
            $response['status'] = StatusCodes::GENERIC_ERROR;
            return $response;
        } else {
            $response = self::JsonToArray($request);

            if (!empty($response) || $response != false) {
                if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
                    parse_str(http_build_query($response), $response);
                }
                return $response;
            } else {
                $response['error'] = True;
                $response['message'] = StatusCodes::GENERIC_ERROR_MSG;
                $response['status'] = StatusCodes::GENERIC_ERROR;
            }
        }
        return $response;
    }
}
