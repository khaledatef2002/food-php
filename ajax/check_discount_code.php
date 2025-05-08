<?php

include '../includes/conn.php';
include '../includes/functions.php';
session_start();
// remember validation
if (isset($_POST['data'])) {
    $data = $_POST['data'];
    $data['code'] = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($data['code']));
    $get_code = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_discounts WHERE LOWER(code)=LOWER('" . $data['code'] . "')");
    if (mysqli_num_rows($get_code) > 0) {
        $code = mysqli_fetch_assoc($get_code);
        if ($code['min_order'] > 0) {
            $total_price = calc_total_price($_SESSION['cart']);
            if ($total_price < $code['min_order']) {
                $data = [
                    "res"   =>  'fail',
                    "msg"   =>  __('min_order_discount') . $code["min_order"] . $site_setting['currency'] . ' !'
                ];
                echo json_encode($data);
                exit();
            }
        }
        if (($code['phone'] == 1 || $code['phone'] == 2 || $code['max_user_use'] > 0) && (!is_phone_valid($data['phone']) || !isset($data['phone']) || empty($data['phone']))) {
            $data = [
                "res"   =>  'fail',
                "msg"   =>  __('enter_correct_phone')
            ];
            echo json_encode($data);
            exit();
        } else if ($code['max_uses'] > 0 && check_total_uses($code['id']) >= $code['max_uses']) {
            $data = [
                "res"   =>  'fail',
                "msg"   =>  __('discount_expired')
            ];
            echo json_encode($data);
            exit();
        }
        // Remember to validate phone number
        if ($code['max_user_use'] > 0 && (!is_phone_valid($data['phone']) || check_user_uses($code['id'], $data['phone']) >= $code['max_user_use'])) {
            $data = [
                "res"   =>  'fail',
                "msg"   =>  __('discount_used_before')
            ];
            echo json_encode($data);
            exit();
        }

        if ($code['locations_type'] == 1 && !is_nan($data['location']) && !check_discount_location($code['id'], $data['location'])) {
            $data = [
                "res"   =>  'fail',
                "msg"   =>  __('discount_area_not_valid')
            ];
            echo json_encode($data);
            exit();
        } else if ($code['locations_type'] == 2 && !is_nan($data['location']) && check_discount_location($code['id'], $data['location'])) {
            $data = [
                "res"   =>  'fail',
                "msg"   =>  __('discount_area_not_valid')
            ];
            echo json_encode($data);
            exit();
        } else if ($code['phone'] == 1 && (!is_phone_valid($data['phone']) || !check_discount_phone($code['id'], $data['phone']))) {
            $data = [
                "res"   =>  'fail',
                "msg"   =>  __('discount_not_valid_for_you')
            ];
            echo json_encode($data);
            exit();
        } else if ($code['phone'] == 2 && !is_phone_valid($data['phone']) && check_discount_phone($code['id'], $data['phone'])) {
            $data = [
                "res"   =>  'fail',
                "msg"   =>  __('discount_not_valid_for_you')
            ];
            echo json_encode($data);
            exit();
        } else if ($code['active'] == 0 || ($code['active'] == 2 && (time() < $code['start_date'] || time() > $code['end_date']))) {
            $data = [
                "res"   =>  'fail',
                "msg"   =>  __('discount_no_av')
            ];
            echo json_encode($data);
            exit();
        } else {
            //check for items and categories
            if ($code['categories_type'] > 0 || $code['items_type'] > 0) {
                $items_to_discount = 0;
                $cart = $_SESSION['cart'] ?? array();
                if (calc_included_items_price($cart, $code) < $code['min_order']) {
                    $data = [
                        "res"       =>  'fail',
                        "msg"       =>  __('discount_min_included'),
                    ];
                    echo json_encode($data);
                    exit();
                }
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
                    $data = [
                        "res"       =>  'success',
                        "msg"       =>  __('discount_approved'),
                        "code_name" =>  $code['name']
                    ];
                    echo json_encode($data);
                    exit();
                } else {
                    $data = [
                        "res"       =>  'fail',
                        "msg"       =>  __('discount_not_av_included'),
                    ];
                    echo json_encode($data);
                    exit();
                }
            } else {
                $data = [
                    "res"       =>  'success',
                    "msg"       =>  __('discount_approved'),
                    "code_name" =>  $code['name']
                ];
                echo json_encode($data);
                exit();
            }
        }
    } else {
        $data = [
            "res"   => 'fail',
            "msg"   => __('discount_not_exist')
        ];
        echo json_encode($data);
        exit();
    }
}
