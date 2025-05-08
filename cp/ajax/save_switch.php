<?php

include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && in_array($_POST['active'], array(0, 1, 2)) && check_user_perm(['switch-items'])) {
    if (!empty($_POST['id'])) {
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

        $check_id_exist = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items WHERE id= $id");
        if (mysqli_num_rows($check_id_exist) > 0) {
            $old_info = mysqli_fetch_assoc($check_id_exist);
            $active = $_POST['active'];

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

            $get_old_info = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items WHERE id=$id");
            $old_item_name = mysqli_fetch_assoc($get_old_info)['title'];

            $update = mysqli_query($GLOBALS['conn'], "UPDATE food_items SET `from`='$start',`to`='$end',active='" . $active . "' WHERE id=$id");

            $admin = get_admin_info()['nickname'];

            logg("items", "لقد قام $admin بتعديل بيانات الصنف $old_item_name بمعرض $id");

            echo json_encode(array("msg" => "success", "body" => "تم تحديث بيانات الصنف"));
        } else {
            echo json_encode(array("msg" => "error", "body" => "هذا الصنف غير موجود"));
        }
    } else {
        echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
    }
} else {
    echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
}
