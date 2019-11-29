<?php

/**
 * Copyright (C) 2018 Lima Links - All Rights Reserved
 *
 * PROPRIETARY AND CONFIDENTIAL.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * This file is part of Lima Links project, developed by William Parke Phiri.
 * Written by William Phiri <williamparken@outlook.com>, January 10, 2018 09:42:25 PM.
 * Edited by Francis Chulu <chulu1francis@gmail.com>, 23 August 2018 
 * Fixed a few bugs here and there and added lima services
 *
 */
date_default_timezone_set("Africa/Lusaka");

// include_once 'db_creds.php';			    //The script for getting the DB Connection
include_once 'logging/flog_configs.php';    //This is a flog configuration file. It tells flog were to keep the log files and the naming convention to use.
include_once 'logging/flog.php';         //Flog php file...
include_once 'config.php';           //Config that will contain the application constants
include_once 'functions/main_services.php'; //The main services script
include_once 'engine/engine.php';      //The main processing script

ini_set('error_log', 'ussd-app-error.log'); //Error log for general php (application) errors.
$request_xml = file_get_contents('php://input');
//$params = file_get_contents("php://input");
//$params = json_decode(file_get_contents("php://input"), true);
//$payload = json_decode($params, true);
//Below is not necessary. Can be done in one line above. Should be removed
//$xml = $request_xml;
//$xml_response = simplexml_load_string($request_xml);
//$json = json_encode($xml_response);
//$array = json_decode($json,1);
$request = xmlrpc_decode($request_xml);
$payload = $request[0];
// Temporary Production Settings.
// $request_xml  = file_get_contents('php://input');
/*$xml_response = simplexml_load_string($request_xml);
$xml_std_class = json_decode(json_encode($xml_response));

$member = $xml_std_class->params->param->value->struct->member;
$member_arr = (array) $member;

$payload = array();

foreach ($member_arr as $inner_value) {
    $name = $inner_value->name;
    $value = $inner_value->value->string;

    $payload[$name] = $value;
}*/
// End Production settings
//flog("Payload-Test... ".print_r($payload, true));

flog("handle()|[" . $payload['MOBILE_NUMBER'] . "]| Request from the gateway is:" . print_r($payload, true));
$sequence = $payload['SEQUENCE'];
$end_of_session = $payload['END_OF_SESSION'];
$language = $payload['LANGUAGE'];
$session_id = $payload['SESSION_ID'];
$service_key = $payload['SERVICE_KEY'];
$mobile_number = $payload['MOBILE_NUMBER'];
$subscr_input = $payload['USSD_BODY'];

$receiverSessionId = $mobile_number . $session_id;
session_id($receiverSessionId);             //Use received session id to create a unique session. TODO: Add MSISDN
//session_id($mobile_number);             //Use received session id to create a unique session. TODO: Add MSISDN
session_start();
//session_unset();
$address = substr($mobile_number, 2);    //Remove 26 from the number.
flog("[content=$subscr_input, address=$address, requestId=$session_id, sequence=$sequence]");
$engine = new USSDEngine($address); //We initialize the engine.
//$engine 	= new USSDEngine($db_connection, $address); //We initialize the engine.
//Menus Array - Dispature....
$menusConfig = "menus_config.json";
$menus_config = json_decode(file_get_contents($menusConfig), true);
$agent_has_details = false;
if (!isset($_SESSION['ussd_gw_sess_id'])) {
    $_SESSION['ussd_gw_sess_id'] = $session_id;
}
$timeout_timediff = "";
$_SESSION["STEP"] = "";


//We use the time to calculate if we present continue menu or not
if (!isset($_SESSION['last_visit_time'])) {
    $_SESSION['last_visit_time'] = strtotime(date("Y-m-d H:i:s"));
}
$now = strtotime(date("Y-m-d H:i:s"));
$timeout_timediff = $now - $_SESSION['last_visit_time'];
$_SESSION['last_visit_time'] = $now; // In case customer cancels again

$agent_has_details = false;
// Remove the time limit for rating
$agent_details = $engine->get_all_market_agent_details($address);

if (sizeof($agent_details) > 0) {
    $dummy_sales_enabled = $agent_details[0]['dummy_sales_enabled'];
}
if ((count($agent_details) > 0) && ($dummy_sales_enabled == 1)) {
    $agent_has_details = true;
}

