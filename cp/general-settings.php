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
    <?php
    include 'temps/head.php';
    if (!is_logged()) header('Location: login.php');
    if (!check_user_perm(['general-settings-view', 'general-settings-view-visa'])) :
        header('Location: 403.php');
        exit;
    endif;
    $get_settings = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings");
    $fetch = mysqli_fetch_all($get_settings, MYSQLI_ASSOC);
    $site_setting = array_column($fetch, 'value', 'title');
    ?>
    <title>
        Powered By Diafh
    </title>
</head>

<body class="g-sidenav-show rtl bg-gray-200">
    <?php include 'temps/sidebar.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
        <?php include 'temps/navbar.php'; ?>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="col-lg-8 col-md-8 col-sm-12 mb-md-0 mb-4 mx-auto">
                <div class="card text-center">
                    <h4 class="my-0 font-weight-bold py-2">الاعدادات العامة</h4>
                </div>
            </div>
            <form action="POST" id="save-settings">
                <div id="sortable" class="row my-4 justify-content-center">
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-2">
                        <?php if (check_user_perm(['general-settings-view'])) : ?>
                            <div class="card mb-2">
                                <div class="card-body p-3 d-flex flex-column justify-content-between">
                                    <div>
                                        <p>
                                            <label for="website_name" class="font-weight-bold text-dark">اسم الموقع:</label>
                                            <input value="<?php echo $site_setting['site-title']; ?>" name="website_name" id="website_name" type="text" class="form-control border px-2" placeholder="اسم الموقع">
                                        </p>
                                        <p class="font-weight-bold text-dark">
                                            <label for="website_keywords" class="font-weight-bold text-dark">الكلمات المفتاحية:</label>
                                            <input value="<?php echo $site_setting['keywords']; ?>" name="website_keywords" id="website_keywords" type="text" class="form-control border px-2" placeholder="الكلمات المفتاحية">
                                        </p>
                                        <p class="font-weight-bold text-dark">
                                            <label for="website_description" class="font-weight-bold text-dark">الوصف:</label>
                                            <textarea name="website_description" id="website_description" class="form-control border px-2" placeholder="الوصف"><?php echo $site_setting['description']; ?></textarea>
                                        </p>
                                        <p class="font-weight-bold text-dark">
                                            <label for="time_zone" class="font-weight-bold text-dark">التوقيت:</label>
                                            <input value="<?php echo $site_setting['time_zone']; ?>" name="time_zone" id="time_zone" class="form-control border px-2" placeholder="التوقيت">
                                        </p>
                                        <p class="font-weight-bold text-dark">
                                            <label for="dir" class="font-weight-bold text-dark">اتجاه الموقع:</label>
                                            <select name="dir" id="dir" class="form-select">
                                                <option value="rtl" <?php echo ($site_setting['dir'] == 'rtl') ? 'selected' : ''; ?>>From Right To Left</option>
                                                <option value="ltr" <?php echo ($site_setting['dir'] == 'ltr') ? 'selected' : ''; ?>>From Left To Right</option>
                                            </select>
                                        </p>
                                        <p class="font-weight-bold text-dark">
                                            <label for="lang" class="font-weight-bold text-dark">لغة الموقع:</label>
                                            <select name="lang" id="lang" class="form-select">
                                                <option value="ar" <?php echo ($site_setting['lang'] == 'ar') ? 'selected' : ''; ?>>Arabic</option>
                                                <option value="en" <?php echo ($site_setting['lang'] == 'en') ? 'selected' : ''; ?>>English</option>
                                            </select>
                                        </p>
                                        <p class="font-weight-bold text-dark">
                                            <label for="currency" class="font-weight-bold text-dark">العملة:</label>
                                            <input value="<?php echo $site_setting['currency']; ?>" name="currency" id="currency" class="form-control border px-2" placeholder="العملة">
                                        </p>
                                        <p class="font-weight-bold text-dark">
                                            <label for="min_order" class="font-weight-bold text-dark">الحد الادنى للطلب:</label>
                                            <input value="<?php echo $site_setting['order_min']; ?>" name="min_order" id="min_order" type="number" min="0" step="0.01" class="form-control border px-2" placeholder="الحد الادنى للطلب">
                                        </p>
                                        <p class="font-weight-bold text-dark">
                                            <label for="website_taxs" class="font-weight-bold text-dark">ضريبة (%):</label>
                                            <input value="<?php echo $site_setting['tax']; ?>" name="website_taxs" id="website_taxs" type="number" min="0" ste="0.01" class="form-control border px-2" placeholder="ضريبة">
                                        </p>
                                        <p class="font-weight-bold text-dark">
                                            <label for="order_av" class="font-weight-bold text-dark">اتاحية الطلب:</label>
                                        <div class="d-flex justify-content-evenly hide-radio" data-toggle="buttons">
                                            <label class="btn btn-secondary" for="order_av1">
                                                <input <?php echo ($site_setting['order_av'] == 1) ? 'CHECKED' : ''; ?> type="radio" name="order_av" id="order_av1" value="1"> متاح
                                            </label>
                                            <label class="btn btn-secondary" for="order_av2">
                                                <input <?php echo ($site_setting['order_av'] == 0) ? 'CHECKED' : ''; ?> type="radio" name="order_av" id="order_av2" value="0"> غير متاح
                                            </label>
                                        </div>
                                        </p>
                                        <p class="font-weight-bold text-dark">
                                            <label for="order_av_reason" class="font-weight-bold text-dark">سبب عدم الاتاحة:</label>
                                            <input value="<?php echo $site_setting['order_dis_msg']; ?>" name="order_av_reason" id="order_av_reason" type="text" class="form-control border px-2" placeholder="سبب عدم الاتاحية">
                                        </p>
                                        <p class="font-weight-bold text-dark">
                                            <label for="time_msg" class="font-weight-bold text-dark">رسالة الوقت المتوقع لوصول الطلب:</label>
                                            <textarea name="time_msg" id="time_msg" class="form-control border px-2" placeholder="رسالة الوقت المتوقع لوصول الطلب"><?php echo $site_setting['delivery_time']; ?></textarea>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-2 clear-after-success">
                                <div class="card-body p-3 d-flex flex-column justify-content-between">
                                    <div>
                                        <p class="text-center fw-bold text-dark">رسالة تاكيد الواتساب</p>
                                        <div class="d-flex justify-content-evenly hide-radio" data-toggle="buttons">
                                            <label class="btn btn-secondary" for="wh_av1">
                                                <input <?php echo ($site_setting['wh_av'] == 1) ? 'CHECKED' : ''; ?> type="radio" name="wh_av" value="1" id="wh_av1"> مفعل
                                            </label>
                                            <label class="btn btn-secondary" for="wh_av2">
                                                <input <?php echo ($site_setting['wh_av'] == 0) ? 'CHECKED' : ''; ?> type="radio" name="wh_av" value="0" id="wh_av2"> غير مفعل
                                            </label>
                                        </div>
                                    </div>
                                    <p class="font-weight-bold text-dark">
                                        <label for="wh_phone" class="font-weight-bold text-dark">رقم الواتساب:</label>
                                        <input value="<?php echo $site_setting['wh_order']; ?>" name="wh_phone" id="wh_phone" type="text" class="form-control border px-2" placeholder="رقم الواتساب">
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="<?php echo (check_user_perm(['general-settings-view'])) ? 'col-lg-4 col-md-4 col-sm-6' : 'col-sm-10'; ?> mb-2">
                        <?php if (check_user_perm(['general-settings-view'])) : ?>
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div>
                                        <p class="text-center fw-bold text-dark">لوجو الموقع</p>
                                        <div class="mb-4 d-flex justify-content-center">
                                            <img id="selectedImage" src="../<?php echo $site_setting['site-logo']; ?>" alt="example placeholder" style="width: 300px;" />
                                        </div>
                                        <p class="text-center">صور بامتداد (png, jpg, jpeg) فقط.</p>
                                        <div class="d-flex justify-content-center">
                                            <div class="btn btn-primary btn-rounded-1 p-1 px-2">
                                                <label class="form-label text-white m-1" for="customFile1" role="button">اختر صورة</label>
                                                <input type="file" name="website_logo" class="form-control d-none" id="customFile1" accept=".jpg, .png, .jpeg" onchange="displaySelectedImage(event, this)" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div>
                                        <p class="text-center fw-bold text-dark">خلفية الرابط</p>
                                        <div class="mb-4 d-flex justify-content-center">
                                            <img id="selectedImage" src="../<?php echo $site_setting['og:image']; ?>" alt="example placeholder" style="width: 300px;" />
                                        </div>
                                        <p class="text-center">صور بامتداد (png, jpg, jpeg) فقط.</p>
                                        <div class="d-flex justify-content-center">
                                            <div class="btn btn-primary btn-rounded-1 p-1 px-2">
                                                <label class="form-label text-white m-1" for="customFile2" role="button">اختر صورة</label>
                                                <input type="file" name="og_image" class="form-control d-none" id="customFile2" accept=".jpg, .png, .jpeg" onchange="displaySelectedImage(event, this)" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (check_user_perm(['general-settings-view-visa'])) : ?>
                            <div class="card mb-2 clear-after-success">
                                <div class="card-body p-3 d-flex flex-column justify-content-between">
                                    <div>
                                        <p class="text-center fw-bold text-dark">الدفع بالفيزا</p>
                                        <div class="d-flex justify-content-evenly hide-radio" data-toggle="buttons">
                                            <label class="btn btn-secondary" for="visa_av1">
                                                <input <?php echo ($site_setting['visa_av'] == 1) ? 'CHECKED' : ''; ?> type="radio" name="visa_av" value="1" id="visa_av1"> مفعل
                                            </label>
                                            <label class="btn btn-secondary" for="visa_av2">
                                                <input <?php echo ($site_setting['visa_av'] == 0) ? 'CHECKED' : ''; ?> type="radio" name="visa_av" value="0" id="visa_av2"> غير مفعل
                                            </label>
                                        </div>
                                        <div class="d-flex gap-1">
                                            <p class="font-weight-bold text-dark flex-fill">
                                                <label for="visa_fixed_tax" class="font-weight-bold text-dark">Fixed Tax:</label>
                                                <input value="<?php echo $site_setting['visa_tax_fixed']; ?>" name="visa_fixed_tax" id="visa_fixed_tax" type="number" class="form-control border px-2" placeholder="API KEY">
                                            </p>
                                            <p class="font-weight-bold text-dark flex-fill">
                                                <label for="visa_percent_tax" class="font-weight-bold text-dark">Percent Tax:</label>
                                                <input value="<?php echo $site_setting['visa_tax_percent']; ?>" name="visa_percent_tax" id="visa_percent_tax" type="number" step="0.01" class="form-control border px-2" placeholder="API KEY">
                                            </p>
                                        </div>
                                        <p class="font-weight-bold text-dark">
                                            <label for="visa_key" class="font-weight-bold text-dark">API KEY:</label>
                                            <input value="<?php echo $site_setting['API_KEY']; ?>" name="visa_key" id="visa_key" type="text" class="form-control border px-2" placeholder="API KEY">
                                        </p>
                                        <p class="font-weight-bold text-dark">
                                            <label for="visa_id" class="font-weight-bold text-dark">merchant ID:</label>
                                            <input value="<?php echo $site_setting['merchantID']; ?>" name="visa_id" id="visa_id" type="text" class="form-control border px-2" placeholder="Integration ID">
                                        </p>
                                        <p class="font-weight-bold text-dark">
                                            <label for="visa_secret" class="font-weight-bold text-dark">Secret Key:</label>
                                            <input value="<?php echo $site_setting['secretKey']; ?>" name="visa_secret" id="visa_secret" type="text" class="form-control border px-2" placeholder="IFRAME ID">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (check_user_perm(['general-settings-edit', 'general-settings-edit-visa'])) : ?>
                    <div class="d-flex justify-content-center col-12">
                        <button type="submit" class="btn bg-gradient-success text-white my-1 py-1 col-lg-8 col-md-8 col-sm-6 fw-bold fs-5 spinner-button-loading"><span class="content-button-loading">حفظ</span>
                            <div class="lds-dual-ring"></div>
                        </button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
        <?php include 'temps/footer.php'; ?>
    </main>

    <?php include 'temps/jslibs.php'; ?>
    <script>
        $("form#save-settings").submit(function(e) {
            e.preventDefault()
            var form = this
            var data = new FormData(form)
            $.ajax({
                url: 'ajax/save-settings.php',
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(form).find("button[type='submit']").prop("disabled", true)
                },
                success: function(data) {
                    console.log(data)
                    var response = JSON.parse(data)
                    if (response.res == "success") {
                        Swal.fire({
                            icon: "success",
                            text: "تم حفظ التعديلات بنجاح"
                        })
                    } else if (response.res == "error") {
                        Swal.fire({
                            icon: "error",
                            text: response.msg
                        })
                    }
                    $(form).find("button[type='submit']").prop("disabled", false)
                }
            })
        })

        function displaySelectedImage(event, me) {
            const selectedImage = $(me).parent().parent().parent().parent().find("img")[0];
            const fileInput = event.target;

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    selectedImage.src = e.target.result;
                };

                reader.readAsDataURL(fileInput.files[0]);
            }
        }
    </script>
</body>

</html>