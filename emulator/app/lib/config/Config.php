<?php
/*
 * @author Francis Chulu <chulu1francis@gmail.com>
 * @since 2015
 * app config class
 */
namespace App\Lib;

class Config {

    const title = "USSD Emulator";
    const APP_INFO_LOG = "emulogger";
    const mno_airtel = "Airtel";
    const mno_mtn = "MTN";
    const mno_zamtel = "Zamtel";
    const default_index_msg = "Welcome to the USSD Emulator. Enter your mobile number and Select your network!";
    const short_code_url_map = [
    '911-airtel' => 'http://localhost/Market_Sales_USSD/airtel.php',
    '911-mtn' => 'http://localhost/Market_Sales_USSD/mtn.php',
    '911-zamtel' => 'http://localhost/Market_Sales_USSD/zamtel.php',
    ];
}
