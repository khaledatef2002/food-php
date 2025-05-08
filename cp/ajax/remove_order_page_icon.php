<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && is_logged() && check_user_perm(['order-page-remove'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $del = mysqli_query($GLOBALS['conn'], "DELETE FROM order_page WHERE id='$id'");

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بإزالة ايقونة من صفحة اطلب دليفري");

    if (!$del) {
        echo "error";
    }
} else {
    echo "error";
}
