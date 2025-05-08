<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['branch_name']) && is_logged() && check_user_perm(['branches-add'])) {
    $name = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['branch_name']));

    $insertion = mysqli_query($GLOBALS['conn'], "INSERT INTO food_branches(branch_name) VALUES('$name')");


    $admin = get_admin_info()['nickname'];

    logg("location", "لقد قام $admin باضافة فرع جديد باسم $name");

    if (!$insertion) {
        echo "error";
    } else {
        echo mysqli_insert_id($GLOBALS['conn']);
    }
} else {
    echo "error";
}
