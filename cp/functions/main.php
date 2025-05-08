<?php

session_start();

function check_login(string $username, string $password): bool
{
    $username = mysqli_real_escape_string($GLOBALS['conn'], $username);
    $password = sha1($password);
    $check_login = mysqli_query($GLOBALS['conn'], "SELECT * FROM panel_user WHERE username='" . $username . "' and password='" . $password . "'");
    if (mysqli_num_rows($check_login) > 0) {
        return true;
    } else {
        return false;
    }
}

function is_logged(): bool
{
    $username = mysqli_real_escape_string($GLOBALS['conn'], $_SESSION['username'] ?? '');
    $password = mysqli_real_escape_string($GLOBALS['conn'], $_SESSION['password'] ?? '');
    $check_login = mysqli_query($GLOBALS['conn'], "SELECT * FROM panel_user WHERE username='" . $username . "' and password='" . sha1($password) . "'");
    if (mysqli_num_rows($check_login) > 0) {
        return true;
    } else {
        return false;
    }
}

function get_last_orders(): mysqli_result
{
    $orders = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders ORDER BY id DESC LIMIT 15");
    return $orders;
}

function get_total_order_price(int $id): float
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $total = 0;
    $get_order = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_order_cart WHERE order_id='" . $id . "'");
    while ($fetch = mysqli_fetch_assoc($get_order)) {
        $total += ($fetch['item_price'] * $fetch['item_count']);

        $get_options = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_order_options WHERE order_card_id='" . $fetch['id'] . "'");
        while ($option = mysqli_fetch_assoc($get_options)) {
            $total += ($option['option_price'] * $fetch['item_count']);
        }
    }
    return $total;
}

function get_option_info(int $id): array
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $get_option = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items_options WHERE id='$id'");
    return mysqli_fetch_assoc($get_option);
}


function get_option_value_info(int $id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $get_option_value = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items_options_values WHERE id='$id'");
    return mysqli_fetch_assoc($get_option_value);
}

function get_admin_info(): array
{
    $username = mysqli_real_escape_string($GLOBALS['conn'], $_SESSION['username']);
    $get_user_info = mysqli_query($GLOBALS['conn'], "SELECT * FROM panel_user WHERE username='$username'");
    return mysqli_fetch_assoc($get_user_info);
}

function get_user_info(int $id)
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

    $get_user_info = mysqli_query($GLOBALS['conn'], "SELECT * FROM panel_user WHERE id='$id'");
    return mysqli_fetch_assoc($get_user_info);
}

function get_period(): mysqli_result|bool
{
    $date_arr = array("Saturday" => 0, "Sunday" => 1, "Monday" => 2, "Tuesday" => 3, "Wednesday" => 4, "Thursday" => 5, "Friday" => 6);
    $week = date("l");
    $current_date = $date_arr[$week] . date("His");

    $date_plus = time() - (1 * 60 * 60);
    $date_plus = $date_arr[date("l", $date_plus)] . date("His", $date_plus);
    $get_period = mysqli_query($GLOBALS['conn'], "SELECT * FROM work_periods WHERE (from_date < to_date AND from_date <='$current_date' AND (to_date >= '$current_date' OR to_date >= '$date_plus')) OR (from_date > to_date AND (from_date <='$current_date' OR (to_date >= '$current_date' OR to_date >= '$date_plus')))");

    if (mysqli_num_rows($get_period) > 0) {
        $period = mysqli_fetch_assoc($get_period);
        if ($period['from_date'] > $period['to_date']) {
            if ($current_date >= $period['from_date']) {
                $from = def_to_time($period['from_date']);
                $to = def_to_time_next_week($period['to_date']);
            } else {
                $from = def_to_time_prev_week($period['from_date']);
                $to = def_to_time($period['to_date']);
            }
        } else {
            $from = def_to_time($period['from_date']);
            $to = def_to_time($period['to_date']);
        }


        $getting_items = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE ordered_date <= '$to' AND ordered_date >= '$from' ORDER BY id DESC");
        return $getting_items;
    } else {
        return false;
    }
}