if ((!isset($_SESSION['sent_menu_id'])) && $agent_has_details == true) {
//if ((date("H") >= 14) && $agent_has_details == true) {
    //Send the agent ranking menu
    $registered_user_menu = get_init_menu_for_registered_msisdn($address);
    $agent_name = $agent_details[0]['username'];
    $market_ref = $agent_details[0]['market_ref'];
    $market_name = $engine->get_the_market_name($market_ref);
    $farmer_details = $engine->get_all_farmer_details($address);
    $farmer_id = $farmer_details['id'];
    $_SESSION['user_selected_farmer_id'] = $farmer_id;
    $_SESSION['user_selected_agent_id'] = $agent_details[0]['id'];
    $_SESSION['ma_selected_market_name'] = $market_name;
    $_SESSION['user_selected_agent_name'] = $agent_name;
    if ((count($registered_user_menu) > 0)) {
        $msg_tmp = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['main_3']); //A temporal message...
        $msg_tmp1 = str_replace("{name_of_market_agent}", $agent_name, $msg_tmp);
        $msg = str_replace("{name_of_market}", $market_name, $msg_tmp1);
        $_SESSION['sent_menu_id'] = "main_3";
        loadUssdSender($session_id, $msg, false);
    }

    $_SESSION['ussd_gw_sess_id'] = $session_id; //This is the first time, we set the USSD GW session id memory
} elseif (!isset($_SESSION['sent_menu_id'])) {
    //Send the main menu
    session_unset(); //Free all session values- we start afresh. Just to be careful.	

    $registered_user_menu = get_init_menu_for_registered_msisdn($address);
    $is_ussd_registered = isset($registered_user_menu[0]['is_ussd_registered']) ? $registered_user_menu[0]['is_ussd_registered'] : 0;
    if (is_array($registered_user_menu) && (count($registered_user_menu) > 0) && $is_ussd_registered == 1) {
        $farmer_details = $engine->get_all_farmer_details($address);
        $_SESSION['farmer_profile_id'] = isset($farmer_details['id']) ? $farmer_details['id'] : 0;
        $_SESSION['farmer_location_id'] = isset($farmer_details['location_ref']) ? $farmer_details['location_ref'] : 0;
        $input_advert_stats = $engine->get_inputs_and_adverts_counters($address); //Set the inputs and adverts object session values.
        $lima_services_stats = $engine->get_main_2_lima_services_stats($address); //Set the messages object session values.

        $no_of_services_unread = isset($lima_services_stats['unread']) ? $lima_services_stats['unread'] : 0;
        //$display_value = (strlen($no_of_services_unread) > 0) ? "-New*" : "";
        $display_value = "";
        if ($no_of_services_unread > 0) {
            $display_value = "-New*";
        }
        $no_of_services_received = isset($lima_services_stats['total']) ? $lima_services_stats['total'] : 0;
        $no_of_inputs_unread = isset($input_advert_stats['unread']) ? $input_advert_stats['unread'] : 0;
        $inputs_display_value = "";
        if ($no_of_inputs_unread > 0) {
            $inputs_display_value = "-New*";
        }

        $total_no_of_inputs = isset($input_advert_stats['total']) ? $input_advert_stats['total'] : 0;
        $msg_tmp1 = str_replace("{unread_services}", $display_value, $menus_config['main_2']);
        $msg_tmp2 = str_replace("{total_services}", $no_of_services_received, $msg_tmp1);
        $msg_tmp3 = str_replace("{input_unread}", $inputs_display_value, $msg_tmp2);
        $msg = str_replace("{total_inputs}", $total_no_of_inputs, $msg_tmp3);
        $_SESSION['sent_menu_id'] = "main_2";
        loadUssdSender($session_id, $msg, false);
    } elseif (is_array($registered_user_menu) && (count($registered_user_menu) > 0) && $is_ussd_registered == 0) {
        $msg = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['reg_1']);
        $_SESSION['sent_menu_id'] = "reg_1";
        loadUssdSender($session_id, $msg, false);
    } else {
        $_SESSION['sent_menu_id'] = "main_1";   //Initialize main menu for non registered users
        loadUssdSender($session_id, $menus_config['main_1'], false);
    }


    $_SESSION['ussd_gw_sess_id'] = $session_id; //This is the first time, we set the USSD GW session id memory
} elseif (isset($_SESSION['sent_menu_id'])) {
    flog("[$address] MSISDN return. Menu ID is:" . $_SESSION['sent_menu_id'] . ", Subscriber Input is : " . $subscr_input . "::$session_id");
    if ($_SESSION['ussd_gw_sess_id'] == $session_id && empty($subscr_input) && isset($_SESSION['sent_menu_id'])) {
        $_SESSION['sent_menu_id'] = $_SESSION['last_menu_id'];
    } elseif ($_SESSION['ussd_gw_sess_id'] != $session_id && empty($subscr_input)) {
        if ($timeout_timediff < CONT_TIMEOUT && $_SESSION['last_menu_id'] != "main_2") {
            $_SESSION['sent_menu_id'] = "cont_or_not";
            $msg = $menus_config['cont_or_not'];
            loadUssdSender($session_id, $msg, false);
        } else {
            $_SESSION['sent_menu_id'] = "default";
        }
        // loadUssdSender($session_id, $msg, false);
    } elseif ($_SESSION['sent_menu_id'] == "cont_or_not" && $subscr_input == "1") {
        if ($_SESSION['last_menu_id'] == "main_2") {
            //If customer had cancelled on the main menu, just present the main menu
            $_SESSION['sent_menu_id'] = "default";
        } else {
            $_SESSION["STEP"] = "CONT_OR_NOT"; // Just a hack, we prevent menu pre-selections for continue or not option selections
            $subscr_input = "";
            $_SESSION['sent_menu_id'] = $_SESSION['last_menu_id'];
        }
    } elseif ($_SESSION['sent_menu_id'] == "cont_or_not" && $subscr_input == "2") {
        $_SESSION['sent_menu_id'] = "default";
    }

    if ($_SESSION['sent_menu_id'] != "cont_or_not") {   //Execute all that is in the switch, only if you are not sending back the do you want to continue menu.
        switch ($_SESSION['sent_menu_id']) {
            case "main_1":
                $response = process_main_1_response($subscr_input);
                break;
            case "reg_1":
                $response = process_reg_1_farmer_update_response($subscr_input);
                break;
            case "main_1.1":
                $response = process_main_1_pnt_1_response($subscr_input);
                break;
            case "1.1":
                $response = process_menu_select_province_response($subscr_input);
                break;
            case "1.1.1":
                $response = process_menu_select_district_response($subscr_input);
                break;
            case "1.1.1.1":
                $response = process_menu_grow_fcrops_response($subscr_input);
                break;
            case "1.1.1.1.1":
                $response = process_menu_grow_vcrops_response($subscr_input);
                break;
            case "1.1.1.1.1.1":
                $response = process_menu_grow_fruits_response($subscr_input);
                break;
            case "1.1.1.1.1.1.1":
                $response = process_menu_keep_livestock_response($subscr_input);
                break;
            case "1.1.1.1.1.1.1.1":
                $response = process_menu_want_to_share_number_response($subscr_input);
                break;
            case "1.1.1.1.1.1.1.1.1":
                $response = process_menu_acces_services_response($subscr_input);
                break;
            case "main_2":
                $response = process_main_2_response($subscr_input);
                break;
            case "2.1":
                $response = process_menu_2_pnt_1_marketprices_produce_category_response($subscr_input);
                break;
            case "2.1.1":
                $response = process_menu_select_field_crop_response($subscr_input);
                break;
            case "2.1.1.1":
                $response = process_menu_select_buyer_response($subscr_input);
                break;
            case "2.1.1.1.1":
                $response = process_menu_select_depot_response($subscr_input);
                break;
            case "2.1.1.1.1.1":
                $response = process_menu_select_package_prices_depot_response($subscr_input);
                break;
            case "2.1.2":
                $response = process_menu_select_vegetable_response($subscr_input);
                break;
            case "2.1.2.1":
                $response = process_menu_select_market_response($subscr_input);
                break;
            case "2.1.2.1.1":
                $response = process_menu_select_market_packages_response($subscr_input);
                break;
            case "2.1.2.1.1.1":
                $response = process_menu_select_vegetable_marketprices_final_menu_response($subscr_input);
                break;
            case "2.1.3":
                $response = process_menu_select_livestock_response($subscr_input);
                break;
            case "2.1.3.1":
                $response = process_menu_select_livestock_buyer_response($subscr_input);
                break;
            case "2.1.3.1.1":
                $response = process_menu_select_livestock_depot_response($subscr_input);
                break;
            case "2.1.3.1.1.1":
                $response = process_menu_select_livestock_selector_range_response($subscr_input);
                break;
            case "2.1.3.1.1.1.1":
                $response = process_menu_select_livestock_depot_prices_response($subscr_input);
                break;
            case "2.2":
                $response = process_menu_2_pnt_2_select_production_stage_reponse($subscr_input);
                break;
            case "2.2.1":
                $response = process_menu_2_pnt_2_pnt_1_select_chemical_inputs_reponse($subscr_input);
                break;
            case "2.2.1.1":
                $response = process_menu_2_pnt_2_pnt_1_pnt_1_select_chemical_input_advertisers_reponse($subscr_input);
                break;
            case "2.2.1.1.1":
                $response = process_menu_2_pnt_2_pnt_1_pnt_1_pnt_1_select_chemical_inputs_reponse($subscr_input);
                break;
            case "2.2.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_1_pnt_1_pnt_1_pnt_1_chemical_inputs_final_display_response($subscr_input);
                break;
            case "2.2.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_chemical_application_menu_response($subscr_input);
                break;
            case "2.2.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_chemical_content_menu_response($subscr_input);
                break;
            case "2.2.1.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_buying_location_menu_response($subscr_input);
                break;
            case "2.2.1.1.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_request_call_menu_response($subscr_input);
                break;
            case "2.2.1.1.1.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_call_requested_successfully_menu_response($subscr_input);
                break;
            case "2.2.2":
                $response = process_menu_2_pnt_2_pnt_2_select_fertilizers_inputs_reponse($subscr_input);
                break;
            case "2.2.2.1":
                $response = process_menu_2_pnt_2_pnt_2_pnt_1_select_fertilizer_inputs_advertizer_reponse($subscr_input);
                break;
            case "2.2.2.1.1":
                $response = process_menu_2_pnt_2_pnt_2_pnt_1_pnt_1_select_active_fertilizer_input_reponse($subscr_input);
                break;
            case "2.2.2.1.1.1":
                $response = process_menu_2_pnt_2_pnt_2_pnt_1_pnt_1_pnt_1_fertilizer_inputs_final_display_response($subscr_input);
                break;
            case "2.2.2.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_2_pnt_1_pnt_1_pnt_1_pnt_1_fertilizer_application_application_menu_response($subscr_input);
                break;
            case "2.2.2.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_2_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_fertilizer_buying_location_menu_response($subscr_input);
                break;
            case "2.2.2.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_2_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_fertilizer_request_call_menu_response($subscr_input);
                break;
            case "2.2.2.1.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_2_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_fertilizer_call_requested_successfully_menu_response($subscr_input);
                break;
            case "2.2.2.1.1.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_2_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_fertilizer_product_details_menu_response($subscr_input);
                break;
            case "2.2.3":
                $response = process_menu_2_pnt_2_pnt_3_select_input_equipement_powersource_response($subscr_input);
                break;
            case "2.2.3.1":
                $response = process_menu_2_pnt_2_pnt_3_pnt_1_select_input_equipement_category_response($subscr_input);
                break;
            case "2.2.3.1.1":
                $response = process_menu_2_pnt_2_pnt_3_pnt_1_pnt_1_select_equipment_input_advertiser_response($subscr_input);
                break;
            case "2.2.3.1.1.1":
                $response = process_menu_2_pnt_2_pnt_3_pnt_1_pnt_1_pnt_1_select_advertised_equipments_response($subscr_input);
                break;
            case "2.2.3.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_3_pnt_1_pnt_1_pnt_1_pnt_1_equipment_input_final_menu_display_response($subscr_input);
                break;
            case "2.2.3.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_3_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_equipment_usage_menu_response($subscr_input);
                break;
            case "2.2.3.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_3_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_equipment_buying_location_menu_response($subscr_input);
                break;
            case "2.2.3.1.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_3_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_request_call_equip_supplier_menu_response($subscr_input);
                break;
            case "2.2.3.1.1.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_3_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_equipments_call_requested_successfully_menu_response($subscr_input);
                break;
            case "2.2.3.1.1.1.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_3_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_equipment_product_details_menu_response($subscr_input);
                break;
            case "Seed_category_selection":
                $response = process_seed_category_selection($subscr_input);
                break;
            case "no_Seed_category":
                $response = process_no_seed_category($subscr_input);
                break;
            case "2.2.4":
                $response = process_menu_2_pnt_2_pnt_4_select_input_seeds_reponse($subscr_input);
                break;
            case "2.2.4.1":
                $response = process_menu_2_pnt_2_pnt_4_pnt_1_select_input_seeds_advertiser_response($subscr_input);
                break;
            case "2.2.4.1.1":
                $response = process_menu_2_pnt_2_pnt_4_pnt_1_pnt_1_select_active_input_seeds_menu_response($subscr_input);
                break;
            case "2.2.4.1.1.1":
                $response = process_menu_2_pnt_2_pnt_4_pnt_1_pnt_1_pnt_1_input_seeds_final_display_response($subscr_input);
                break;
            case "2.2.4.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_4_pnt_1_pnt_1_pnt_1_pnt_1_seeds_planting_info_menu_response($subscr_input);
                break;
            case "2.2.4.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_4_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_fertilizer_application_menu_response($subscr_input);
                break;
            case "2.2.4.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_4_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_seeds_diseases_and_pastes_menu_response($subscr_input);
                break;
            case "2.2.4.1.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_4_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_seeds_buying_location_menu_response($subscr_input);
                break;
            case "2.2.4.1.1.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_4_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_input_seeds_request_call_menu_response($subscr_input);
                break;
            case "2.2.4.1.1.1.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_4_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_seeds_call_requested_successfully_menu_response($subscr_input);
                break;
            case "2.2.5":
                $response = process_menu_2_pnt_2_pnt_5_select_input_seedlings_reponse($subscr_input);
                break;
            case "2.2.5.1":
                $response = process_menu_2_pnt_2_pnt_5_pnt_1_select_input_seedlings_advertiser_response($subscr_input);
                break;
            case "2.2.5.1.1":
                $response = process_menu_2_pnt_2_pnt_5_pnt_1_pnt_1_select_inputs_active_seedlings_menu_response($subscr_input);
                break;
            case "2.2.5.1.1.1":
                $response = process_menu_2_pnt_2_pnt_5_pnt_1_pnt_1_pnt_1_input_seedings_final_display_response($subscr_input);
                break;
            case "2.2.5.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_5_pnt_1_pnt_1_pnt_1_pnt_1_seedlings_planting_info_menu_response($subscr_input);
                break;
            case "2.2.5.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_5_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_seedlings_fertilizer_application_menu_response($subscr_input);
                break;
            case "2.2.5.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_5_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_seedlings_diseases_and_pastes_menu_response($subscr_input);
                break;
            case "2.2.5.1.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_5_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_seedlings_buying_location_menu_response($subscr_input);
                break;
            case "2.2.5.1.1.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_5_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_input_seedlings_request_call_menu_response($subscr_input);
                break;
            case "2.2.5.1.1.1.1.1.1.1.1.1":
                $response = process_menu_2_pnt_2_pnt_5_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_seedlings_call_requested_successfully_menu_response($subscr_input);
                break;
            case "no_buying_locations":
                $response = process_menu_no_buying_locations_available_menu_response($subscr_input);
                break;
            case "no_service_providers_location":
                $response = process_menu_no_service_provider_locations_available_menu_response($subscr_input);
                break;
            case "2.3":
                $response = process_menu_2_pnt_3_select_service_categories_response($subscr_input);
                break;
            case "NO_LIMA_SERVICES":
                $response = process_no_lima_services($subscr_input);
                break;
            case "providers_other_provinces_menu":
                $response = process_lima_services_providers_other_provinces($subscr_input);
                break;
            case "providers_other_provinces_transportation_menu":
                $response = process_providers_other_provinces_transportation_selection($subscr_input);
                break;
            case "2.3.2":
                $response = process_menu_2_pnt_3_pnt_2_select_lima_service_category_response($subscr_input);
                break;
            case "2.3.2.1":
                $response = process_menu_2_pnt_3_pnt_2_pnt_1_select_lima_service_providers_response($subscr_input);
                break;
            case "2.3.2.1.1":
                $response = process_menu_2_pnt_3_pnt_2_pnt_1_pnt_1_services_final_display_response($subscr_input);
                break;
            case "2.3.2.1.1.1":
                $response = process_lima_services_service_details_display($subscr_input);
                break;
            case "2.3.2.1.1.1.1":
                $response = process_lima_services_provider_locations_display($subscr_input);
                break;
            case "2.3.2.1.1.1.1.1":
                $response = process_lima_services_more_infor_display($subscr_input);
                break;
            case "2.3.2.1.1.1.1.1.1":
                $response = process_menu_lima_services_request_call_menu_response($subscr_input);
                break;
            case "2.3.2.1.1.1.1.1.1.1":
                $response = process_menu_lima_service_call_requested_successfully_menu_response($subscr_input);
                break;
            case "2.3.3":
                $response = process_menu_2_pnt_3_pnt_3_select_transportation_category_response($subscr_input);
                break;
            case "2.3.3.1":
                $response = process_menu_2_pnt_3_pnt_3_pnt_1_select_transportation_service_providers_response($subscr_input);
                break;
            case "2.3.3.1.1":
                $response = process_menu_2_pnt_3_pnt_3_pnt_1_pnt_1_select_active_transportation_service_response($subscr_input);
                break;
            case "2.3.3.1.1.1":
                $response = process_menu_2_pnt_3_pnt_3_pnt_1_pnt_1_pnt_1_transportation_service_final_display_response($subscr_input);
                break;
            case "2.3.3.1.1.1.1":
                $response = process_menu_2_pnt_3_pnt_3_pnt_1_pnt_1_pnt_1_pnt_1_transportation_service_details_menu_response($subscr_input);
                break;
            case "2.3.3.1.1.1.1.1":
                $response = process_menu_2_pnt_3_pnt_3_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_transportation_service_location_menu_response($subscr_input);
                break;
            case "2.3.3.1.1.1.1.1.1":
                $response = process_menu_2_pnt_3_pnt_3_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_transport_request_call_menu_response($subscr_input);
                break;
            case "2.3.3.1.1.1.1.1.1.1":
                $response = process_menu_2_pnt_3_pnt_3_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_pnt_1_transport_service_call_requested_successfully_menu_response($subscr_input);
                break;
            case "2.4":
                $response = process_menu_2_pnt_4_menu_response($subscr_input);
                break;
            case "2.4.1":
                $response = process_menu_2_pnt_4_pnt_1_change_farmer_location_menu_response($subscr_input);
                break;
            case "2.4.1.1":
                $response = process_menu_2_pnt_4_pnt_1_pnt_1_select_new_province_menu_response($subscr_input);
                break;
            case "2.4.1.1.1":
                $response = process_menu_2_pnt_4_pnt_1_pnt_1_pnt_1_select_new_district_response($subscr_input);
                break;
            case "2.4.1.1.1.1":
                $response = process_menu_2_pnt_4_pnt_1_pnt_1_pnt_1_pnt_1_confirm_relocation_menu_response($subscr_input);
                break;
            case "2.4.1.1.1.1.1":
                $response = process_menu_my_profile_successfuly_updated_location_menu_response($subscr_input);
                break;
            case "2.4.2":
                $response = process_menu_2_pnt_4_pnt_2_view_and_update_profile_menu_response($subscr_input);
                break;
            case "2.4.2.1":
                $response = process_menu_2_pnt_4_pnt_2_pnt_1_select_produce_categories_menu_response($subscr_input);
                break;
            case "2.4.2.1.1":
                $response = process_menu_2_pnt_4_pnt_2_pnt_1_pnt_1_field_crops_grown_response($subscr_input);
                break;
            case "2.4.2.1.1.1":
                $response = process_menu_2_pnt_4_pnt_2_pnt_1_pnt_1_pnt_1_update_farmer_crops_fc_confirmation($subscr_input);
                break;
            case "2.4.2.1.1.1.1":
                $response = process_menu_2_pnt_4_pnt_2_pnt_1_pnt_1_pnt_1_pnt_1_remove_another_crop_from_farmer_crops($subscr_input);
                break;
            case "2.4.2.1.2":
                $response = process_menu_2_pnt_4_pnt_2_pnt_1_pnt_2_veg_crops_grown_response($subscr_input);
                break;
            case "2.4.2.1.2.1":
                $response = process_menu_2_pnt_4_pnt_2_pnt_1_pnt_2_pnt_1_update_farmer_crops_veg_confirmation($subscr_input);
                break;
            case "2.4.2.1.2.1.1":
                $response = process_menu_2_pnt_4_pnt_2_pnt_1_pnt_2_pnt_1_pnt_1_remove_another_veg_crop_from_farmer_crops($subscr_input);
                break;
            case "2.4.2.2":
                $response = process_menu_2_pnt_4_pnt_2_pnt_2_add_to_my_crops_menu_response($subscr_input);
                break;
            case "2.4.2.2.1":
                $response = process_menu_2_pnt_4_pnt_2_pnt_2_pnt_1_field_crops_menu_response($subscr_input);
                break;
            case "2.4.2.2.1.1":
                $response = process_menu_2_pnt_4_pnt_2_pnt_2_pnt_1_pnt_1_add_more_field_crops_menu_response($subscr_input);
                break;
            case "2.4.2.2.1.1.1":
                $response = process_menu_2_pnt_4_pnt_2_pnt_2_pnt_1_pnt_1_pnt_1_field_crops_success_message_menu_response($subscr_input);
                break;
            case "2.4.2.2.1.1.1.1":
                $response = process_menu_2_pnt_4_pnt_2_pnt_2_pnt_1_pnt_1_pnt_1_pnt_1_go_through_field_crops_again_menu_response($subscr_input);
                break;
            case "2.4.2.2.2":
                $response = process_menu_2_pnt_4_pnt_2_pnt_2_pnt_2_grow_vegetables_menu_response($subscr_input);
                break;
            case "2.4.2.2.2.1":
                $response = process_menu_2_pnt_4_pnt_2_pnt_2_pnt_2_pnt_1_add_more_vegetables_menu_response($subscr_input);
                break;
            case "2.4.2.2.2.1.1":
                $response = process_menu_2_pnt_4_pnt_2_pnt_2_pnt_2_pnt_1_pnt_1_vegetables_success_message_menu_response($subscr_input);
                break;
            case "2.4.2.2.2.1.1.1":
                $response = process_menu_2_pnt_4_pnt_2_pnt_2_pnt_2_pnt_1_pnt_1_pnt_1_go_through_vegetables_again_menu_response($subscr_input);
                break;
            case "2.5":
                $response = process_menu_sell_crops_reponse($subscr_input);
                break;
            case "2.5.1":
                $response = process_menu_crop_to_sell_reponse($subscr_input);
                break;
            case "2.5.1.1":
                $response = process_menu_select_market_sell_veg_crops_packages_response($subscr_input);
                break;
            case "2.5.1.1.1":
                $response = process_enter_quantity_of_veg_crops_menu_resp($subscr_input);
                break;
            case "2.5.1.1.1.1":
                $response = process_menu_select_market_closest_to_you_response($subscr_input);
                break;
            case "2.5.1.1.1.1.1":
                $response = process_menu_market_agent_rated_response($subscr_input);
                break;
            case "2.5.1.1.1.1.1.1":
                $response = process_menu_help_farmer_sell_crops_reponse($subscr_input);
                break;
            case "2.5.2":
                $response = process_menu_select_field_crop_to_sell_response($subscr_input);
                break;
            case "2.5.2.1":
                $response = process_menu_enter_field_crop_quantity_to_sell_response($subscr_input);
                break;
            case "2.5.5":
                $response = process_menu_no_vegetables_on_profile_to_sell_response($subscr_input);
                break;
            case "2.5.6":
                $response = process_menu_no_field_crops_on_profile_to_sell_response($subscr_input);
                break;
            case "2.6":
                $response = process_menu_help_reponse($subscr_input);
                break;
            case "main_3":
                $response = process_menu_main_3_response($subscr_input);
                break;
            case "3.1":
                $response = process_menu_price_market_agent_sold_crops_response($subscr_input);
                break;
            case "3.1.1":
                $response = process_menu_commission_charged_response($subscr_input);
                break;
            case "3.1.1.1":
                $response = process_menu_recommend_agent_response($subscr_input);
                break;
            case "3.1.1.1.1":
                $response = process_menu_rating_transaction_with_market_agent_response($subscr_input);
                break;
            case "other_providers_province_selection":
                $response = process_providers_other_provinces_selection($subscr_input);
                break;

            case "No_providers_other_provinces":
                $response = process_no_providers_other_provinces_selection($subscr_input);
                break;
            default:
                //Send the main menu
                session_unset(); //Free all session values- we start afresh. Just to be careful.
                $registered_user_menu = get_init_menu_for_registered_msisdn($address);
                $is_ussd_registered = isset($registered_user_menu[0]['is_ussd_registered']) ? $registered_user_menu[0]['is_ussd_registered'] : 0;
                if (is_array($registered_user_menu) && (count($registered_user_menu) > 0) && $is_ussd_registered == 1) {
                    $farmer_details = $engine->get_all_farmer_details($address);
                    $_SESSION['farmer_profile_id'] = isset($farmer_details['id']) ? $farmer_details['id'] : 0;
                    $_SESSION['farmer_location_id'] = isset($farmer_details['location_ref']) ? $farmer_details['location_ref'] : 0;
                    $input_advert_stats = $engine->get_inputs_and_adverts_counters($address); //Set the inputs and adverts object session values.
                    $lima_services_stats = $engine->get_main_2_lima_services_stats($address); //Set the messages object session values.
                    $no_of_services_unread = isset($lima_services_stats['unread']) ? $lima_services_stats['unread'] : 0;
                    flog("Unread services:" . $no_of_services_unread);
                    //$display_value = (strlen($no_of_services_unread) > 0) ? "-New*" : "";
                    $display_value = "";
                    if ($no_of_services_unread > 0) {
                        $display_value = "-New*";
                    }

                    $no_of_services_received = isset($lima_services_stats['total']) ? $lima_services_stats['total'] : 0;
                    $no_of_inputs_unread = isset($input_advert_stats['unread']) ? $input_advert_stats['unread'] : 0;
                    flog("Unread services:" . $no_of_inputs_unread);
                    //$inputs_display_value = (strlen($no_of_inputs_unread) > 0) ? "-New*" : "";
                    $inputs_display_value = "";
                    if ($no_of_inputs_unread > 0) {
                        $inputs_display_value = "-New*";
                    }
                    $total_no_of_inputs = isset($input_advert_stats['total']) ? $input_advert_stats['total'] : 0;
                    $msg_tmp1 = str_replace("{unread_services}", $display_value, $menus_config['main_2']);
                    $msg_tmp2 = str_replace("{total_services}", $no_of_services_received, $msg_tmp1);
                    $msg_tmp3 = str_replace("{input_unread}", $inputs_display_value, $msg_tmp2);
                    $msg = str_replace("{total_inputs}", $total_no_of_inputs, $msg_tmp3);
                    $_SESSION['sent_menu_id'] = "main_2";
                    loadUssdSender($session_id, $msg, false);
                } else {
                    $_SESSION['sent_menu_id'] = "main_1";
                    loadUssdSender($session_id, $menus_config['main_1'], false);
                }
                break;
        }
    }
}

