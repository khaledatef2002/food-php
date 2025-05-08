<?php

require '../../includes/config.php';
require '../../includes/conn.php';
require '../functions/main.php';
if (!is_logged() || !check_user_perm(['items-view'])) {
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
$table = 'itemstable';

// Table's primary key 
$primaryKey = 'id';

$columns = array(
    array('db' => 'id',   'dt' => 0),
    array(
        'db' => 'img',
        'dt' => 1,
        'formatter' => function ($d, $row) {
            return "<div style='height:40px;'><img style='height:100%;' src='../" . $d . "'></div>";
        }
    ),
    array('db' => 'title', 'dt' => 2),
    array(
        'db' => 'cat_id',
        'dt'    => 3,
        'formatter' => function ($d, $row) {
            return get_category_info($d)['category_name'];
        }
    ),
    array('db' => 'description', 'dt' => 4),
    array('db' => 'price', 'dt' => 5),
    array('db' => 'before_discount', 'dt' => 6),
    array('db' => 'tax', 'dt' => 7),
    array(
        'db' => 'active',
        'dt'    => 8,
        'formatter' => function ($d, $row) {
            return match ((int)$d) {
                0 => 'غير مٌفعل',
                1 => 'مُفعل دائماً',
                2 => 'مُفعل خلال فترة',
            };
        }
    ),
    array('db' => 'active_Status', 'dt' => 9, 'formatter' => function ($d) {
        return match ((int)$d) {
            1 => '<span class="badge text-bg-success text-white">يعمل</span>',
            0 => '<span class="badge text-bg-danger text-white">لا يعمل</span>',
        };
    }),
    ['db' => 'start', 'dt' => 10, 'formatter' => function ($d) {
        return "<bdi>$d</bdi>";
    }],
    ['db' => 'end', 'dt' => 11, 'formatter' => function ($d) {
        return "<bdi>$d</bdi>";
    }],
    array(
        'db'        => 'id',
        'dt'        => 12,
        'formatter' => function ($d, $row) {
            if (check_user_perm(['items-remove']) || check_user_perm(['items-edit'])) :
                $action = '<div class="d-flex justify-content-center align-items-center gap-1">';

                if (check_user_perm(['items-remove'])) :
                    $action .= '<button class="btn btn-danger my-0 py-1" onclick="remove_item(this, ' . $d . ')">إزالة</button>';
                endif;
                if (check_user_perm(['items-edit'])) :
                    $action .= '<a  href="edit-item.php?id=' . $d . '"><button class="btn btn-success my-0 py-1">تعديل</button></a>';
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