function get_waiting_period(): mysqli_result|bool
{
    $date_arr = array("Saturday" => 0, "Sunday" => 1, "Monday" => 2, "Tuesday" => 3, "Wednesday" => 4, "Thursday" => 5, "Friday" => 6);
    $week = date("l");
    $current_date = $date_arr[$week] . date("His");

    $date_plus = time() - (1 * 60 * 60);
    $date_plus = $date_arr[date("l", $date_plus)] . date("His", $date_plus);
    $get_period = mysqli_query($GLOBALS['conn'], "SELECT * FROM work_periods WHERE (from_date < to_date AND from_date <='$current_date' AND (to_date >= '$current_date' OR to_date >= '$date_plus')) OR (from_date > to_date AND (from_date <='$current_date' OR (to_date >= '$current_date' OR to_date >= '$date_plus')))");

    if (mysqli_num_rows($get_period) > 0) {
        $period = mysqli_fetch_assoc($get_period);
        if ($period['from_date'] > $period['to_date']) {
            if ($current_date >= $period['from_date']) {
                $from = def_to_time($period['from_date']);
                $to = def_to_time_next_week($period['to_date']);
            } else {
                $from = def_to_time_prev_week($period['from_date']);
                $to = def_to_time($period['to_date']);
            }
        } else {
            $from = def_to_time($period['from_date']);
            $to = def_to_time($period['to_date']);
        }

        $getting_items = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE ordered_date <= '$to' AND ordered_date >= '$from' AND marked=0 ORDER BY id DESC");
        return $getting_items;
    } else {
        return false;
    }
}

function get_approved_period(): mysqli_result|bool
{
    $date_arr = array("Saturday" => 0, "Sunday" => 1, "Monday" => 2, "Tuesday" => 3, "Wednesday" => 4, "Thursday" => 5, "Friday" => 6);
    $week = date("l");
    $current_date = $date_arr[$week] . date("His");

    $date_plus = time() - (1 * 60 * 60);
    $date_plus = $date_arr[date("l", $date_plus)] . date("His", $date_plus);
    $get_period = mysqli_query($GLOBALS['conn'], "SELECT * FROM work_periods WHERE (from_date < to_date AND from_date <='$current_date' AND (to_date >= '$current_date' OR to_date >= '$date_plus')) OR (from_date > to_date AND (from_date <='$current_date' OR (to_date >= '$current_date' OR to_date >= '$date_plus')))");

    if (mysqli_num_rows($get_period) > 0) {
        $period = mysqli_fetch_assoc($get_period);
        if ($period['from_date'] > $period['to_date']) {
            if ($current_date >= $period['from_date']) {
                $from = def_to_time($period['from_date']);
                $to = def_to_time_next_week($period['to_date']);
            } else {
                $from = def_to_time_prev_week($period['from_date']);
                $to = def_to_time($period['to_date']);
            }
        } else {
            $from = def_to_time($period['from_date']);
            $to = def_to_time($period['to_date']);
        }

        $getting_items = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE ordered_date <= '$to' AND ordered_date >= '$from' AND marked=1 ORDER BY id DESC");
        return $getting_items;
    } else {
        return false;
    }
}

