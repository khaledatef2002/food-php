<?php

include '../../includes/conn.php';
include '../functions/main.php';


if (isset($_POST['id']) && isset($_FILES['image']) && is_logged() && check_user_perm(['manual-menu-edit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $msg = upload_image($_FILES['image']);
    if ($msg == "FILE_SIZE_ERROR" || $msg == "FILE_TYPE_ERROR") {
        return $msg;
    } else {

        $get_menu_info = mysqli_query($GLOBALS['conn'], "SELECT * FROM menu WHERE id='$id'");
        $menu_info = mysqli_fetch_assoc($get_menu_info);

        if (file_exists("../../" . $menu_info['url'])) {
            unlink("../../" . $menu_info['url']);
        }

        $update = mysqli_query($GLOBALS['conn'], "UPDATE menu SET url='$msg' WHERE id='$id'");

        $admin = get_admin_info()['nickname'];

        logg("login", "لقد قام $admin بتغيير بيانات صورة منيو بمعرف $id");

        echo $msg;
    }
}
