<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && isset($_POST['active']) && is_logged() && check_user_perm(['categories-edit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $active = ($_POST['active'] == "true") ? 1 : 0;
    $insertion = mysqli_query($GLOBALS['conn'], "UPDATE food_categories SET active=" . $active . " WHERE id=" . $id . "");

    $cat_name = get_category_info($id)['name'];

    $as = ($_POST['active'] == "true") ? 'نشط' : 'غير نشط';

    $admin = get_admin_info()['nickname'];

    logg("categories", "لقد قام $admin بتغيير حالة التصنيف $cat_name الى $as");


    if (!$insertion) {
        echo "error";
    }
} else {
    echo "error";
}
