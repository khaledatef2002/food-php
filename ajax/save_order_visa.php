<?php

include '../includes/conn.php';
include '../includes/functions.php';

$get_settings_api_av = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='visa_av'");
$isAvillable = mysqli_fetch_assoc($get_settings_api_av)['value'];

$get_settings_api = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='API_KEY'");
$API_KEY = mysqli_fetch_assoc($get_settings_api)['value'];

// if(isAvillable == 0)
// {
//     exit;
// }

$jsonData = file_get_contents('php://input');

$parsedInput = json_decode($jsonData, true);

$data_obj = $parsedInput['data'];
$event = $parsedInput['event'];

if (check_signatures() && $event == "pay" && $data_obj['status'] == "SUCCESS") {
    $order = $data_obj['merchantOrderId'];
    $amount_cents = $data_obj['amount'];
    $transaction_id = $data_obj['kashierOrderId']; //DB

    $get_order_data = mysqli_query($GLOBALS['conn'], "SELECT * FROM visa_orders_req WHERE id='" . $order . "' AND status = 0");
    if (mysqli_num_rows($get_order_data) > 0) {
        $order_data = mysqli_fetch_assoc($get_order_data);

        $date = time();

        $add_cart = mysqli_query($GLOBALS['conn'], "INSERT INTO food_orders(client_name,client_phone,client_branch_id,client_branch,client_area_id,client_area_name,client_address,address_price,client_notice,ordered_date,discount_id,discount_code,discount_name,delivery_discount,total_discount, method, transaction_id, paid, tax) VALUES ('" . $order_data['client_name'] . "','" . $order_data['client_phone'] . "','" . $order_data['client_branch_id'] . "','" . $order_data['client_branch'] . "','" . $order_data['client_area_id'] . "','" . $order_data['client_area_name'] . "','" . $order_data['client_address'] . "','" . $order_data['address_price'] . "','" . $order_data['client_notice'] . "','" . $date . "','" . $order_data['discount_id'] . "','" . $order_data['discount_code'] . "','" . $order_data['discount_name'] . "','" . $order_data['delivery_discount'] . "','" . $order_data['total_discount'] . "',1,'" . $transaction_id . "','" . $amount_cents . "','" . $order_data['tax'] . "')");
        $order_id = mysqli_insert_id($GLOBALS['conn']);
        http_response_code(200);
        $notify = mysqli_query($GLOBALS['conn'], "INSERT INTO live_notify(page,type,data) VALUES('order', 'add', '$order_id')");

        $get_cart_data = mysqli_query($GLOBALS['conn'], "SELECT * FROM visa_cart_req WHERE order_id='" . $order_data['id'] . "'");
        while ($cart_data = mysqli_fetch_assoc($get_cart_data)) {
            $insert_cart_data = mysqli_query($GLOBALS['conn'], "INSERT INTO food_order_cart(order_id,item_id,item_name,item_count,item_price,item_size,item_size_name) VALUES('" . $order_id . "','" . $cart_data['item_id'] . "','" . $cart_data['item_name'] . "','" . $cart_data['item_count'] . "','" . $cart_data['item_price'] . "','" . $cart_data['item_size'] . "','" . $cart_data['item_size_name'] . "')");

            $order_card_id = mysqli_insert_id($GLOBALS['conn']);

            $get_options_data = mysqli_query($GLOBALS['conn'], "SELECT * FROM visa_options_req WHERE order_card_id='" . $cart_data['id'] . "'");
            while ($options_data = mysqli_fetch_assoc($get_options_data)) {
                $insert_cart_data = mysqli_query($GLOBALS['conn'], "INSERT INTO food_order_options(order_card_id, option_id, option_value_id, option_name, option_value, option_price) VALUES('" . $order_card_id . "','" . $options_data['option_id'] . "','" . $options_data['option_value_id'] . "','" . $options_data['option_name'] . "','" . $options_data['option_value'] . "','" . $options_data['option_price'] . "')");
            }
        }

        mysqli_query($GLOBALS['conn'], "UPDATE visa_orders_req SET status = 1 WHERE id='" . $order . "'");
    }
}

function check_signatures()
{
    global $data_obj;

    sort($data_obj['signatureKeys']);

    $headers = getallheaders();

    // Lower case all keys
    $headers = array_change_key_case($headers);
    $kashierSignature = $headers['x-kashier-signature'];

    $data = [];
    foreach ($data_obj['signatureKeys'] as $key) {
        $data[$key] = $data_obj[$key];
    }
    $queryString = http_build_query($data, $numeric_prefix = "", $arg_separator = '&', $encoding_type = PHP_QUERY_RFC3986);
    $signature = hash_hmac('sha256', $queryString, $GLOBALS['API_KEY'], false);
    if ($signature == $kashierSignature) {
        return true;
    } else {
        return false;
    }
}
