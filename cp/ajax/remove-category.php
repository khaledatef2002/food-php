<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && is_logged() && check_user_perm(['categories-remove'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $get_old_data = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_categories WHERE id=$id");
    $old_data = mysqli_fetch_assoc($get_old_data)['category_name'];

    $del = mysqli_query($GLOBALS['conn'], "DELETE FROM food_categories WHERE id='$id'");

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بإزالة تصنيف باسم $old_data");

    if (!$del) {
        echo "error";
    }
} else {
    echo "error";
}
