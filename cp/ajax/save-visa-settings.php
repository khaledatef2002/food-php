<?php

include '../../includes/conn.php';
include '../functions/main.php';

if (!is_logged()) header('Location: login.php');
if (!check_user_perm(['general-settings-edit-visa'])) :
    header('Location: 403.php');
    exit;
endif;

if (
    check_user_perm(['general-settings-edit-visa']) && 
    isset($_POST['visa_av']) && in_array($_POST['visa_av'], ['0', '1']) &&
    isset($_POST['visa_fixed_tax']) && is_numeric($_POST['visa_fixed_tax']) && $_POST['visa_fixed_tax'] >= 0 &&
    isset($_POST['visa_percent_tax']) && is_numeric($_POST['visa_percent_tax']) && $_POST['visa_percent_tax'] >= 0 &&
    isset($_POST['selected_payment_method_providor']) && in_array($_POST['selected_payment_method_providor'], ['qnb', 'paymob']) &&
    isset($_POST['visa_qnb_merchant_id']) && 
    isset($_POST['visa_qnb_api_password']) &&
    isset($_POST['paymob_hmac']) &&
    isset($_POST['paymob_secret_key']) &&
    isset($_POST['paymob_iframe_id']) &&
    isset($_POST['paymob_integration_id'])
) {

    $visa_av = mysqli_real_escape_string($GLOBALS['conn'], $_POST['visa_av']);
    $visa_fixed_tax = mysqli_real_escape_string($GLOBALS['conn'], $_POST['visa_fixed_tax']);
    $visa_percent_tax = mysqli_real_escape_string($GLOBALS['conn'], $_POST['visa_percent_tax']);
    $selected_payment_method_providor = mysqli_real_escape_string($GLOBALS['conn'], $_POST['selected_payment_method_providor']);
    $visa_qnb_merchant_id = mysqli_real_escape_string($GLOBALS['conn'], $_POST['visa_qnb_merchant_id']);
    $visa_qnb_api_password = mysqli_real_escape_string($GLOBALS['conn'], $_POST['visa_qnb_api_password']);
    $paymob_hmac = mysqli_real_escape_string($GLOBALS['conn'], $_POST['paymob_hmac']);
    $paymob_secret_key = mysqli_real_escape_string($GLOBALS['conn'], $_POST['paymob_secret_key']);
    $paymob_iframe_id = mysqli_real_escape_string($GLOBALS['conn'], $_POST['paymob_iframe_id']);
    $paymob_integration_id = mysqli_real_escape_string($GLOBALS['conn'], $_POST['paymob_integration_id']);
    
    $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$visa_av' WHERE title='visa_av'");
    $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$visa_fixed_tax' WHERE title='visa_fixed_tax'");
    $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$visa_percent_tax' WHERE title='visa_percent_tax'");
    $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$selected_payment_method_providor' WHERE title='selected_payment_method_providor'");
    $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$visa_qnb_merchant_id' WHERE title='visa_qnb_merchant_id'");
    $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$visa_qnb_api_password' WHERE title='visa_qnb_api_password'");
    $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$paymob_hmac' WHERE title='paymob_hmac'");
    $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$paymob_secret_key' WHERE title='paymob_secret_key'");
    $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$paymob_iframe_id' WHERE title='paymob_iframe_id'");
    $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$paymob_integration_id' WHERE title='paymob_integration_id'");

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بتغيير اعدادات الدفع");

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
