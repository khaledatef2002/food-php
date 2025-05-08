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
?>

<body class="g-sidenav-show rtl bg-gray-200">
    <?php include 'temps/sidebar.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
        <?php include 'temps/navbar.php'; ?>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div id="parent" class="row my-4 justify-content-center">
                <?php
                $get_order = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE id='" . $id . "'");
                $item = mysqli_fetch_assoc($get_order);
                $get_cart_info = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_order_cart WHERE order_id='$id'");
                ?>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                    <div class="card mb-2 align-content-between">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-3 pb-2">
                                <h6 class="text-white text-capitalize ps-3 text-center">تفاصيل عامه</h6>
                            </div>
                        </div>
                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            <div>
                                <?php
                                if ($item['marked'] == 0) {
                                    echo '<div class="badge badge-sm bg-gradient-warning m-auto d-block" style="width: fit-content;">النتظار القبول</div>';
                                } else if ($item['marked'] == 1) {
                                    echo '<div class="badge badge-sm bg-gradient-success m-auto d-block" style="width: fit-content;">تم القبول</div>';
                                } else {
                                    echo '<div class="badge badge-sm bg-gradient-danger m-auto d-block" style="width: fit-content;">تم الالغاء</div>';
                                }
                                ?>
                                <p class="text-center text-dark fw-bold mt-2">
                                    <i role="button" class="fas fa-clipboard text-dark copy-button ms-2"></i>رقم الطلب <span><?php echo "#" . $item['id']; ?></span>
                                </p>
                                <p class="font-weight-bold my-0 text-dark">
                                    بيانات العميل
                                </p>
                                <p class="font-weight-bold my-0">
                                    </span><i role="button" class="fas fa-clipboard text-dark copy-button ms-2"></i> اسم العميل: <span class="font-weight-normal"><?php echo $item['client_name']; ?></span>
                                </p>
                                <p class="font-weight-bold my-0">
                                    </span><i role="button" class="fas fa-clipboard text-dark copy-button ms-2"></i> رقم الهاتف: <span class="font-weight-normal"><?php echo $item['client_phone']; ?></span> <?php echo (check_new_number($item['client_phone'])) ? '<span class="badge bg-warning py-1">عميل جديد</span>' : ''; ?>
                                </p>
                                <p class="font-weight-bold my-0">
                                    </span><i role="button" class="fas fa-clipboard text-dark copy-button ms-2"></i> المنطقة: <span class="font-weight-normal"><?php echo $item['client_area_name']; ?></span>
                                </p>
                                <p class="font-weight-bold my-0">
                                    </span><i role="button" class="fas fa-clipboard text-dark copy-button ms-2"></i> العنوان: <span class="font-weight-normal"><?php echo $item['client_address']; ?>
                                </p>
                                <p class="font-weight-bold my-0">
                                    تاريخ الطلب: <span class="font-weight-normal"><bdi><?php echo date("Y-m-d h:i:s a", $item['ordered_date']); ?></bdi></span>
                                </p>
                            </div>
                            <?php if ($item['accepted_by']) : ?>
                                <hr class="dark horizontal">
                                <div class="d-flex justify-content-between">
                                    <p class="font-weight-bold my-0">
                                        تم القبول من: <span class="font-weight-normal"><bdi><?php echo get_user_info($item['accepted_by'])['nickname']; ?></bdi></span>
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="font-weight-bold my-0">
                                        تاريخ القبول: <span class="font-weight-normal"><bdi><?php echo date("Y-m-d h:i:s a", $item['marked_date']); ?></bdi></span>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <?php if ($item['canceled_by']) : ?>
                                <hr class="dark horizontal">
                                <p class="font-weight-bold my-0">
                                    تم الالغاء من: <span class="font-weight-normal"><bdi><?php echo get_user_info($item['canceled_by'])['nickname']; ?></bdi></span>
                                </p>
                                <p class="font-weight-bold my-0">
                                    تاريخ الالغاء: <span class="font-weight-normal"><bdi><?php echo date("Y-m-d h:i:s a", $item['canceled_date']); ?></bdi></span>
                                </p>
                                <p class="font-weight-bold my-0">
                                    سبب الالغاء: <span class="font-weight-normal"><bdi><?php echo $item['cancel_reason']; ?></bdi></span>
                                </p>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                    <div class="card mb-2 align-content-between">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-3 pb-2">
                                <h6 class="text-white text-capitalize ps-3 text-center">تفاصيل الطلب</h6>
                            </div>
                        </div>
                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            <div>
                                <p class="font-weight-bold my-0 text-dark">
                                    تفاصيل الطلب
                                </p>
                                <?php while ($cart = mysqli_fetch_assoc($get_cart_info)) { ?>
                                    <!-- item name -->
                                    <div class="d-flex justify-content-between">
                                        <label style="text-align:right;">
                                            <?php
                                            echo "<bdi style='direction:rtl;'>"  . $cart['item_count'] . " X </bdi>" . $cart['item_name'];
                                            ?>
                                        </label>
                                        <label><?php echo $cart['item_price'] . " " . $currency; ?></label>
                                    </div>
                                    <!-- For sizes -->
                                    <?php
                                    if ($cart['item_size'] != 0) {
                                    ?>
                                        <div>
                                            <label style="text-align:right;">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;الحجم: <?php echo $cart['item_size_name']; ?>
                                            </label>
                                        </div>
                                    <?php } ?>

                                    <!-- for options -->
                                    <?php
                                    $get_options = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_order_options WHERE order_card_id='" . $cart['id'] . "'");
                                    while ($option = mysqli_fetch_assoc($get_options)) {
                                    ?>
                                        <div class="d-flex justify-content-between">
                                            <label style="text-align:right;">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $option['option_name'] ?> : <?php echo $option['option_value']; ?>
                                            </label>
                                            <label>
                                                <?php
                                                echo ($option['option_price'] > 0) ? "[+" . $option['option_price'] . " " . $currency . "]" : '';
                                                ?>
                                            </label>
                                        </div>
                                    <?php } ?>
                                <?php } ?>

                                <!-- For Notice -->
                                <?php if ($item['client_notice'] != "") { ?>
                                    <p class="font-weight-bold my-0">
                                        ملاحظات: <span class="font-weight-normal"><?php echo $item['client_notice']; ?></span>
                                    </p>
                                <?php } ?>
                            </div>
                            <hr class="dark horizontal">
                            <div>
                                <p class="font-weight-bold my-0">
                                    إجمال الطلب: <span class="font-weight-normal"><?php echo get_total_order_price($item['id']) . $currency; ?></span>
                                </p>
                                <p class="font-weight-bold my-0">
                                    التوصيل: <span class="font-weight-normal"><?php echo $item['address_price'] . $currency; ?></span>
                                </p>
                                <?php if ($item['delivery_discount'] > 0 || $item['total_discount'] > 0) { ?>
                                    <div class="d-flex justify-content-between text-danger">
                                        <p class="font-weight-bold my-0">
                                            خصم:
                                            <span class="font-weight-normal">
                                                <?php
                                                if ($item['delivery_discount'] > 0)
                                                    echo "-" . $item['delivery_discount'] . $currency;
                                                else if ($item['total_discount'] > 0)
                                                    echo "-" . $item['total_discount'] . $currency;
                                                ?>
                                            </span>
                                        </p>
                                        <p class="my-0">
                                            (<?php echo $item['discount_name']; ?>)
                                        </p>
                                    </div>
                                <?php } ?>
                                <?php if ($item['tax'] > 0) : ?>
                                    <p class="font-weight-bold my-0">
                                        إجمال الضريبة: <span class="font-weight-normal"><?php echo $item['tax'] . $currency; ?></span>
                                    </p>
                                <?php endif; ?>
                                <p class="font-weight-bold my-0">
                                    صافي الطلب: <span class="font-weight-normal"><?php echo get_total_order_price($item['id']) + $item['tax'] + $item['address_price'] - $item['delivery_discount'] - $item['total_discount'] . $currency; ?></span>
                                </p>
                                <?php if ($item['method'] == 1) : ?>
                                    <p class="font-weight-bold my-0">
                                        تم الدفع بإستخدام: <span class="font-weight-normal">visa/matercard <i class="fab fa-cc-visa text-info"></i></span>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-center">
                    <div class="card col-lg-6 col-12">
                        <div class="card-body py-1">
                            <div class="d-flex justify-content-center align-items-center">
                                <button class="bg-gradient-primary btn mb-0 py-2 shadow-none" onclick="print_rec(<?php echo $id; ?>);">طــبــاعــة</button>
                                <a href="live-order.php"><button class="btn btn-default text-decoration-underline mb-0">الــعــودة</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include 'temps/footer.php'; ?>
        </div>
    </main>

    <?php include 'temps/jslibs.php'; ?>
    <script>
        <?php
        $last_notify = mysqli_query($GLOBALS['conn'], "SELECT * FROM live_notify WHERE page ='order' ORDER BY ID DESC");
        $last_notify = mysqli_fetch_assoc($last_notify);
        ?>

        var audio = new Audio('notification_sound.wav');
        audio.loop = true;

        let last_notify = "<?php echo $last_notify['id'] ?? '0'; ?>";

        if (!window.Notification) {
            console.log('Browser does not support notifications.');
        } else {
            // check if permission is already granted
            if (Notification.permission !== 'granted') {
                // request permission from user
                Notification.requestPermission().then(function(p) {
                    if (p !== 'granted') {
                        console.log('User blocked notifications.');
                    }
                }).catch(function(err) {
                    console.error(err);
                });
            }
        }


        setInterval(() => {
            $.post("ajax/check_unmarked.php", function(res) {
                console.log(res)
                if (res == "true") {
                    if (audio.paused) {
                        audio.play().catch(function(error) {
                            console.log("error with sound")
                        })
                    }
                } else
                if (!audio.paused) {
                    audio.pause()
                } {

                }
            })
        }, 500);


        live()

        function live() {
            setTimeout(() => {
                $.post("ajax/live-notify.php", {
                    last: last_notify,
                    page: 'order'
                }, function(result) {
                    console.log(result)
                    if (result != "empty") {
                        var data = JSON.parse(result)
                        data.forEach(function(val, index) {
                            if (val.type == "add") {
                                var notify = new Notification(
                                    `لقد تم اضافة طلب جديد!`, {
                                        body: `رقم الاوردر #${val.order_id}`,
                                        icon: '../<?php echo $site_setting['site-logo']; ?>'
                                    }
                                );
                                notify.onclick = function() {
                                    window.open('live-order.php');
                                    window.focus();
                                    notification.close();
                                };
                                notify.addEventListener("error", e => {
                                    alert("فشل ارسال اشعار بالطلب الجديد، يرجى التحقق من اعطاء النظام جميع الصلاحيات الازمة!")
                                })
                            }
                            last_notify = val.id
                        })
                    }

                    live()
                }).fail(function(xhr, status, error) {
                    live()
                })
            }, 500);
        }
        var isPlaying = function() {
            return audio.currentAudio &&
                audio.currentAudio.currentTime > 0 &&
                !audio.currentAudio.paused &&
                !audio.currentAudio.ended &&
                audio.currentAudio.readyState > 2;
        }
        $(".copy-button").click(function() {
            var vl = $(this).parent().find("span:eq(0)").text().trim()
            navigator.clipboard.writeText(vl)
        })
    </script>
</body>

</html>