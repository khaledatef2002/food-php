<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (is_logged() && isset($_POST['id'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $get_data = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE id='" . $id . "'");
    if(mysqli_num_rows($get_data) == 0) {
        echo "";
        exit();
    }
    $data = mysqli_fetch_assoc($get_data);

    $is_pending = check_unresponsed_order_period();
    echo json_encode([
        "is_pending" => $is_pending,
        "data" => get_order_card($data)
    ]);
} else {
    echo "";
}