function get_canceled_period(): mysqli_result|bool
{
    $date_arr = array("Saturday" => 0, "Sunday" => 1, "Monday" => 2, "Tuesday" => 3, "Wednesday" => 4, "Thursday" => 5, "Friday" => 6);
    $week = date("l");
    $current_date = $date_arr[$week] . date("His");

    $date_plus = time() - (1 * 60 * 60);
    $date_plus = $date_arr[date("l", $date_plus)] . date("His", $date_plus);
    $get_period = mysqli_query($GLOBALS['conn'], "SELECT * FROM work_periods WHERE (from_date < to_date AND from_date <='$current_date' AND (to_date >= '$current_date' OR to_date >= '$date_plus')) OR (from_date > to_date AND (from_date <='$current_date' OR (to_date >= '$current_date' OR to_date >= '$date_plus')))");

    if (mysqli_num_rows($get_period) > 0) {
        $period = mysqli_fetch_assoc($get_period);
        if ($period['from_date'] > $period['to_date']) {
            if ($current_date >= $period['from_date']) {
                $from = def_to_time($period['from_date']);
                $to = def_to_time_next_week($period['to_date']);
            } else {
                $from = def_to_time_prev_week($period['from_date']);
                $to = def_to_time($period['to_date']);
            }
        } else {
            $from = def_to_time($period['from_date']);
            $to = def_to_time($period['to_date']);
        }

        $getting_items = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE ordered_date <= '$to' AND ordered_date >= '$from' AND marked=2 ORDER BY id DESC");
        return $getting_items;
    } else {
        return false;
    }
}

function def_to_time(string $date): string
{
    if (date("l") == "Saturday") {
        $firstday = date('Y-m-d h:i:s a', strtotime("saturday"));
    } else {
        $firstday = date('Y-m-d h:i:s a', strtotime("Saturday -1 week"));
    }

    $date_arr = array("Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday");
    $date = substr_replace($date, ":", -2, 0);
    $date = substr_replace($date, ":", -5, 0);
    $date = substr_replace($date, ":", 1, 0);
    $date = explode(":", $date);
    $calc = $date[3];
    $calc += $date[2] * 60;
    $calc += $date[1] * 60 * 60;
    $calc += $date[0] * 24 * 60 * 60;

    $time = strtotime($firstday) + $calc;

    return $time;
}
function def_to_time_next_week(string $date): string
{
    if (date("l") == "Saturday") {
        $firstday = date('Y-m-d h:i:s a', strtotime("saturday"));
    } else {
        $firstday = date('Y-m-d h:i:s a', strtotime("Saturday -1 week"));
    }

    $date = substr_replace($date, ":", -2, 0);
    $date = substr_replace($date, ":", -5, 0);
    $date = substr_replace($date, ":", 1, 0);
    $date = explode(":", $date);
    $calc = $date[3];
    $calc += $date[2] * 60;
    $calc += $date[1] * 60 * 60;
    $calc += $date[0] * 24 * 60 * 60;
    //$calc += (7 * 60 * 60);

    $time = strtotime($firstday) + $calc + (7 * 24 * 60 * 60);

    return $time;
}
function def_to_time_prev_week(string $date): string
{
    if (date("l") == "Saturday") {
        $firstday = date('Y-m-d h:i:s a', strtotime("saturday"));
    } else {
        $firstday = date('Y-m-d h:i:s a', strtotime("Saturday -1 week"));
    }

    $date = substr_replace($date, ":", -2, 0);
    $date = substr_replace($date, ":", -5, 0);
    $date = substr_replace($date, ":", 1, 0);
    $date = explode(":", $date);
    $calc = $date[3];
    $calc += $date[2] * 60;
    $calc += $date[1] * 60 * 60;
    $calc += $date[0] * 24 * 60 * 60;
    //$calc += (7 * 60 * 60);

    $time = strtotime($firstday) + $calc - (7 * 24 * 60 * 60);

    return $time;
}

function get_area_info(int $id): array
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

    $get_area = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_locations WHERE id='" . $id . "'");
    $area = mysqli_fetch_assoc($get_area);
    return $area;
}

function compressImage(string $source, string $destination, int $quality): string
{
    // Get image info 
    $imgInfo = getimagesize($source);
    $mime = $imgInfo['mime'];

    // Create a new image from file 
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            // Save image 
            imagejpeg($image, $destination, $quality);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            // Save image 
            imagepng($image, $destination, 8);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            imagegif($image, $destination);
            break;
        default:
            $image = imagecreatefromjpeg($source);
            // Save image 
            imagejpeg($image, $destination, $quality);
    }


    // Return compressed image 
    return $destination;
}

