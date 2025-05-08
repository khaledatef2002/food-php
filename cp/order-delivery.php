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
    if (!check_user_perm(['order-page-view'])) :
        header('Location: 403.php');
        exit;
    endif;
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
            <?php if (check_user_perm(['order-page-add'])) : ?>
                <div class="row my-4 justify-content-center">
                    <a class="btn btn-info py-2" style="width:fit-content;" data-bs-toggle="modal" data-bs-target="#add_new_order_page_modal">اضافة جديد</a>
                </div>
            <?php endif; ?>
            <div id="parent" class="row my-4 justify-content-center">
                <?php
                $get_order_page = mysqli_query($GLOBALS['conn'], "SELECT * FROM order_page");
                while ($order_page = mysqli_fetch_assoc($get_order_page)) {
                ?>
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-2" data-id="<?php echo $order_page['id']; ?>">
                        <div class="card mb-2 h-100">
                            <div class="card-body p-3 d-flex flex-column">
                                <div class="my-0">
                                    <div class="icon" style="width:100%;height:fit-content;">
                                        <?php
                                        switch ($order_page['type']) {
                                            case 'order_online':
                                                include "../imgs/wb-esite.svg";
                                                break;
                                            case 'phone':
                                                include '../imgs/newhot.svg';
                                                break;
                                            case 'whatsapp':
                                                include '../imgs/whatsapp.svg';
                                                break;
                                        }
                                        ?>
                                    </div>
                                </div>
                                <p class="font-weight-bold my-0 fs-6 d-flex align-items-center justify-content-center flex-fill text-break text-center">
                                    <?php echo $order_page['value']; ?>
                                </p>
                                <?php if (check_user_perm(['order-page-remove'])) : ?>
                                    <div class="d-flex justify-content-center">
                                        <button class="btn bg-gradient-danger text-white my-1 py-1" onclick="remove_order_page_icon(<?php echo $order_page['id']; ?>,this)">حذف</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div>
            <?php include 'temps/footer.php'; ?>
        </div>
    </main>

    <!-- Start Add New Social modal -->
    <div class="modal fade" data-bs-backdrop="static" id="add_new_order_page_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة وسيلة تواصل جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-wrap justify-content-start column-gap-1">
                    <div class="px-1 col-sm-6" style='flex-grow:1'>
                        <label class="text-bold">اختر الوسيلة:</label>
                        <select name="order-type" class="form-select mb-2 border">
                            <option value="order_online">Order Online</option>
                            <option value="phone">Phone</option>
                            <option value="whatsapp">Whatsapp</option>
                        </select>
                        <input class="form-control border px-2" name="order-details" placeholder="بيانات">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="add_new_order_page()">اضافة</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add New Social modal -->

    <?php include 'temps/jslibs.php'; ?>
    <script>
        function add_new_order_page() {
            var icon = $("#add_new_order_page_modal").find("select[name='order-type'] option:selected").val()
            var data = $("#add_new_order_page_modal").find("input[name='order-details']").val()
            $.post("ajax/add_new_order_page.php", {
                icon: icon,
                data: data
            }, function(res) {
                console.log(res);
                if (res !== "error") {
                    $("#parent").append(res)
                }
            })
        }

        function remove_order_page_icon(id, me) {
            $.post("ajax/remove_order_page_icon.php", {
                id: id
            }, function(res) {
                //Rembmer to valid errors
                if (res != "error") {
                    $(me).parent().parent().parent().parent().remove()
                }
            })
        }
    </script>
</body>

</html>