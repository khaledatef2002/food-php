<?php

    include '../includes/conn.php';
    include '../includes/functions.php';

    $get_min_order_settings = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='order_min'");
    $min_order = mysqli_fetch_assoc($get_min_order_settings)['value'];

    if(!is_work() || is_disabled())
    {
        exit();
    }

    session_start();
    if(isset($_SESSION['cart']) && !empty($_SESSION['cart']))
    {
        $cart = $_SESSION['cart'];
    }
    else
    {
        exit();
    }

    if(calc_total_price($cart) < $min_order)
    {
        exit;
    }

    $data = $_POST['data'];

    // Checker
    if(empty(trim($data['client_name'])) || strlen(trim($data['client_name'])) < 3 || strpos($data['client_name'],'<') !== false || strpos($data['client_name'],'>')  !== false || strpos($data['client_name'],'"')  !== false || strpos($data['client_name'],"'")  !== false || strpos($data['client_name'],'/')  !== false || strpos($data['client_name'],'&')  !== false || strpos($data['client_name'],';')  !== false )
    {
        exit();
    }
    else if(strlen(trim($data['client_phone'])) != 11 || !(substr( $data['client_phone'], 0, 3 ) != "010" || substr( $data['client_phone'], 0, 3 ) != "011" || substr( $data['client_phone'], 0, 3 ) != "012" || substr( $data['client_phone'], 0, 3 ) != "015") || is_nan($data['client_phone']))
    {
        exit();
    }
    else if(empty(trim($data['client_location'])) || is_nan($data['client_location']))
    {
        exit();
    }
    else if(empty(trim($data['client_address'])) || strlen(trim($data['client_address'])) < 5 || strpos($data['client_address'],'<') !== false || strpos($data['client_address'],'>') !== false || strpos($data['client_address'],'"') !== false || strpos($data['client_address'],"'") !== false || strpos($data['client_address'],'/') !== false || strpos($data['client_address'],'&') !== false || strpos($data['client_address'],';') !== false)
    {
        exit();
    }

    $data['client_name'] = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($data['client_name']));
    $data['client_phone'] = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($data['client_phone']));
    $data['client_location'] = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($data['client_location']));
    $data['client_address'] = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($data['client_address']));
    $data['client_notice'] = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($data['client_notice']));

    // Getting Delivery Price
    $get_price = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_locations WHERE id='".$data['client_location']."'");
    $fetch = mysqli_fetch_assoc($get_price);
    
    //Getting branch Name
    $get_branch = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_branches WHERE id='".$fetch['branch_id']."'");
    $fetchBranch = mysqli_fetch_assoc($get_branch);

    $branch = $fetchBranch['branch_name'];
    $branch_id = $fetchBranch['id'];
    $del_price = $fetch['price'];

    $tax = get_total_tax($cart) + (get_general_tax() * $del_price / 100);
    
    $date = time();
    $client_area_name = get_area_info($data['client_location'])['name'];

    $discount_data = [
            "discount_id" => "",
            "discount_code" => "",
            "discount_total" => 0,
            "discount_delv" => "",
        ];
    if(isset($_POST['data']['discount']))
    {
        $discount_data = get_discount_values($_POST['data']['discount']);
    }

    $add_cart = mysqli_query($GLOBALS['conn'], "INSERT INTO food_orders(client_name,client_phone,client_branch_id,client_branch,client_area_id,client_area_name,client_address,address_price,client_notice,ordered_date,discount_id,discount_code,discount_name,delivery_discount,total_discount,tax) VALUES ('".$data['client_name']."','".$data['client_phone']."','".$branch_id."','".$branch."','".$data['client_location']."','".$client_area_name."','".$data['client_address']."','".$del_price."','".$data['client_notice']."','".$date."','".$discount_data['discount_id']."','".$discount_data['discount_code']."','".$discount_data['discount_name']."','".$discount_data['discount_delv']."','".$discount_data['discount_total']."', '$tax')");

    $order_id = mysqli_insert_id($GLOBALS['conn']);

    $notify = mysqli_query($GLOBALS['conn'], "INSERT INTO live_notify(page,type,data) VALUES('order', 'add', '$order_id')");

    foreach($cart as $key => $item) {
        $item_info = get_item_info($item['item_id']);
        if(isset($item['size']))
        {
            $size = $item['size'];
            $price = get_size_info($item['size'])['size_price'];
            $size_name = get_size_info($item['size'])['size_name'];
        }
        else
        {
            $size = 0;
            $price = $item_info['price'];
            $size_name = "";
        }
        $item['item_id'] = mysqli_real_escape_string($GLOBALS['conn'], $item['item_id']);
        $item_info['title'] = mysqli_real_escape_string($GLOBALS['conn'], $item_info['title']);
        $item['count'] = mysqli_real_escape_string($GLOBALS['conn'], $item['count']);
        $price = mysqli_real_escape_string($GLOBALS['conn'], $price);
        $size = mysqli_real_escape_string($GLOBALS['conn'], $size);
        $size_name = mysqli_real_escape_string($GLOBALS['conn'], $size_name);
        $insert_item = mysqli_query($GLOBALS['conn'], "INSERT INTO food_order_cart(order_id,item_id,item_name,item_count,item_price,item_size,item_size_name) VALUES('".$order_id."','".$item['item_id']."','".$item_info['title']."','".$item['count']."','".$price."','".$size."','$size_name')"); 
        $order_card_id = mysqli_insert_id($GLOBALS['conn']);

        if(isset($item['options']))
        {
            foreach($item['options'] as $key=>$option)
            {
                $option_id = $option['option_id'];
                $option_value = $option['option_value'];
                $option_name = get_option_info($option['option_id'])['name'];
                if(is_array($option['option_value']))
                {
                    foreach($option['option_value'] as $ky=>$val)
                    {
                        $option_id = mysqli_real_escape_string($GLOBALS['conn'], $option_id);
                        $val = mysqli_real_escape_string($GLOBALS['conn'], $val);
                        $option_name = mysqli_real_escape_string($GLOBALS['conn'], $option_name);

                        $insert_options = mysqli_query($GLOBALS['conn'], "INSERT INTO food_order_options(order_card_id,option_id,option_value_id,option_name,option_value,option_price) VALUES($order_card_id, $option_id, $val,'$option_name','".get_option_value_info($val)['name']."','".get_option_value_info($val)['price']."')");
                    }
                }
                else
                {
                    $option_value_name = get_option_value_info($option['option_value'])['name'];
                    $option_id = mysqli_real_escape_string($GLOBALS['conn'], $option_id);
                    $option_value = mysqli_real_escape_string($GLOBALS['conn'], $option_value);
                    $option_name = mysqli_real_escape_string($GLOBALS['conn'], $option_name);
                    $insert_options = mysqli_query($GLOBALS['conn'], "INSERT INTO food_order_options(order_card_id,option_id,option_value_id,option_name,option_value,option_price) VALUES($order_card_id, $option_id, '$option_value','$option_name','$option_value_name','".get_option_value_info($option['option_value'])['price']."')");
                }
            }
        }
    }

    unset($_SESSION['cart']);
    echo $order_id;

?>