<?php
include '../../includes/conn.php';
include '../functions/main.php';

if (isset($_POST['id']) && is_logged() && check_user_perm(['roles-remove'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $del = mysqli_query($GLOBALS['conn'], "DELETE FROM roles WHERE id='$id'");

    if (!$del) {
        echo "error";
    }
} else {
    echo "error";
}
