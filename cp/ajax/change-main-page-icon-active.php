<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && isset($_POST['active']) && is_logged() && check_user_perm(['main-page-icon-edit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $active = ($_POST['active'] == "true") ? 1 : 0;
    $insertion = mysqli_query($GLOBALS['conn'], "UPDATE main_page_icons SET icon_active='" . $active . "' WHERE id=" . $id . "");


    $as = ($_POST['active'] == "true") ? 'نشط' : 'غير نشط';

    $admin = get_admin_info()['nickname'];

    logg("main page", "لقد قام $admin بتغيير حالة ايقونة الصفحة الرئيسية $id الى $as");


    if (!$insertion) {
        echo "error";
    }
} else {
    echo "error";
}
