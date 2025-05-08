<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && is_logged() && check_user_perm(['main-page-cover-remove'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $get_menu_info = mysqli_query($GLOBALS['conn'], "SELECT * FROM main_page_header WHERE id='$id'");
    $menu_info = mysqli_fetch_assoc($get_menu_info);

    if (file_exists("../../" . $menu_info['url'])) {
        unlink("../../" . $menu_info['url']);
    }

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بإزالة غلاف للصفحة الرئيسية");

    $del = mysqli_query($GLOBALS['conn'], "DELETE FROM main_page_header WHERE id='$id'");
    if (!$del) {
        echo "error";
    }
} else {
    echo "error";
}
