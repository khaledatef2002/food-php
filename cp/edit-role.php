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
    if (!check_user_perm(['roles-edit'])) :
        header('Location: 403.php');
        exit;
    endif;

    $id = 0;

    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $get_role = mysqli_query($GLOBALS['conn'], "SELECT * FROM roles WHERE id='$id'");
        if (mysqli_num_rows($get_role) > 0) {
            $role = mysqli_fetch_assoc($get_role);
        } else {
            header('Location: 404.php');
            exit;
        }
    } else {
        header('Location: 404.php');
        exit;
    }
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
            <div class="row my-4 text-center">
                <h4>تعديل دور</h4>
            </div>
            <div id="sortable" class="row my-4 justify-content-center">
                <div class="col-lg-10 col-md-10 col-sm-11 mb-2">
                    <div class="card mb-2 h-100">
                        <form action="POST" id="edit-role">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <input name="id" value="<?php echo $role['id']; ?>" type="hidden">
                                    <p>
                                        <label for="role_name" class="font-weight-bold text-dark">اسم الدور:</label>
                                        <input name="role_name" value="<?php echo $role['name']; ?>" id="role_name" type="text" class="form-control border px-2" placeholder="اسم الدور">
                                    </p>
                                </div>
                                <p class="text-center fw-bold ">صلاحيات الدور</p>
                                <div>
                                    <table class="w-100 table table-striped text-center">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="text-center">الصفحة</th>
                                                <th class="text-center">الصلاحيات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th class="fw-bold text-dark">QR Code</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'qrcode-view') ? 'CHECKED' : ''; ?> name="qrcode-view" class="form-check-input" type="checkbox" role="switch" id="general-settings-view">
                                                        <label class="form-check-label" for="qrcode-view" value="1">عرض</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">الاعدادات العامة</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'general-settings-view') ? 'CHECKED' : ''; ?> name="general-settings-view" class="form-check-input" type="checkbox" role="switch" id="general-settings-view">
                                                        <label class="form-check-label" for="general-settings-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'general-settings-view-visa') ? 'CHECKED' : ''; ?> name="general-settings-view-visa" class="form-check-input" type="checkbox" role="switch" id="general-settings-view-visa">
                                                        <label class="form-check-label" for="general-settings-view-visa" value="1">عرض اعدادات الفيزا</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'general-settings-edit') ? 'CHECKED' : ''; ?> name="general-settings-edit" class="form-check-input" type="checkbox" role="switch" id="general-settings-edit">
                                                        <label class="form-check-label" for="general-settings-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'general-settings-edit-visa') ? 'CHECKED' : ''; ?> name="general-settings-edit-visa" class="form-check-input" type="checkbox" role="switch" id="general-settings-edit-visa">
                                                        <label class="form-check-label" for="general-settings-edit-visa" value="1">تعديل اعدادات الفيزا</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">ايقونات الصفحة الرئيسية</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'main-page-icon-view') ? 'CHECKED' : ''; ?> name="main-page-icon-view" class="form-check-input" type="checkbox" role="switch" id="general-settings-view">
                                                        <label class="form-check-label" for="main-page-icon-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'main-page-icon-edit') ? 'CHECKED' : ''; ?> name="main-page-icon-edit" class="form-check-input" type="checkbox" role="switch" id="general-settings-view-visa">
                                                        <label class="form-check-label" for="main-page-icon-edit" value="1">تعديل</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">علاف الصفحة الرئيسية</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'main-page-cover-view') ? 'CHECKED' : ''; ?> name="main-page-cover-view" class="form-check-input" type="checkbox" role="switch" id="main-page-cover-view">
                                                        <label class="form-check-label" for="main-page-cover-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'main-page-cover-add') ? 'CHECKED' : ''; ?> name="main-page-cover-add" class="form-check-input" type="checkbox" role="switch" id="main-page-cover-add">
                                                        <label class="form-check-label" for="main-page-cover-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'main-page-cover-edit') ? 'CHECKED' : ''; ?> name="main-page-cover-edit" class="form-check-input" type="checkbox" role="switch" id="main-page-cover-edit">
                                                        <label class="form-check-label" for="main-page-cover-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'main-page-cover-remove') ? 'CHECKED' : ''; ?> name="main-page-cover-remove" class="form-check-input" type="checkbox" role="switch" id="main-page-cover-remove">
                                                        <label class="form-check-label" for="main-page-cover-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">اعدادات الالوان</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'colors-settings-view') ? 'CHECKED' : ''; ?> name="colors-settings-view" class="form-check-input" type="checkbox" role="switch" id="colors-settings-view">
                                                        <label class="form-check-label" for="colors-settings-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'colors-settings-edit') ? 'CHECKED' : ''; ?> name="colors-settings-edit" class="form-check-input" type="checkbox" role="switch" id="colors-settings-edit">
                                                        <label class="form-check-label" for="colors-settings-edit" value="1">تعديل</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">فترات العمل</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'working-period-view') ? 'CHECKED' : ''; ?> name="working-period-view" class="form-check-input" type="checkbox" role="switch" id="working-period-view">
                                                        <label class="form-check-label" for="working-period-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'working-period-add') ? 'CHECKED' : ''; ?> name="working-period-add" class="form-check-input" type="checkbox" role="switch" id="working-period-add">
                                                        <label class="form-check-label" for="working-period-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'working-period-edit') ? 'CHECKED' : ''; ?> name="working-period-edit" class="form-check-input" type="checkbox" role="switch" id="working-period-edit">
                                                        <label class="form-check-label" for="working-period-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'working-period-remove') ? 'CHECKED' : ''; ?> name="working-period-remove" class="form-check-input" type="checkbox" role="switch" id="working-period-remove">
                                                        <label class="form-check-label" for="working-period-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">الفروع</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'branches-view') ? 'CHECKED' : ''; ?> name="branches-view" class="form-check-input" type="checkbox" role="switch" id="branches-view">
                                                        <label class="form-check-label" for="branches-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'branches-add') ? 'CHECKED' : ''; ?> name="branches-add" class="form-check-input" type="checkbox" role="switch" id="branches-add">
                                                        <label class="form-check-label" for="branches-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'branches-edit') ? 'CHECKED' : ''; ?> name="branches-edit" class="form-check-input" type="checkbox" role="switch" id="branches-edit">
                                                        <label class="form-check-label" for="branches-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'branches-remove') ? 'CHECKED' : ''; ?> name="branches-remove" class="form-check-input" type="checkbox" role="switch" id="branches-remove">
                                                        <label class="form-check-label" for="branches-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">المناطق</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'locations-view') ? 'CHECKED' : ''; ?> name="locations-view" class="form-check-input" type="checkbox" role="switch" id="locations-view">
                                                        <label class="form-check-label" for="locations-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'locations-add') ? 'CHECKED' : ''; ?> name="locations-add" class="form-check-input" type="checkbox" role="switch" id="locations-add">
                                                        <label class="form-check-label" for="locations-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'locations-edit') ? 'CHECKED' : ''; ?> name="locations-edit" class="form-check-input" type="checkbox" role="switch" id="locations-edit">
                                                        <label class="form-check-label" for="locations-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'locations-remove') ? 'CHECKED' : ''; ?> name="locations-remove" class="form-check-input" type="checkbox" role="switch" id="locations-remove">
                                                        <label class="form-check-label" for="locations-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">صفحة وسائل التواصل</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'social-page-view') ? 'CHECKED' : ''; ?> name="social-page-view" class="form-check-input" type="checkbox" role="switch" id="social-page-view">
                                                        <label class="form-check-label" for="social-page-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'social-page-add') ? 'CHECKED' : ''; ?> name="social-page-add" class="form-check-input" type="checkbox" role="switch" id="social-page-add">
                                                        <label class="form-check-label" for="social-page-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'social-page-edit') ? 'CHECKED' : ''; ?> name="social-page-edit" class="form-check-input" type="checkbox" role="switch" id="social-page-edit">
                                                        <label class="form-check-label" for="social-page-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'social-page-remove') ? 'CHECKED' : ''; ?> name="social-page-remove" class="form-check-input" type="checkbox" role="switch" id="social-page-remove">
                                                        <label class="form-check-label" for="social-page-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">صفحة التقييمات</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'rating-page-view') ? 'CHECKED' : ''; ?> name="rating-page-view" class="form-check-input" type="checkbox" role="switch" id="rating-page-view">
                                                        <label class="form-check-label" for="rating-page-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'rating-page-remove') ? 'CHECKED' : ''; ?> name="rating-page-remove" class="form-check-input" type="checkbox" role="switch" id="rating-page-remove">
                                                        <label class="form-check-label" for="rating-page-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">صفحة اطلب دليفري</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'order-page-view') ? 'CHECKED' : ''; ?> name="order-page-view" class="form-check-input" type="checkbox" role="switch" id="order-page-view">
                                                        <label class="form-check-label" for="order-page-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'order-page-add') ? 'CHECKED' : ''; ?> name="order-page-add" class="form-check-input" type="checkbox" role="switch" id="order-page-add">
                                                        <label class="form-check-label" for="order-page-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'order-page-remove') ? 'CHECKED' : ''; ?> name="order-page-remove" class="form-check-input" type="checkbox" role="switch" id="order-page-remove">
                                                        <label class="form-check-label" for="order-page-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">غلاف صفحة العروض</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'offers-page-cover-view') ? 'CHECKED' : ''; ?> name="offers-page-cover-view" class="form-check-input" type="checkbox" role="switch" id="offers-page-cover-view">
                                                        <label class="form-check-label" for="offers-page-cover-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'offers-page-cover-add') ? 'CHECKED' : ''; ?> name="offers-page-cover-add" class="form-check-input" type="checkbox" role="switch" id="offers-page-cover-add">
                                                        <label class="form-check-label" for="offers-page-cover-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'offers-page-cover-edit') ? 'CHECKED' : ''; ?> name="offers-page-cover-edit" class="form-check-input" type="checkbox" role="switch" id="offers-page-cover-edit">
                                                        <label class="form-check-label" for="offers-page-cover-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'offers-page-cover-remove') ? 'CHECKED' : ''; ?> name="offers-page-cover-remove" class="form-check-input" type="checkbox" role="switch" id="offers-page-cover-remove">
                                                        <label class="form-check-label" for="offers-page-cover-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">صفحة العروض</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'offers-page-view') ? 'CHECKED' : ''; ?> name="offers-page-view" class="form-check-input" type="checkbox" role="switch" id="offers-page-view">
                                                        <label class="form-check-label" for="offers-page-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'offers-page-add') ? 'CHECKED' : ''; ?> name="offers-page-add" class="form-check-input" type="checkbox" role="switch" id="offers-page-add">
                                                        <label class="form-check-label" for="offers-page-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'offers-page-edit') ? 'CHECKED' : ''; ?> name="offers-page-edit" class="form-check-input" type="checkbox" role="switch" id="offers-page-edit">
                                                        <label class="form-check-label" for="offers-page-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'offers-page-remove') ? 'CHECKED' : ''; ?> name="offers-page-remove" class="form-check-input" type="checkbox" role="switch" id="offers-page-remove">
                                                        <label class="form-check-label" for="offers-page-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">غلاف صفحة كارت الصفوة</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'safwa-card-cover-view') ? 'CHECKED' : ''; ?> name="safwa-card-cover-view" class="form-check-input" type="checkbox" role="switch" id="safwa-card-cover-view">
                                                        <label class="form-check-label" for="safwa-card-cover-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'safwa-card-cover-add') ? 'CHECKED' : ''; ?> name="safwa-card-cover-add" class="form-check-input" type="checkbox" role="switch" id="safwa-card-cover-add">
                                                        <label class="form-check-label" for="safwa-card-cover-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'safwa-card-cover-edit') ? 'CHECKED' : ''; ?> name="safwa-card-cover-edit" class="form-check-input" type="checkbox" role="switch" id="safwa-card-cover-edit">
                                                        <label class="form-check-label" for="safwa-card-cover-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'safwa-card-cover-remove') ? 'CHECKED' : ''; ?> name="safwa-card-cover-remove" class="form-check-input" type="checkbox" role="switch" id="safwa-card-cover-remove">
                                                        <label class="form-check-label" for="safwa-card-cover-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">صفحة كارت الصفوة</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'safwa-card-view') ? 'CHECKED' : ''; ?> name="safwa-card-view" class="form-check-input" type="checkbox" role="switch" id="safwa-card-view">
                                                        <label class="form-check-label" for="safwa-card-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'safwa-card-add') ? 'CHECKED' : ''; ?> name="safwa-card-add" class="form-check-input" type="checkbox" role="switch" id="safwa-card-add">
                                                        <label class="form-check-label" for="safwa-card-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'safwa-card-edit') ? 'CHECKED' : ''; ?> name="safwa-card-edit" class="form-check-input" type="checkbox" role="switch" id="safwa-card-edit">
                                                        <label class="form-check-label" for="safwa-card-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'safwa-card-remove') ? 'CHECKED' : ''; ?> name="safwa-card-remove" class="form-check-input" type="checkbox" role="switch" id="safwa-card-remove">
                                                        <label class="form-check-label" for="safwa-card-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">المنيو</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'manual-menu-view') ? 'CHECKED' : ''; ?> name="manual-menu-view" class="form-check-input" type="checkbox" role="switch" id="manual-menu-view">
                                                        <label class="form-check-label" for="manual-menu-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'manual-menu-add') ? 'CHECKED' : ''; ?> name="manual-menu-add" class="form-check-input" type="checkbox" role="switch" id="manual-menu-add">
                                                        <label class="form-check-label" for="manual-menu-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'manual-menu-edit') ? 'CHECKED' : ''; ?> name="manual-menu-edit" class="form-check-input" type="checkbox" role="switch" id="manual-menu-edit">
                                                        <label class="form-check-label" for="manual-menu-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'manual-menu-remove') ? 'CHECKED' : ''; ?> name="manual-menu-remove" class="form-check-input" type="checkbox" role="switch" id="manual-menu-remove">
                                                        <label class="form-check-label" for="manual-menu-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">التصنيفات</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'categories-view') ? 'CHECKED' : ''; ?> name="categories-view" class="form-check-input" type="checkbox" role="switch" id="categories-view">
                                                        <label class="form-check-label" for="categories-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'categories-add') ? 'CHECKED' : ''; ?> name="categories-add" class="form-check-input" type="checkbox" role="switch" id="categories-add">
                                                        <label class="form-check-label" for="categories-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'categories-edit') ? 'CHECKED' : ''; ?> name="categories-edit" class="form-check-input" type="checkbox" role="switch" id="categories-edit">
                                                        <label class="form-check-label" for="categories-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'categories-remove') ? 'CHECKED' : ''; ?> name="categories-remove" class="form-check-input" type="checkbox" role="switch" id="categories-remove">
                                                        <label class="form-check-label" for="categories-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">الاصناف</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'items-view') ? 'CHECKED' : ''; ?> name="items-view" class="form-check-input" type="checkbox" role="switch" id="items-view">
                                                        <label class="form-check-label" for="items-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'items-add') ? 'CHECKED' : ''; ?> name="items-add" class="form-check-input" type="checkbox" role="switch" id="items-add">
                                                        <label class="form-check-label" for="items-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'items-edit') ? 'CHECKED' : ''; ?> name="items-edit" class="form-check-input" type="checkbox" role="switch" id="items-edit">
                                                        <label class="form-check-label" for="items-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'items-remove') ? 'CHECKED' : ''; ?> name="items-remove" class="form-check-input" type="checkbox" role="switch" id="items-remove">
                                                        <label class="form-check-label" for="items-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">الخصومات</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'discounts-view') ? 'CHECKED' : ''; ?> name="discounts-view" class="form-check-input" type="checkbox" role="switch" id="discounts-view">
                                                        <label class="form-check-label" for="discounts-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'discounts-view-orders') ? 'CHECKED' : ''; ?> name="discounts-view-orders" class="form-check-input" type="checkbox" role="switch" id="discounts-view-orders">
                                                        <label class="form-check-label" for="discounts-view-orders" value="1">عرض الطلبات</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'discounts-view-customers') ? 'CHECKED' : ''; ?> name="discounts-view-customers" class="form-check-input" type="checkbox" role="switch" id="discounts-view-customers">
                                                        <label class="form-check-label" for="discounts-view-customers" value="1">عرض العملاء</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'discounts-add') ? 'CHECKED' : ''; ?> name="discounts-add" class="form-check-input" type="checkbox" role="switch" id="discounts-add">
                                                        <label class="form-check-label" for="discounts-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'discounts-edit') ? 'CHECKED' : ''; ?> name="discounts-edit" class="form-check-input" type="checkbox" role="switch" id="discounts-edit">
                                                        <label class="form-check-label" for="discounts-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'discounts-remove') ? 'CHECKED' : ''; ?> name="discounts-remove" class="form-check-input" type="checkbox" role="switch" id="discounts-remove">
                                                        <label class="form-check-label" for="discounts-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">الطلبات لايف</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'live-orders-view') ? 'CHECKED' : ''; ?> name="live-orders-view" class="form-check-input" type="checkbox" role="switch" id="live-orders-view">
                                                        <label class="form-check-label" for="live-orders-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'live-orders-action') ? 'CHECKED' : ''; ?> name="live-orders-action" class="form-check-input" type="checkbox" role="switch" id="live-orders-action">
                                                        <label class="form-check-label" for="live-orders-action" value="1">اجراء</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">سجل الطلبات</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'orders-data-view') ? 'CHECKED' : ''; ?> name="orders-data-view" class="form-check-input" type="checkbox" role="switch" id="orders-data-view">
                                                        <label class="form-check-label" for="orders-data-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'orders-data-remove') ? 'CHECKED' : ''; ?> name="orders-data-remove" class="form-check-input" type="checkbox" role="switch" id="orders-data-remove">
                                                        <label class="form-check-label" for="orders-data-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">تعطيل/تفعيل الاصناف</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'switch-items') ? 'CHECKED' : ''; ?> name="switch-items" class="form-check-input" type="checkbox" role="switch" id="switch-items">
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">الحسابات</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'accounts-view') ? 'CHECKED' : ''; ?> name="accounts-view" class="form-check-input" type="checkbox" role="switch" id="accounts-view">
                                                        <label class="form-check-label" for="accounts-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'accounts-add') ? 'CHECKED' : ''; ?> name="accounts-add" class="form-check-input" type="checkbox" role="switch" id="accounts-add">
                                                        <label class="form-check-label" for="accounts-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'accounts-edit') ? 'CHECKED' : ''; ?> name="accounts-edit" class="form-check-input" type="checkbox" role="switch" id="accounts-edit">
                                                        <label class="form-check-label" for="accounts-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'accounts-remove') ? 'CHECKED' : ''; ?> name="accounts-remove" class="form-check-input" type="checkbox" role="switch" id="accounts-remove">
                                                        <label class="form-check-label" for="accounts-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">الادوار</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'roles-view') ? 'CHECKED' : ''; ?> name="roles-view" class="form-check-input" type="checkbox" role="switch" id="roles-view">
                                                        <label class="form-check-label" for="roles-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'roles-add') ? 'CHECKED' : ''; ?> name="roles-add" class="form-check-input" type="checkbox" role="switch" id="roles-add">
                                                        <label class="form-check-label" for="roles-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'roles-edit') ? 'CHECKED' : ''; ?> name="roles-edit" class="form-check-input" type="checkbox" role="switch" id="roles-edit">
                                                        <label class="form-check-label" for="roles-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'roles-remove') ? 'CHECKED' : ''; ?> name="roles-remove" class="form-check-input" type="checkbox" role="switch" id="roles-remove">
                                                        <label class="form-check-label" for="roles-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">السجل</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'log-view') ? 'CHECKED' : ''; ?> name="log-view" class="form-check-input" type="checkbox" role="switch" id="log-view">
                                                        <label class="form-check-label" for="log-view" value="1">عرض</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">التقارير</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input <?php echo get_role_permission($role['id'], 'reports-view') ? 'CHECKED' : ''; ?> name="reports-view" class="form-check-input" type="checkbox" role="switch" id="reports-view">
                                                        <label class="form-check-label" for="reports-view" value="1">عرض</label>
                                                    </div>
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-between col-12">
                                    <button type="submit" class="btn bg-gradient-success text-white my-1 py-1 col-12 fw-bold fs-5 spinner-button-loading"><span class="content-button-loading">حفظ</span>
                                        <div class="lds-dual-ring"></div>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <?php include 'temps/footer.php'; ?>
        </div>
    </main>

    <?php include 'temps/jslibs.php'; ?>
    <script>
        $("form#edit-role").submit(function(e) {
            e.preventDefault()
            var form = this
            var data = new FormData(form)
            $.ajax({
                url: 'ajax/edit-role.php',
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
                    $(form).find("button[type='submit']").prop("disabled", false)
                    if (response.msg == "success") {
                        Swal.fire({
                            icon: "success",
                            text: "تم تحديث بيانات الدور بنجاح"
                        })
                    } else if (response.msg == "error") {
                        Swal.fire({
                            icon: "error",
                            text: response.body
                        })
                    }
                }
            })
        })
    </script>
</body>

</html>