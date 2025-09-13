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
    if (!check_user_perm(['roles-add'])) :
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
            <div class="row my-4 text-center">
                <h4>اضافة دور جديد</h4>
            </div>
            <div id="sortable" class="row my-4 justify-content-center">
                <div class="col-lg-10 col-md-10 col-sm-11 mb-2">
                    <div class="card mb-2 h-100">
                        <form action="POST" id="add-role">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <p>
                                        <label for="role_name" class="font-weight-bold text-dark">اسم الدور:</label>
                                        <input name="role_name" id="role_name" type="text" class="form-control border px-2" placeholder="اسم الدور">
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
                                                        <input name="qrcode-view" class="form-check-input" type="checkbox" role="switch" id="general-settings-view">
                                                        <label class="form-check-label" for="qrcode-view" value="1">عرض</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">الاعدادات العامة</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="general-settings-view" class="form-check-input" type="checkbox" role="switch" id="general-settings-view">
                                                        <label class="form-check-label" for="general-settings-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="general-settings-view-visa" class="form-check-input" type="checkbox" role="switch" id="general-settings-view-visa">
                                                        <label class="form-check-label" for="general-settings-view-visa" value="1">عرض اعدادات الفيزا</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="general-settings-edit" class="form-check-input" type="checkbox" role="switch" id="general-settings-edit">
                                                        <label class="form-check-label" for="general-settings-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="general-settings-edit-visa" class="form-check-input" type="checkbox" role="switch" id="general-settings-edit-visa">
                                                        <label class="form-check-label" for="general-settings-edit-visa" value="1">تعديل اعدادات الفيزا</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">علاف الصفحة الرئيسية</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="main-page-cover-view" class="form-check-input" type="checkbox" role="switch" id="main-page-cover-view">
                                                        <label class="form-check-label" for="main-page-cover-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="main-page-cover-add" class="form-check-input" type="checkbox" role="switch" id="main-page-cover-add">
                                                        <label class="form-check-label" for="main-page-cover-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="main-page-cover-edit" class="form-check-input" type="checkbox" role="switch" id="main-page-cover-edit">
                                                        <label class="form-check-label" for="main-page-cover-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="main-page-cover-remove" class="form-check-input" type="checkbox" role="switch" id="main-page-cover-remove">
                                                        <label class="form-check-label" for="main-page-cover-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">اعدادات الالوان</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="colors-settings-view" class="form-check-input" type="checkbox" role="switch" id="colors-settings-view">
                                                        <label class="form-check-label" for="colors-settings-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="colors-settings-edit" class="form-check-input" type="checkbox" role="switch" id="colors-settings-edit">
                                                        <label class="form-check-label" for="colors-settings-edit" value="1">تعديل</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">فترات العمل</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="working-period-view" class="form-check-input" type="checkbox" role="switch" id="working-period-view">
                                                        <label class="form-check-label" for="working-period-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="working-period-add" class="form-check-input" type="checkbox" role="switch" id="working-period-add">
                                                        <label class="form-check-label" for="working-period-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="working-period-edit" class="form-check-input" type="checkbox" role="switch" id="working-period-edit">
                                                        <label class="form-check-label" for="working-period-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="working-period-remove" class="form-check-input" type="checkbox" role="switch" id="working-period-remove">
                                                        <label class="form-check-label" for="working-period-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">الفروع</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="branches-view" class="form-check-input" type="checkbox" role="switch" id="branches-view">
                                                        <label class="form-check-label" for="branches-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="branches-add" class="form-check-input" type="checkbox" role="switch" id="branches-add">
                                                        <label class="form-check-label" for="branches-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="branches-edit" class="form-check-input" type="checkbox" role="switch" id="branches-edit">
                                                        <label class="form-check-label" for="branches-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="branches-remove" class="form-check-input" type="checkbox" role="switch" id="branches-remove">
                                                        <label class="form-check-label" for="branches-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">المناطق</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="locations-view" class="form-check-input" type="checkbox" role="switch" id="locations-view">
                                                        <label class="form-check-label" for="locations-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="locations-add" class="form-check-input" type="checkbox" role="switch" id="locations-add">
                                                        <label class="form-check-label" for="locations-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="locations-edit" class="form-check-input" type="checkbox" role="switch" id="locations-edit">
                                                        <label class="form-check-label" for="locations-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="locations-remove" class="form-check-input" type="checkbox" role="switch" id="locations-remove">
                                                        <label class="form-check-label" for="locations-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">صفحة وسائل التواصل</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="social-page-view" class="form-check-input" type="checkbox" role="switch" id="social-page-view">
                                                        <label class="form-check-label" for="social-page-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="social-page-add" class="form-check-input" type="checkbox" role="switch" id="social-page-add">
                                                        <label class="form-check-label" for="social-page-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="social-page-edit" class="form-check-input" type="checkbox" role="switch" id="social-page-edit">
                                                        <label class="form-check-label" for="social-page-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="social-page-remove" class="form-check-input" type="checkbox" role="switch" id="social-page-remove">
                                                        <label class="form-check-label" for="social-page-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">صفحة التقييمات</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="rating-page-view" class="form-check-input" type="checkbox" role="switch" id="rating-page-view">
                                                        <label class="form-check-label" for="rating-page-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="rating-page-remove" class="form-check-input" type="checkbox" role="switch" id="rating-page-remove">
                                                        <label class="form-check-label" for="rating-page-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">صفحة اطلب دليفري</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="order-page-view" class="form-check-input" type="checkbox" role="switch" id="order-page-view">
                                                        <label class="form-check-label" for="order-page-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="order-page-add" class="form-check-input" type="checkbox" role="switch" id="order-page-add">
                                                        <label class="form-check-label" for="order-page-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="order-page-remove" class="form-check-input" type="checkbox" role="switch" id="order-page-remove">
                                                        <label class="form-check-label" for="order-page-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">غلاف صفحة العروض</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="offers-page-cover-view" class="form-check-input" type="checkbox" role="switch" id="offers-page-cover-view">
                                                        <label class="form-check-label" for="offers-page-cover-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="offers-page-cover-add" class="form-check-input" type="checkbox" role="switch" id="offers-page-cover-add">
                                                        <label class="form-check-label" for="offers-page-cover-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="offers-page-cover-edit" class="form-check-input" type="checkbox" role="switch" id="offers-page-cover-edit">
                                                        <label class="form-check-label" for="offers-page-cover-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="offers-page-cover-remove" class="form-check-input" type="checkbox" role="switch" id="offers-page-cover-remove">
                                                        <label class="form-check-label" for="offers-page-cover-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">صفحة العروض</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="offers-page-view" class="form-check-input" type="checkbox" role="switch" id="offers-page-view">
                                                        <label class="form-check-label" for="offers-page-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="offers-page-add" class="form-check-input" type="checkbox" role="switch" id="offers-page-add">
                                                        <label class="form-check-label" for="offers-page-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="offers-page-edit" class="form-check-input" type="checkbox" role="switch" id="offers-page-edit">
                                                        <label class="form-check-label" for="offers-page-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="offers-page-remove" class="form-check-input" type="checkbox" role="switch" id="offers-page-remove">
                                                        <label class="form-check-label" for="offers-page-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">غلاف صفحة كارت الصفوة</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="safwa-card-cover-view" class="form-check-input" type="checkbox" role="switch" id="safwa-card-cover-view">
                                                        <label class="form-check-label" for="safwa-card-cover-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="safwa-card-cover-add" class="form-check-input" type="checkbox" role="switch" id="safwa-card-cover-add">
                                                        <label class="form-check-label" for="safwa-card-cover-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="safwa-card-cover-edit" class="form-check-input" type="checkbox" role="switch" id="safwa-card-cover-edit">
                                                        <label class="form-check-label" for="safwa-card-cover-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="safwa-card-cover-remove" class="form-check-input" type="checkbox" role="switch" id="safwa-card-cover-remove">
                                                        <label class="form-check-label" for="safwa-card-cover-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">صفحة كارت الصفوة</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="safwa-card-view" class="form-check-input" type="checkbox" role="switch" id="safwa-card-view">
                                                        <label class="form-check-label" for="safwa-card-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="safwa-card-add" class="form-check-input" type="checkbox" role="switch" id="safwa-card-add">
                                                        <label class="form-check-label" for="safwa-card-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="safwa-card-edit" class="form-check-input" type="checkbox" role="switch" id="safwa-card-edit">
                                                        <label class="form-check-label" for="safwa-card-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="safwa-card-remove" class="form-check-input" type="checkbox" role="switch" id="safwa-card-remove">
                                                        <label class="form-check-label" for="safwa-card-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">المنيو</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="manual-menu-view" class="form-check-input" type="checkbox" role="switch" id="manual-menu-view">
                                                        <label class="form-check-label" for="manual-menu-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="manual-menu-add" class="form-check-input" type="checkbox" role="switch" id="manual-menu-add">
                                                        <label class="form-check-label" for="manual-menu-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="manual-menu-edit" class="form-check-input" type="checkbox" role="switch" id="manual-menu-edit">
                                                        <label class="form-check-label" for="manual-menu-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="manual-menu-remove" class="form-check-input" type="checkbox" role="switch" id="manual-menu-remove">
                                                        <label class="form-check-label" for="manual-menu-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">التصنيفات</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="categories-view" class="form-check-input" type="checkbox" role="switch" id="categories-view">
                                                        <label class="form-check-label" for="categories-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="categories-add" class="form-check-input" type="checkbox" role="switch" id="categories-add">
                                                        <label class="form-check-label" for="categories-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="categories-edit" class="form-check-input" type="checkbox" role="switch" id="categories-edit">
                                                        <label class="form-check-label" for="categories-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="categories-remove" class="form-check-input" type="checkbox" role="switch" id="categories-remove">
                                                        <label class="form-check-label" for="categories-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">الاصناف</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="items-view" class="form-check-input" type="checkbox" role="switch" id="items-view">
                                                        <label class="form-check-label" for="items-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="items-add" class="form-check-input" type="checkbox" role="switch" id="items-add">
                                                        <label class="form-check-label" for="items-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="items-edit" class="form-check-input" type="checkbox" role="switch" id="items-edit">
                                                        <label class="form-check-label" for="items-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="items-remove" class="form-check-input" type="checkbox" role="switch" id="items-remove">
                                                        <label class="form-check-label" for="items-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">الخصومات</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="discounts-view" class="form-check-input" type="checkbox" role="switch" id="discounts-view">
                                                        <label class="form-check-label" for="discounts-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="discounts-view-orders" class="form-check-input" type="checkbox" role="switch" id="discounts-view-orders">
                                                        <label class="form-check-label" for="discounts-view-orders" value="1">عرض الطلبات</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="discounts-view-customers" class="form-check-input" type="checkbox" role="switch" id="discounts-view-customers">
                                                        <label class="form-check-label" for="discounts-view-customers" value="1">عرض العملاء</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="discounts-add" class="form-check-input" type="checkbox" role="switch" id="discounts-add">
                                                        <label class="form-check-label" for="discounts-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="discounts-edit" class="form-check-input" type="checkbox" role="switch" id="discounts-edit">
                                                        <label class="form-check-label" for="discounts-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="discounts-remove" class="form-check-input" type="checkbox" role="switch" id="discounts-remove">
                                                        <label class="form-check-label" for="discounts-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">الطلبات لايف</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="live-orders-view" class="form-check-input" type="checkbox" role="switch" id="live-orders-view">
                                                        <label class="form-check-label" for="live-orders-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="live-orders-action" class="form-check-input" type="checkbox" role="switch" id="live-orders-action">
                                                        <label class="form-check-label" for="live-orders-action" value="1">اجراء</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">سجل الطلبات</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="orders-data-view" class="form-check-input" type="checkbox" role="switch" id="orders-data-view">
                                                        <label class="form-check-label" for="orders-data-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="orders-data-remove" class="form-check-input" type="checkbox" role="switch" id="orders-data-remove">
                                                        <label class="form-check-label" for="orders-data-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">الحسابات</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="accounts-view" class="form-check-input" type="checkbox" role="switch" id="accounts-view">
                                                        <label class="form-check-label" for="accounts-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="accounts-add" class="form-check-input" type="checkbox" role="switch" id="accounts-add">
                                                        <label class="form-check-label" for="accounts-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="accounts-edit" class="form-check-input" type="checkbox" role="switch" id="accounts-edit">
                                                        <label class="form-check-label" for="accounts-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="accounts-remove" class="form-check-input" type="checkbox" role="switch" id="accounts-remove">
                                                        <label class="form-check-label" for="accounts-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">تعطيل/تفعيل الاصناف</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="switch-items" class="form-check-input" type="checkbox" role="switch" id="switch-items">
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">الادوار</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="roles-view" class="form-check-input" type="checkbox" role="switch" id="roles-view">
                                                        <label class="form-check-label" for="roles-view" value="1">عرض</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="roles-add" class="form-check-input" type="checkbox" role="switch" id="roles-add">
                                                        <label class="form-check-label" for="roles-add" value="1">اضافة</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="roles-edit" class="form-check-input" type="checkbox" role="switch" id="roles-edit">
                                                        <label class="form-check-label" for="roles-edit" value="1">تعديل</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input name="roles-remove" class="form-check-input" type="checkbox" role="switch" id="roles-remove">
                                                        <label class="form-check-label" for="roles-remove" value="1">حذف</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">السجل</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="log-view" class="form-check-input" type="checkbox" role="switch" id="log-view">
                                                        <label class="form-check-label" for="log-view" value="1">عرض</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="fw-bold text-dark">التقارير</th>
                                                <th class="d-flex gap-5 text-wrap justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <input name="reports-view" class="form-check-input" type="checkbox" role="switch" id="reports-view">
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
        $("form#add-role").submit(function(e) {
            e.preventDefault()
            var form = this
            var data = new FormData(form)
            $.ajax({
                url: 'ajax/add-role.php',
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
                            text: "تم اضافة الدور بنجاح"
                        })
                        $(form).find("input").val("")
                        $(form).find("input[type='checkbox']").prop("checked", false)
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