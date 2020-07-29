<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$payload = ['seller_mobile_number' => "260969240309"];
$url = "http://18.188.249.56/MarketSalesAPI/v1/market_fee";
$ch = curl_init($url . '?' . http_build_query($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//Spend n seconds trying to connect to.
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 90);
//Take n seconds to complete operations.
curl_setopt($ch, CURLOPT_TIMEOUT, 90);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode == 404) {
    $result = "";
}
curl_close($ch);

$json_response = json_decode($result, TRUE);

if ($json_response['found']) {
    echo "Stands are";
    foreach ($json_response['market_fee'] as $key => $value) {
        echo "<br>" . ($key + 1) . ".Stand " . $value['stand_number'] . " - K" . $value['stand_price'];
    }
} else {
    echo "No result";
}

