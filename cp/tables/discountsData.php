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
$table = 'discountstable';

// Table's primary key 
$primaryKey = 'id';

$columns = array(
    array('db' => 'id',   'dt' => 0),
    array('db' => 'code', 'dt' => 1),
    array('db' => 'name', 'dt' => 2),
    array('db' => 'min_order', 'dt' => 3),
    array('db' => 'max_uses', 'dt' => 4),
    array('db' => 'max_user_use', 'dt' => 5),
    array('db' => 'max_discount', 'dt' => 6),
    array('db' => 'active', 'dt' => 7, 'formatter' => function ($d) {
        return match ((int)$d) {
            0 => 'غير مٌفعل',
            1 => 'مُفعل دائماً',
            2 => 'مُفعل خلال فترة',
        };
    }),
    array('db' => 'active_status', 'dt' => 8, 'formatter' => function ($d) {
        return match ((int)$d) {
            1 => '<span class="badge text-bg-success text-white">يعمل</span>',
            0 => '<span class="badge text-bg-danger text-white">لا يعمل</span>',
        };
    }),
    array('db' => 'start_date', 'dt' => 9, 'formatter' => function ($d) {
        return (!empty($d)) ? "<bdi>$d</bdi>" : '';
    }),
    array('db' => 'end_date', 'dt' => 10, 'formatter' => function ($d) {
        return (!empty($d)) ? "<bdi>$d</bdi>" : '';
    }),
    array('db' => 'locations_type', 'dt' => 11, 'formatter' => function ($d) {
        return match ((int)$d) {
            0 => 'بدون شروط',
            1 => 'متاح لمناطق محددة',
            2 => 'غير متاح لمانطق محددة'
        };
    }),
    array('db' => 'categories_type', 'dt'    => 12, 'formatter' => function ($d) {
        return match ((int)$d) {
            0 => 'بدون شروط',
            1 => 'متاح لتصنيفات محدده',
            2 => 'غير متاح لتصنيفات محدده'
        };
    }),
    array('db' => 'items_type', 'dt'    => 13, 'formatter' => function ($d) {
        return match ((int)$d) {
            0 => 'بدون شروط',
            1 => 'متاح لاصناف محدده',
            2 => 'غير متاح لاصناف محدده'
        };
    }),
    array('db' => 'phone', 'dt'    => 14, 'formatter' => function ($d) {
        return match ((int)$d) {
            0 => 'بدون شروط',
            1 => 'متاح لعملاء محددين',
            2 => 'غير متاح لعملاء محددين'
        };
    }),
    array('db' => 'discount_type', 'dt' => 15, 'formatter' => function ($d) {
        return match ((int)$d) {
            0 => 'خصم الطلب',
            1 => 'خصم التوصيل'
        };
    }),
    array('db' => 'discount_value', 'dt' => 16),
    array('db' => 'total_uses', 'dt' => 17, 'formatter' => function ($d, $row) {
        $ret = $d;
        if (check_user_perm(['discounts-view-orders'])) :
            $ret .= " <br><a class='text-primary' href='discount-orders.php?id={$row['id']}' target='_blank'>عرض الطلبات</a>";

            return $ret;
        else :
            return $ret;
        endif;
    }),
    array('db' => 'total_users', 'dt' => 18),
    array(
        'db'        => 'id',
        'dt'        => 19,
        'formatter' => function ($d, $row) {
            if (check_user_perm(['discounts-remove']) || check_user_perm(['discounts-edit'])) :
                $action = '<div class="d-flex justify-content-center align-items-center gap-3">';

                if (check_user_perm(['discounts-remove'])) :
                    $action .= '<button class="btn btn-danger my-0" onclick="remove_discount(this, ' . $d . ')">إزالة</button>';
                endif;
                if (check_user_perm(['discounts-edit'])) :
                    $action .= '<a  href="edit-discount.php?id=' . $d . '"><button class="btn btn-success my-0">تعديل</button></a>';
                endif;
                $action .= '</div>';

                return $action;
            else :
                return '';
            endif;
        }
    ),
);

// Include SQL query processing class 
require '../functions/ssp.class.php';

// Output data as json format 
echo json_encode(
    SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
);
