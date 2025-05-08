<?php

include '../../includes/conn.php';
include '../functions/main.php';

if (is_logged() && check_user_perm(['live-orders-view'])) {
    if (check_unresponsed_order_period()) {
        echo "true";
    } else {
        echo "false";
    }
}
