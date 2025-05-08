<?php

include '../../includes/conn.php';
include '../functions/main.php';

//isset($_FILES['image'])
if (isset($_POST['active']) && isset($_POST['id']) && isset($_POST['title']) && isset($_POST['des']) && isset($_POST['start']) && isset($_POST['end']) && is_logged() && check_user_perm(['safwa-card-edit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $title = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['title']));
    $des = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['des']));
    $start = strtotime($_POST['start']);
    $end = strtotime($_POST['end']);
    $active = ($_POST['active'] == "true") ? 1 : 0;
    mysqli_query($GLOBALS['conn'], "UPDATE safwa_cards SET active=$active, title= '$title', description='$des', start_date='$start', last_date='$end' WHERE id='$id'");

    $get_offer_info = mysqli_query($GLOBALS['conn'], "SELECT * FROM safwa_cards WHERE id='$id'");
    $offer_info = mysqli_fetch_assoc($get_offer_info);

    if (isset($_FILES['image']) && !empty($_FILES['image'])) {
        $msg = upload_image($_FILES['image']);
        if ($msg == "FILE_SIZE_ERROR" || $msg == "FILE_TYPE_ERROR") {
            return $msg;
        } else {

            if (file_exists("../../" . $offer_info['url'])) {
                unlink("../../" . $offer_info['url']);
            }

            $update = mysqli_query($GLOBALS['conn'], "UPDATE safwa_cards SET url='$msg' WHERE id='$id'");

            $admin = get_admin_info()['nickname'];

            logg("login", "لقد قام $admin بتغيير بيانات عنصر من صفحة كارت الصفوه بمعرف $id");

            echo $msg;
        }
    } else {
        echo $offer_info['url'];
    }
}
