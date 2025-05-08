<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && isset($_POST['location_name']) && isset($_POST['location_price']) && is_logged() && check_user_perm(['locations-add'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['location_name']));
    $price = filter_var($_POST['location_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $active = ($_POST['active'] == "true") ? 1 : 0;

    $insertion = mysqli_query($GLOBALS['conn'], "INSERT INTO food_locations(branch_id, name, price, active) VALUES($id, '$name', $price, $active)");

    $admin = get_admin_info()['nickname'];

    logg("location", "لقد قام $admin باضافة منطقة جديدة الى الفرع رقم $id");

    if (!$insertion) {
        echo "error";
    }
} else {
    echo "error";
}
