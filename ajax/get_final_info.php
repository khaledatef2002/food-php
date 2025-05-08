<?php

include '../includes/conn.php';
include '../includes/functions.php';

if (!is_work() || is_disabled()) {
    exit();
}

session_start();
$cart = $_SESSION['cart'] ?? array();

$data = array();

// Getting Total Card Price
$data['price'] = calc_total_price($_SESSION['cart']);

//getting data
$rec = $_POST['data'];
$rec['phone'] = filter_var(trim($rec['phone']), FILTER_SANITIZE_NUMBER_INT);


$_POST['del'] = filter_var($rec['del'], FILTER_SANITIZE_NUMBER_INT);


// Getting Delivery Price
$get_price = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_locations WHERE id='" . $_POST['del'] . "'");
$fetch = mysqli_fetch_assoc($get_price);

$data['del'] = $fetch['price'];

// Getting Delivery Time
$get_price = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='delivery_time'");
$fetch = mysqli_fetch_assoc($get_price);

$data['del_time'] = $fetch['value'];

// Getting Delivery Phone
$get_price = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='wh_order'");
$fetch = mysqli_fetch_assoc($get_price);

// Getting Visa Tax
$get_visa_av = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='visa_av'");
$visa_av = mysqli_fetch_assoc($get_visa_av)['value'];

if ($visa_av == 1 && $rec['method'] == 1) :

    $get_fixed = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='visa_tax_fixed'");
    $data['visa_fixed_tax'] = mysqli_fetch_assoc($get_fixed)['value'];

    $get_percent = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='visa_tax_percent'");
    $data['visa_percent_tax'] = mysqli_fetch_assoc($get_percent)['value'];

else :
    $data['visa_fixed_tax'] = 0;
    $data['visa_percent_tax'] = 0;
endif;

$data['wh'] = $fetch['value'];

// Card Info
$data['items'] = array();
foreach ($cart as $key => $item) {
    $item_info = get_item_info($item['item_id']);
    $data_item = array("name" => $item_info['title'], "count" => $item['count']);
    if (isset($item['size'])) {
        $data_item["size"] = get_size_info($item['size'])['size_name'];
        $data_item["price"] = get_size_info($item['size'])['size_price'];
    } else {
        $data_item["price"] = $item_info['price'];
    }
    if (isset($item['options'])) {
        $options = array();
        foreach ($item['options'] as $k => $option) {
            if (is_array($option['option_value'])) {
                $vl = "";
                $price = "";
                $my_option = [];
                foreach ($option['option_value'] as $ky => $val) {
                    if ($ky > 0) {
                        $vl = $vl .  ",";
                        $price = $price . ",";
                    }
                    $vl = $vl . get_option_value_info($val)['name'];
                    $price = $price . get_option_value_info($val)['price'];
                    array_push($my_option, ["name" => $vl, "price" => $price]);
                }
                $option = array(
                    "name" => get_option_info($option['option_id'])['name'],
                    "split" => true,
                    "value" => $vl,
                    "price" => $price
                );
            } else {
                $option = array(
                    "name" => get_option_info($option['option_id'])['name'],
                    "value" => get_option_value_info($option['option_value'])['name'],
                    "price" => get_option_value_info($option['option_value'])['price']
                );
            }
            array_push($options, $option);
        }
        $data_item["options"] = $options;
    }
    array_push($data['items'], $data_item);
}

if (isset($_POST['data']['discount'])) {
    $discount_data = get_discount_values($_POST['data']['discount']);
    $data['discount'] = $discount_data;
}

$tax = get_total_tax($cart) + (get_general_tax() * $data['del'] / 100);

$data['tax'] = $tax;

echo json_encode($data);
