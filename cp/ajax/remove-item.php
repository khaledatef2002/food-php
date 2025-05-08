<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && is_logged() && check_user_perm(['items-remove'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $get_old = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items WHERE id=$id");

    $admin = get_admin_info()['nickname'];

    $old_data = mysqli_fetch_assoc($get_old);

    logg("login", "لقد قام $admin بإزالة صنف باسم {$old_data['title']}");

    if (file_exists("../../" . $old_data['img'])) {
        unlink("../../" . $old_data['img']);
    }

    $del1 = mysqli_query($GLOBALS['conn'], "DELETE FROM food_items_sizes WHERE item_id=$id");
    $get_all_options = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items_options WHERE item_id=$id");
    while ($option = mysqli_fetch_assoc($get_all_options)) {
        $del_options_values = mysqli_query($GLOBALS['conn'], "DELETE FROM food_items_options_values WHERE option_id={$option['id']}");
    }
    $del_options = mysqli_query($GLOBALS['conn'], "DELETE FROM food_items_options WHERE item_id=$id");
    $del_item = mysqli_query($GLOBALS['conn'], "DELETE FROM food_items WHERE id=$id");

    if (!$del1 || !$del_item || !$del_options) {
        echo "error";
    } else {
        echo "success";
    }
} else {
    echo "error";
}
