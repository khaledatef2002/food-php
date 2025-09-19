<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && isset($_POST['type']) && isset($_POST['link']) && is_logged() && check_user_perm(['social-page-edit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_URL);
    $type = filter_var($_POST['type'], FILTER_SANITIZE_URL);
    $link = filter_var($_POST['link'], FILTER_SANITIZE_URL);
    $update = mysqli_query($GLOBALS['conn'], "UPDATE social_media SET type = '$type',link = '$link' WHERE id='$id'");

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بتغيير بيانات ايقونة التواصل بمعرف $id");

    if (!$update) {
        echo "error";
    } else {
        switch($type){
            case 'tiktok':
                ?>
                    <img src="../imgs/social-media/tiktok.png" style="width: 80px;height: 80px;" alt="tiktok">
                <?php
                break;
            case 'twitter':
                ?>
                    <img src="../imgs/social-media/x.png" style="width: 80px;height: 80px;" alt="tiktok">
                <?php
                break;
            case 'instagram':
                ?>
                    <img src="../imgs/social-media/instagram.png" style="width: 80px;height: 80px;" alt="tiktok">
                <?php
                break;
            case 'facebook':
                ?>
                    <img src="../imgs/social-media/facebook.webp" style="width: 80px;height: 80px;" alt="tiktok">
                <?php
                break;
            case 'telegram':
                ?>
                    <img src="../imgs/social-media/telegram.png" style="width: 80px;height: 80px;" alt="tiktok">
                <?php
                break;
        }
    }
} else {
    echo "error";
}
