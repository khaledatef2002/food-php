<?php

include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && isset($_POST['username']) && isset($_POST['nickname']) && isset($_POST['role']) && is_logged() && check_user_perm(['accounts-edit'])) {
    if (!empty($_POST['id']) && !empty($_POST['username']) && !empty($_POST['nickname']) && !empty($_POST['role'])) {
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $username = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['username']));
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

        $insert = mysqli_query($GLOBALS['conn'], "UPDATE panel_user SET username='$username', nickname='$nickname', role_id='$role_id' WHERE id='$id'");
        $insert2 = mysqli_query($GLOBALS['conn'], "UPDATE users_perm SET logs=$logs, website_settings='$perm_website_settings',general='$perm_general',menu='$perm_menu',ratings='$perm_ratings',order_data='$perm_order_data',order_live='$perm_order_live',accounts='$perm_accounts',discount='$perm_discount',change_active='$perm_change_active',system_menu='$perm_system_menu' WHERE account_id='$id'");

        $admin = get_admin_info()['nickname'];

        logg("login", "لقد قام $admin بتغيير بيانات حساب بمعرف $id");

        if (isset($_POST['password']) && !empty($_POST['password'])) {
            $password = sha1($_POST['password']);
            $insert = mysqli_query($GLOBALS['conn'], "UPDATE panel_user SET password='$password' WHERE id='$id'");
        }

        if (isset($_FILES['image']) && $_FILES["image"]["error"] == 0) {
            $msg = upload_image($_FILES['image']);
            if ($msg == "FILE_SIZE_ERROR" || $msg == "FILE_TYPE_ERROR") {
                echo json_encode(array("msg" => "error", "error" => "حجم او صيغة الصورة غير صحيحيين"));
                exit;
            } else {
                $old_data = mysqli_query($GLOBALS['conn'], "SELECT * FROM panel_user WHERE id=$id");
                $old_data = mysqli_fetch_assoc($old_data)['img'];

                if (file_exists("../../" . $old_data)) {
                    unlink("../../" . $old_data);
                }

                $insert = mysqli_query($GLOBALS['conn'], "UPDATE panel_user SET img='$msg' WHERE id='$id'");
            }
        }

        echo json_encode(array("msg" => "success", "body" => $id));
        exit;
    } else {
        echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
        exit;
    }
} else {
    echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
}
