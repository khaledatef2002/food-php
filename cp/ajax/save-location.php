<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && isset($_POST['price']) && isset($_POST['active']) && is_logged() && check_user_perm(['locations-edit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $active = ($_POST['active'] == "true") ? 1 : 0;

    $insertion = mysqli_query($GLOBALS['conn'], "UPDATE food_locations SET price='$price', active='$active' WHERE id='$id'");

    $admin = get_admin_info()['nickname'];

    logg("location", "لقد قام $admin بتغيير بيانات منطقة توصيل بمعرف $id");

    if (!$insertion) {
        echo "error";
    }
} else {
    echo "error";
}
