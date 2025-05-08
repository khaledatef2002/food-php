<?php

    include '../includes/conn.php';
    include '../includes/functions.php';

    session_start();
    $cart = $_SESSION['cart'] ?? array();
    if(!is_nan($_POST['count']) && !is_nan($_POST['index']))
    {
        if($_POST['count'] <= 0)
        {
            unset($_SESSION['cart'][$_POST['index']]);
        }
        else
        {
            $_SESSION['cart'][$_POST['index']]['count'] = $_POST['count'];
        }
        echo json_encode(array("count"=>calc_total_count($_SESSION['cart']), "price"=> calc_total_price($_SESSION['cart'])));
    }

?>