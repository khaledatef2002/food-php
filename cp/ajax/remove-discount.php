<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && is_logged() && check_user_perm(['discounts-remove'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $get_old_data = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_discounts WHERE id=$id");
    $old_code = mysqli_fetch_assoc($get_old_data)['code'];
    $old_name = mysqli_fetch_assoc($get_old_data)['name'];

    $del1 = mysqli_query($GLOBALS['conn'], "DELETE FROM food_discounts_cat WHERE discount_id='$id'");
    $del2 = mysqli_query($GLOBALS['conn'], "DELETE FROM food_discounts_items WHERE discount_id='$id'");
    $del3 = mysqli_query($GLOBALS['conn'], "DELETE FROM food_discounts_locations WHERE discount_id='$id'");
    $del4 = mysqli_query($GLOBALS['conn'], "DELETE FROM food_discounts_phones WHERE discount_id='$id'");
    $del5 = mysqli_query($GLOBALS['conn'], "DELETE FROM food_discounts WHERE id='$id'");

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بإزالة خصم باسم $old_name وكود $old_code");

    if (!$del1 || !$del2 || !$del3 || !$del4 || !$del5) {
        echo "error";
    } else {
        echo "success";
    }
} else {
    echo "error";
}
