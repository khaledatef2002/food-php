<?php

function generatePayment($order_id, $amount)
{
    $get_settings_merchant = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='visa_qnb_merchant_id'");
    $merchantID = mysqli_fetch_assoc($get_settings_merchant)['value'];

    $get_settings_api_password = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='visa_qnb_api_password'");
    $api_password = mysqli_fetch_assoc($get_settings_api_password)['value'];

    $url = 'https://qnbalahli.gateway.mastercard.com/api/rest/version/67/merchant/'. $merchantID .'/session';
    $server = $_SERVER['SERVER_NAME'];
    $redirect_url = "https://$server/visa_payment";
    
    $get_settings_title = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='site-title'");
    $site_title = mysqli_fetch_assoc($get_settings_title)['value'];
    
    $get_settings_logo = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='site-logo'");
    $site_logo = mysqli_fetch_assoc($get_settings_logo)['value'];

    $data = [
        "apiOperation" => "INITIATE_CHECKOUT",
        "interaction" => [
            "operation" => "PURCHASE",
            "displayControl" => [
                "billingAddress" => "HIDE",
                "customerEmail" => "HIDE"
            ],
            "merchant" => [
                "name" => $site_title,
                "url" => "https://$server",
                "logo" => "https://$server/$site_logo"
            ],
            "returnUrl" => $redirect_url
        ],
        "order" => [
            "currency" => "EGP",
            "amount" => number_format((float)$amount, 2, '.', ''),
            "id" => $order_id,
            "description" => "Payment order for total amount of " . number_format((float)$amount, 2, '.', '')
        ]
    ];

    $jsonData = json_encode($data);

    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_USERPWD, 'merchant.'. $merchantID .':' . $api_password);

    // Execute the request
    $response = curl_exec($ch);

    $data = json_decode($response, true);

    $sessionId = $data['session']['id'] ?? null;

    $operation_id = $data['successIndicator'] ?? null;

    $add_cart = mysqli_query($GLOBALS['conn'], "UPDATE visa_orders_req SET operation_id='" . $operation_id . "' WHERE id='" . $order_id . "'");

    return json_encode([
        "res" => "success",
        "session_id" => $sessionId
    ]);
}
