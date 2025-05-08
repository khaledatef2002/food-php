<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && is_logged() && check_user_perm(['safwa-card-cover-remove'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $old_data = mysqli_query($GLOBALS['conn'], "SELECT * FROM safwa_card_header WHERE id=$id");
    $old_data = mysqli_fetch_assoc($old_data)['url'];

    if (file_exists("../../" . $old_data)) {
        unlink("../../" . $old_data);
    }

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بإزالة غلاف صفحة كارت الصفوة");

    $del = mysqli_query($GLOBALS['conn'], "DELETE FROM safwa_card_header WHERE id='$id'");
    if (!$del) {
        echo "error";
    }
} else {
    echo "error";
}
