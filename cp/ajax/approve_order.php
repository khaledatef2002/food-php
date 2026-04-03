<?php
    include '../../includes/conn.php'; 
    include '../functions/main.php';

    if(isset($_POST['id']) && is_logged() && check_user_perm(['live-orders-action']))
    {
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $date = time();
        $acceptor = get_admin_info()['id'];
        $admin_name = get_admin_info()['nickname'];
        $insertion = mysqli_query($GLOBALS['conn'], "UPDATE food_orders SET marked=1, marked_date='$date', accepted_by='$acceptor' WHERE id='$id'");

        $jwt = generateJWT([
            "channel" => $GLOBALS['channel'],
            "iat" => time(),
            "exp" => time() + 3600
        ]);

        sendWebSocketCurl(["orderId" => $id], "approve-order", $jwt);

        logg('live orders', "لقد قام $admin_name بقبول الطلب رقم $id");

        if(!$insertion)
        {
            echo "error";
        }
    }
    else
    {
        echo "error";
    }

?>