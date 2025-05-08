<?php

include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['role_name']) && is_logged() && check_user_perm(['roles-add'])) {
    if (!empty($_POST['role_name'])) {
        $role_name = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['role_name']));

        $system_permissions = get_system_permissions();
        $permissions = [];
        foreach ($system_permissions as $perm_name) {
            $permissions[$perm_name] = (isset($_POST[$perm_name]) && $_POST[$perm_name]) ? 1 : 0;
        }

        $insert_role = mysqli_query($GLOBALS['conn'], "INSERT INTO roles(name) VALUES('$role_name')");
        $id = mysqli_insert_id($GLOBALS['conn']);

        foreach ($permissions as $key => $val) {
            $insert_permission = mysqli_query($GLOBALS['conn'], "INSERT INTO permissions (role_id, permission_key, permission_value) VALUES($id, '$key', '$val')");
        }

        $admin = get_admin_info()['nickname'];

        logg("roles", "لقد قام $admin باضافة دور جديد باسم $role_name ومعرف $id");

        echo json_encode(array("msg" => "success", "body" => $id));
        exit;
    } else {
        echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
        exit;
    }
} else {
    echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
}
