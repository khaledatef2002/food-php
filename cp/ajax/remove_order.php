<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && is_logged() && check_user_perm(['orders-data-remove'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $del1 = mysqli_query($GLOBALS['conn'], "DELETE FROM food_orders WHERE id='$id'");

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بإزالة طلب رقم $id");

    mysqli_query($GLOBALS['conn'], "INSERT INTO live_notify(page,type,data) VALUES('order', 'remove','$id')");
    if (!$del1) {
        echo "error";
    }
    $get_cart = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_order_cart WHERE order_id='$id'");
    while ($cart = mysqli_fetch_assoc($get_cart)) {
        $del2 = mysqli_query($GLOBALS['conn'], "DELETE FROM food_order_cart WHERE order_id='$id'");
        $del3 = mysqli_query($GLOBALS['conn'], "DELETE FROM food_order_options WHERE order_card_id='" . $cart['id'] . "'");
        if (!$del2 || !$del3) {
            echo "error";
        }
    }
} else {
    echo "error";
}
