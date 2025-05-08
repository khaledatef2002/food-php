<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && is_logged() && check_user_perm(['accounts-remove'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $old_data = mysqli_query($GLOBALS['conn'], "SELECT * FROM panel_user WHERE id=$id");
    $data = mysqli_fetch_assoc($old_data);

    $name = $data['nickname'];

    $old_data = $data['img'];

    if (file_exists("../../" . $old_data)) {
        unlink("../../" . $old_data);
    }

    $del = mysqli_query($GLOBALS['conn'], "DELETE FROM panel_user WHERE id='$id'");

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بإزالة حساب باسم $name");

    if (!$del) {
        echo "error";
    }
} else {
    echo "error";
}
