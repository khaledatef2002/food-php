<?php

function calc_total_price($arr)
{
    $price = 0;
    foreach ($arr as $key => $item) {
        if (isset($item['size']) && is_numeric($item['size'])) {
            $price += (get_size_info($item['size'])['size_price'] * $item['count']);
        } else {
            $price += (get_item_price($item['item_id']) * $item['count']);
        }
        if (isset($item['options'])) {
            foreach ($item['options'] as $k => $option) {
                if (is_array($option['option_value'])) {
                    foreach ($option['option_value'] as $ky => $val) {
                        $price += get_option_value_info($val)['price'] * $item['count'];
                    }
                } else {
                    $price += get_option_value_info($option['option_value'])['price'] * $item['count'];
                }
            }
        }
    }
    return $price;
}

function get_total_tax(array $arr): float
{
    $price = 0;
    foreach ($arr as $key => $item) {
        $id = $item['item_id'];
        $item_price = calc_item_price($item);
        $count = $item['count'];

        $item_tax = get_item_info($id)['tax'];
        $general_tax = get_general_tax();

        if ($item_tax > 0) {
            $tax = $item_tax * $item_price / 100;
            return $tax;
        } else if ($general_tax > 0) {
            $tax = $general_tax * $item_price / 100;
            return $tax;
        } else {
            return 0;
        }
    }
}

function get_general_tax(): float
{
    $get_tax_settings = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='tax'");
    $tax = mysqli_fetch_assoc($get_tax_settings)['value'];

    return $tax;
}

function calc_total_count($arr)
{
    $count = 0;
    foreach ($arr as $key => $item) {
        $count += $item['count'];
    }
    return $count;
}

function get_item_price($id)
{
    if (is_nan($id)) {
        return false;
    }
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $get_items = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items WHERE id='" . $id . "'");
    $item = mysqli_fetch_assoc($get_items);
    return $item['price'];
}

function get_item_info($id)
{
    if (is_nan($id)) {
        return false;
    }
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

    $get_item = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items WHERE id='" . $id . "'");
    $item =  mysqli_fetch_assoc($get_item);
    return $item;
}
function get_size_info($id)
{
    if (is_nan($id)) {
        return false;
    }
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

    $get_item = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items_sizes WHERE id='" . $id . "'");
    $item =  mysqli_fetch_assoc($get_item);
    return $item;
}

function get_option_info($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

    $get_option = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items_options WHERE id='$id'");
    return mysqli_fetch_assoc($get_option);
}

function get_option_value_info($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

    $get_option_value = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items_options_values WHERE id='$id'");
    return mysqli_fetch_assoc($get_option_value);
}

function get_area_info($id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

    $get_area = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_locations WHERE id='" . $id . "'");
    $area = mysqli_fetch_assoc($get_area);
    return $area;
}

function calc_item_price($item)
{
    $item_info = get_item_info($item['item_id']);
    $price = 0;
    $price += ((isset($item['size'])) ? get_size_info($item['size'])['size_price'] : $item_info['price']) * $item['count'];
    if (isset($item['options']) && count($item['options']) > 0) {
        foreach ($item['options'] as $key => $option) {
            if (isset($option['option_value']) && is_array($option['option_value'])) {
                $option_info = get_option_info($option['option_id']);
                foreach ($option['option_value'] as $k => $val) {
                    $option_value_price = get_option_value_info($val)['price'];
                    $price += $option_value_price * $item['count'];
                }
            } else if (isset($option['option_value'])) {
                $option_value_price = get_option_value_info($option['option_value'])['price'];
                $price += $option_value_price * $item['count'];
            }
        }
    }
    return $price;
}
function is_work()
{
    $date_arr = array("Saturday" => 0, "Sunday" => 1, "Monday" => 2, "Tuesday" => 3, "Wednesday" => 4, "Thursday" => 5, "Friday" => 6);

    $week = date("l");

    $current_date = $date_arr[$week] . date("His");


    $select = mysqli_query($GLOBALS['conn'], "SELECT * FROM work_periods WHERE (from_date < to_date AND from_date <= '$current_date' AND to_date >= '$current_date') OR (from_date > to_date AND (from_date <= '$current_date' OR to_date >= '$current_date'))");

    return mysqli_num_rows($select) > 0;
}

function is_disabled()
{
    $select = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title = 'order_av' AND value = '1'");


    return mysqli_num_rows($select) <= 0;
}

