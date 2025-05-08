<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && is_logged() && check_user_perm(['branches-remove'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $del = mysqli_query($GLOBALS['conn'], "DELETE FROM food_locations WHERE branch_id='$id'");
    $del = mysqli_query($GLOBALS['conn'], "DELETE FROM food_branches WHERE id='$id'");

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بحذف فرع بمعرف $id");

    if (!$del) {
        echo "error";
    }
} else {
    echo "error";
}
