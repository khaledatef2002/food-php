<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && is_logged() && check_user_perm(['working-period-remove'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $del = mysqli_query($GLOBALS['conn'], "DELETE FROM work_periods WHERE id='$id'");

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بإزالة فترة عمل");

    if (!$del) {
        echo "error";
    }
} else {
    echo "error";
}