/**
 * Function for checking if the number is registered
 *
 * @global type $session_id
 * @global type $menus_config
 * @global type $address
 * @global USSDEngine $engine
 * @param  type $subscriber_input
 */
function get_init_menu_for_registered_msisdn($address) {

    global $session_id;
    global $menus_config;
    global $address;
    global $engine;
    flog("[$address] get_init_menu_for_registered_msisdn: Checking if the user is registered in the DB");
    return $engine->check_user_curl($address);
}

/**
 * Function for checking if the commission agent exists
 *
 * @global type $session_id
 * @global type $menus_config
 * @global USSDEngine $engine
 * @param  type $agent_code
 */
function get_init_menu_for_registered_agent_commission($agent_code) {
    global $session_id;
    global $menus_config;
    global $address;
    global $engine;
    flog("[$address] get_init_menu_for_registered_agent_commission: Checking if commission code is in the DB");
    return $engine->check_agent_commission_code($agent_code);
}

/**
 * Function for processing the selected option on the main menu 1
 * 
 * @global type $session_id
 * @global type $menus_config
 * @global type $address
 * @global USSDEngine $engine
 * @param  type $subscriber_input
 */
function process_main_1_response($subscriber_input) {
    global $session_id;
    global $menus_config;
    global $address;
    global $engine;
    flog("[$address] process_main_1_menu_response: " . $_SESSION['sent_menu_id'] . ", Subscriber Input is : " . $subscriber_input);
    switch ($subscriber_input) {
        case "1":
            //We do the registration process
            $provinces_menu = $engine->get_all_province_names_curl(); //Get all provinces from the DB
            if (strlen($provinces_menu) > 0) {
                $msg_tmp = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1']);
                $msg = str_replace("{lima_location_provinces}", $provinces_menu, $msg_tmp);
                $_SESSION['sent_menu_id'] = "1.1";
                loadUssdSender($session_id, $msg, false);
            } else {
                loadUssdSender($session_id, $menus_config['system_busy'], true);
            }
            break;
        case "789":
            //Register with an agent
            $msg = str_replace("{invalid_code}", $menus_config['first_display'], $menus_config['main_1.1']);
            $_SESSION['sent_menu_id'] = "main_1.1";
            loadUssdSender($session_id, $msg, false);
            break;
        default:
            $msg = str_replace("{invalid_option}", $menus_config['invalid_option'], $menus_config['main_1']);
            $_SESSION['sent_menu_id'] = "main_1";
            loadUssdSender($session_id, $msg, false); //Remain on main menu 1
            break;
    }
}

