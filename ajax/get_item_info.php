<?php

    include '../includes/conn.php';
    include '../includes/functions.php';
    

    if(!is_nan($_POST['id']))
    {
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $info = get_item_info($id);
        $info['description'] = htmlspecialchars($info['description']);
        echo json_encode($info);   
    }

?>