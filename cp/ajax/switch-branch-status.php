<?php

include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && check_user_perm(['branches-edit'])) {
    if (!empty($_POST['id'])) {
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

        $check_id_exist = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_branches WHERE id= $id");
        if (mysqli_num_rows($check_id_exist) > 0) {
            $item = mysqli_fetch_assoc($check_id_exist);

            $new_active_status = !$item['active'];
            $update = mysqli_query($GLOBALS['conn'], "UPDATE food_branches SET active='$new_active_status' WHERE id=$id");

            $admin = get_admin_info()['nickname'];

            logg("branches", "لقد قام $admin بتعديل تفعيل الفرع $item[branch_name] بمعرض $id");

            echo json_encode(array("msg" => "success", "body" => "تم تحديث بيانات الفرع بنجاح"));
        } else {
            echo json_encode(array("msg" => "error", "body" => "هذا الفرع غير موجود"));
        }
    } else {
        echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
    }
} else {
    echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
}