/**
 * Function for processing the selected option on the menu reg_1
 * 
 * @global type $session_id
 * @global type $menus_config
 * @global type $address
 * @global USSDEngine $engine
 * @param  type $subscriber_input
 */
function process_reg_1_farmer_update_response($subscriber_input) {
    global $session_id;
    global $menus_config;
    global $address;
    global $engine;
    flog("[$address] process_reg_1_farmer_update_response: " . $_SESSION['sent_menu_id'] . ", Subscriber Input is : " . $subscriber_input);
    switch ($subscriber_input) {
        case "1":
            //We do the registration process
            $provinces_menu = $engine->get_all_province_names_curl(); //Get all provinces from the DB
            if (strlen($provinces_menu) > 0) {
                $msg_tmp = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1']);
                $msg = str_replace("{lima_location_provinces}", $provinces_menu, $msg_tmp);
                $_SESSION['sent_menu_id'] = "1.1";
                loadUssdSender($session_id, $msg, false);
            } else {
                loadUssdSender($session_id, $menus_config['system_busy'], true);
            }
            break;
        case "0":
            //We exit the system
            loadUssdSender($session_id, $menus_config['system_exit'], true);
            break;
        default:
            $msg = str_replace("{invalid_option}", $menus_config['invalid_option'], $menus_config['reg_1']);
            $_SESSION['sent_menu_id'] = "reg_1";
            loadUssdSender($session_id, $msg, false); //Remain on main menu 1
            break;
    }
}

