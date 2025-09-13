<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && is_logged() && check_user_perm(['rating-page-remove'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $old_data = mysqli_query($GLOBALS['conn'], "SELECT * FROM ratings WHERE id=$id");
    $old_data = mysqli_fetch_assoc($old_data)['client_name'];

    $del = mysqli_query($GLOBALS['conn'], "DELETE FROM ratings WHERE id='$id'");

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بإزالة تقييم باسم $old_data ومعرف $id");

    if (!$del) {
        echo "error";
    }
} else {
    echo "error";
}