// need to be secured
function upload_image($files): string
{
    // Get the original file name
    $original_file_name = $files['name'];

    // Get file extension
    $file_extension = pathinfo($original_file_name, PATHINFO_EXTENSION);

    $filetype = array('jpeg', 'jpg', 'png');

    $name = time() . rand(100000, 999999) . "." . $file_extension;

    $size = $files['size'];

    $path = '../../uploads/' . $name;
    if (in_array(strtolower($file_extension), $filetype)) {
        if ($size < 200000) {
            move_uploaded_file($files['tmp_name'], $path);
            $upload_path = '/uploads/' . $name;
            return $upload_path;
        } else if ($size > 200000 && $size < 20000000) {
            // Compress
            compressImage($files['tmp_name'], $path, 55);
            $upload_path = '/uploads/' . $name;
            return $upload_path;
        } else {
            return "FILE_SIZE_ERROR";
        }
    } else {
        return "FILE_TYPE_ERROR";
    }
}

function check_category_exist(int $id): bool
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $get_cat = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_categories WHERE id=$id");

    return mysqli_num_rows($get_cat);
}

function get_category_info(int $id): array
{
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

    $get_cat = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_categories WHERE id=$id");

    return mysqli_fetch_assoc($get_cat);
}

function check_new_number(string $phone): bool
{
    $phone = mysqli_real_escape_string($GLOBALS['conn'], $phone);

    $get_phone_order = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE client_phone='$phone'");

    return mysqli_num_rows($get_phone_order) == 0;
}