/**
 * Function for processing the selected option on the main menu 1
 * 
 * @global type $session_id
 * @global type $menus_config
 * @global type $address
 * @global USSDEngine $engine
 * @param  type $subscriber_input
 */
function process_main_1_pnt_1_response($subscriber_input) {
    global $session_id;
    global $menus_config;
    global $address;
    global $engine;
    flog("[$address] process_main_1_pnt_1_response: " . $_SESSION['sent_menu_id'] . ", Subscriber Input is : " . $subscriber_input);
    //Agent enters the registration code
    $reg_code = explode(" ", $subscriber_input); //Agent enters the code
    if (is_array($reg_code) && count($reg_code) > 0) {
        $_SESSION['reg_agent_entered_code'] = $reg_code[0];
        flog("Agent code returns: " . print_r($reg_code[0], true));
        //We do the registration process
        //If condition to check if the agent commission code exist in the DB
        $agent_code_exists = get_init_menu_for_registered_agent_commission($reg_code[0]);
        $_SESSION['com_agent_code_id'] = $agent_code_exists[0]['id'];
        flog("Commission agent code id: " . print_r($_SESSION['com_agent_code_id'], true));
        flog("Agent code returns: " . print_r($agent_code_exists, true));
        if (count($agent_code_exists) > 0) {
            //We start registration
            $provinces_menu = $engine->get_all_province_names_curl(); //Get all provinces from the DB
            if (strlen($provinces_menu) > 0) {
                $msg_tmp = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1']);
                $msg = str_replace("{lima_location_provinces}", $provinces_menu, $msg_tmp);
                $_SESSION['sent_menu_id'] = "1.1";
                loadUssdSender($session_id, $msg, false);
            } else {
                loadUssdSender($session_id, $menus_config['system_busy'], true);
            }
        } else {
            $_SESSION['sent_menu_id'] = "main_1.1";
            $msg = str_replace("{invalid_code}", $menus_config['invalid_code'], $menus_config['main_1.1']);
            loadUssdSender($session_id, $msg, false); //Remain on main menu main_1.1
        }
    } else {
        loadUssdSender($session_id, $menus_config['system_busy'], true);
    }
}

