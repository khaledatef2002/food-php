<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && isset($_POST['sort']) && is_logged() && check_user_perm(['items-edit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $sort = filter_var($_POST['sort'], FILTER_SANITIZE_NUMBER_INT);

    $update = mysqli_query($GLOBALS['conn'], "UPDATE food_items SET sort='$sort' WHERE id='$id'");

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بتغيير ترتيب صنف بمعرف $id الى $sort");

    if (!$update) {
        echo "error";
    }
} else {
    echo "error";
}
