<?php

include '../../includes/conn.php';
include '../functions/main.php';

if (!is_logged()) header('Location: login.php');
if (!check_user_perm(['colors-settings-edit'])) :
    header('Location: 403.php');
    exit;
endif;

if (isset($_POST['header']) && isset($_POST['footer']) && isset($_POST['footer_color']) && isset($_POST['icon']) && isset($_POST['icon_back']) && isset($_POST['icon_border']) && isset($_POST['button_back']) && isset($_POST['button_color']) && isset($_POST['radio_back']) && isset($_POST['radio_border']) && isset($_POST['radio_color']) && isset($_POST['cat_header_back']) && isset($_POST['cat_header_color']) && isset($_POST['cat_header_active_back']) && isset($_POST['cat_header_active_color']) && isset($_POST['order_footer_back']) && isset($_POST['order_footer_color']) && isset($_POST['order_footer_n_back']) && isset($_POST['order_footer_n_color']) && isset($_POST['modal_header_back']) && isset($_POST['modal_header_color']) && isset($_POST['text'])) {
    if (!empty($_POST['header']) || !empty($_POST['footer']) || !empty($_POST['footer_color']) || !empty($_POST['icon']) || !empty($_POST['icon_back']) || !empty($_POST['icon_border']) || !empty($_POST['button_back']) || !empty($_POST['button_color']) || !empty($_POST['radio_back']) || !empty($_POST['radio_border']) || !empty($_POST['radio_color']) || !empty($_POST['cat_header_back']) || !empty($_POST['cat_header_color']) || !empty($_POST['cat_header_active_back']) || !empty($_POST['cat_header_active_color']) || !empty($_POST['order_footer_back']) || !empty($_POST['order_footer_color']) || !empty($_POST['order_footer_n_back']) || !empty($_POST['order_footer_n_color']) || !empty($_POST['modal_header_back']) || !empty($_POST['modal_header_color']) || !empty($_POST['text'])) {

        $header = mysqli_real_escape_string($GLOBALS['conn'], $_POST['header']);
        $footer = mysqli_real_escape_string($GLOBALS['conn'], $_POST['footer']);
        $footer_color = mysqli_real_escape_string($GLOBALS['conn'], $_POST['footer_color']);
        $icon = mysqli_real_escape_string($GLOBALS['conn'], $_POST['icon']);
        $icon_back = mysqli_real_escape_string($GLOBALS['conn'], $_POST['icon_back']);
        $icon_border = mysqli_real_escape_string($GLOBALS['conn'], $_POST['icon_border']);
        $button_back = mysqli_real_escape_string($GLOBALS['conn'], $_POST['button_back']);
        $button_color = mysqli_real_escape_string($GLOBALS['conn'], $_POST['button_color']);
        $radio_back = mysqli_real_escape_string($GLOBALS['conn'], $_POST['radio_back']);
        $radio_border = mysqli_real_escape_string($GLOBALS['conn'], $_POST['radio_border']);
        $radio_color = mysqli_real_escape_string($GLOBALS['conn'], $_POST['radio_color']);
        $cat_header_back = mysqli_real_escape_string($GLOBALS['conn'], $_POST['cat_header_back']);
        $cat_header_color = mysqli_real_escape_string($GLOBALS['conn'], $_POST['cat_header_color']);
        $cat_header_active_back = mysqli_real_escape_string($GLOBALS['conn'], $_POST['cat_header_active_back']);
        $cat_header_active_color = mysqli_real_escape_string($GLOBALS['conn'], $_POST['cat_header_active_color']);
        $order_footer_back = mysqli_real_escape_string($GLOBALS['conn'], $_POST['order_footer_back']);
        $order_footer_color = mysqli_real_escape_string($GLOBALS['conn'], $_POST['order_footer_color']);
        $order_footer_n_back = mysqli_real_escape_string($GLOBALS['conn'], $_POST['order_footer_n_back']);
        $order_footer_n_color = mysqli_real_escape_string($GLOBALS['conn'], $_POST['order_footer_n_color']);
        $modal_header_back = mysqli_real_escape_string($GLOBALS['conn'], $_POST['modal_header_back']);
        $modal_header_color = mysqli_real_escape_string($GLOBALS['conn'], $_POST['modal_header_color']);
        $text = mysqli_real_escape_string($GLOBALS['conn'], $_POST['text']);

        $update = mysqli_query($GLOBALS['conn'], "UPDATE colors_settings SET header='$header', footer='$footer', icon='$icon', icon_back='$icon_back', icon_border='$icon_border', button_back='$button_back', button_color='$button_color', cat_header_back='$cat_header_back', cat_header_color='$cat_header_color', cat_header_active_back='$cat_header_active_back', cat_header_active_color='$cat_header_active_color', order_footer_back='$order_footer_back', order_footer_color='$order_footer_color', order_footer_n_back='$order_footer_n_back', order_footer_n_color='$order_footer_n_color', footer_color='$footer_color', radio_border='$radio_border', radio_back='$radio_back', radio_color='$radio_color', `text`='$text', modal_header_back='$modal_header_back', modal_header_color='$modal_header_color'");

        $admin = get_admin_info()['nickname'];

        logg("colors settings", "لقد قام $admin بتغيير الوان الموقع");

        echo json_encode([
            'res' => 'success',
        ]);
        exit;
    } else {
        // missing data
        echo json_encode([
            'res' => 'error',
            'msg' => 'يرجى ادخال جميع البيانات المطلوبة'
        ]);
        exit;
    }
} else {
    // missing data
    echo json_encode([
        'res' => 'error',
        'msg' => 'يرجى ادخال جميع البيانات المطلوبة'
    ]);
    exit;
}
