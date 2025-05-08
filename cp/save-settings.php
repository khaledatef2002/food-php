<?php

include '../../includes/conn.php';
include '../functions/main.php';

if (!is_logged()) header('Location: login.php');
if (!check_user_perm(['general-settings-edit', 'general-settings-edit-visa'])) :
    header('Location: 403.php');
    exit;
endif;

if (
    (check_user_perm(['general-settings-edit-visa']) && isset($_POST['visa_fixed_tax']) && isset($_POST['visa_percent_tax']) && isset($_POST['visa_key']) && isset($_POST['visa_id']) && isset($_POST['visa_secret']) && in_array($_POST['visa_av'], [0, 1])) ||
    (check_user_perm(['general-settings-edit']) && isset($_POST['dir']) && isset($_POST['lang']) && isset($_POST['time_zone']) && isset($_POST['currency']) && isset($_POST['website_name']) && isset($_POST['website_keywords']) && isset($_POST['website_description']) && isset($_POST['min_order']) && isset($_POST['website_taxs']) && in_array($_POST['order_av'], [0, 1]) && isset($_POST['order_av_reason']) && isset($_POST['time_msg']) && in_array($_POST['wh_av'], [0, 1]) && isset($_POST['wh_phone']))
) {

    if (check_user_perm(['general-settings-edit-visa'])) {
        if ($_POST['visa_av'] == 1 && (empty($_POST['visa_key']) || empty($_POST['visa_id']) || empty($_POST['visa_secret']))) {
            // empty number
            echo json_encode([
                'res' => 'error',
                'msg' => 'يرجى ادخال بيانات الدفع الالكتروني'
            ]);
            exit;
        }

        $visa_av = mysqli_real_escape_string($GLOBALS['conn'], filter_var($_POST['visa_av'], FILTER_SANITIZE_NUMBER_INT));
        $visa_key = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['visa_key']));
        $visa_id = mysqli_real_escape_string($GLOBALS['conn'], $_POST['visa_id']);
        $visa_secret = mysqli_real_escape_string($GLOBALS['conn'], filter_var($_POST['visa_secret'], FILTER_SANITIZE_NUMBER_INT));
        $visa_percent_tax = mysqli_real_escape_string($GLOBALS['conn'], $_POST['visa_percent_tax']);
        $visa_fixed_tax = mysqli_real_escape_string($GLOBALS['conn'], $_POST['visa_fixed_tax']);

        $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$visa_av' WHERE title='visa_av'");
        $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$visa_key' WHERE title='API_KEY'");
        $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$visa_id' WHERE title='merchantID'");
        $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$visa_secret' WHERE title='secretKey'");
        $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$visa_percent_tax' WHERE title='visa_tax_percent'");
        $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$visa_fixed_tax' WHERE title='visa_tax_fixed'");
    }
    if (check_user_perm(['general-settings-edit'])) {
        if (!empty($_POST['website_name']) || !empty($_POST['website_keywords']) || !empty($_POST['website_description']) || !empty($_POST['time_msg'])) {
            if ($_POST['wh_av'] == 1 && empty($_POST['wh_phone'])) {
                echo json_encode([
                    'res' => 'error',
                    'msg' => 'يرجى ادخال رقم الواتساب'
                ]);
                exit;
            }



            $website_name = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['website_name']));
            $website_keywords = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['website_keywords']));
            $website_description = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['website_description']));
            $min_order = mysqli_real_escape_string($GLOBALS['conn'], filter_var($_POST['min_order'], FILTER_SANITIZE_NUMBER_FLOAT));
            $website_taxs = mysqli_real_escape_string($GLOBALS['conn'], filter_var($_POST['website_taxs'], FILTER_SANITIZE_NUMBER_FLOAT));
            $order_av = mysqli_real_escape_string($GLOBALS['conn'], filter_var($_POST['order_av'], FILTER_SANITIZE_NUMBER_INT));
            $wh_av = mysqli_real_escape_string($GLOBALS['conn'], filter_var($_POST['wh_av'], FILTER_SANITIZE_NUMBER_INT));
            $wh_phone = mysqli_real_escape_string($GLOBALS['conn'], $_POST['wh_phone']);
            $order_av_reason = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['order_av_reason']));
            $time_msg = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['time_msg']));
            $currency = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['currency']));
            $time_zone = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['time_zone']));
            $dir = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['dir']));
            $lang = mysqli_real_escape_string($GLOBALS['conn'], htmlspecialchars($_POST['lang']));



            $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$website_name' WHERE title='site-title'");
            $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$website_keywords' WHERE title='keywords'");
            $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$website_description' WHERE title='description'");
            $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$min_order' WHERE title='order_min'");
            $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$website_taxs' WHERE title='tax'");
            $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$order_av' WHERE title='order_av'");
            $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$wh_av' WHERE title='wh_av'");
            $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$wh_phone' WHERE title='wh_order'");
            $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$order_av_reason' WHERE title='order_dis_msg'");
            $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$time_msg' WHERE title='delivery_time'");
            $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$currency' WHERE title='currency'");
            $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$time_zone' WHERE title='time_zone'");
            $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$dir' WHERE title='dir'");
            $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$lang' WHERE title='lang'");

            if (isset($_FILES['website_logo']) && !empty($_FILES['website_logo']) && $_FILES["website_logo"]["error"] == 0) {
                $msg = upload_image($_FILES['website_logo']);
                if ($msg != "FILE_SIZE_ERROR" && $msg != "FILE_TYPE_ERROR") {
                    $get_old = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='site-logo'");
                    $old_image = mysqli_fetch_assoc($get_old)['value'];

                    if (file_exists("../../" . $old_image)) {
                        unlink("../../" . $old_image);
                    }

                    $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$msg' WHERE title='site-logo'");
                }
            }
            if (isset($_FILES['og_image']) && !empty($_FILES['og_image']) && $_FILES["og_image"]["error"] == 0) {
                $msg = upload_image($_FILES['og_image']);
                if ($msg != "FILE_SIZE_ERROR" && $msg != "FILE_TYPE_ERROR") {
                    $get_old = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='og:image'");
                    $old_image = mysqli_fetch_assoc($get_old)['value'];

                    if (file_exists("../../" . $old_image)) {
                        unlink("../../" . $old_image);
                    }

                    $update = mysqli_query($GLOBALS['conn'], "UPDATE website_settings SET value='$msg' WHERE title='og:image'");
                }
            }
        } else {
            // missing data
            echo json_encode([
                'res' => 'error',
                'msg' => 'يرجى ادخال جميع البيانات المطلوبة'
            ]);
            exit;
        }
    }

    $admin = get_admin_info()['nickname'];

    logg("login", "لقد قام $admin بتغيير اعدادات النظام");

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
