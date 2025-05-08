<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && isset($_POST['name']) && is_logged() && check_user_perm(['branches-edit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['name']));

    $insertion = mysqli_query($GLOBALS['conn'], "UPDATE food_branches SET branch_name='$name' WHERE id='$id'");

    $admin = get_admin_info()['nickname'];

    logg("branches", "لقد قام $admin بتغيير بيانات فرع بمعرف $id");

    if (!$insertion) {
        echo "error";
    }
} else {
    echo "error";
}
