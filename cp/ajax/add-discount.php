<?php

include '../../includes/conn.php';
include '../functions/main.php';

if (in_array($_POST['active'], [0, 1, 2]) && isset($_POST['discount_max_price']) && isset($_POST['discount_code']) && isset($_POST['discount_name']) && isset($_POST['discount_min']) && isset($_POST['discount_max_uses']) && isset($_POST['discount_user_uses']) && isset($_POST['discount_start_date']) && isset($_POST['discount_start_time']) && isset($_POST['discount_end_date']) && isset($_POST['discount_end_time']) && in_array($_POST['discount_type'], array(0, 1)) && isset($_POST['discount_value']) && in_array($_POST['discount_value_type'], array(0, 1)) && in_array($_POST['location_type'], array(0, 1, 2)) && in_array($_POST['category_type'], array(0, 1, 2)) && in_array($_POST['items_type'], array(0, 1, 2)) && in_array($_POST['phone_type'], array(0, 1, 2)) && is_logged() && check_user_perm(['discounts-add'])) {
    if (!empty($_POST['discount_code']) || !empty($_POST['discount_name']) || !empty($_POST['discount_start_date']) || !empty($_POST['discount_start_time']) || !empty($_POST['discount_end_date']) || !empty($_POST['discount_end_time']) || !empty($_POST['discount_value'])) {
        $discount_code = mysqli_real_escape_string($GLOBALS['conn'], $_POST['discount_code']);
        $discount_name = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['discount_name']));
        $discount_min = filter_var($_POST['discount_min'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $discount_value = filter_var($_POST['discount_value'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $discount_max_uses = filter_var($_POST['discount_max_uses'], FILTER_SANITIZE_NUMBER_INT);
        $discount_user_uses = filter_var($_POST['discount_user_uses'], FILTER_SANITIZE_NUMBER_INT);
        $discount_type = filter_var($_POST['discount_type'], FILTER_SANITIZE_NUMBER_INT);
        $discount_value_type = filter_var($_POST['discount_value_type'], FILTER_SANITIZE_NUMBER_INT);
        $location_type = filter_var($_POST['location_type'], FILTER_SANITIZE_NUMBER_INT);
        $category_type = filter_var($_POST['category_type'], FILTER_SANITIZE_NUMBER_INT);
        $items_type = filter_var($_POST['items_type'], FILTER_SANITIZE_NUMBER_INT);
        $phone_type = filter_var($_POST['phone_type'], FILTER_SANITIZE_NUMBER_INT);
        $discount_max_price = filter_var($_POST['discount_max_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $active = filter_var($_POST['active'], FILTER_SANITIZE_NUMBER_INT);

        $locations_data = $_POST['locations_data'] ?? array();
        $categories_data = $_POST['categories_data'] ?? array();
        $items_data = $_POST['items_data'] ?? array();
        $phones_data = $_POST['phones_data'] ?? array();

        //Check exist Code
        $get_code = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_discounts WHERE code='$discount_code'");
        if (mysqli_num_rows($get_code) > 0) {
            echo json_encode(array("msg" => "error", "body" => "الكود الخصم موجود بالفعل"));
            exit;
        }

        $start = $end = 0;
        if ($active == 2) {
            if (empty($_POST['discount_start_date']) || empty($_POST['discount_start_time']) || empty($_POST['discount_end_date']) || empty($_POST['discount_end_time'])) {
                echo json_encode(array("msg" => "error", "body" => "يرجى ادخال تاريخ البداية والنهاية"));
                exit();
            }
            $start = strtotime($_POST['discount_start_date'] . $_POST['discount_start_time']);
            $end = strtotime($_POST['discount_end_date'] . $_POST['discount_end_time']);
        }

        $insert = mysqli_query($GLOBALS['conn'], "INSERT INTO food_discounts(active, `code`,name,min_order,max_user_use,max_uses,start_date,end_date,visibility,locations_type,categories_type,items_type,phone,discount_type,discount_value,discount_value_type,max_discount) VALUES($active, '" . $discount_code . "','" . $discount_name . "','" . $discount_min . "','" . $discount_user_uses . "','" . $discount_max_uses . "','" . $start . "','" . $end . "',0,'" . $location_type . "','" . $category_type . "','" . $items_type . "','" . $phone_type . "','" . $discount_type . "','" . $discount_value . "','" . $discount_value_type . "','" . $discount_max_price . "')");

        $id = mysqli_insert_id($GLOBALS['conn']);

        $admin = get_admin_info()['nickname'];

        logg("discounts", "لقد قام $admin باضافة كود خصم جديد باسم $discount_name ومعرف $id");

        if (count($locations_data) > 0) {
            foreach ($locations_data as $location) {
                $location_id = filter_var($location, FILTER_SANITIZE_NUMBER_INT);
                $insert = mysqli_query($GLOBALS['conn'], "INSERT INTO food_discounts_locations(discount_id, location_id) VALUES($id, $location_id)");
            }
        }
        if (count($categories_data) > 0) {
            foreach ($categories_data as $category) {
                $category_id = filter_var($category, FILTER_SANITIZE_NUMBER_INT);
                $insert = mysqli_query($GLOBALS['conn'], "INSERT INTO food_discounts_cat(discount_id, category_id) VALUES($id, $category_id)");
            }
        }
        if (count($items_data) > 0) {
            foreach ($items_data as $item) {
                $item_id = filter_var($item, FILTER_SANITIZE_NUMBER_INT);
                $insert = mysqli_query($GLOBALS['conn'], "INSERT INTO food_discounts_items(discount_id, item_id) VALUES($id, $item_id)");
            }
        }
        if (count($phones_data) > 0) {
            foreach ($phones_data as $phone) {
                $phone_id = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
                $insert = mysqli_query($GLOBALS['conn'], "INSERT INTO food_discounts_phones(discount_id, phone) VALUES($id, $phone_id)");
            }
        }
        echo json_encode(array("msg" => "success", "body" => "تم اضافة الخصم بنجاح"));
        exit;
    } else {
        echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
        exit;
    }
} else {
    echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
    exit;
}
