<?php

include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_GET['id']) && is_logged() && check_user_perm(['switch-items'])) {
    if (!empty($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        
        $get_item_info = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items WHERE id='$id'");
        $item_info = mysqli_fetch_assoc($get_item_info);

        echo json_encode(
            array(
                "msg" => "success", 
                "body" => array(
                    "title" => $item_info['title'],
                    "active" => $item_info['active'],
                    "date_from" => date("Y-m-d", $item_info['from']),
                    "time_from" => date("H:i:s", $item_info['from']),
                    "date_to" => date("Y-m-d", $item_info['to']),
                    "time_to" => date("H:i:s", $item_info['to'])
                )
            )
        );
        exit;
    } else {
        echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
        exit;
    }
} else {
    echo json_encode(array("msg" => "error", "body" => "يرجى ادخال جميع البيانات"));
}