function get_order_card(array $item): string
{
    $get_settings = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='currency'");
    $fetch = mysqli_fetch_assoc($get_settings);
    $currency = $fetch['value'];

    if ($item['marked'] == 0) {
        $mark = 'gold';
    } else if ($item['marked'] == 1) {
        $mark = 'green';
    } else {
        $mark = 'red';
    };
    $str = <<<HERE
        <div data-id="{$item['id']}" data-marked="{$item['marked']}" class="col-lg-3 col-md-4 col-sm-6 mb-2">       
            <div class="card mb-2 h-100 align-content-between" style="border-top: 10px solid {$mark}">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <p class="font-weight-bold my-0">
                            <i role="button" class="fas fa-clipboard text-dark copy-button ms-2"></i><span>#{$item['id']}</span>
                        </p>
                        <p class="font-weight-bold my-0">
                            <i role="button" class="fas fa-clipboard text-dark copy-button ms-2"></i>اسم العميل: <span class="font-weight-normal">{$item['client_name']}</span>
                        </p>
                        <p class="font-weight-bold my-0">
                            <i role="button" class="fas fa-clipboard text-dark copy-button ms-2"></i>رقم الهاتف: <span class="font-weight-normal">{$item['client_phone']}</span>
                        </p>
        HERE;


    $new = (check_new_number($item['client_phone'])) ? '<span class="badge bg-warning py-1">عميل جديد</span>' : '';
    $str .= <<<HERE
                            $new            
                        </p>
            HERE;
    $branch = "";
    if ($item['client_branch'] != "") :
        $branch = <<<HERE
            <p class="font-weight-bold my-0">
                <i role="button" class="fas fa-clipboard text-dark -button ms-2"></i>الفرع: <span class="font-weight-normal">{$item['client_branch']}</span>
            </p>
            HERE;
    endif;

    $str .= <<<HERE
        $branch
        <p class="font-weight-bold my-0">
            <i role="button" class="fas fa-clipboard text-dark copy-button ms-2"></i>المنطقة: <span class="font-weight-normal">{$item['client_area_name']}</span>
        </p>
        <p class="font-weight-bold my-0">
            <i role="button" class="fas fa-clipboard text-dark copy-button ms-2"></i>العنوان: <span class="font-weight-normal">{$item['client_address']}</span>
        </p>
    HERE;
    if ($item['client_notice'] != "") {
        $str .= <<<HERE
           <p class="font-weight-bold my-0">
                ملاحظات: <span class="font-weight-normal">{$item['client_notice']}</span>
            </p>
        HERE;
    }
    $total_order_price = get_total_order_price($item['id']);

    $str .= <<<HERE
                    </div>
                    <hr class="dark horizontal">
                    <div>
                        <p class="font-weight-bold my-0">
                            إجمال الطلب: <span class="font-weight-normal">$total_order_price $currency</span>
                        </p>
                        <p class="font-weight-bold my-0">
                            التوصيل: <span class="font-weight-normal">{$item['address_price']} $currency</span>
                        </p>
            HERE;
    $discount = "";
    if ($item['delivery_discount'] > 0 || $item['total_discount'] > 0) {
        if ($item['delivery_discount'] > 0)
            $dds =  "-" . $item['delivery_discount'] . $currency;
        else if ($item['total_discount'] > 0)
            $dds =  "-" . $item['total_discount'] . $currency;
        $discount = <<<HERE
            <p class="font-weight-bold my-0 text-danger">
                خصم: 
                <span class="font-weight-normal">
                    $dds
                </span>
            </p>
        HERE;
    }
    $str .= $discount;
    if ($item['tax'] > 0) :
        $str .= <<<HERE
            <p class="font-weight-bold my-0">
                إجمال الضريبة: <span class="font-weight-normal">{$item['tax']} $currency</span>
            </p>
        HERE;
    endif;

    $final_price = get_total_order_price($item['id']) + $item['tax'] + $item['address_price'] - $item['delivery_discount'] - $item['total_discount'];

    $str .= <<<HERE
            <p class="font-weight-bold my-0">
                صافي الطلب: <span class="font-weight-normal">$final_price $currency</span>
            </p>
    HERE;

    if ($item['method'] == 1) :
        $str .= <<<HERE
                <p class="font-weight-bold my-0">
                    تم الدفع بإستخدام: <span class="font-weight-normal">visa/matercard <i class="fab fa-cc-visa text-info"></i></span>
                </p>
        HERE;
    endif;
    $order_date = date("Y-m-d h:i:s a", $item['ordered_date']);
    $str .= <<<HERE
                <hr class="dark horizontal">
                <p class="font-weight-bold my-0">
                    تاريخ الطلب: <span class="font-weight-normal"><bdi>$order_date</bdi></span>
                </p>
        HERE;

    if ($item['accepted_by']) :
        $acceptor = get_user_info($item['accepted_by']);
        $acceptor_nick_name = $acceptor['nickname'] . ($acceptor['id'] == get_admin_info()['id'] ? " (أنا)" : "");
        $acceptor_color = $acceptor['id'] == get_admin_info()['id'] ? "text-success" : "";
        $marked_accepted_date = date("Y-m-d h:i:s a", $item['marked_date']);
        $str .= <<<HERE
            <hr class="dark horizontal">
            <p class="font-weight-bold my-0">
                تم القبول من: <span class="font-weight-normal {$acceptor_color}"><bdi>$acceptor_nick_name</bdi></span>
            </p>
            <p class="font-weight-bold my-0">
                تاريخ القبول: <span class="font-weight-normal"><bdi>$marked_accepted_date</bdi></span>
            </p>
        HERE;
    endif;
    if ($item['canceled_by']) :
        $canceler = get_user_info($item['accepted_by']);
        $canceler_nick_name = $canceler['nickname'] . ($canceler['id'] == get_admin_info()['id'] ? " (أنا)" : "");
        $canceler_color = $canceler['id'] == get_admin_info()['id'] ? "text-danger" : "";
        $canceler_get_date = date("Y-m-d h:i:s a", $item['canceled_date']);
        $str .= <<<HERE
            <hr class="dark horizontal">
            <p class="font-weight-bold my-0">
                تم الالغاء من: <span class="font-weight-normal {$canceler_color}"><bdi>$canceler_nick_name</bdi></span>
            </p>
            <p class="font-weight-bold my-0">
                تاريخ الالغاء: <span class="font-weight-normal"><bdi>$canceler_get_date</bdi></span>
            </p>
            <p class="font-weight-bold my-0">
                سبب الالغاء: <span class="font-weight-normal"><bdi>{$item['cancel_reason']}</bdi></span>
            </p>
        HERE;
    endif;

    if ($item['marked'] == 0) {
        $marked_badge = '<div class="badge badge-sm bg-gradient-warning m-auto d-block" style="width: fit-content;">النتظار القبول</div>';
    } else if ($item['marked'] == 1) {
        $marked_badge = '<div class="badge badge-sm bg-gradient-success m-auto d-block" style="width: fit-content;">تم القبول</div>';
    } else {
        $marked_badge = '<div class="badge badge-sm bg-gradient-danger m-auto d-block" style="width: fit-content;">تم الالغاء</div>';
    }

    $marked_button = "";
    if ($item['marked'] == 0 && check_user_perm(['live-orders-action'])) {
        $marked_button = '<button class="btn btn-success mx-1 spinner-button-loading" onclick="approve_order(' . $item["id"] . ', this)"><span class="content-button-loading">قبول</span><div class="lds-dual-ring"></div></button>';
    } else if ($item['marked'] == 1) {
        if (check_user_perm(['live-orders-action'])) :
            $marked_button = '<button class="btn btn-danger mx-1" onclick="cancel_button(' . $item["id"] . ', this)">الغاء</button>';
        endif;
        if (check_user_perm(['live-orders-view'])) :
            $marked_button .= '<a href="show-order.php?id=' . $item['id'] . '" target="_blank"><button class="btn btn-info mx-1">عرض</button></a>';
        endif;
    } else {
        if (check_user_perm(['live-orders-view'])) :
            $marked_button = '<a href="show-order.php?id=' . $item['id'] . '" target="_blank"><button class="btn btn-info mx-1">عرض</button></a>';
        endif;
    }

    $str .= <<<HERE
                    </div>
                    <hr class="dark horizontal">
                    <div>
                        $marked_badge
                        <div class="actions mt-2 mb-0 text-center">
                        $marked_button
                        </div>
                    </div>
                </div>
            </div>
        </div>
    HERE;

    return $str;
}


