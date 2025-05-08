<?php

require '../../includes/config.php';
require '../../includes/conn.php';
require '../functions/main.php';
if (!is_logged() || !check_user_perm(['log-view'])) {
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
$table = 'logstable';

// Table's primary key 
$primaryKey = 'id';

$columns = array(
    array('db' => 'id',   'dt' => 0),
    array('db' => 'page', 'dt' => 1,),
    array('db' => 'admin', 'dt' => 2),
    array('db' => 'logDate', 'dt' => 3, 'formatter' => function ($d) {
        return "<bdi>" . $d . "</bdi>";
    }),
    array('db' => 'ip', 'dt' => 4),
    array('db' => 'log', 'dt' => 5),
);

// Include SQL query processing class 
require '../functions/ssp.class.php';

// Output data as json format 
echo json_encode(
    SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
);
