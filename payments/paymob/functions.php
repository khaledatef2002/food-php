<?php

function generatePayment($order_id, $amount)
{
    $get_paymob_secret_key = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='paymob_secret_key'");
    $paymob_secret_key = mysqli_fetch_assoc($get_paymob_secret_key)['value'];

    $get_paymob_integration_id = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='paymob_integration_id'");
    $paymob_integration_id = mysqli_fetch_assoc($get_paymob_integration_id)['value'];

    $get_paymob_public_key = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='paymob_public_key'");
    $paymob_public_key = mysqli_fetch_assoc($get_paymob_public_key)['value'];

    $server = $_SERVER['SERVER_NAME'];
    $redirect_url = "https://$server/visa_payment";
    $webhook_url = "https://$server/payments/paymob/webhook.php";
    
    $get_order_data = mysqli_query($GLOBALS['conn'], "SELECT * FROM visa_orders_req WHERE id='" . $order_id . "'");
    $order_data = mysqli_fetch_assoc($get_order_data);
    $phone = $order_data['client_phone'] ?? '';

    $data = [
        "amount" => $amount * 100,
        "currency" => "EGP",
        "payment_methods" => [
            (int)$paymob_integration_id
        ],
        "billing_data" => [
            "first_name" => "ala",
            "last_name" => "zain",
            "phone_number" => $phone,
            "email" => "",
        ],
        "expiration" => 3600,
        "notification_url" => $webhook_url,
        "redirection_url" => $redirect_url,
    ];

    $jsonData = json_encode($data);

    $ch = curl_init('https://accept.paymob.com/v1/intention/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Token ' . $paymob_secret_key
    ]);
    $response = curl_exec($ch);

    $data = json_decode($response, true);
    
    $operation_id = $data['intention_order_id'] ?? null;
    $client_secret = $data['client_secret'] ?? null;

    mysqli_query($GLOBALS['conn'], "UPDATE visa_orders_req SET operation_id='" . $operation_id . "' WHERE id='" . $order_id . "'");

    return json_encode([
        "res" => "success",
        "payment_url" => "https://accept.paymob.com/unifiedcheckout/?publicKey=". $paymob_public_key ."&clientSecret=" . $client_secret
    ]);
}

function validate_redirect_payment_response($get_params)
{
    $get_paymob_hmac = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='paymob_hmac'");
    $paymob_hmac = mysqli_fetch_assoc($get_paymob_hmac)['value'];

    $keys_order = [
        'amount_cents',
        'created_at',
        'currency',
        'error_occured',
        'has_parent_transaction',
        'id',
        'integration_id',
        'is_3d_secure',
        'is_auth',
        'is_capture',
        'is_refunded',
        'is_standalone_payment',
        'is_voided',
        'order.id',
        'owner',
        'pending',
        'source_data.pan',
        'source_data.sub_type',
        'source_data.type',
        'success',
    ];

    $concatenated_string = '';

    foreach ($keys_order as $key) {
        if (strpos($key, '.') !== false) {
            [$parent, $child] = explode('.', $key, 2);
            $value = $get_params[$parent][$child] ?? '';
        } else {
            $value = $get_params[$key] ?? '';
        }

        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }

        $concatenated_string .= $value;
    }

    $hmac = hash_hmac('sha512', $concatenated_string, $paymob_hmac);

    return $hmac === $get_params['hmac'];
}

function validate_webhook($data, $hmac) {
    $order_obj = $data['obj'] ?? [];

    $keys = [
        'amount_cents',
        'created_at',
        'currency',
        'error_occured',
        'has_parent_transaction',
        'obj.id',             
        'integration_id',
        'is_3d_secure',
        'is_auth',
        'is_capture',
        'is_refunded',
        'is_standalone_payment',
        'is_voided',
        'order.id',
        'owner',
        'pending',
        'source_data.pan',
        'source_data.sub_type',
        'source_data.type',
        'success'
    ];

    function get_nested_value($array, $key) {
        $parts = explode('.', $key);
        $value = $array;
        foreach ($parts as $part) {
            if (isset($value[$part])) {
                $value = $value[$part];
            } else {
                $value = '';
                break;
            }
        }
        return $value;
    }

    $hmac = '';
    foreach ($keys as $key) {
        if ($key === 'obj.id') {
            $hmac .= $order_obj['id'] ?? '';
        } else {
            $hmac .= get_nested_value($order_obj, $key);
        }
    }

    $get_paymob_hmac = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='paymob_hmac'");
    $paymob_hmac = mysqli_fetch_assoc($get_paymob_hmac)['value'];

    $calculated_hmac = hash_hmac('sha256', $hmac, $paymob_hmac);

    if ($calculated_hmac === $hmac) {
        return true;
    }

    return false;
}