/**
 * Process menu 1.1 Response.
 *
 * @global type $session_id
 * @global type $menus_config
 * @global type $address
 * @global USSDEngine $engine
 * @param type $subscriber_input
 */
function process_menu_select_province_response($subscriber_input) {
    global $session_id;
    global $menus_config;
    global $address;
    global $engine;
    flog("[$address] process_menu_select_province_response: " . $_SESSION['sent_menu_id'] . ", Subscriber Input is : " . $subscriber_input);
    if (isset($_SESSION['provinces']) && isset($_SESSION['provinces'][$subscriber_input])) {
        //The user selected a valid option.
        $_SESSION['user_selected_province_id'] = $_SESSION['provinces'][$subscriber_input]['id'];  //We have to keep the selected province id.
        $_SESSION['user_selected_province_name'] = $_SESSION['provinces'][$subscriber_input]['name'];  //We have to keep the selected province name.
        //This is for the sake of being careful
        if (isset($_SESSION['districts_menu_navigators'])) {
            unset($_SESSION['districts_menu_navigators']);
        }
        //We need to fetch the province districts from the API.
        $districts_menu = $engine->get_province_districts_curl($_SESSION['user_selected_province_id']); //Get all provice districts from the API
        if (strlen($districts_menu) > 0) {
            $msg_tmp = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1.1']);
            $msg_temp1 = str_replace("{lima_location_districts}", $districts_menu, $msg_tmp); //A tempral message..
            $msg = str_replace("{province_name}", $_SESSION['user_selected_province_name'], $msg_temp1);
            $_SESSION['sent_menu_id'] = "1.1.1";
            loadUssdSender($session_id, $msg, false);
        } else {
            loadUssdSender($session_id, $menus_config['system_busy'], true);
        }
    } else {
        //The user selected an invalid option. Stay on the same menu
        $provinces_menu = $engine->get_all_province_names_curl(); //Get all provinces from the DB
        if (strlen($provinces_menu) > 0) {
            $msg_tmp = str_replace("{invalid_option}", $menus_config['invalid_option'], $menus_config['1.1']);
            $msg = str_replace("{lima_location_provinces}", $provinces_menu, $msg_tmp);
            $_SESSION['sent_menu_id'] = "1.1";
            loadUssdSender($session_id, $msg, false);
        }
    }
}

function process_no_providers_other_provinces_selection($subscr_input) {
    global $session_id;
    global $menus_config;
    global $address;
    global $engine;
    unset($_SESSION['lima_service_provider_menu_navigators']);
    switch ($subscr_input) {
        case "1":
            process_main_2_response("");
            break;
        default :
            process_main_2_response("");
            break;
    }
}

function process_no_lima_services($subscr_input) {
    global $session_id;
    global $menus_config;
    global $address;
    global $engine;
    if ($subscr_input == "1") {
        process_main_2_response("");
    } else {
        //No lima services
        $msg_tmp = str_replace("{invalid_option}", "Invalid selection", $menus_config['NO_LIMA_SERVICES']); //A tempral message...
        $_SESSION['sent_menu_id'] = "NO_LIMA_SERVICES";
        loadUssdSender($session_id, $msg_tmp, false);
    }
}

function process_providers_other_provinces_selection($subscriber_input) {
    global $session_id;
    global $menus_config;
    global $address;
    global $engine;
    flog("[$address] process_providers_other_provinces_selection: " . $_SESSION['sent_menu_id'] . ", Subscriber Input is : " . $subscriber_input);
    if (isset($_SESSION['provinces']) && isset($_SESSION['provinces'][$subscriber_input])) {
        //The user selected a valid option.
        $_SESSION['user_selected_province_id'] = $_SESSION['provinces'][$subscriber_input]['id'];  //We have to keep the selected province id.
        $_SESSION['user_selected_province_name'] = $_SESSION['provinces'][$subscriber_input]['name'];  //We have to keep the selected province name.
        if ($_SESSION['user_selected_service_cat_name'] == "Transportation") {
            //Transportation
            $transport_services_cat = $engine->get_all_transportation_service_providers($_SESSION['user_selected_province_id'], $_SESSION['user_selected_service_cat_id'], "other_prov");
            flog("Transport services providers are: " . print_r($transport_services_cat, true));
            if (strlen($transport_services_cat) > 0) {
                $msg_tmp = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['2.3.3']); //A temporal message.
                $msg = str_replace("{transport_services_providers}", $transport_services_cat, $msg_tmp);
                $msg = str_replace("{province}", $_SESSION['user_selected_province_name'], $msg);
                $_SESSION['sent_menu_id'] = "2.3.3";
                loadUssdSender($session_id, $msg, false);
            } else {
                loadUssdSender($session_id, $menus_config['system_busy'], true);
            }
        } else {
            //Other Services
            $lima_service_provider_menu = $engine->get_all_lima_service_providers($_SESSION['user_selected_province_id'], $_SESSION['lima_service_type_cat_id'], "other_prov");
            if (strlen($lima_service_provider_menu) > 0) {
                $msg_tmp = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['2.3.2.1']); //A temporal message.
                $msg = str_replace("{lima_services_providers}", $lima_service_provider_menu, $msg_tmp);
                $msg = str_replace("{province}", $_SESSION['user_selected_province_name'], $msg);
                $msg = str_replace("{type_of_service}", $_SESSION['lima_service_type_name'], $msg);
                $_SESSION['sent_menu_id'] = "2.3.2.1";
                loadUssdSender($session_id, $msg, false);
            } else {
                loadUssdSender($session_id, $menus_config['system_busy'], true);
            }
        }
    } else {
        //The user selected an invalid option. Stay on the same menu
        if ($_SESSION['user_selected_service_cat_id'] == "transportation")
            $provinces_menu = $engine->get_all_province_names_curl1($address, $_SESSION['user_selected_service_cat_id']);
        else {
            $provinces_menu = $engine->get_all_province_names_curl1($address, $_SESSION['lima_service_type_cat_id']);
        }
        if (strlen($provinces_menu) > 0) {
            $msg_tmp = str_replace("{invalid_option}", $menus_config['invalid_option'], $menus_config['1.1']);
            $msg = str_replace("{lima_location_provinces}", $provinces_menu, $msg_tmp);
            $_SESSION['sent_menu_id'] = "other_providers_province_selection";
            loadUssdSender($session_id, $msg, false);
        }
    }
}

/**
 * Function for processing the district selection or districts menu navigation.
 * 
 * @global type $session_id
 * @global type $menus_config
 * @global type $address
 * @global USSDEngine $engine
 * @param type $subscriber_input
 */
