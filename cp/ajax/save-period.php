<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && isset($_POST['fromD']) && isset($_POST['fromT']) && isset($_POST['toT']) && isset($_POST['toD']) && is_logged() && check_user_perm(['working-period-edit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $fromD = filter_var($_POST['fromD'], FILTER_SANITIZE_NUMBER_INT);
    $toD = filter_var($_POST['toD'], FILTER_SANITIZE_NUMBER_INT);

    $fromT = htmlspecialchars($_POST['fromT']);
    $toT = htmlspecialchars($_POST['toT']);

    $start_time = explode(':', $_POST['fromT']);
    $from = mysqli_real_escape_string($GLOBALS['conn'], $fromD . $start_time[0] . $start_time[1] . "00");

    $end_time = explode(':', $_POST['toT']);
    $to = mysqli_real_escape_string($GLOBALS['conn'], $toD . $end_time[0] . $end_time[1] . "00");

    $insertion = mysqli_query($GLOBALS['conn'], "UPDATE work_periods SET from_date='$from', to_date='$to' WHERE id='$id'");

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بتغيير بيانات فترة عمل بمعرف $id");

    if (!$insertion) {
        echo "error";
    }
} else {
    echo "error";
}
