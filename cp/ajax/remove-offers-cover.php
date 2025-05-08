<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && is_logged() && check_user_perm(['offers-page-cover-edit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $old_data = mysqli_query($GLOBALS['conn'], "SELECT * FROM offers_page_header WHERE id=$id");
    $old_data = mysqli_fetch_assoc($old_data)['url'];

    if (file_exists("../../" . $old_data)) {
        unlink("../../" . $old_data);
    }

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بإزالة غلاف صفحة العروض");

    $del = mysqli_query($GLOBALS['conn'], "DELETE FROM offers_page_header WHERE id='$id'");
    if (!$del) {
        echo "error";
    }
} else {
    echo "error";
}
