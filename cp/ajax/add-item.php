<?php

include '../../includes/conn.php';
include '../functions/main.php';

if (in_array($_POST['active'], array(0, 1, 2)) && isset($_POST['item_name']) && isset($_POST['item_price']) && isset($_POST['item-category']) && isset($_POST['item-discrption']) && in_array($_POST['size_type'], array(0, 1)) && is_logged() && check_user_perm(['items-add'])) {
    if (!empty($_POST['item_name']) && check_category_exist($_POST['item-category'])) {
        $item_name = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['item_name']));
        $item_cat = filter_var($_POST['item-category'], FILTER_SANITIZE_NUMBER_INT);
        $item_des = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['item-discrption']));
        $active = filter_var($_POST['active'], FILTER_SANITIZE_NUMBER_INT);
        $item_img = "";

        if (isset($_FILES['image']) && !empty($_FILES['image'])) {
            $msg = upload_image($_FILES['image']);
            if ($msg != "FILE_SIZE_ERROR" && $msg != "FILE_TYPE_ERROR") {
                $item_img = $msg;
            }
        }

        if (isset($_POST['size_type']) && $_POST['size_type'] == 0) {
            $item_price = filter_var($_POST['size_price'][0], FILTER_SANITIZE_NUMBER_FLOAT);
            foreach ($_POST['size_name'] as $key => $val) {
                if (empty($val) || empty($_POST['size_price'][$key])) {
                    echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع بيانات الحجم"));
                    exit();
                }
            }
        } else {
            $item_price = filter_var($_POST['item_price'], FILTER_SANITIZE_NUMBER_FLOAT);
        }

        if (isset($_POST['option'])) {
            foreach ($_POST['option'] as $key => $val) {
                if (empty($val['name']) || !in_array($val['type'], array(0, 1))) {
                    echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع بيانات الخواص"));
                    exit();
                }
                foreach ($val['values_name'] as $k => $v) {
                    if (empty($v)) {
                        echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع بيانات الخواص"));
                        exit();
                    }
                }
            }
        }
        $old_price = filter_var($_POST['item_price_before'], FILTER_SANITIZE_NUMBER_FLOAT);
        $tax = filter_var($_POST['item_tax'], FILTER_SANITIZE_NUMBER_FLOAT);

        if ($active == 2) {
            if (empty($_POST['item_start_date']) || empty($_POST['item_start_time']) || empty($_POST['item_end_date']) || empty($_POST['item_end_time'])) {
                echo json_encode(array("msg" => "error", "body" => "يرجى ادخال تاريخ البداية والنهاية"));
                exit();
            }
            $start = strtotime($_POST['item_start_date'] . $_POST['item_start_time']);
            $end = strtotime($_POST['item_end_date'] . $_POST['item_end_time']);
        } else {
            $start = $end = 0;
        }

        $get_last_sort = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items WHERE cat_id =$item_cat ORDER BY sort DESC");
        $item_sort = mysqli_fetch_assoc($get_last_sort)['sort'] ?? 0;
        $insert = mysqli_query($GLOBALS['conn'], "INSERT INTO food_items(`from`,`to`, tax,before_discount,cat_id,title,description,price,img,active,sort) VALUES('$start','$end','$tax','$old_price','" . $item_cat . "','" . $item_name . "','" . $item_des . "','" . $item_price . "','" . $item_img . "','" . $active . "',$item_sort)");

        $item_id = mysqli_insert_id($GLOBALS['conn']);

        if (isset($_POST['size_type']) && $_POST['size_type'] == 0) {
            foreach ($_POST['size_name'] as $key => $val) {
                $insert_size = mysqli_query($GLOBALS['conn'], "INSERT INTO food_items_sizes(item_id,size_name,size_price) VALUES('" . $item_id . "','" . $val . "','" . $_POST['size_price'][$key] . "')");
            }
        }

        if (isset($_POST['option'])) {
            foreach ($_POST['option'] as $key => $val) {
                $insert_option = mysqli_query($GLOBALS['conn'], "INSERT INTO food_items_options(item_id,type,name) VALUES('" . $item_id . "','" . $val['type'] . "','" . $val['name'] . "')");

                $option_id = mysqli_insert_id($GLOBALS['conn']);

                foreach ($val['values_name'] as $k => $v) {
                    $insert_option = mysqli_query($GLOBALS['conn'], "INSERT INTO food_items_options_values(option_id,name,price) VALUES('" . $option_id . "','" . $v . "','" . $val['values_price'][$k] . "')");
                }
            }
        }


        echo json_encode(array("msg" => "success", "body" => "تم اضافة الصنف بنجاح"));
    } else {
        echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
    }
} else {
    echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
}
