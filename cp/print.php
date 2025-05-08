<!--
=========================================================
* Material Dashboard 2 - v3.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2023 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <?php include 'temps/head.php'; ?>
    <?php if (!is_logged()) header('Location: login.php'); ?>
    <title>
        Powered by diafh
    </title>
    <style>
            body {
                width: 80mm;
            }
            .receipt {
                width: 80mm;
                font-size: 12px; /* Adjust as needed */
            }
        tbody th {
            border-left: 1px solid #c9c9c9;
        }
        tr, td, th {
            width: 80mm;
            white-space: normal !important;
        }
    </style>
</head>
<?php
if (!check_user_perm(['live-orders-view']) && !check_user_perm(['orders-data-view'])) :
    header('Location: 403.php');
    exit;
endif;
?>
<?php
$id = 0;
if (isset($_GET['id'])) {
    $id = filter_Var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
} else {
    die();
}

$get_settings = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='currency'");
$fetch = mysqli_fetch_assoc($get_settings);
$currency = $fetch['value'];

$get_order = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE id='" . $id . "'");
$item = mysqli_fetch_assoc($get_order);
$get_cart_info = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_order_cart WHERE order_id='$id'");
?>

<body class="g-sidenav-show rtl bg-gray-200">
    <img class="mx-auto d-block" src="../<?php echo $site_setting['site-logo']; ?>" alt="<?php echo $site_setting['site-title']; ?>" width="70px">
    <h4 class="text-center mt-2">رقم الطلب: #<?php echo $id; ?></h4>
    <table class="table table-striped receipt">
        <thead>
            <tr class="text-center table-dark" style="border-color: #000; border-width: 2px 0">
                <th colspan="100%">بيانات العميل</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>اسم العميل</th>
                <td><?php echo $item['client_name']; ?></td>
            </tr>
            <tr>
                <th>رقم الهاتف</th>
                <td><?php echo $item['client_phone']; ?></td>
            </tr>
            <tr>
                <th>منطقة التوصيل</th>
                <td><?php echo $item['client_area_name']; ?></td>
            </tr>
            <tr>
                <th>عنوان التوصيل</th>
                <td><?php echo $item['client_address']; ?></td>
            </tr>
            <tr>
                <th>تاريخ الطلب</th>
                <td><?php echo date("Y-m-d h:i:s a", $item['ordered_date']); ?></td>
            </tr>
            <?php if ($item['method'] == 1) : ?>
                <tr>
                    <th>طريقة الدفع:</th>
                    <td>visa/matercard</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <thead>
            <tr class="text-center" style="border-color: #000; border-width: 2px 0">
                <th colspan="100%" class="table-dark">تفاصيل الطلب</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($cart = mysqli_fetch_assoc($get_cart_info)) { ?>
                <tr>
                    <th><?php echo "<bdi style='direction:ltr;'>"  . $cart['item_count'] . " X </bdi>" . $cart['item_name']; ?></td>
                    <?php if($cart['item_price'] > 0): ?>
                        <td><?php echo $cart['item_price'] . " " . $currency; ?></td>
                    <?php endif; ?>
                </tr>
                <?php if ($cart['item_size'] != 0): ?>
                    <tr>
                        <td class="ps-5">Size : <?php echo $cart['item_size_name']; ?></td>
                    </tr>
                <?php endif; ?>
                <?php
                    $get_options = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_order_options WHERE order_card_id='" . $cart['id'] . "'");
                    while ($option = mysqli_fetch_assoc($get_options)):
                ?>
                    <tr>
                        <td class="ps-5" <?php echo ($option['option_price'] == 0) ? 'colspan="2"': ''; ?>><?php echo $option['option_name'] ?> : <?php echo $option['option_value']; ?></td>
                        <?php if($option['option_price'] > 0): ?>
                            <td class="ps-5"><?php echo "[+" . $option['option_price'] . " " . $currency . "]"; ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            <?php } ?>
        </tbody>
        <?php if ($item['client_notice'] != ""): ?>
            <thead>
                <tr class="text-center table-dark" style="border-color: #000; border-width: 2px 0">
                    <th colspan="100%">ملاحظات على الطلب</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td colspan="100%"><?php echo $item['client_notice']; ?></td>
                </tr>
            </tbody>
        <?php endif; ?>
        <thead>
            <tr class="text-center table-dark" style="border-color: #000; border-width: 2px 0">
                <th colspan="100%">تفاصيل الدفع</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>اجمالي الطلب</th>
                <td><?php echo get_total_order_price($item['id']) . $currency; ?></td>
            </tr>
            <tr>
                <th>التوصيل</th>
                <td><?php echo $item['address_price'] . $currency; ?></td>
            </tr>
            <?php if ($item['delivery_discount'] > 0 || $item['total_discount'] > 0): ?>
                <tr>
                    <th>الخصم</th>
                    <td>
                        <?php
                            if ($item['delivery_discount'] > 0)
                                echo "-" . $item['delivery_discount'] . $currency;
                            else if ($item['total_discount'] > 0)
                                echo "-" . $item['total_discount'] . $currency;
                        ?>
                    </td>
                </tr>
            <?php endif; ?>
            <?php if ($item['tax'] > 0) : ?>
                <tr>
                    <th>الضريبة</th>
                    <td><?php echo $item['tax'] . $currency; ?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <th>المطلوب سداده</th>
                <td><?php echo get_total_order_price($item['id']) + $item['tax'] + $item['address_price'] - $item['delivery_discount'] - $item['total_discount'] . $currency; ?></td>
            </tr>
        </tbody>
    </table>
    <?php include 'temps/jslibs.php'; ?>
    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() {
                // window.close();
            }, 1000); // Adjust the timeout duration as needed
        };
    </script>
</body>

</html>