function check_unresponsed_order_period(): bool
{
    $date_arr = array("Saturday" => 0, "Sunday" => 1, "Monday" => 2, "Tuesday" => 3, "Wednesday" => 4, "Thursday" => 5, "Friday" => 6);
    $week = date("l");
    $current_date = $date_arr[$week] . date("His");

    $date_plus = time() - (1 * 60 * 60);
    $date_plus = $date_arr[date("l", $date_plus)] . date("His", $date_plus);
    $get_period = mysqli_query($GLOBALS['conn'], "SELECT * FROM work_periods WHERE (from_date < to_date AND from_date <='$current_date' AND (to_date >= '$current_date' OR to_date >= '$date_plus')) OR (from_date > to_date AND (from_date <='$current_date' OR (to_date >= '$current_date' OR to_date >= '$date_plus')))");

    if (mysqli_num_rows($get_period) > 0) {
        $period = mysqli_fetch_assoc($get_period);
        if ($period['from_date'] > $period['to_date']) {
            if ($current_date >= $period['from_date']) {
                $from = def_to_time($period['from_date']);
                $to = def_to_time_next_week($period['to_date']);
            } else {
                $from = def_to_time_prev_week($period['from_date']);
                $to = def_to_time($period['to_date']);
            }
        } else {
            $from = def_to_time($period['from_date']);
            $to = def_to_time($period['to_date']);
        }

        $getting_items = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE ordered_date <= '$to' AND ordered_date >= '$from' AND marked=0 ORDER BY id DESC");
        return (mysqli_num_rows($getting_items) > 0) ? true : false;
    } else {
        return false;
    }
}

