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
    if (!check_user_perm(['colors-settings-view'])) :
        header('Location: 403.php');
        exit;
    endif;
    $get_settings = mysqli_query($GLOBALS['conn'], "SELECT * FROM colors_settings WHERE 1");
    $colors = mysqli_fetch_assoc($get_settings);
    ?>
    <title>
        Powered by diafh
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
                    <h4 class="my-0 font-weight-bold py-2">اعدادات الالوان</h4>
                </div>
            </div>
            <form action="POST" id="save-colors">
                <div id="sortable" class="row my-4 justify-content-center">
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-2">
                        <div class="card mb-2">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <p>
                                    <label for="header" class="font-weight-bold text-dark">header:</label>
                                    <input value="<?php echo $colors['header']; ?>" name="header" id="header" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="footer" class="font-weight-bold text-dark">footer:</label>
                                    <input value="<?php echo $colors['footer']; ?>" name="footer" id="footer" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="footer_color" class="font-weight-bold text-dark">footer text color:</label>
                                    <input value="<?php echo $colors['footer_color']; ?>" name="footer_color" id="footer_color" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="icon" class="font-weight-bold text-dark">icon color:</label>
                                    <input value="<?php echo $colors['icon']; ?>" name="icon" id="icon" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="icon_back" class="font-weight-bold text-dark">icon background color:</label>
                                    <input value="<?php echo $colors['icon_back']; ?>" name="icon_back" id="icon_back" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="icon_border" class="font-weight-bold text-dark">icon border color:</label>
                                    <input value="<?php echo $colors['icon_border']; ?>" name="icon_border" id="icon_border" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="button_back" class="font-weight-bold text-dark">buttons background color:</label>
                                    <input value="<?php echo $colors['button_back']; ?>" name="button_back" id="button_back" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="button_color" class="font-weight-bold text-dark">buttons text color:</label>
                                    <input value="<?php echo $colors['button_color']; ?>" name="button_color" id="button_color" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <p>
                                    <label for="radio_back" class="font-weight-bold text-dark">radio button background color:</label>
                                    <input value="<?php echo $colors['radio_back']; ?>" name="radio_back" id="radio_back" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="radio_border" class="font-weight-bold text-dark">radio button border color:</label>
                                    <input value="<?php echo $colors['radio_border']; ?>" name="radio_border" id="radio_border" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="radio_color" class="font-weight-bold text-dark">radio button checked color:</label>
                                    <input value="<?php echo $colors['radio_color']; ?>" name="radio_color" id="radio_color" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-2">
                        <div class="card mb-2">
                            <div class="card-body">
                                <p>
                                    <label for="cat_header_back" class="font-weight-bold text-dark">Order page categories header backgorund color:</label>
                                    <input value="<?php echo $colors['cat_header_back']; ?>" name="cat_header_back" id="cat_header_back" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="cat_header_color" class="font-weight-bold text-dark">Order page categories header text color:</label>
                                    <input value="<?php echo $colors['cat_header_color']; ?>" name="cat_header_color" id="cat_header_color" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="cat_header_active_back" class="font-weight-bold text-dark">Order page active categories header background color:</label>
                                    <input value="<?php echo $colors['cat_header_active_back']; ?>" name="cat_header_active_back" id="cat_header_active_back" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="cat_header_active_color" class="font-weight-bold text-dark">Order page active categories header text color:</label>
                                    <input value="<?php echo $colors['cat_header_active_color']; ?>" name="cat_header_active_color" id="cat_header_active_color" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="order_footer_back" class="font-weight-bold text-dark">Order page footer button background color:</label>
                                    <input value="<?php echo $colors['order_footer_back']; ?>" name="order_footer_back" id="order_footer_back" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="order_footer_color" class="font-weight-bold text-dark">Order page footer button text color:</label>
                                    <input value="<?php echo $colors['order_footer_color']; ?>" name="order_footer_color" id="order_footer_color" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="order_footer_n_back" class="font-weight-bold text-dark">Order page footer button items number background color:</label>
                                    <input value="<?php echo $colors['order_footer_n_back']; ?>" name="order_footer_n_back" id="order_footer_n_back" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="order_footer_n_color" class="font-weight-bold text-dark">Order page footer button items number text color:</label>
                                    <input value="<?php echo $colors['order_footer_n_color']; ?>" name="order_footer_n_color" id="order_footer_n_color" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <p>
                                    <label for="modal_header_back" class="font-weight-bold text-dark">Modals header background color:</label>
                                    <input value="<?php echo $colors['modal_header_back']; ?>" name="modal_header_back" id="modal_header_back" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="modal_header_color" class="font-weight-bold text-dark">Modals header text color:</label>
                                    <input value="<?php echo $colors['modal_header_color']; ?>" name="modal_header_color" id="modal_header_color" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                                <p>
                                    <label for="text" class="font-weight-bold text-dark">general text color:</label>
                                    <input value="<?php echo $colors['text']; ?>" name="text" id="text" type="color" class="form-control border px-2" placeholder="اسم الموقع">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (check_user_perm(['colors-settings-edit'])) : ?>
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
        $("form#save-colors").submit(function(e) {
            e.preventDefault()
            var form = this
            var data = new FormData(form)
            $.ajax({
                url: 'ajax/save-colors.php',
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(this).find("button[type='submit']").prop("disabled", true)
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
                    $(this).find("button[type='submit']").prop("disabled", false)
                }
            })
        })
    </script>
</body>

</html>