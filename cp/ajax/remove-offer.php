<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && is_logged() && check_user_perm(['offers-page-remove'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $get_offer_info = mysqli_query($GLOBALS['conn'], "SELECT * FROM offers WHERE id='$id'");
    $offer_info = mysqli_fetch_assoc($get_offer_info);


    if (file_exists("../../" . $offer_info['url'])) {
        unlink("../../" . $offer_info['url']);
    }

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بإزالة عرض بمعرف $id");

    $del = mysqli_query($GLOBALS['conn'], "DELETE FROM offers WHERE id='$id'");
    if (!$del) {
        echo "error";
    }
} else {
    echo "error";
}
