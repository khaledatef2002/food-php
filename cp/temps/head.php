<?php include '../includes/conn.php'; ?>
<?php include 'functions/main.php'; ?>
<?php
    $get_colors_settings = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings");
    $fetch = mysqli_fetch_all($get_colors_settings, MYSQLI_ASSOC);
    $site_setting = array_column($fetch, 'value' , 'title');
?>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="apple-touch-icon" sizes="76x76" href="../<?php echo $site_setting['site-logo']; ?>">
<link rel="icon" type="image/png" href="../<?php echo $site_setting['site-logo']; ?>">
<!-- Font Awesome Icons -->
<script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
<!-- CSS Files -->
<link id="pagestyle" href="assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
<link id="pagestyle" href="libs/sweetalert2/sweet.css" rel="stylesheet" />
<link id="pagestyle" href="libs/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link id="pagestyle" href="libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link rel="stylesheet" href="css/main.css">