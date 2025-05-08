<?php
include 'includes/conn.php';
$get_colors_settings = mysqli_query($GLOBALS['conn'], "SELECT * FROM colors_settings WHERE 1");
$colors_settings = mysqli_fetch_assoc($get_colors_settings);
