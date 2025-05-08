<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && is_logged() && check_user_perm(['safwa-card-remove'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $get_offer_info = mysqli_query($GLOBALS['conn'], "SELECT * FROM safwa_cards WHERE id='$id'");
    $offer_info = mysqli_fetch_assoc($get_offer_info);

    if (file_exists("../../" . $offer_info['url'])) {
        unlink("../../" . $offer_info['url']);
    }

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بإزالة عنصر من صفحة كارت الصفوة ");

    $del = mysqli_query($GLOBALS['conn'], "DELETE FROM safwa_cards WHERE id='$id'");
    if (!$del) {
        echo "error";
    }
} else {
    echo "error";
}
