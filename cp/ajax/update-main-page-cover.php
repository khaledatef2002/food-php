<?php

include '../../includes/conn.php';
include '../functions/main.php';

//isset($_FILES['image'])
if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['des']) && is_logged() && check_user_perm(['main-page-cover-edit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $title = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['title']));
    $des = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['des']));
    mysqli_query($GLOBALS['conn'], "UPDATE main_page_header SET title= '$title', description='$des' WHERE id='$id'");

    $get_cover_info = mysqli_query($GLOBALS['conn'], "SELECT * FROM main_page_header WHERE id='$id'");
    $cover_info = mysqli_fetch_assoc($get_cover_info);

    if (isset($_FILES['image']) && !empty($_FILES['image'])) {
        $msg = upload_image($_FILES['image']);
        if ($msg == "FILE_SIZE_ERROR" || $msg == "FILE_TYPE_ERROR") {
            return $msg;
        } else {

            if (file_exists("../../" . $cover_info['url'])) {
                unlink("../../" . $cover_info['url']);
            }

            $update = mysqli_query($GLOBALS['conn'], "UPDATE main_page_header SET url='$msg' WHERE id='$id'");

            $admin = get_admin_info()['nickname'];

            logg("login", "لقد قام $admin بتغيير بيانات غلاف الصفحة الرئيسية بمعرف $id");

            echo $msg;
        }
    } else {
        echo $cover_info['url'];
    }
}