function process_menu_select_district_response($subscriber_input) {
    global $session_id;
    global $menus_config;
    global $address;
    global $engine;
    flog("[$address] process_menu_select_district_response: " . $_SESSION['sent_menu_id'] . ", Subscriber Input is : " . $subscriber_input);
    if ((isset($_SESSION['current_districts']) && isset($_SESSION['current_districts'][$subscriber_input]))) {
        //The user selected a valid option.
        $district_details = explode(",", $_SESSION['current_districts'][$subscriber_input]);
        $_SESSION['user_selected_district_id'] = $district_details[0]; //We have to keep the selected province id.
        $_SESSION['user_selected_district_name'] = $district_details[1]; //We have to keep the selected province name.
        //We need to ask a question
        $msg = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1.1.1']); //A tempral message...				
        $_SESSION['sent_menu_id'] = "1.1.1.1";
        loadUssdSender($session_id, $msg, false);
    } elseif ($subscriber_input == "00" && $_SESSION['districts_menu_navigators']['last_first_opt'] == 0) {
        //The user selected go to previous menu option
        process_main_1_response("1");
        //$fn_response =  array('TRUE', $menus_config['invalid_option']);
    } elseif ($subscriber_input == "00" && $_SESSION['districts_menu_navigators']['last_first_opt'] > 0) {
        //Previous menu...
        //Take districts navigation
        $districts_menu = $engine->format_districts($_SESSION['districts_menu_navigators']['districts'], false, true);
        if (strlen($districts_menu) > 0) {
            $msg_tmp = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1.1']);
            $msg_temp1 = str_replace("{lima_location_districts}", $districts_menu, $msg_tmp); //A tempral message..
            $msg = str_replace("{province_name}", $_SESSION['user_selected_province_name'], $msg_temp1);
            $_SESSION['sent_menu_id'] = "1.1.1";
            loadUssdSender($session_id, $msg, false);
        } else {
            loadUssdSender($session_id, $menus_config['system_busy'], true);
        }
    } elseif ($subscriber_input == "99" && ($_SESSION['districts_menu_navigators']['last_last_opt'] < count($_SESSION['districts_menu_navigators']['districts']))) {
        //More districts...
        //Take districts navigation
        $districts_menu = $engine->format_districts($_SESSION['districts_menu_navigators']['districts'], true, false);
        if (strlen($districts_menu) > 0) {
            $msg_tmp = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1.1']);
            $msg_temp1 = str_replace("{lima_location_districts}", $districts_menu, $msg_tmp); //A tempral message..
            $msg = str_replace("{province_name}", $_SESSION['user_selected_province_name'], $msg_temp1);
            $_SESSION['sent_menu_id'] = "1.1.1";
            loadUssdSender($session_id, $msg, false);
        } else {
            loadUssdSender($session_id, $menus_config['system_busy'], true);
        }
    } else {
        //The user selected an invalid option. Stay on the same menu
        $districts_menu = $engine->get_province_districts_curl($_SESSION['user_selected_province_id']); //Get all provice districts from the API
        if (strlen($districts_menu) > 0) {
            $msg_tmp = str_replace("{invalid_option}", $menus_config['invalid_option'], $menus_config['1.1.1']); // A tempral message
            $msg_tmp1 = str_replace("{lima_location_districts}", $districts_menu, $msg_tmp); //A tempral message..
            $msg = str_replace("{province_name}", $_SESSION['user_selected_province_name'], $msg_tmp1);
            $_SESSION['sent_menu_id'] = "1.1.1";
            loadUssdSender($session_id, $msg, false);
        }
    }
}

/**
 * Function for processing do you grow field crops menu.
 *
 * @global type $session_id
 * @global type $menus_config
 * @global type $address
 * @global USSDEngine $engine
 * @param type $subscriber_input
 */
function process_menu_grow_fcrops_response($subscriber_input) {
    global $session_id;
    global $menus_config;
    global $address;
    global $engine;
    flog("[$address] process_menu_grow_fcrops_response: " . $_SESSION['sent_menu_id'] . ", Subscriber Input is : " . $subscriber_input);
    switch ($subscriber_input) {
        case "1":
            //Yes
            $_SESSION['user_grows_field_crops'] = 'Yes';
            $msg = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1.1.1.1']); //A tempral message...				
            $_SESSION['sent_menu_id'] = "1.1.1.1.1";
            loadUssdSender($session_id, $msg, false);
            break;
        case "2":
            //No
            $_SESSION['user_grows_field_crops'] = 'No';
            $msg = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1.1.1.1']); //A tempral message...				
            $_SESSION['sent_menu_id'] = "1.1.1.1.1";
            loadUssdSender($session_id, $msg, false);
            break;
        default:
            //Invalid input
            $msg = str_replace("{invalid_option}", $menus_config['invalid_option'], $menus_config['1.1.1.1']); //A tempral message...				
            $_SESSION['sent_menu_id'] = "1.1.1.1";
            loadUssdSender($session_id, $msg, false);
            break;
    }
}

function process_menu_my_profile_successfuly_updated_location_menu_response($subscriber_input) {
    global $session_id;
    global $menus_config;
    global $address;
    global $engine;
    flog("[$address] process_menu_my_profile_successfuly_updated_location_menu_response: " . $_SESSION['sent_menu_id'] . ", Subscriber Input is : " . $subscriber_input);
    switch ($subscriber_input) {
        case "1":
            process_main_2_response("");
            break;
        case "2":
            process_main_2_response(4);
            break;
        default :
            if ($_SESSION["STEP"] == "CONT_OR_NOT") {
                $msg = str_replace("{invalid_option}", "", $menus_config['2.4.1.1.1.1.1']); //A tempral message...
                $_SESSION['sent_menu_id'] = "2.4.1.1.1.1.1";
                loadUssdSender($session_id, $msg, false);
            } else {
                //invalid input
                $msg = str_replace("{invalid_option}", $menus_config['invalid_option'], $menus_config['2.4.1.1.1.1.1']); //A tempral message...
                $_SESSION['sent_menu_id'] = "2.4.1.1.1.1.1";
                loadUssdSender($session_id, $msg, false);
            }
            break;
    }
}

/**
 * Function for processing do you grow vegitable crops menu.
 * 
 * @global type $session_id
 * @global type $menus_config
 * @global type $address
 * @global USSDEngine $engine
 * @param  type $subscriber_input
 */
function process_menu_grow_vcrops_response($subscriber_input) {
    global $session_id;
    global $menus_config;
    global $address;
    global $engine;
    flog("[$address] process_menu_grow_vcrops_response: " . $_SESSION['sent_menu_id'] . ", Subscriber Input is : " . $subscriber_input);
    switch ($subscriber_input) {
        case "1":
            //Yes
            $_SESSION['user_grows_vegitable_crops'] = 'Yes';
            $msg = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1.1.1.1.1']); //A tempral message...				
            $_SESSION['sent_menu_id'] = "1.1.1.1.1.1";
            loadUssdSender($session_id, $msg, false);
            break;
        case "2":
            //No
            $_SESSION['user_grows_vegitable_crops'] = 'No';
            $msg = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1.1.1.1.1']); //A tempral message...				
            $_SESSION['sent_menu_id'] = "1.1.1.1.1.1";
            loadUssdSender($session_id, $msg, false);
            break;
        default:
            //Invalid input
            $msg = str_replace("{invalid_option}", $menus_config['invalid_option'], $menus_config['1.1.1.1.1']); //A tempral message...				
            $_SESSION['sent_menu_id'] = "1.1.1.1.1";
            loadUssdSender($session_id, $msg, false);
            break;
    }
}

/**
 * Function for processing do you grow fruits crops menu.
 *
 * @global type $session_id
 * @global type $menus_config
 * @global type $address
 * @global USSDEngine $engine
 * @param  type $subscriber_input
 */
function process_menu_grow_fruits_response($subscriber_input) {
    global $session_id;
    global $menus_config;
    global $address;
    global $engine;
    flog("[$address] process_menu_grow_fruits_response: " . $_SESSION['sent_menu_id'] . ", Subscriber Input is : " . $subscriber_input);
    switch ($subscriber_input) {
        case "1":
            //Yes
            $_SESSION['user_grows_fruits'] = 'Yes';
            $msg = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1.1.1.1.1.1']); //A tempral message...				
            $_SESSION['sent_menu_id'] = "1.1.1.1.1.1.1";
            loadUssdSender($session_id, $msg, false);
            break;
        case "2":
            //No
            $_SESSION['user_grows_fruits'] = 'No';
            $msg = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1.1.1.1.1.1']); //A tempral message...				
            $_SESSION['sent_menu_id'] = "1.1.1.1.1.1.1";
            loadUssdSender($session_id, $msg, false);
            break;
        default:
            //Invalid input
            $msg = str_replace("{invalid_option}", $menus_config['invalid_option'], $menus_config['1.1.1.1.1.1']); //A tempral message...				
            $_SESSION['sent_menu_id'] = "1.1.1.1.1.1";
            loadUssdSender($session_id, $msg, false);
            break;
    }
}

