<?php

include 'config.php';

$GLOBALS['conn'] = mysqli_connect($GLOBALS['HOST'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD'], $GLOBALS['DB']);

if (!$GLOBALS['conn']) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");


$get_colors_settings = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings");
$fetch = mysqli_fetch_all($get_colors_settings, MYSQLI_ASSOC);
$site_setting = array_column($fetch, 'value', 'title');

date_default_timezone_set($site_setting['time_zone']);
