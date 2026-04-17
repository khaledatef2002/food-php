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
    <?php
    if (!check_user_perm(['live-orders-view'])) :
        header('Location: 403.php');
        exit;
    endif;
    ?>
    <title>
        Deiafh - <?php echo $site_setting['site-title']; ?>
    </title>
</head>

<body class="g-sidenav-show rtl bg-gray-200">
    <?php include 'temps/sidebar.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
        <?php include 'temps/navbar.php'; ?>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <?php
            $period = get_period();
            $waiting_period = get_waiting_period();
            $approved_period = get_approved_period();
            $canceled_period = get_canceled_period();
            if (!$period)
                exit('<div class="col-lg-12 col-md-12 mb-md-0 mb-4 d-flex justify-content-center"><div class="card text-center col-sm-6 col-12 bg-gradient bg-danger"><h3 class="my-0 font-weight-bold py-2 text-white">المطعم مغلق حالياً</h3></div></div>');
            ?>
            <div class="row mt-0 pt-2 d-flex justify-content-center bg-gradient-dark" style="width:fit-content;margin-right:auto;margin-left:auto;border-radius:7px;">
                <div class="form-check" style="width:fit-content;">
                    <input class="form-check-input" type="radio" name="filter_live_order" value="0" id="flexRadioDefault1" style="cursor:pointer;" CHECKED>
                    <label class="form-check-label text-white text-bold" for="flexRadioDefault1">
                        الجميع (<span id="total_num"><?php echo mysqli_num_rows($period) ?></span>)
                    </label>
                </div>
                <div class="form-check" style="width:fit-content;">
                    <input class="form-check-input" type="radio" name="filter_live_order" value="1" id="flexRadioDefault2" style="cursor:pointer;">
                    <label class="form-check-label text-white text-bold" for="flexRadioDefault2">
                        انتظار القبول (<span id="waiting_num"><?php echo mysqli_num_rows($waiting_period) ?></span>)
                    </label>
                </div>
                <div class="form-check" style="width:fit-content;">
                    <input class="form-check-input" type="radio" name="filter_live_order" value="2" id="flexRadioDefault3" style="cursor:pointer;">
                    <label class="form-check-label text-white text-bold" for="flexRadioDefault3">
                        مقبول (<span id="approved_num"><?php echo mysqli_num_rows($approved_period) ?></span>)
                    </label>
                </div>
                <div class="form-check" style="width:fit-content;">
                    <input class="form-check-input" type="radio" name="filter_live_order" value="3" id="flexRadioDefault4" style="cursor:pointer;">
                    <label class="form-check-label text-white text-bold" for="flexRadioDefault4">
                        ملغي (<span id="canceled_num"><?php echo mysqli_num_rows($canceled_period) ?></span>)
                    </label>
                </div>
            </div>
            <div id="parent" class="row my-4 justify-content-center">
                <?php
                while ($item = mysqli_fetch_assoc($period)) {
                    echo get_order_card($item);
                }
                ?>

            </div>
            <?php include 'temps/footer.php'; ?>
        </div>
    </main>

    <!-- Start cancel reason -->
    <!-- Modal -->
    <div class="modal fade" id="cancel_modal" data-order-id="" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">سبب الالغاء</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select class="form-select border">
                        <option value="الغاء من قبل العميل">الغاء من قبل العميل</option>
                        <option value="اوردر متكرر">اوردر متكرر</option>
                        <option value="الغاء للتعديل">الغاء للتعديل</option>
                        <option value="الغاء لعدم توفر المنتج">الغاء لعدم توفر المنتج</option>
                        <option value="الغاء للتبديل بآخر">الغاء للتبديل بآخر</option>
                        <option value="عنوان خاطئ او خارج نطاق التوصيل">عنوان خاطئ او خارج نطاق التوصيل</option>
                        <option value="الغاء للتأخير">الغاء للتأخير</option>
                        <option value="المطعم غير متاح">المطعم غير متاح</option>
                        <option value="مشكلة فى الوصول للعميل">مشكلة فى الوصول للعميل</option>
                        <option value="انقطاع الانترنت">انقطاع الانترنت</option>
                        <option value="عطل فى السيستم">عطل فى السيستم</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button id="submit_cancel" type="button" class="btn btn-primary spinner-button-loading"><span class="content-button-loading">تأكيد</span>
                        <div class="lds-dual-ring"></div>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End cancel reason -->


    <?php include 'temps/jslibs.php'; ?>
    <script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
    <script>
        var audio = new Audio('notification_sound.mp3');
        audio.loop = true;

        const worker = new Worker("js/worker.js");
        worker.postMessage({
            type: "INIT",
            payload: {
                url: "<?php echo $GLOBALS['websocket_base_url']; ?>",
                token: "<?php echo generateJWT(["channel" => $GLOBALS['channel'], "iat" => time(), "exp" => time() + 3600]); ?>"
            }
        });

        worker.onmessage = function(event) {
            console.log(event)
            const { type, data } = event.data;
            switch (type) {
                case "order:new":
                    $.post("ajax/get-order-card.php", {
                        id: data.orderId,
                    }, function(result) {
                        if (result != "") {
                            const res = JSON.parse(result);
                            $("#parent").prepend(res.data)
                            if(res.is_pending) {
                                audio.play()
                            } else {
                                audio.pause()
                            }
                            try {
                                var notify = new Notification(
                                    `لقد تم اضافة طلب جديد!`, {
                                        body: `رقم الاوردر #${data.orderId}`,
                                    }
                                );
                                notify.onclick = function() {
                                    window.focus();
                                    notification.close();
                                };
                                notify.addEventListener("error", e => {
                                    alert("فشل ارسال اشعار بالطلب الجديد، يرجى التحقق من اعطاء النظام جميع الصلاحيات الازمة!")
                                })
                            } catch (e) {
                                console.error("Notification error:", e);
                            }
                        }
                        check_live_order_visibility()
                    });
                    break;
                case "order:approve":
                    $.post("ajax/get-order-card.php", {
                        id: data.orderId,
                    }, function(result) {
                        if (result != "") {
                            const res = JSON.parse(result);
                            var parent = $(`#parent > div[data-id='${data.orderId}']`)
                            parent.replaceWith(res.data)
                            if(res.is_pending) {
                                audio.play()
                            } else {
                                audio.pause()
                            }
                        }
                        check_live_order_visibility()
                    })
                    break;
                case "order:cancel":
                    console.log("order cancel received for order id: " + data.orderId);
                    $.post("ajax/get-order-card.php", {
                        id: data.orderId,
                    }, function(result) {
                        if (result != "") {
                            const res = JSON.parse(result);
                            var parent = $(`#parent > div[data-id='${data.orderId}']`)
                            parent.replaceWith(res.data)
                            if(res.is_pending) {
                                audio.play()
                            } else {
                                audio.pause()
                            }
                        }
                        check_live_order_visibility()
                    })
                    break;
                case "order:remove":
                    var parent = $(`#parent > div[data-id='${data.orderId}']`)
                    parent.remove()
                    $.post("ajax/check_unmarked.php", {
                        id: data.orderId,
                    }, function(result) {
                        if (result == "true") {
                            audio.play()
                        } else {
                            audio.pause()
                        }
                        check_live_order_visibility()
                    })
                    break;
            }
        }

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
        <?php 
            if(check_unresponsed_order_period()) {
                echo "audio.play()";
            }
        ?>

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