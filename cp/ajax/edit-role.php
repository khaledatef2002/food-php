<?php

include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && isset($_POST['role_name']) && is_logged() && check_user_perm(['roles-edit'])) {
    if (!empty($_POST['id']) && !empty($_POST['role_name'])) {
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $role_name = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['role_name']));

        $system_permissions = get_system_permissions();
        $permissions = [];
        foreach ($system_permissions as $perm_name) {
            $permissions[$perm_name] = (isset($_POST[$perm_name]) && $_POST[$perm_name]) ? 1 : 0;
        }

        $update_role_name = mysqli_query($GLOBALS['conn'], "UPDATE roles SET name='$role_name' WHERE id=$id");

        foreach ($permissions as $key => $val) {
            get_role_permission($id, $key);
            $update_permission = mysqli_query($GLOBALS['conn'], "UPDATE permissions SET permission_value='$val' WHERE permission_key='$key' AND role_id='$id'");
        }

        $admin = get_admin_info()['nickname'];

        logg("roles", "لقد قام $admin بتغيير بيانات الدور بمعرف $id");

        echo json_encode(array("msg" => "success", "body" => $id));
        exit;
    } else {
        echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
        exit;
    }
} else {
    echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
}
