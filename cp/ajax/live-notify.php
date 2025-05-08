<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (is_logged() && isset($_POST['last']) && isset($_POST['page'])) {
    $last = filter_var($_POST['last'], FILTER_SANITIZE_NUMBER_INT);
    $page = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['page']));

    $query = mysqli_query($GLOBALS['conn'], "SELECT * FROM live_notify WHERE id > '" . $last . "' AND page='" . $page . "'");
    if (mysqli_num_rows($query) > 0) {
        $total_notifies = array();
        while ($notify = mysqli_fetch_assoc($query)) {
            $get_data = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE id='" . $notify['data'] . "'");
            $data = mysqli_fetch_assoc($get_data);
            $notify_data = array(
                "id" => $notify['id'],
                "page" => $notify['page'],
                "type" => $notify['type'],
                "order_id" =>    $data['id'],
                "order" => get_order_card($data),
            );
            array_push($total_notifies, $notify_data);
        }
        echo json_encode($total_notifies);
    } else {
        echo "empty";
    }
} else {
    echo "empty";
}