/**
 * Function for processing do you keep livestock menu.
 *
 * @global type $session_id
 * @global type $menus_config
 * @global type $address
 * @global USSDEngine $engine
 * @param  type $subscriber_input
 */
function process_menu_keep_livestock_response($subscriber_input) {
    global $session_id;
    global $menus_config;
    global $address;
    global $engine;
    flog("[$address] process_menu_keep_livestock_response: " . $_SESSION['sent_menu_id'] . ", Subscriber Input is : " . $subscriber_input);
    switch ($subscriber_input) {
        case "1":
            //Yes
            $_SESSION['user_keeps_livestock'] = 'Yes';
            $msg = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1.1.1.1.1.1.1']); //A tempral message...				
            $_SESSION['sent_menu_id'] = "1.1.1.1.1.1.1.1";
            loadUssdSender($session_id, $msg, false);
            break;
        case "2":
            //No
            $_SESSION['user_keeps_livestock'] = 'No';
            $msg = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1.1.1.1.1.1.1']); //A tempral message...				
            $_SESSION['sent_menu_id'] = "1.1.1.1.1.1.1.1";
            loadUssdSender($session_id, $msg, false);
            break;
        default:
            //Invalid input
            $msg = str_replace("{invalid_option}", $menus_config['invalid_option'], $menus_config['1.1.1.1.1.1.1']); //A tempral message...				
            $_SESSION['sent_menu_id'] = "1.1.1.1.1.1.1";
            loadUssdSender($session_id, $msg, false);
            break;
    }
}

/**
 * Function for processing do you want to share your number.
 *
 * @global type $session_id
 * @global type $menus_config
 * @global type $address
 * @global USSDEngine $engine
 * @param  type $subscriber_input
 */
function process_menu_want_to_share_number_response($subscriber_input) {
    global $session_id;
    global $menus_config;
    global $address;
    global $engine;
    flog("[$address] process_menu_want_to_share_number_response: " . $_SESSION['sent_menu_id'] . ", Subscriber Input is : " . $subscriber_input);
    switch ($subscriber_input) {
        case "1":
            //Yes
            $_SESSION['user_want_to_share_number'] = 'Yes';
            $msg = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1.1.1.1.1.1.1.1']); //A tempral message...				
            $_SESSION['sent_menu_id'] = "1.1.1.1.1.1.1.1.1";
            loadUssdSender($session_id, $msg, false);
            break;
        case "2":
            //No
            $_SESSION['user_want_to_share_number'] = 'No';
            $msg = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1.1.1.1.1.1.1.1']); //A tempral message...				
            $_SESSION['sent_menu_id'] = "1.1.1.1.1.1.1.1.1";
            loadUssdSender($session_id, $msg, false);
            break;
        default:
            //Invalid input				
            $msg = str_replace("{invalid_option}", $menus_config['invalid_option'], $menus_config['1.1.1.1.1.1.1.1']); //A tempral message...				
            $_SESSION['sent_menu_id'] = "1.1.1.1.1.1.1.1";
            loadUssdSender($session_id, $msg, false);
            break;
    }
}

/**
 * Function to process if user wants to access lima links services
 *
 * @global type $session_id
 * @global type $menus_config
 * @global type $address
 * @global USSDEngine $engine
 * @param  type $subscriber_input
 */
function process_menu_acces_services_response($subscriber_input) {
    global $session_id;
    global $menus_config;
    global $address;
    global $engine;
    flog("[$address] process_menu_acces_services_response: " . $_SESSION['sent_menu_id'] . ", Subscriber Input is : " . $subscriber_input);
    switch ($subscriber_input) {
        case "1":
            //Yes
            $profile_details = $engine->check_user_curl($address);
            flog("Profile details: " . print_r($profile_details, true));
            $ussd_profile_id = isset($profile_details[0]['id']) ? $profile_details[0]['id'] : 0;
            $is_active = isset($profile_details[0]['is_active']) ? $profile_details[0]['is_active'] : 0;
            $user_type = isset($profile_details[0]['user_type']) ? $profile_details[0]['user_type'] : 0;
            $is_ussd_registered = isset($profile_details[0]['is_ussd_registered']) ? $profile_details[0]['is_ussd_registered'] : 0;
            flog("Farmer profile ID: " . print_r($ussd_profile_id, true));
            flog("Active farmer: " . print_r($is_active, true));
            flog("USSD user type: " . print_r($user_type, true));
            flog("Is USSD Registered: " . print_r($is_ussd_registered, true));
            if ((is_array($profile_details)) && (count($profile_details) > 0) && ($is_ussd_registered == 0) && ($is_active == 1)) {
                if ($user_type == "Farmer") {
                    $engine->update_farmer($address, $ussd_profile_id); //Update farmer
                    $engine->update_profile($address, $ussd_profile_id);
                } else {
                    $engine->update_profile($address, $ussd_profile_id);
                }
            } else {
                $engine->insert_farmer($address); //create new farmer
            }
            $msg = str_replace("{invalid_option}", $menus_config['first_display'], $menus_config['1.1.1.1.1.1.1.1.1.1']);
            $_SESSION['sent_menu_id'] = "1.1.1.1.1.1.1.1.1.1";
            loadUssdSender($session_id, $msg, true);
            break;
        case "0":
            //No Exit
            loadUssdSender($session_id, $menus_config['system_exit'], true);
            break;
        default:
            //Invalid input
            $msg = str_replace("{invalid_option}", $menus_config['invalid_option'], $menus_config['1.1.1.1.1.1.1.1.1']); //A tempral message...				
            $_SESSION['sent_menu_id'] = "1.1.1.1.1.1.1.1.1";
            loadUssdSender($session_id, $msg, false);
            break;
    }
}

/**
 * Get the session id and Response message as parameter
 * Create sender object and send ussd with appropriate parameters
 */
function loadUssdSender($session_id, $response_string, $is_end_of_session) {
    global $sequence;

    if ($_SESSION['sent_menu_id'] != "cont_or_not") {
        $_SESSION['last_menu_id'] = $_SESSION['sent_menu_id']; //Set the last_menu_id only if you are not sending back the do you want to continue or not menu.
    }

    if ($is_end_of_session == true) {
        $is_end_of_session = "TRUE";
        session_unset();
    } else {
        $is_end_of_session = "FALSE";
    }
    $main_menu_response = "<methodResponse>
      <params><param>
      <value>
      <struct>
      <member>
      <name>RESPONSE_CODE</name>
      <value>
      <string>0</string>
      </value>
      </member><member>
      <name>REQUEST_TYPE</name>
      <value>
      <string>REQUEST</string>
      </value>
      </member><member>
      <name>SESSION_ID</name>
      <value>
      <string>" . $session_id . "</string>
      </value>
      </member><member>
      <name>SEQUENCE</name>
      <value>
      <string>" . $sequence . "</string>
      </value>
      </member><member>
      <name>USSD_BODY</name>
      <value>
      <string>" . str_replace("&", "and", $response_string) . "
      </string>
      </value>
      </member><member>
      <name>END_OF_SESSION</name>
      <value>
      <string>" . $is_end_of_session . "</string>
      </value>
      </member>
      </struct>
      </value>
      </param></params>
      </methodResponse>";
    /*
      $response = [];
      $response['RESPONSE_CODE'] = '0';
      $response['SESSION_ID'] = $session_id;
      $response['SEQUENCE'] = $sequence;
      $response['USSD_BODY'] = $response_string;
      $response['END_OF_SESSION'] = $is_end_of_session;
      $response['REQUEST_TYPE'] = 'RESPONSE'; */
    //return $main_menu_response;
    flog("Response to the gateway is:" . print_r($main_menu_response, true));
    //echo xmlrpc_encode($response);
    echo $main_menu_response;
}