function order_disabled_msg()
{
    $select = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title = 'order_dis_msg'");


    return mysqli_fetch_assoc($select)['value'];
}

function check_disccount_code_av($data)
{
    $data['code'] = mysqli_real_escape_string($GLOBALS['conn'], $data['code']);
    $get_code = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_discounts WHERE code='" . $data['code'] . "'");
    if (mysqli_num_rows($get_code) > 0) {
        $code = mysqli_fetch_assoc($get_code);
        if ($code['min_order'] > 0) {
            $total_price = calc_total_price($_SESSION['cart']);
            if ($total_price < $code['min_order']) {
                return false;
            }
        }
        if ($code['max_uses'] > 0 && check_total_uses($code['id']) >= $code['max_uses']) {
            return false;
        }
        if (($code['phone'] == 1 || $code['phone'] == 2 || $code['max_user_use'] > 0) && (!is_phone_valid($data['phone']) || !isset($data['phone']) || empty($data['phone']))) {
            return false;
        }
        if ($code['max_user_use'] > 0 && (!is_phone_valid($data['phone']) || check_user_uses($code['id'], $data['phone']) >= $code['max_user_use'])) {
            return false;
        }

        if ($code['locations_type'] == 1 && !is_nan($data['location']) && !check_discount_location($code['id'], $data['location'])) {
            return false;
        } else if ($code['locations_type'] == 2 && !is_nan($data['location']) && check_discount_location($code['id'], $data['location'])) {
            return false;
        }

        if ($code['phone'] == 1 && (!is_phone_valid($data['phone']) || !check_discount_phone($code['id'], $data['phone']))) {
            return false;
        } else if ($code['phone'] == 2 && !is_phone_valid($data['phone']) && check_discount_phone($code['id'], $data['phone'])) {
            return false;
        } else if ($code['active'] == 0 || ($code['active'] == 2 && (time() < $code['start_date'] || time() > $code['end_date']))) {
            return false;
        } else {
            //check for items and categories
            if ($code['categories_type'] > 0 || $code['items_type'] > 0) {
                $items_to_discount = 0;
                $cart = $_SESSION['cart'] ?? array();
                foreach ($cart as $key => $item) {
                    $item_info = get_item_info($item['item_id']);
                    if ($code['categories_type'] == 0) {
                        if ($code['items_type'] == 0) {
                            $items_to_discount++;
                        } else if ($code['items_type'] == 1 && check_item_discount($code['id'], $item['item_id'])) {
                            $items_to_discount++;
                        } else if ($code['items_type'] == 2 && !check_item_discount($code['id'], $item['item_id'])) {
                            $items_to_discount++;
                        }
                    } else if ($code['categories_type'] == 1) {
                        if ($code['items_type'] == 0 && check_category_discount($code['id'], $item_info['cat_id'])) {
                            $items_to_discount++;
                        } else if ($code['items_type'] == 1 && (check_category_discount($code['id'], $item_info['cat_id']) || check_item_discount($code['id'], $item['item_id']))) {
                            $items_to_discount++;
                        } else if ($code['items_type'] == 2 && (check_category_discount($code['id'], $item_info['cat_id']) && !check_item_discount($code['id'], $item['item_id']))) {
                            $items_to_discount++;
                        }
                    } else if ($code['categories_type'] == 2) {
                        if ($code['items_type'] == 0 && !check_category_discount($code['id'], $item_info['cat_id'])) {
                            $items_to_discount++;
                        } else if ($code['items_type'] == 1 && (!check_category_discount($code['id'], $item_info['cat_id']) || check_item_discount($code['id'], $item['item_id']))) {
                            $items_to_discount++;
                        } else if ($code['items_type'] == 2 && (!check_category_discount($code['id'], $item_info['cat_id']) && !check_item_discount($code['id'], $item['item_id']))) {
                            $items_to_discount++;
                        }
                    }
                }
                if ($items_to_discount > 0) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        return true;
    } else {
        return false;
    }
}
function check_total_uses($discount_id)
{
    $discount_id = filter_var($discount_id, FILTER_SANITIZE_NUMBER_INT);

    $get_code = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE discount_id='" . $discount_id . "'");
    return mysqli_num_rows($get_code);
}
function check_user_uses($discount_id, $phone)
{
    $discount_id = filter_var($discount_id, FILTER_SANITIZE_NUMBER_INT);
    $phone = mysqli_real_escape_string($GLOBALS['conn'], $phone);

    $get_code = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE discount_id='" . $discount_id . "' AND client_phone='" . $phone . "'");
    return mysqli_num_rows($get_code);
}
function check_discount_location($discount_id, $location_id)
{
    $discount_id = filter_var($discount_id, FILTER_SANITIZE_NUMBER_INT);
    $location_id = filter_var($location_id, FILTER_SANITIZE_NUMBER_INT);

    $get_code = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_discounts_locations WHERE discount_id='" . $discount_id . "' AND location_id='" . $location_id . "'");
    return mysqli_num_rows($get_code) > 0;
}
function check_discount_phone($discount_id, $phone)
{
    $discount_id = filter_var($discount_id, FILTER_SANITIZE_NUMBER_INT);
    $phone = mysqli_real_escape_string($GLOBALS['conn'], $phone);

    $get_code = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_discounts_phones WHERE discount_id='" . $discount_id . "' AND phone='" . $phone . "'");
    return mysqli_num_rows($get_code) > 0;
}

function is_phone_valid($phone)
{
    if (strlen(trim($phone)) != 11 || !(substr($phone, 0, 3) != "010" || substr($phone, 0, 3) != "011" || substr($phone, 0, 3) != "012" || substr($phone, 0, 3) != "015") || is_nan($phone)) {
        return false;
    }
    return true;
}


function get_discount_values($data_discount)
{
    $discount_data = [
        "discount_id" => "",
        "discount_code" => "",
        "discount_name" => "",
        "discount_total" => 0,
        "discount_delv" => 0,
    ];
    if (check_disccount_code_av($data_discount)) {
        $thecode = mysqli_real_escape_string($GLOBALS['conn'], $data_discount['code']);
        $get_code = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_discounts WHERE LOWER(code)=LOWER('" . $thecode . "')");
        $code = mysqli_fetch_assoc($get_code);
        $discount_data['discount_id'] = $code['id'];
        $discount_data['discount_code'] = $code['code'];
        $discount_data['discount_name'] = $code['name'];


        if (($code['categories_type'] > 0 || $code['items_type'] > 0) && $code['discount_type'] == 0) {
            $discount_data['discount_total'] = calc_included_items_discount($_SESSION['cart'], $code);
        } else if ($code['discount_type'] == 0) {
            $price = calc_total_price($_SESSION['cart']) + get_total_tax($_SESSION['cart']);
            if ($code['discount_value_type'] == 0) {
                if ($code['discount_value'] > $price) {
                    $discount_data['discount_total'] = $price;
                } else {
                    $discount_data['discount_total'] = $code['discount_value'];
                }
            } else if ($code['discount_value_type'] == 1) {
                $discount_data['discount_total'] = $code['discount_value'] * $price / 100;
            }
        } else if ($code['discount_type'] == 1) {
            $data_discount['location'] = filter_var($data_discount['location'], FILTER_SANITIZE_NUMBER_INT);
            $location = get_area_info($data_discount['location']);
            $price = $location['price'] + (get_general_tax() * $location['price'] / 100);
            if ($code['discount_value_type'] == 0) {
                if ($code['discount_value'] > $price) {
                    $discount_data['discount_delv'] = $price;
                } else {
                    $discount_data['discount_delv'] = $code['discount_value'];
                }
            } else if ($code['discount_value_type'] == 1) {
                $discount_data['discount_delv'] = $code['discount_value'] * $price / 100;
            }
        }

        //Check for max
        if ($code['max_discount'] > 0 && $code['max_discount'] < $discount_data['discount_total']) {
            $discount_data['discount_total'] = $code['max_discount'];
        }
        if ($code['max_discount'] > 0 && $code['max_discount'] < $discount_data['discount_delv']) {
            $discount_data['discount_delv'] = $code['max_discount'];
        }
    }

    return $discount_data;
}

function check_item_discount($dis_id, $item_id)
{
    $dis_id = filter_var($dis_id, FILTER_SANITIZE_NUMBER_INT);
    $item_id = filter_var($item_id, FILTER_SANITIZE_NUMBER_INT);

    $get = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_discounts_items WHERE discount_id='$dis_id' AND item_id='$item_id'");
    return mysqli_num_rows($get) > 0;
}
function check_category_discount($dis_id, $cat_id)
{
    $dis_id = filter_var($dis_id, FILTER_SANITIZE_NUMBER_INT);
    $cat_id = filter_var($cat_id, FILTER_SANITIZE_NUMBER_INT);

    $get = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_discounts_cat WHERE discount_id='$dis_id' AND category_id='$cat_id'");
    return mysqli_num_rows($get) > 0;
}

function calc_included_items_price($cart, $code)
{
    $total_price = 0;
    foreach ($cart as $key => $item) {
        //check for items and categories
        $price = calc_item_price($item);
        $count = $item['count'];
        $items_to_discount = 0;
        $item_info = get_item_info($item['item_id']);
        if ($code['categories_type'] == 0) {
            if ($code['items_type'] == 0) {
                $items_to_discount++;
            } else if ($code['items_type'] == 1 && check_item_discount($code['id'], $item['item_id'])) {
                $items_to_discount++;
            } else if ($code['items_type'] == 2 && !check_item_discount($code['id'], $item['item_id'])) {
                $items_to_discount++;
            }
        } else if ($code['categories_type'] == 1) {
            if ($code['items_type'] == 0 && check_category_discount($code['id'], $item_info['cat_id'])) {
                $items_to_discount++;
            } else if ($code['items_type'] == 1 && (check_category_discount($code['id'], $item_info['cat_id']) || check_item_discount($code['id'], $item['item_id']))) {
                $items_to_discount++;
            } else if ($code['items_type'] == 2 && (check_category_discount($code['id'], $item_info['cat_id']) && !check_item_discount($code['id'], $item['item_id']))) {
                $items_to_discount++;
            }
        } else if ($code['categories_type'] == 2) {
            if ($code['items_type'] == 0 && !check_category_discount($code['id'], $item_info['cat_id'])) {
                $items_to_discount++;
            } else if ($code['items_type'] == 1 && (!check_category_discount($code['id'], $item_info['cat_id']) || check_item_discount($code['id'], $item['item_id']))) {
                $items_to_discount++;
            } else if ($code['items_type'] == 2 && (!check_category_discount($code['id'], $item_info['cat_id']) && !check_item_discount($code['id'], $item['item_id']))) {
                $items_to_discount++;
            }
        }

        if ($items_to_discount > 0) {
            $total_price += ($price);
        }
    }
    return $total_price;
}

function calc_included_items_discount($cart, $code)
{
    $discount = 0;
    foreach ($cart as $key => $item) {
        //check for items and categories
        $price = calc_item_price($item);
        $count = $item['count'];
        $items_to_discount = 0;
        $item_info = get_item_info($item['item_id']);
        if ($code['categories_type'] == 0) {
            if ($code['items_type'] == 0) {
                $items_to_discount++;
            } else if ($code['items_type'] == 1 && check_item_discount($code['id'], $item['item_id'])) {
                $items_to_discount++;
            } else if ($code['items_type'] == 2 && !check_item_discount($code['id'], $item['item_id'])) {
                $items_to_discount++;
            }
        } else if ($code['categories_type'] == 1) {
            if ($code['items_type'] == 0 && check_category_discount($code['id'], $item_info['cat_id'])) {
                $items_to_discount++;
            } else if ($code['items_type'] == 1 && (check_category_discount($code['id'], $item_info['cat_id']) || check_item_discount($code['id'], $item['item_id']))) {
                $items_to_discount++;
            } else if ($code['items_type'] == 2 && (check_category_discount($code['id'], $item_info['cat_id']) && !check_item_discount($code['id'], $item['item_id']))) {
                $items_to_discount++;
            }
        } else if ($code['categories_type'] == 2) {
            if ($code['items_type'] == 0 && !check_category_discount($code['id'], $item_info['cat_id'])) {
                $items_to_discount++;
            } else if ($code['items_type'] == 1 && (!check_category_discount($code['id'], $item_info['cat_id']) || !check_item_discount($code['id'], $item['item_id']))) {
                $items_to_discount++;
            } else if ($code['items_type'] == 2 && (!check_category_discount($code['id'], $item_info['cat_id']) && !check_item_discount($code['id'], $item['item_id']))) {
                $items_to_discount++;
            }
        }

        if ($items_to_discount > 0) {
            if ($code['discount_value_type'] == 0) {
                $discount = $code['discount_value'];
            } else if ($code['discount_value_type'] == 1) {
                $discount += (($code['discount_value'] * $price / 100));
            } else {
                if ($code['discount_value'] > $price) {
                    $discount += ($price);
                } else {
                    $discount += (($price - $code['discount_value']));
                }
            }
        }
    }
    return $discount;
}

function get_total_order_price($id)
{
    $total = 0;
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $get_order = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_order_cart WHERE order_id='" . $id . "'");
    while ($fetch = mysqli_fetch_assoc($get_order)) {
        $total += ($fetch['item_price'] * $fetch['item_count']);

        $get_options = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_order_options WHERE order_card_id='" . $fetch['id'] . "'");
        while ($option = mysqli_fetch_assoc($get_options)) {
            $total += ($option['option_price'] * $fetch['item_count']);
        }
    }
    return $total;
}

function get_total_visa_order_price(int $id): float
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $total = 0;
    $get_order = mysqli_query($GLOBALS['conn'], "SELECT * FROM visa_cart_req WHERE order_id='" . $id . "'");
    while ($fetch = mysqli_fetch_assoc($get_order)) {
        $total += ($fetch['item_price'] * $fetch['item_count']);

        $get_options = mysqli_query($GLOBALS['conn'], "SELECT * FROM visa_options_req WHERE order_card_id='" . $fetch['id'] . "'");
        while ($option = mysqli_fetch_assoc($get_options)) {
            $total += ($option['option_price'] * $fetch['item_count']);
        }
    }
    return $total;
}

function get_items_data()
{
    $data = [];
    $get_items = mysqli_query($GLOBALS['conn'], "SELECT id,title,description,img,price FROM food_items WHERE active=1 OR (active=2 AND  `from` <= '" . time() . "' AND  `to` >= '" . time() . "')");
    while ($fetch = mysqli_fetch_assoc($get_items)) {
        $fetch['description'] = htmlspecialchars($fetch['description']);
        $id = $fetch['id'];

        // basic item info
        $data[$id] = $fetch;

        //Sizes
        $sizes = [];
        $get_sizes = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items_sizes WHERE item_id='$id'");
        while ($size = mysqli_fetch_assoc($get_sizes)) {
            array_push($sizes, $size);
        }
        $data[$id]['sizes'] = $sizes;

        // options
        $options = [];
        $get_options = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items_options WHERE item_id='$id'");
        while ($option = mysqli_fetch_assoc($get_options)) {
            $values = [];

            $option_id = $option['id'];
            $get_options_values = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items_options_values WHERE option_id='$option_id'");
            while ($option_value = mysqli_fetch_assoc($get_options_values)) {
                array_push($values, $option_value);
            }

            $option['values'] = $values;
            array_push($options, $option);
        }
        $data[$id]['options'] = $options;
    }

    return json_encode($data);
}

function add_visit($page)
{
    $ip = $_SERVER['REMOTE_ADDR'];
    $insert = mysqli_query($GLOBALS['conn'], "INSERT INTO visits (page, ip) VALUES('$page', '$ip')");
}

$lang = [];

function __(string $key)
{
    global $lang, $site_setting;
    if (count($lang) == 0) {
        $lang = include __DIR__ . "/../language/{$site_setting['lang']}.php";
    }

    return $lang[$key];
}

function save_visa_order($order)
{
    $get_order_data = mysqli_query($GLOBALS['conn'], "SELECT * FROM visa_orders_req WHERE operation_id='" . $order . "'");
    if (mysqli_num_rows($get_order_data) > 0) {
        $order_data = mysqli_fetch_assoc($get_order_data);
        if($order_data['status'] == 0){
            $date = time();

            $add_cart = mysqli_query($GLOBALS['conn'], "INSERT INTO food_orders(client_name,client_phone,client_branch_id,client_branch, order_type, client_area_id,client_area_name,client_address,address_price,client_notice,ordered_date,discount_id,discount_code,discount_name,delivery_discount,total_discount, method, transaction_id, paid, tax, total_order, visa_providor) VALUES ('" . $order_data['client_name'] . "','" . $order_data['client_phone'] . "','" . $order_data['client_branch_id'] . "','" . $order_data['client_branch'] . "', '". $order_data['order_type'] ."' ,'" . $order_data['client_area_id'] . "','" . $order_data['client_area_name'] . "','" . $order_data['client_address'] . "','" . $order_data['address_price'] . "','" . $order_data['client_notice'] . "','" . $date . "','" . $order_data['discount_id'] . "','" . $order_data['discount_code'] . "','" . $order_data['discount_name'] . "','" . $order_data['delivery_discount'] . "','" . $order_data['total_discount'] . "',1,'" . $order_data['operation_id'] . "','" . $order_data['total_order'] . "', '". $order_data['tax'] ."', '" . $order_data['total_order'] . "', '". $order_data['visa_providor'] ."')");
            $order_id = mysqli_insert_id($GLOBALS['conn']);
            http_response_code(200);

            $get_cart_data = mysqli_query($GLOBALS['conn'], "SELECT * FROM visa_cart_req WHERE order_id='" . $order_data['id'] . "'");
            while ($cart_data = mysqli_fetch_assoc($get_cart_data)) {
                $insert_cart_data = mysqli_query($GLOBALS['conn'], "INSERT INTO food_order_cart(order_id,item_id,item_name,item_count,item_price,item_size,item_size_name,notes) VALUES('" . $order_id . "','" . $cart_data['item_id'] . "','" . $cart_data['item_name'] . "','" . $cart_data['item_count'] . "','" . $cart_data['item_price'] . "','" . $cart_data['item_size'] . "','" . $cart_data['item_size_name'] . "','" . $cart_data['notes'] . "')");

                $order_card_id = mysqli_insert_id($GLOBALS['conn']);

                $get_options_data = mysqli_query($GLOBALS['conn'], "SELECT * FROM visa_options_req WHERE order_card_id='" . $cart_data['id'] . "'");
                while ($options_data = mysqli_fetch_assoc($get_options_data)) {
                    $insert_cart_data = mysqli_query($GLOBALS['conn'], "INSERT INTO food_order_options(order_card_id, option_id, option_value_id, option_name, option_value, option_price) VALUES('" . $order_card_id . "','" . $options_data['option_id'] . "','" . $options_data['option_value_id'] . "','" . $options_data['option_name'] . "','" . $options_data['option_value'] . "','" . $options_data['option_price'] . "')");
                }
            }

            mysqli_query($GLOBALS['conn'], "UPDATE visa_orders_req SET status = 1 WHERE operation_id='" . $order . "'");

            $jwt = generateJWT([
                "channel" => $GLOBALS['channel'],
                "iat" => time(),
                "exp" => time() + 3600
            ]);

            sendWebSocketCurl(["orderId" => $order_id], "notify-order", $jwt);

            return $order_id;
        } else {
            $get_order = mysqli_query($GLOBALS['conn'], "SELECT id FROM food_orders WHERE transaction_id='" . $order . "'");
            $id = mysqli_fetch_assoc($get_order)['id'];
            return $id;
        }
    }

    return -1;
}

function generate_uuid()
{
    $time = round(microtime(true) * 10000); // more precision than time()
    $timeHex = str_pad(dechex($time), 12, '0', STR_PAD_LEFT); // use 12 hex digits

    // Generate remaining parts
    $rand1 = bin2hex(random_bytes(2)); // 4 hex chars
    $rand2 = bin2hex(random_bytes(2)); // 4 hex chars
    $rand3 = bin2hex(random_bytes(2)); // 4 hex chars
    $rand4 = bin2hex(random_bytes(6)); // 12 hex chars

    // Format: 8-4-4-4-12
    return sprintf(
        'pay_%08s-%s-%s-%s-%s',
        substr($timeHex, 0, 8),
        substr($timeHex, 8, 4),
        $rand1,
        $rand2,
        $rand4
    );
}


function generate_order_whatsapp_message($order_id) 
{
    global $site_setting, $lang;
    __('name');
    $order_from_branch = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='order_from_branch'");
    $order_from_branch = mysqli_fetch_assoc($order_from_branch);

    $get_order_data = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE id = '" . $order_id . "'");
    $order_data = mysqli_fetch_assoc($get_order_data);

    $branches = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_branches");
    $branches_count = mysqli_num_rows($branches);

    $msg_header = <<<MSG
        {$lang['order_approved']} #{$order_id}
        ----------------
        {$lang['name']}: {$order_data['client_name']}
        {$lang['phone']}: {$order_data['client_phone']}
        MSG;

    if ($order_from_branch['value'] == 1 || $order_data['order_type'] == 'branch') {
    $order_type = $order_data['order_type'] == 'branch' ? "استلام من الفرع" : "توصيل للمنزل";
    $msg_header .= <<<MSG

        نوع الطلب: {$order_type}
        MSG;
    }

    $msg_order_det = <<<MSG
    {$lang['payment_method']}: {$lang['payment_with_visa']}
    MSG;

    if ($branches_count > 1) {
    $msg_header .= <<<MSG

        الفرع: {$order_data['client_branch']}
        MSG;
    }

    $msg_header .= <<<MSG

        {$lang['area']}: {$order_data['client_area_name']}
        {$lang['address']}: {$order_data['client_address']}
        {$lang['payment_method']}: {$lang['payment_with_visa']}
        MSG;


    $msg_order_det_header = <<<MSG

        ----------------
        {$lang['order_details']}
        ----------------
        MSG;

    $msg_order_det = "";
    $get_cart_det = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_order_cart WHERE order_id='" . $order_id . "'");
    while ($cart = mysqli_fetch_assoc($get_cart_det)) {
    $item_price = $cart['item_price'] * $cart['item_count'];
    $det = <<<MSG
        
            {$cart['item_count']} × {$cart['item_name']} - {$item_price}
        MSG;

    if (!empty($cart['item_size_name'])) {
        $det .= <<<MSG

                - {$lang['size']}: {$cart['item_size_name']}
            MSG;
    }

    $get_options_titles = mysqli_query($GLOBALS['conn'], "SELECT DISTINCT(option_id), option_name FROM food_order_options WHERE order_card_id='" . $cart['id'] . "'");

    while ($option = mysqli_fetch_assoc($get_options_titles)) {
        $det .= <<<MSG

                - {$option['option_name']}:
            MSG;

        $get_values = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_order_options WHERE option_id='" . $option['option_id'] . "' AND order_card_id='" . $cart['id'] . "'");
        while ($value = mysqli_fetch_assoc($get_values)) {
        $price = $cart['item_count'] * $value['option_price'];
        $price_det = ($price > 0) ? '  -  ' . $price . ' ' . $site_setting['currency'] : '';
        $det .= <<<MSG

                    {$cart['item_count']} × {$value['option_value']} {$price_det}
            MSG;
        }
    }

    $msg_order_det .= $det;
    }


    $total_disc = "";
    $del_disc   = "";
    if ($order_data['total_discount'] > 0) {
    $total_disc = <<<MSG
            {$lang['order_discount']}: {$order_data['total_discount']}
        MSG;
    }
    if ($order_data['order_type'] == 'branch' && $order_data['delivery_discount'] > 0) {
    $del_disc = <<<MSG
            {$lang['delivery_discount']}: {$order_data['delivery_discount']}
        MSG;
    }

    $total_order = get_total_order_price($order_id);
    if($order_data['order_type'] == 'branch') {
    $order_data['address_price'] = 0;
    }
    $final_total = $total_order + $order_data['address_price'] - $order_data['total_discount'] - $order_data['delivery_discount'] + $order_data['tax'];
    $msg_footer = <<<MSG

        ----------------
        {$lang['pay_info']}
        ----------------
        {$lang['sum']}: {$total_order} {$site_setting['currency']}{$total_disc}
        MSG;
    if($order_data['order_type'] == 'delivery') {
        $msg_footer .= <<<MSG

        {$lang['delivery']}: {$order_data['address_price']} {$site_setting['currency']}{$del_disc}
        MSG;
    }
    $msg_footer .= <<<MSG

        {$lang['tax']}: {$order_data['tax']}
        {$lang['total_cost']}: {$final_total} {$site_setting['currency']}
        تم السداد
        MSG;

    return $msg_header . $msg_order_det_header . $msg_order_det . $msg_footer;
}

function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function generateJWT($payload) {
    $header = [
        "alg" => "HS256",
        "typ" => "JWT"
    ];

    $headerEncoded = base64UrlEncode(json_encode($header));
    $payloadEncoded = base64UrlEncode(json_encode($payload));

    $signature = hash_hmac(
        'sha256',
        $headerEncoded . "." . $payloadEncoded,
        $GLOBALS['JWT_SECRET_KEY'],
        true
    );

    $signatureEncoded = base64UrlEncode($signature);

    return $headerEncoded . "." . $payloadEncoded . "." . $signatureEncoded;
}

function sendWebSocketCurl($data, $request, $token) {
    $jsonData = json_encode($data);

    $ch = curl_init($GLOBALS['websocket_base_url'] . "/" . $request);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return false;
    } else {
        return $response;
    }
}