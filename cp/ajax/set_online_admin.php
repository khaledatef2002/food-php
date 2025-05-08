<?php
    include '../../includes/conn.php';
    include '../functions/main.php';

    session_start();
    if(is_logged())
    {

        $get_user_info = mysqli_query($GLOBALS['conn'], "SELECT * FROM panel_user WHERE username='".$_SESSION['username']."'");
        $user = mysqli_fetch_assoc($get_user_info)['id'];

        $time = time() + (120);

        mysqli_query($GLOBALS['conn'], "UPDATE panel_user SET last_online='$time', page='".$_POST['page']."' WHERE id=$user");

    }
?>