function logg($page, $log)
{
    $admin = get_admin_info()['id'];

    $page = mysqli_real_escape_string($GLOBALS['conn'], $page);
    $log = mysqli_real_escape_string($GLOBALS['conn'], $log);

    $ip = $_SERVER['REMOTE_ADDR'];

    $date = date("Y-m-d H:i:s");

    mysqli_query($GLOBALS['conn'], "INSERT INTO `logging`(`page`, `admin`, `log`, `date`, `ip`) VALUES ('$page', $admin, '$log', '$date', '$ip')");
}

function get_role_permission(int $role_id, string $permission_name): bool
{
    $check_role_exist = mysqli_query($GLOBALS['conn'], "SELECT * FROM roles WHERE id=$role_id");

    if (mysqli_num_rows($check_role_exist) == 0) {
        die("Role with id: $role_id doesn't exist");
    }

    $check_permission_exist = mysqli_query($GLOBALS['conn'], "SELECT * FROM permissions WHERE role_id=$role_id AND permission_key='$permission_name'");
    if (mysqli_num_rows($check_permission_exist) > 0) {
        $permission = mysqli_fetch_assoc($check_permission_exist);
        return $permission['permission_value'];
    } else {
        mysqli_query($GLOBALS['conn'], "INSERT INTO permissions(role_id,permission_key,permission_value) VALUES ($role_id, '$permission_name', 0)");
        return 0;
    }
}

function check_user_perm(array $permissions_key): bool
{
    $admin = get_admin_info();

    $role_id = $admin['role_id'];

    foreach ($permissions_key as $permission_key) {
        get_role_permission($role_id, $permission_key);
        $get_permission = mysqli_query($GLOBALS['conn'], "SELECT * FROM permissions WHERE role_id=$role_id AND permission_key='$permission_key'");
        if ((bool) mysqli_fetch_assoc($get_permission)['permission_value']) {
            return true;
        }
    }

    return false;
}

function get_system_permissions(): array
{
    return [
        'general-settings-view',
        'general-settings-view-visa',
        'general-settings-edit',
        'general-settings-edit-visa',
        'main-page-cover-view',
        'main-page-cover-add',
        'main-page-cover-edit',
        'main-page-cover-remove',
        'colors-settings-view',
        'colors-settings-edit',
        'working-period-view',
        'working-period-add',
        'working-period-edit',
        'working-period-remove',
        'branches-view',
        'branches-add',
        'branches-edit',
        'branches-remove',
        'locations-view',
        'locations-add',
        'locations-edit',
        'locations-remove',
        'social-page-view',
        'social-page-add',
        'social-page-edit',
        'social-page-remove',
        'rating-page-view',
        'rating-page-remove',
        'order-page-view',
        'order-page-add',
        'order-page-remove',
        'offers-page-cover-view',
        'offers-page-cover-add',
        'offers-page-cover-edit',
        'offers-page-cover-remove',
        'offers-page-view',
        'offers-page-add',
        'offers-page-edit',
        'offers-page-remove',
        'safwa-card-cover-view',
        'safwa-card-cover-add',
        'safwa-card-cover-edit',
        'safwa-card-cover-remove',
        'safwa-card-view',
        'safwa-card-add',
        'safwa-card-edit',
        'safwa-card-remove',
        'manual-menu-view',
        'manual-menu-add',
        'manual-menu-edit',
        'manual-menu-remove',
        'categories-view',
        'categories-add',
        'categories-edit',
        'categories-remove',
        'items-view',
        'items-add',
        'items-edit',
        'items-remove',
        'discounts-view',
        'discounts-view-orders',
        'discounts-view-customers',
        'discounts-add',
        'discounts-edit',
        'discounts-remove',
        'live-orders-view',
        'live-orders-action',
        'orders-data-view',
        'orders-data-remove',
        'accounts-view',
        'accounts-add',
        'accounts-edit',
        'accounts-remove',
        'roles-view',
        'roles-add',
        'roles-edit',
        'roles-remove',
        'log-view',
        'reports-view',
        'qrcode-view',
        'main-page-icon-view',
        'main-page-icon-edit',
        'switch-items'
    ];
}
