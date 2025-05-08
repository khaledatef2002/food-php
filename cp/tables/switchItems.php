<?php

require '../../includes/config.php';
require '../../includes/conn.php';
require '../functions/main.php';
if (!is_logged() || !check_user_perm(['switch-items'])) {
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
        'db' => 'title', 
        'dt' => 1, 
        'formatter'=> function($d, $row) {
            return  "<div class='d-flex align-items-center gap-2'><div style='height:40px;'><img class='rounded' style='height:100%;' src='../" . $row['img'] . "'></div>" . "<span class='fw-bold'> $d</span></div>";
        }
    ),
    array(
        'db' => 'cat_id',
        'dt'    => 2,
        'formatter' => function ($d, $row) {
            return get_category_info($d)['category_name'];
        }
    ),
    array(
        'db' => 'active',
        'dt'    => 3,
        'formatter' => function ($d, $row) {
            return match ((int)$d) {
                0 => 'غير مٌفعل',
                1 => 'مُفعل دائماً',
                2 => 'مُفعل خلال فترة',
            };
        }
    ),
    array('db' => 'active_Status', 'dt' => 4, 'formatter' => function ($d) {
        return match ((int)$d) {
            1 => '<span class="badge text-bg-success text-white">يعمل</span>',
            0 => '<span class="badge text-bg-danger text-white">لا يعمل</span>',
        };
    }),
    array(
        'db'        => 'id',
        'dt'        => 5,
        'formatter' => function ($d, $row) {
            return '<div class="d-flex justify-content-center align-items-center gap-1"><button class="btn btn-success my-0 py-1" onclick="open_switch('.$d.')">تعديل</button></div>';
        }
    ),
    array(
        'db' => 'img',
        'dt' => 6,
        'formatter' => function ($d, $row) {
            return "<div style='height:40px;'><img style='height:100%;' src='../" . $d . "'></div>";
        }
    ),
);

// Include SQL query processing class 
require '../functions/ssp.class.php';

// Output data as json format 
echo json_encode(
    SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
);
