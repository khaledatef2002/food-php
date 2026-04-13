<?php

include '../../includes/conn.php';
include '../../includes/functions.php';
include 'functions.php';

$get_settings_api_av = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='visa_av'");
$isAvillable = mysqli_fetch_assoc($get_settings_api_av)['value'];

error_log("visa vailable");

if ($isAvillable == 0) {
    exit();
}

$payload = file_get_contents('php://input');
error_log("Webhook received: " . $payload);
$data = json_decode($payload, true);

if (!isset($data['obj']['order']['id'])) {
    exit('Order ID not found in payload');
}

if($data['obj']['success'] != true && $data['obj']['pending'] != true) {
    exit('Payment not successful');
}

error_log("order id found ");
$order_id = $data['obj']['order']['id'];

$order_query = mysqli_query($GLOBALS['conn'], "SELECT * FROM visa_orders_req WHERE operation_id = " . intval($order_id));
$order = mysqli_fetch_assoc($order_query);

if (!$order) {
    exit;
}

error_log("order found ");


if (isset($order['visa_providor']) && strtolower($order['visa_providor']) === 'paymob') {
    error_log("paymob ");

    if(validate_webhook($data, $_GET['hmac'])) {
        error_log("valid hmac ");

        save_visa_order($order_id);

        error_log("saved ");

    }
}