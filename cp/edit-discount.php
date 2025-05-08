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
    if (!check_user_perm(['discounts-view', 'discounts-edit'])) :
        header('Location: 403.php');
        exit;
    endif;
    $id = 0;

    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $get_discount = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_discounts WHERE id='$id'");
        if (mysqli_num_rows($get_discount) > 0) {
            $discount = mysqli_fetch_assoc($get_discount);
            $get_discount_cat = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_discounts_cat WHERE discount_id='$id'");
            $get_discount_items = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_discounts_items WHERE discount_id='$id'");
            $get_discount_locations = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_discounts_locations WHERE discount_id='$id'");
            $get_discount_phones = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_discounts_phones WHERE discount_id='$id'");
        } else {
            header('Location: 404.php');
            exit;
        }
    } else {
        header('Location: 404.php');
        exit;
    }

    $get_settings = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='currency'");
    $fetch = mysqli_fetch_assoc($get_settings);
    $currency = $fetch['value'];
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
            <div class="col-lg-10 col-md-8 col-sm-12 mb-md-0 mb-4 mx-auto">
                <div class="card text-center">
                    <h3 class="my-0 font-weight-bold py-2">تعديل خصم</h3>
                </div>
            </div>
            <form action="POST" id="save-discount">
                <div id="sortable" class="row my-4 justify-content-center">
                    <div class="col-lg-5 col-md-4 col-sm-6 mb-2">
                        <div class="card mb-2">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <p>
                                        <label for="discount_code" class="font-weight-bold text-dark">كود الخصم:</label>
                                        <input value="<?php echo $discount['code']; ?>" name="discount_code" id="discount_code" type="text" class="form-control border px-2" placeholder="كود الخصم">
                                    </p>
                                    <p class="font-weight-bold text-dark">
                                        <label for="discount_name" class="font-weight-bold text-dark">مسمى الخصم:</label>
                                        <input value="<?php echo $discount['name']; ?>" name="discount_name" id="discount_name" type="text" class="form-control border px-2" placeholder="مسمى الخصم">
                                    </p>
                                    <p class="font-weight-bold text-dark">
                                        <label for="discount_min" class="font-weight-bold text-dark">الحد الادنى للطلب:</label>
                                        <input value="<?php echo $discount['min_order']; ?>" name="discount_min" id="discount_min" type="number" min="0" step="0.01" class="form-control border px-2" placeholder="الحد الادنى للطلب">
                                    </p>
                                    <p class="font-weight-bold text-dark">
                                        <label for="discount_max_uses" class="font-weight-bold text-dark">الحد الاقصى للاستخدامات:</label>
                                        <input value="<?php echo $discount['max_uses']; ?>" name="discount_max_uses" id="discount_max_uses" type="number" min="0" class="form-control border px-2" placeholder="الحد الاقصى للاستخدامات">
                                    </p>
                                    <p class="font-weight-bold text-dark">
                                        <label for="discount_user_uses" class="font-weight-bold text-dark">الحد الاقصى لاستخدامات العميل الواحد:</label>
                                        <input value="<?php echo $discount['max_user_use']; ?>" name="discount_user_uses" id="discount_user_uses" type="number" min="0" class="form-control border px-2" placeholder="الحد الاقصى لاستخدامات العميل الواحد">
                                    </p>
                                    <p class="font-weight-bold text-dark">
                                        <label for="discount_max_price" class="font-weight-bold text-dark">الحد الاقصى للخصم:</label>
                                        <input value="<?php echo $discount['max_discount']; ?>" name="discount_max_price" id="discount_max_price" type="number" min="0" class="form-control border px-2" placeholder="الحد الاقصى لاستخدامات العميل الواحد">
                                    </p>
                                    <div class="d-flex justify-content-evenly hide-radio" data-toggle="buttons">
                                        <label class="btn btn-secondary" for="active1">
                                            <input type="radio" name="active" id="active1" value="1" <?php echo ($discount['active'] == 1) ? 'CHECKED' : ''; ?>> مفعل
                                        </label>
                                        <label class="btn btn-secondary" for="active2">
                                            <input type="radio" name="active" id="active2" value="2" <?php echo ($discount['active'] == 2) ? 'CHECKED' : ''; ?>> مفعل خلال فترة
                                        </label>
                                        <label class="btn btn-secondary" for="active3">
                                            <input type="radio" name="active" id="active3" value="0" <?php echo ($discount['active'] == 0) ? 'CHECKED' : ''; ?>> غير مفعل
                                        </label>
                                    </div>
                                    <div id="active_period" class="flex-row justify-content-between gap-2" <?php echo (empty($discount['start_date']) && empty($discount['end_date']) || $discount['active'] != 2) ? 'style="display:none;"' : 'style="display:flex;"'; ?>>
                                        <p class="font-weight-bold text-dark flex-fill">
                                            <label for="discount_start" class="font-weight-bold text-dark">بداية الخصم:</label>
                                            <input value="<?php echo date("Y-m-d", $discount['start_date']); ?>" name="discount_start_date" id="discount_start" type="date" class="form-control border px-2 mb-1">
                                            <input value="<?php echo date("H:i", $discount['start_date']); ?>" name="discount_start_time" id="discount_start" type="time" class="form-control border px-2">
                                        </p>
                                        <p class="font-weight-bold text-dark flex-fill">
                                            <label for="discount_end" class="font-weight-bold text-dark">نهاية الخصم:</label>
                                            <input value="<?php echo date("Y-m-d", $discount['end_date']); ?>" name="discount_end_date" id="discount_end" type="date" class="form-control border px-2 mb-1">
                                            <input value="<?php echo date("H:i", $discount['end_date']); ?>" name="discount_end_time" id="discount_end" type="time" class="form-control border px-2">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <p class="text-center fw-bold text-dark">نوع الخصم</p>
                                    <div class="d-flex justify-content-evenly hide-radio" data-toggle="buttons">
                                        <label class="btn btn-secondary" for="discount_type1">
                                            <input type="radio" name="discount_type" id="discount_type1" value="0" <?php echo ($discount['discount_type'] == 0) ? 'CHECKED' : ''; ?>> خصم الطلب
                                        </label>
                                        <label class="btn btn-secondary" for="discount_type2">
                                            <input type="radio" name="discount_type" id="discount_type2" value="1" <?php echo ($discount['discount_type'] == 1) ? 'CHECKED' : ''; ?>> خصم التوصيل
                                        </label>
                                    </div>
                                    <hr class="bg-dark">
                                    <p class="font-weight-bold text-dark">
                                        <label for="discount_value" class="font-weight-bold text-dark">قيمة الخصم:</label>
                                        <input value="<?php echo $discount['discount_value']; ?>" name="discount_value" id="discount_value" type="number" min="0" step="0.01" class="form-control border px-2" placeholder="قيمة الخصم">
                                    </p>
                                    <hr class="bg-dark">
                                    <p class="text-center fw-bold text-dark">نوع القيمة</p>
                                    <div class="d-flex justify-content-evenly hide-radio" data-toggle="buttons">
                                        <label class="btn btn-secondary" for="discount_value1">
                                            <input type="radio" name="discount_value_type" value="0" id="discount_value1" <?php echo ($discount['discount_value_type'] == 0) ? 'CHECKED' : ''; ?>> خصم عددي (<?php echo $currency; ?>)
                                        </label>
                                        <label class="btn btn-secondary" for="discount_value2">
                                            <input type="radio" name="discount_value_type" value="1" id="discount_value2" <?php echo ($discount['discount_value_type'] == 1) ? 'CHECKED' : ''; ?>> نسبه مئوية (%)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-4 col-sm-6 mb-2">
                        <div class="card mb-2 clear-after-success">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <p class="text-center fw-bold text-dark">شروط المنطقة</p>
                                    <div class="d-flex justify-content-evenly hide-radio" data-toggle="buttons">
                                        <label class="btn btn-secondary" for="location_type1">
                                            <input type="radio" name="location_type" value="0" <?php echo ($discount['locations_type'] == 0) ? 'CHECKED' : ''; ?> id="location_type"> بدون شروط
                                        </label>
                                        <label class="btn btn-secondary" for="location_type2">
                                            <input type="radio" name="location_type" value="1" <?php echo ($discount['locations_type'] == 1) ? 'CHECKED' : ''; ?> id="location_type2"> ساري على مناطق محددة
                                        </label>
                                        <label class="btn btn-secondary" for="location_type3">
                                            <input type="radio" name="location_type" value="2" <?php echo ($discount['locations_type'] == 2) ? 'CHECKED' : ''; ?> id="location_type3"> لا يسري على مناطق محددة
                                        </label>
                                    </div>
                                    <div>
                                        <!--  -->
                                        <?php while ($location = mysqli_fetch_assoc($get_discount_locations)) { ?>
                                            <div>
                                                <label for="locations_data" class="font-weight-bold text-dark">اختر المنطقة:</label>
                                                <div class="d-flex align-items-center my-0">
                                                    <select name="locations_data[]" id="locations_data" type="text" class="form-control border px-2">
                                                        <?php
                                                        $get_branches = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_branches");
                                                        while ($branch = mysqli_fetch_assoc($get_branches)) {
                                                            $get_locations = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_locations WHERE branch_id='" . $branch['id'] . "'");
                                                        ?>
                                                            <optgroup label="<?php echo $branch['branch_name']; ?>">
                                                                <?php while ($loc = mysqli_fetch_assoc($get_locations)) { ?>
                                                                    <option value="<?php echo $loc['id']; ?>" <?php echo ($loc['id'] == $location['location_id']) ? 'SELECTED' : ''; ?>><?php echo $loc['name']; ?></option>
                                                                <?php } ?>
                                                            </optgroup>
                                                        <?php } ?>
                                                    </select>
                                                    <i class="fas fa-remove me-2 text-danger" role="button" onclick="$(this).parent().parent().remove()"></i>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <button type="button" class="btn btn-success col-12 mt-3 mb-0" onclick="add_location(this)">اضافة منطقة</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2 clear-after-success">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <p class="text-center fw-bold text-dark">شروط التصنيفات</p>
                                    <div class="d-flex justify-content-evenly hide-radio" data-toggle="buttons">
                                        <label class="btn btn-secondary" for="category_type1">
                                            <input type="radio" name="category_type" value="0" id="category_type1" <?php echo ($discount['categories_type'] == 0) ? 'CHECKED' : ''; ?>> بدون شروط
                                        </label>
                                        <label class="btn btn-secondary" for="category_type2">
                                            <input type="radio" name="category_type" value="1" id="category_type2" <?php echo ($discount['categories_type'] == 1) ? 'CHECKED' : ''; ?>> ساري على تصنيفات محددة
                                        </label>
                                        <label class="btn btn-secondary" for="category_type3">
                                            <input type="radio" name="category_type" value="2" id="category_type3" <?php echo ($discount['categories_type'] == 2) ? 'CHECKED' : ''; ?>> لا يسري على تصنيفات محددة
                                        </label>
                                    </div>
                                    <div>
                                        <?php while ($categ = mysqli_fetch_assoc($get_discount_cat)) {; ?>
                                            <div>
                                                <label for="categories_data" class="font-weight-bold text-dark">اختر التصنيف:</label>
                                                <div class="d-flex align-items-center my-0">
                                                    <select name="categories_data[]" id="categories_data" type="text" class="form-control border px-2">
                                                        <?php
                                                        $get_cat = mysqli_query($GLOBALS['conn'], "SELECT *, (SELECT COUNT(*) FROM food_items WHERE food_items.cat_id = food_categories.id) AS count FROM food_categories");
                                                        while ($cat = mysqli_fetch_assoc($get_cat)) {
                                                        ?>
                                                            <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $categ['category_id']) ? 'SELECTED' : ''; ?>><?php echo $cat['category_name']; ?> (<?php echo $cat['count']; ?> صنف) - <?php echo ($cat['active'] == 1) ? 'مفعل' : 'غير مفعل'; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <i class="fas fa-remove me-2 text-danger" role="button" onclick="$(this).parent().parent().remove()"></i>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <button type="button" class="btn btn-success col-12 mt-3 mb-0" onclick="add_category(this)">اضافة تصنيف</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2 clear-after-success">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <p class="text-center fw-bold text-dark">شروط الاصناف</p>
                                    <div class="d-flex justify-content-evenly hide-radio" data-toggle="buttons">
                                        <label class="btn btn-secondary" for="items_type1">
                                            <input type="radio" name="items_type" value="0" id="items_type1" <?php echo ($discount['items_type'] == 0) ? 'CHECKED' : ''; ?>> بدون شروط
                                        </label>
                                        <label class="btn btn-secondary" for="items_type2">
                                            <input type="radio" name="items_type" value="1" id="items_type2" <?php echo ($discount['items_type'] == 1) ? 'CHECKED' : ''; ?>> ساري على اصناف محددة
                                        </label>
                                        <label class="btn btn-secondary" for="items_type3">
                                            <input type="radio" name="items_type" value="2" id="items_type3" <?php echo ($discount['items_type'] == 2) ? 'CHECKED' : ''; ?>> لا يسري على اصناف محددة
                                        </label>
                                    </div>
                                    <div>
                                        <!--  -->
                                        <?php while ($d_item = mysqli_fetch_assoc($get_discount_items)) { ?>
                                            <div>
                                                <label for="items_data" class="font-weight-bold text-dark">اختر النصف:</label>
                                                <div class="d-flex align-items-center my-0">
                                                    <select name="items_data[]" id="items_data" type="text" class="form-control border px-2">
                                                        <?php
                                                        $get_cat = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_categories WHERE (SELECT COUNT(*) FROM food_items WHERE food_items.cat_id = food_categories.id) > 0");
                                                        while ($cat = mysqli_fetch_assoc($get_cat)) {
                                                            $get_items = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items WHERE cat_id='" . $cat['id'] . "'");
                                                        ?>
                                                            <optgroup label="<?php echo $cat['category_name']; ?>">
                                                                <?php while ($item = mysqli_fetch_assoc($get_items)) { ?>
                                                                    <option value="<?php echo $item['id']; ?>" <?php echo ($item['id'] == $d_item['item_id']) ? 'SELECTED' : ''; ?>><?php echo $item['title']; ?> - <?php echo ($item['active'] == 1) ? 'مفعل' : 'غير مفعل'; ?></option>
                                                                <?php } ?>
                                                            </optgroup>
                                                        <?php } ?>
                                                    </select>
                                                    <i class="fas fa-remove me-2 text-danger" role="button" onclick="$(this).parent().parent().remove()"></i>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <button type="button" class="btn btn-success col-12 mt-3 mb-0" onclick="add_item(this)">اضافة صنف</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2 clear-after-success">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <p class="text-center fw-bold text-dark">شروط العملاء</p>
                                    <div class="d-flex justify-content-evenly hide-radio" data-toggle="buttons">
                                        <label class="btn btn-secondary" for="phone_type1">
                                            <input type="radio" name="phone_type" value="0" id="phone_type1" <?php echo ($discount['phone'] == 0) ? 'CHECKED' : ''; ?>> بدون شروط
                                        </label>
                                        <label class="btn btn-secondary" for="phone_type2">
                                            <input type="radio" name="phone_type" value="1" id="phone_type2" <?php echo ($discount['phone'] == 1) ? 'CHECKED' : ''; ?>> ساري لعملاء محددين
                                        </label>
                                        <label class="btn btn-secondary" for="phone_type3">
                                            <input type="radio" name="phone_type" value="2" id="phone_type3" <?php echo ($discount['phone'] == 2) ? 'CHECKED' : ''; ?>> لا يسري على عملاء محددين
                                        </label>
                                    </div>
                                    <div>
                                        <!--  -->
                                        <?php while ($d_phone = mysqli_fetch_assoc($get_discount_phones)) { ?>
                                            <div>
                                                <label class="font-weight-bold text-dark">ادخل رقم العميل:</label>
                                                <div class="d-flex align-items-center my-0">
                                                    <input value="<?php echo $d_phone['phone']; ?>" name="phones_data[]" class="form-control border px-2 d-flex align-items-center my-0" placeholder="رقم العميل">
                                                    <i class="fas fa-remove me-2 text-danger" role="button" onclick="$(this).parent().parent().remove()"></i>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <button type="button" class="btn btn-success col-12 mt-3 mb-0 spinner-button-loading" onclick="add_phone(this)"><span class="content-button-loading">اضافة عميل</span>
                                            <div class="lds-dual-ring"></div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (check_user_perm(['discounts-edit'])) : ?>
                    <div class="d-flex justify-content-center col-12">
                        <button type="submit" class="btn bg-gradient-success text-white my-1 py-1 col-lg-8 col-md-8 col-sm-6 fw-bold fs-5">حفظ</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
        <?php include 'temps/footer.php'; ?>
    </main>

    <?php include 'temps/jslibs.php'; ?>
    <script>
        $("form#save-discount").submit(function(e) {
            e.preventDefault()
            var form = this
            var data = new FormData(form)
            data.append("id", <?php echo $id; ?>)
            $.ajax({
                url: 'ajax/save-discount.php',
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
                    if (response.msg == "success") {
                        Swal.fire({
                            icon: "success",
                            text: "تم حفظ التعديلات بنجاح"
                        })
                    } else if (response.msg == "error") {
                        Swal.fire({
                            icon: "error",
                            text: response.body
                        })
                    }
                    $(form).find("button[type='submit']").prop("disabled", false)
                }
            })
        })

        $("input[name='active']").change(function() {
            if ($(this).val() == 2) {
                $("#active_period").slideDown();
            } else {
                $("#active_period").slideUp();
            }
        })

        function add_location(me) {
            $(me).before(`
            <div>
                <label for="locations_data" class="font-weight-bold text-dark">اختر المنطقة:</label>
                <div class="d-flex align-items-center my-0">
                    <select name="locations_data[]" id="locations_data" type="text" class="form-control border px-2">   
                        <?php
                        $get_branches = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_branches");
                        while ($branch = mysqli_fetch_assoc($get_branches)) {
                            $get_locations = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_locations WHERE branch_id='" . $branch['id'] . "'");
                        ?>
                            <optgroup label="<?php echo $branch['branch_name']; ?>">
                                <?php while ($loc = mysqli_fetch_assoc($get_locations)) { ?>
                                    <option value="<?php echo $loc['id']; ?>"><?php echo $loc['name']; ?></option>
                                <?php } ?>
                            </optgroup>  
                        <?php } ?>
                    </select>
                    <i class="fas fa-remove me-2 text-danger" role="button" onclick="$(this).parent().parent().remove()"></i>
                </div>
            </div>
        `)
        }

        function add_category(me) {
            $(me).before(`
            <div>
                <label for="categories_data" class="font-weight-bold text-dark">اختر التصنيف:</label>
                <div class="d-flex align-items-center my-0">
                    <select name="categories_data[]" id="categories_data" type="text" class="form-control border px-2">   
                        <?php
                        $get_cat = mysqli_query($GLOBALS['conn'], "SELECT *, (SELECT COUNT(*) FROM food_items WHERE food_items.cat_id = food_categories.id) AS count FROM food_categories");
                        while ($cat = mysqli_fetch_assoc($get_cat)) {
                        ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['category_name']; ?> (<?php echo $cat['count']; ?> صنف) - <?php echo ($cat['active'] == 1) ? 'مفعل' : 'غير مفعل'; ?></option>
                        <?php } ?>
                    </select>
                    <i class="fas fa-remove me-2 text-danger" role="button" onclick="$(this).parent().parent().remove()"></i>
                </div>
            </div>
        `)
        }

        function add_item(me) {
            $(me).before(`
            <div>
                <label for="items_data" class="font-weight-bold text-dark">اختر النصف:</label>
                <div class="d-flex align-items-center my-0">
                    <select name="items_data[]" id="items_data" type="text" class="form-control border px-2">
                        <?php
                        $get_cat = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_categories WHERE (SELECT COUNT(*) FROM food_items WHERE food_items.cat_id = food_categories.id) > 0");
                        while ($cat = mysqli_fetch_assoc($get_cat)) {
                            $get_items = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items WHERE cat_id='" . $cat['id'] . "'");
                        ?>
                            <optgroup label="<?php echo $cat['category_name']; ?>">
                                <?php while ($item = mysqli_fetch_assoc($get_items)) { ?>
                                    <option value="<?php echo $item['id']; ?>"><?php echo $item['title']; ?> - <?php echo ($item['active'] == 1) ? 'مفعل' : 'غير مفعل'; ?></option>
                                <?php } ?>
                            </optgroup>                                          
                        <?php } ?>
                    </select>
                    <i class="fas fa-remove me-2 text-danger" role="button" onclick="$(this).parent().parent().remove()"></i>
                </div>
            </div>
        `)
        }

        function add_phone(me) {
            $(me).before(`
            <div>
                <label class="font-weight-bold text-dark">ادخل رقم العميل:</label>
                <div class="d-flex align-items-center my-0">
                    <input name="phones_data[]" class="form-control border px-2 d-flex align-items-center my-0" placeholder="رقم العميل">
                    <i class="fas fa-remove me-2 text-danger" role="button" onclick="$(this).parent().parent().remove()"></i>
                </div>
            </div>
        `)
        }

        $("input[name='active']").click(function() {
            var vl = $(this).val()
            if (vl == 2) {
                $("#active_period").css("display", "flex")
            } else {
                $("#active_period").css("display", "none")
            }
        })
    </script>
</body>

</html>