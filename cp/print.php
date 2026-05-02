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
                width: 58mm;
            }
            .receipt {
                width: 58mm;
                font-size: 12px; /* Adjust as needed */
            }
        tbody th {
            border-left: 1px solid #c9c9c9;
        }
        tr, td, th {
            width: 58mm;
            white-space: normal !important;
        }

        @media print {
            @page {
                padding-top: 20px;
                margin: 0; /* Remove default browser print margins */
            }

            body {
                margin: 0;
                padding: 0;
            }

            /* Optional: Adjust elements to fit exactly the page */
            * {
                box-sizing: border-box;
            }
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

$get_settings = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='allow-item-notes'");
$fetch = mysqli_fetch_assoc($get_settings);
$allow_item_notes = $fetch['value'];

$get_order = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE id='" . $id . "'");
$order = mysqli_fetch_assoc($get_order);
$get_cart_info = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_order_cart WHERE order_id='$id'");
?>

<body class="g-sidenav-show rtl bg-gray-200">
    <img class="mx-auto d-block" src="../<?php echo $site_setting['site-logo']; ?>" alt="<?php echo $site_setting['site-title']; ?>" width="70px">
    <h4 class="text-center mt-2">رقم الطلب: #<?php echo $id; ?></h4>
    <?php if($order['order_type'] == "delivery"): ?>
        <h5 class="text-center mt-2" style="font-size: 16px;">توصيل الى المنزل</h5>
    <?php else: ?>
        <p class="text-center mt-2">استلام من الفرع</p>
    <?php endif; ?>
    <table class="table receipt fw-bold border mb-0">
        <thead>
            <tr class="text-center" style="border-color: #000; border-width: 2px 0">
                <th colspan="100%">بيانات العميل</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border">
                <th>اسم العميل</th>
                <td><?php echo $order['client_name']; ?></td>
            </tr>
            <tr class="border">
                <th>رقم الهاتف</th>
                <td><?php echo $order['client_phone']; ?></td>
            </tr>
            <?php if ($order['order_type'] == "delivery") : ?>
                <tr class="border">
                    <th>منطقة التوصيل</th>
                    <td><?php echo $order['client_area_name']; ?></td>
                </tr>
                <tr class="border">
                    <th>عنوان التوصيل</th>
                    <td><?php echo $order['client_address']; ?></td>
                </tr>
            <?php endif; ?>
            <tr class="border">
                <th>تاريخ الطلب</th>
                <td><bdi><?php echo date("Y-m-d h:i:s a", $order['ordered_date']); ?></bdi></td>
            </tr>
            <?php if ($order['method'] == 1) : ?>
                <tr class="border">
                    <th>طريقة الدفع:</th>
                    <td>visa/matercard</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <table>
        <thead>
            <tr class="text-center" style="border-color: #000; border-width: 2px 0">
                <th colspan="100%">تفاصيل الطلب</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($cart = mysqli_fetch_assoc($get_cart_info)) { ?>
                <tr class="border">
                    <td colspan="2"><?php echo "<bdi style='direction:ltr;'>"  . $cart['item_count'] . "</bdi> X " . $cart['item_name']; ?></td>
                </tr>
                <?php if ($cart['item_size'] != 0): ?>
                    <tr class="border">
                        <td class="pe-3" colspan="2">Size : <?php echo $cart['item_size_name']; ?></td>
                    </tr>
                <?php endif; ?>
                <?php if($cart['item_price'] > 0): ?>
                    <tr>
                        <td colspan="2" class="ps-1" style="text-align: end"><?php echo $cart['item_price'] . " " . $currency; ?></td>
                    </tr>
                <?php endif; ?>
                <?php
                    $get_options = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_order_options WHERE order_card_id='" . $cart['id'] . "'");
                    while ($option = mysqli_fetch_assoc($get_options)):
                ?>
                    <tr class="border">
                        <td class="pe-3"><?php echo $option['option_name'] ?> : <?php echo $option['option_value']; ?></td>
                    </tr>
                    <?php if($option['option_price'] > 0): ?>
                        <tr class="border">
                            <td colspan="2" class="ps-1" style="text-align:end;"><?php echo "[+" . $option['option_price'] . " " . $currency . "]"; ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endwhile; ?>
                <?php if (!empty(trim($cart['notes'])) && $allow_item_notes == 1): ?>
                    <tr class="border">
                        <td class="pe-3" colspan="2" style="font-style: italic; color: #666;">ملاحظات: <?php echo htmlspecialchars($cart['notes']); ?></td>
                    </tr>
                <?php endif; ?>
            <?php } ?>
        </tbody>
        <?php if ($order['client_notice'] != ""): ?>
            <thead>
                <tr class="text-center" style="border-color: #000; border-width: 2px 0">
                    <th colspan="100%">ملاحظات على الطلب</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td colspan="100%"><?php echo $order['client_notice']; ?></td>
                </tr>
            </tbody>
        <?php endif; ?>
        <thead>
            <tr class="text-center" style="border-color: #000; border-width: 2px 0">
                <th colspan="100%">تفاصيل الدفع</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border">
                <th>اجمالي الطلب</th>
                <td class="text-center"><?php echo get_total_order_price($order['id']) . $currency; ?></td>
            </tr>
            <?php if($order['order_type'] == "delivery"): ?>
                <tr class="border">
                    <th>التوصيل</th>
                    <td class="text-center"><?php echo $order['address_price'] . $currency; ?></td>
                </tr>
            <?php endif; ?>
            <?php if ($order['delivery_discount'] > 0 || $order['total_discount'] > 0): ?>
                <tr class="border">
                    <th>الخصم</th>
                    <td class="text-center">
                        <?php
                            if ($order['delivery_discount'] > 0)
                                echo "-" . $order['delivery_discount'] . $currency;
                            else if ($order['total_discount'] > 0)
                                echo "-" . $order['total_discount'] . $currency;
                        ?>
                    </td>
                </tr>
            <?php endif; ?>
            <?php if ($order['tax'] > 0) : ?>
                <tr class="border">
                    <th>الضريبة</th>
                    <td class="text-center"><?php echo $order['tax'] . $currency; ?></td>
                </tr>
            <?php endif; ?>
            <?php if ($order['order_type'] == "delivery" || $order['delivery_discount'] > 0 || $order['total_discount'] > 0 || $order['tax'] > 0) : ?>
                <tr class="border">
                    <th>المطلوب سداده</th>
                    <td class="text-center"><?php echo get_total_order_price($order['id']) + $order['tax'] + $order['address_price'] - $order['delivery_discount'] - $order['total_discount'] . $currency; ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php include 'temps/jslibs.php'; ?>
</body>

</html>