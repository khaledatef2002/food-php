<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && isset($_POST['name']) && is_logged() && check_user_perm(['categories-edit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_URL);
    $name = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars(trim($_POST['name'])));
    $update = mysqli_query($GLOBALS['conn'], "UPDATE food_categories SET category_name = '$name' WHERE id='$id'");

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بتغيير بيانات تصنيف بمعرف $id");

    if (!$update) {
        echo "error";
    }
} else {
    echo "error";
}
