<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && isset($_POST['icon']) && isset($_POST['link']) && is_logged() && check_user_perm(['social-page-edit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_URL);
    $icon = filter_var($_POST['icon'], FILTER_SANITIZE_URL);
    $link = filter_var($_POST['link'], FILTER_SANITIZE_URL);
    $update = mysqli_query($GLOBALS['conn'], "UPDATE social_media SET img_url = '$icon',link = '$link' WHERE id='$id'");

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بتغيير بيانات ايقونة التواصل بمعرف $id");

    if (!$update) {
        echo "error";
    } else {
        include "../../" . $icon;
    }
} else {
    echo "error";
}
