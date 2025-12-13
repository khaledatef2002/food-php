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
    if (!check_user_perm(['general-settings-view-visa'])) :
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
                    <h4 class="my-0 font-weight-bold py-2">إعدادات الدفع</h4>
                </div>
            </div>
            <form action="POST" id="save-settings">
                <div class="row my-4 px-3">
                    <div class="card col-lg-8 col-md-8 col-sm-12 mx-auto mb-2 clear-after-success">
                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            <h5 class="font-weight-bold text-dark text-center">إعدادات عامة</h5>
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
                                    <input value="<?php echo $site_setting['visa_tax_fixed']; ?>" name="visa_fixed_tax" id="visa_fixed_tax" type="number" class="form-control border px-2">
                                </p>
                                <p class="font-weight-bold text-dark flex-fill">
                                    <label for="visa_percent_tax" class="font-weight-bold text-dark">Percent Tax:</label>
                                    <input value="<?php echo $site_setting['visa_tax_percent']; ?>" name="visa_percent_tax" id="visa_percent_tax" type="number" step="0.01" class="form-control border px-2">
                                </p>
                            </div>
                            <p class="font-weight-bold text-dark">
                                <label for="selected_payment_method_providor" class="font-weight-bold text-dark">مقدم الخدمة:</label>
                                <select name="selected_payment_method_providor" id="selected_payment_method_providor" class="form-select">
                                    <option value="qnb" <?php echo ($site_setting['selected_payment_method_providor'] == 'qnb') ? 'selected' : ''; ?>>QNB</option>
                                    <option value="paymob" <?php echo ($site_setting['selected_payment_method_providor'] == 'paymob') ? 'selected' : ''; ?>>PAYMOB</option>
                                </select>
                            </p>
                        </div>
                    </div>
                </div>  
                <div id="sortable" class="row my-4 justify-content-center">
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-2">
                        <div class="card mb-2 clear-after-success">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <p class="text-center fw-bold text-dark">QNB</p>
                                    <p class="font-weight-bold text-dark">
                                        <label for="visa_qnb_merchant_id" class="font-weight-bold text-dark">merchant ID:</label>
                                        <input value="<?php echo $site_setting['visa_qnb_merchant_id']; ?>" name="visa_qnb_merchant_id" id="visa_qnb_merchant_id" type="text" class="form-control border px-2" placeholder="Merchant ID">
                                    </p>
                                    <p class="font-weight-bold text-dark">
                                        <label for="visa_qnb_api_password" class="font-weight-bold text-dark">Api Password:</label>
                                        <input value="<?php echo $site_setting['visa_qnb_api_password']; ?>" name="visa_qnb_api_password" id="visa_qnb_api_password" type="text" class="form-control border px-2" placeholder="Api Password">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-2">
                        <div class="card mb-2 clear-after-success">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <p class="text-center fw-bold text-dark">Paymob</p>
                                    <p class="font-weight-bold text-dark">
                                        <label for="paymob_hmac" class="font-weight-bold text-dark">HMAC:</label>
                                        <input value="<?php echo $site_setting['paymob_hmac']; ?>" name="paymob_hmac" id="paymob_hmac" type="text" class="form-control border px-2" placeholder="Paymob HMAC">
                                    </p>
                                    <p class="font-weight-bold text-dark">
                                        <label for="paymob_secret_key" class="font-weight-bold text-dark">SECRET KEY:</label>
                                        <input value="<?php echo $site_setting['paymob_secret_key']; ?>" name="paymob_secret_key" id="paymob_secret_key" type="text" class="form-control border px-2" placeholder="Paymob Secret Key">
                                    </p>
                                    <p class="font-weight-bold text-dark">
                                        <label for="paymob_iframe_id" class="font-weight-bold text-dark">IFRAME ID:</label>
                                        <input value="<?php echo $site_setting['paymob_iframe_id']; ?>" name="paymob_iframe_id" id="paymob_iframe_id" type="text" class="form-control border px-2" placeholder="Paymob Iframe ID">
                                    </p>
                                    <p class="font-weight-bold text-dark">
                                        <label for="paymob_integration_id" class="font-weight-bold text-dark">INTEGRATION ID:</label>
                                        <input value="<?php echo $site_setting['paymob_integration_id']; ?>" name="paymob_integration_id" id="paymob_integration_id" type="text" class="form-control border px-2" placeholder="Paymob Integration ID">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (check_user_perm(['general-settings-edit-visa'])) : ?>
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
                url: 'ajax/save-visa-settings.php',
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