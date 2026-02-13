<?php

include 'includes/conn.php';

$key = $argv[1];
$active = $argv[2];
$id = $argv[3];

if($key != "secret123")
    die("Unauthorized " . $key);

mysqli_query($GLOBALS['conn'], "UPDATE food_branches SET active=".$active." WHERE id=" .$id);
