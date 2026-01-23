<?php

include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && check_user_perm(['switch-items'])) {
    if (!empty($_POST['id'])) {
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

        $check_id_exist = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items WHERE id= $id");
        if (mysqli_num_rows($check_id_exist) > 0) {
            $item = mysqli_fetch_assoc($check_id_exist);

            if($item['active'] == 1){
                $update = mysqli_query($GLOBALS['conn'], "UPDATE food_items SET active='0' WHERE id=$id");
            } elseif ($item['active'] == 0){
                $update = mysqli_query($GLOBALS['conn'], "UPDATE food_items SET active='1' WHERE id=$id");
            } else {
                $current_time = time();
                if ($current_time >= $item['from'] && $current_time <= $item['to']) {
                    $new_active_status = 0; 
                } else {
                    $new_active_status = 1; 
                }
                $update = mysqli_query($GLOBALS['conn'], "UPDATE food_items SET active='$new_active_status' WHERE id=$id");
            }

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
