<?php

include '../includes/conn.php';
$_GET['id'] = htmlspecialchars($_GET['id']);
$_GET['id'] = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
if(!is_nan($_GET['id']))
{
    $offers_query = mysqli_query($conn, "SELECT * FROM safwa_cards WHERE id = '".$_GET['id']."'");
    if(mysqli_num_rows($offers_query) > 0) {
        echo json_encode(mysqli_fetch_assoc($offers_query));
    }
}

?>