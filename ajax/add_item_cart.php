<?php

    include '../includes/conn.php';
    include '../includes/functions.php';

    session_start();
    $cart = $_SESSION['cart'] ?? array();
    $data = $_POST['data'];

    $get_min_order_settings = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='order_min'");
    $min_order = mysqli_fetch_assoc($get_min_order_settings)['value'];

    //check for existing item basic info and they are numbers
    if(isset($data['id']) && !is_nan($data['count']) && !is_nan($data['id']))
    {
        $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
        $count = filter_var($data['count'], FILTER_SANITIZE_NUMBER_INT);
        if(is_valid_exist_item($id) && item_valid_info($data))
        {
            // check if there is already item with these info in the card
            $exist = search_exist($data);
            if($exist >= 0)
            {
                // just add new count to the existing one
                $cart[$exist]['count'] = $cart[$exist]['count'] + $count;
            }
            else
            {
                $new_item = array(
                    "item_id" => $id,
                    "count" => $count,
                );
                if(isset($data['size_id']))
                {
                    $new_item['size'] = filter_var($data['size_id'] , FILTER_SANITIZE_NUMBER_INT);
                }
                if(isset($data['options']))
                {
                    $new_item['options'] = $data['options'];
                }
                array_push($cart, $new_item);
            }
    
            $_SESSION['cart'] = $cart;
            echo json_encode(
                [
                    "reached_min" => (calc_total_price($cart) >= $min_order) ? 1 : 0,
                    "count" => calc_total_count($cart),
                    "price" => calc_total_price($cart)
                ]
            );
        }
    }

    function search_exist($data)
    {
        $cart = $_SESSION['cart'] ?? array();
        $index = -1;
        foreach($cart as $key=>$item)
        {
            if($data['id'] == filter_var($item['item_id'], FILTER_SANITIZE_NUMBER_INT))
            {
                if(is_sizes_equals($data,$item) && is_options_equals($data,$item))
                {
                    $index = $key;
                    break;
                }
            }
        }
        return $index;
    }
    function is_sizes_equals($data, $item)
    {
        if(isset($data['size_id']) && !is_nan($data['size_id']) && isset($item['size']))
        {
            if($data['size_id'] == $item['size'])
            {
              return true;  
            }
            else
            {
                return false;
            }
        }
        else
        {
            return true;
        }
    }
    function is_options_equals($data, $item)
    {
        if(isset($item['options']) && count($item['options']) > 0)
        {
            if(isset($data['options']))
            {
                $options1 = $data['options'];
                $options2 = $item['options'];
                if(count($options1) === count($options2))
                {
                    foreach($options1 as $value)
                    {
                        if(!is_options_contain($options2, $value))
                        {
                            return false;
                        }
                    }
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            if(isset($data['options']))
            {
                return false;
            }
            return true;
        }
    }
    function is_options_contain($options, $val)
    {
        foreach($options as $item)
        {
            if($item['option_id'] == $val['option_id'] && $item['option_value'] == $val['option_value'])
            {
                return true;
            }
        }
        return false;
    }
    function item_valid_info($data)
    {
        return item_valid_size($data) && item_valid_options($data);
    }
    function item_valid_size($data)
    {
        $item = get_item_info($data['id']);
        $id = filter_var($item['id'], FILTER_SANITIZE_NUMBER_INT);
        if(isset($data['size_id']))
        {
            $size =  filter_var($data['size_id'], FILTER_SANITIZE_NUMBER_INT) ?? '';
        }
        else
        {
            $size = '';
        }
        $query = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items_sizes WHERE item_id='$id'");
        if(mysqli_num_rows($query) > 0)
        {
            if(!empty($size) && is_numeric($data['size_id']))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            if(empty($size))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
    function item_valid_options($data)
    {
        $item = get_item_info($data['id']);
        $id = filter_var($item['id'], FILTER_SANITIZE_NUMBER_INT); 
        $options =  $data['options'] ?? '';
        $query = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items_options WHERE item_id='$id' AND type=0");
        $query2 = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items_options WHERE item_id='$id' AND type=1");
        if(mysqli_num_rows($query) > 0)
        {
            if(!empty($options) && count($options) > 0)
            {
                while($option = mysqli_fetch_assoc($query))
                {
                    if($option['type'] == 0 && is_option_and_value_valid($options, $option['id']) == -1)
                    {
                        return false;
                    }
                }
                return true;
            }
            else
            {
                return false;
            }
        }
        else if(mysqli_num_rows($query2) > 0){
            return true;
        }
        else
        {
            if(empty($options))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
    function is_option_and_value_valid($options, $id)
    {
        foreach($options as $key=>$option)
        {
            if(!is_nan($option['option_id']) && !is_nan($option['option_value']) && $option['option_id'] == $id)
            {
                if( is_option_value_exist($option['option_id'], $option['option_value']) )
                {
                    return $key;
                }
            }
        }
        return -1;
    }
    function is_option_value_exist($option_id, $option_value)
    {
        $option_id = filter_var($option_id, FILTER_SANITIZE_NUMBER_INT);
        $option_value = filter_var($option_value, FILTER_SANITIZE_NUMBER_INT);
        $query = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items_options_values WHERE option_id='$option_id' AND id='$option_value'");
        return mysqli_num_rows($query) > 0;
    }
    
    function is_valid_exist_item($id)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $query = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items WHERE id='$id' AND (active=1 OR (active=2 AND  `from` <= '".time()."' AND  `to` >= '".time()."'))");
        if(mysqli_num_rows($query) == 0)
        {
            return false;
        }

        $cat = mysqli_fetch_assoc($query)['cat_id'];
        $query2 = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_categories WHERE id='$cat' AND active=1");
        if(mysqli_num_rows($query2) == 0)
        {
            return false;
        }

        return true;
    }
?>