<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && isset($_POST['active']) && is_logged() && check_user_perm(['offers-page-edit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $active = ($_POST['active'] == "true") ? 1 : 0;
    $insertion = mysqli_query($GLOBALS['conn'], "UPDATE offers SET active=" . $active . " WHERE id=" . $id . "");

    $get_old_info = mysqli_query($GLOBALS['conn'], "SELECT * FROM offers WHERE id=$id");
    $offer_name = mysqli_fetch_assoc($get_old_info)['title'];

    $as = ($_POST['active'] == "true") ? 'نشط' : 'غير نشط';

    $admin = get_admin_info()['nickname'];

    logg("offers", "لقد قام $admin بتغيير حالة العرض $offer_name الى $as");

    if (!$insertion) {
        echo "error";
    }
} else {
    echo "error";
}
