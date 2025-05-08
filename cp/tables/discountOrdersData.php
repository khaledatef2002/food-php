<?php

require '../../includes/config.php';
require '../../includes/conn.php';
require '../functions/main.php';
if (!is_logged() || !check_user_perm(['discounts-view'])) {
    exit();
}

// Database connection info 
$dbDetails = array(
    'host' => $GLOBALS['HOST'],
    'user' => $GLOBALS['USERNAME'],
    'pass' => $GLOBALS['PASSWORD'],
    'db'   => $GLOBALS['DB']
);

// DB table to use 
$table = 'orderstable';

// Table's primary key 
$primaryKey = 'id';

$get_settings = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='currency'");
$fetch = mysqli_fetch_array($get_settings);
$currency = $fetch['value'];

$columns = array(
    array('db' => 'id',   'dt' => 0),
    array('db' => 'client_name', 'dt' => 1,),
    array('db' => 'client_phone', 'dt' => 2),
    array('db' => 'client_branch', 'dt' => 3),
    array('db' => 'client_area_name', 'dt' => 4),
    array('db' => 'client_address', 'dt' => 5),
    array('db' => 'client_notice', 'dt' => 6),

    array('db' => 'method', 'dt' => 7, 'formatter' => function ($d, $row) {
        return match ((int)$d) {
            0 => 'الدفع عند الاستلام',
            1 => 'الدفع بالفيزا'
        };
    }),
    array('db' => 'order_date', 'dt' => 8, 'formatter' => fn ($d) => "<bdi>" . $d . "</bdi>"),
    array('db' => 'marked', 'dt' => 9, 'formatter' => function ($d) {
        return match ((int)$d) {
            0 => '<span class="badge text-bg-warning text-white">في انتظار القبول</span>',
            1 => '<span class="badge text-bg-success text-white">تم القبول</span>',
            2 => '<span class="badge text-bg-danger text-white">تم الرفض</span>',
        };
    }),

    array('db' => 'tax', 'dt' => 10, 'formatter' => fn ($d) => round($d, 2) . $currency),
    array('db' => 'total_cart', 'dt' => 11, 'formatter' => fn ($d) => round($d, 2) . $currency),
    array('db' => 'address_price', 'dt' => 12, 'formatter' => fn ($d) => round($d, 2) . $currency),

    array('db' => 'discount', 'dt' => 13, 'formatter' => fn ($d) => round($d, 2) . $currency),
    array('db' => 'discount_name', 'dt' => 14),
    array('db' => 'total_order', 'dt' => 15, 'formatter' => fn ($d) => round($d, 2) . $currency),

    array('db' => 'accept_date', 'dt' => 16, 'formatter' => fn ($d) => "<bdi>" . $d . "</bdi>"),
    array('db' => 'accepted_by', 'dt' => 17),
    array('db' => 'canceled_date', 'dt' => 18, 'formatter' => fn ($d) => "<bdi>" . $d . "</bdi>"),
    array('db' => 'canceled_by', 'dt' => 19),

    array(
        'db'        => 'id',
        'dt'        => 20,
        'formatter' => function ($d, $row) {
            if (check_user_perm(['discounts-view-orders', 'orders-data-remove'])) :
                $action = "<div class='d-flex justify-content-center align-items-center gap-3'>";
                if (check_user_perm(['orders-data-remove'])) :
                    $action .= "<button class='btn btn-danger my-0 py-1' onclick='remove_order($d, this)'>إزالة</button>";
                endif;

                if (check_user_perm(['discounts-view-orders'])) :
                    $action .= "<a href='show-order.php?id=$d' target='_blank'><button class='btn btn-info my-0 py-1'>عرض الطلب</button></a>";
                endif;
                $action .= "</div>";

                return $action;
            else :
                return '';
            endif;
        }
    ),
);

// Include SQL query processing class 
require '../functions/ssp.class.php';

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$where = "discount_id=" . $id;

// Output data as json format 
echo json_encode(
    SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns, $where)
);
