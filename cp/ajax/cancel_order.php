<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && isset($_POST['reason']) && is_logged() && check_user_perm(['live-orders-action'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $reason = htmlspecialchars($_POST['reason']);
    $date = time();
    $admin = get_admin_info()['id'];
    $admin_name = get_admin_info()['nickname'];
    $insertion = mysqli_query($GLOBALS['conn'], "UPDATE food_orders SET marked=2, cancel_reason='" . $reason . "',canceled_by=$admin,canceled_date='$date' WHERE id='$id'");
    mysqli_query($GLOBALS['conn'], "INSERT INTO live_notify(page,type,data) VALUES('order', 'cancel','$id')");

    logg('live orders', "لقد قام $admin_name بالغاء الطلب رقم $id");

    if (!$insertion) {
        echo "error";
    }
} else {
    echo "error";
}
