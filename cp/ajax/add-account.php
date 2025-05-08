<?php

include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['role']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['nickname']) && is_logged() && check_user_perm(['accounts-add'])) {
    if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['nickname']) && !empty($_POST['role'])) {
        $username = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['username']));
        $password = sha1($_POST['password']);
        $nickname = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['nickname']));

        $role_id = filter_var($_POST['role'], FILTER_SANITIZE_NUMBER_INT);

        //Check fake role
        $get_role = mysqli_query($GLOBALS['conn'], "SELECT * FROM roles WHERE id={$role_id}");
        if (mysqli_num_rows($get_role) == 0) {
            echo json_encode(array("msg" => "error", "body" => "يرجى اختيار دور صحيح"));
            exit;
        }

        $perm_website_settings = (isset($_POST['website_settings'])) ? 1 : 0;
        $perm_general = (isset($_POST['general'])) ? 1 : 0;
        $perm_menu = (isset($_POST['menu'])) ? 1 : 0;
        $perm_ratings = (isset($_POST['ratings'])) ? 1 : 0;
        $perm_order_data = (isset($_POST['order_data'])) ? 1 : 0;
        $perm_order_live = (isset($_POST['order_live'])) ? 1 : 0;
        $perm_change_active = (isset($_POST['change_active'])) ? 1 : 0;
        $perm_accounts = (isset($_POST['accounts'])) ? 1 : 0;
        $perm_discount = (isset($_POST['discount'])) ? 1 : 0;
        $perm_system_menu = (isset($_POST['system_menu'])) ? 1 : 0;
        $logs = (isset($_POST['logs'])) ? 1 : 0;

        $msg = '';

        if (isset($_FILES['image'])) {
            $msg = upload_image($_FILES['image']);
            if ($msg == "FILE_SIZE_ERROR" || $msg == "FILE_TYPE_ERROR") {
                echo json_encode(array("msg" => "error", "error" => "حجم او صيغة الصورة غير صحيحيين"));
                exit;
            }
        }

        $insert = mysqli_query($GLOBALS['conn'], "INSERT INTO panel_user(username, password, nickname, img, role_id) VALUES('$username','$password','$nickname','$msg', '$role_id')");
        $id = mysqli_insert_id($GLOBALS['conn']);

        $admin = get_admin_info()['nickname'];

        logg("accounts", "لقد قام $admin باضافة حساب جديد باسم $nickname ومعرف $id");

        echo json_encode(array("msg" => "success", "body" => $id));
        exit;
    } else {
        echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
        exit;
    }
} else {
    echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
    exit;
}
