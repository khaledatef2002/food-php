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
    if (!check_user_perm(['categories-view'])) :
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
            <?php if (check_user_perm(['categories-add'])) : ?>
                <div class="row my-4 justify-content-center">
                    <a class="btn btn-info py-2" style="width:fit-content;" data-bs-toggle="modal" data-bs-target="#add_new_category_modal">اضافة جديد</a>
                </div>
            <?php endif; ?>
            <div id="sortable" class="row my-4 justify-content-center">
                <?php
                $get_cat = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_categories ORDER BY SORT ASC");
                while ($cat = mysqli_fetch_assoc($get_cat)) {
                ?>
                    <div class="row my-1" data-id="<?php echo $cat['id']; ?>" data-sort="<?php echo $cat['sort']; ?>">
                        <div class="card mb-2">
                            <div class="card-body p-3 py-1 d-flex flex-row align-items-center justify-content-between">
                                <span class="badge badge-sm bg-gradient-primary mb-1 cursor-pointer">
                                    <?php if (check_user_perm(['categories-edit'])) : ?> <i class="fas fa-grip-horizontal"></i> <?php endif; ?>
                                    <span class="order"><?php echo $cat['sort']; ?></span>
                                    <?php if (check_user_perm(['categories-edit'])) : ?><i class="fas fa-grip-horizontal"></i> <?php endif; ?>
                                </span>
                                <div class="vr mx-3"></div>
                                <p class="my-0">
                                    <?php echo $cat['category_name']; ?>
                                </p>
                                <div class="vr"></div>
                                <p class="my-0">
                                    عدد الاصناف:
                                    <?php
                                    $get_items = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items WHERE cat_id='" . $cat['id'] . "'");
                                    echo mysqli_num_rows($get_items);
                                    ?>
                                </p>
                                <div class="vr"></div>
                                <?php if (check_user_perm(['categories-edit'])) : ?>
                                    <p class="my-0 form-check d-flex">
                                        التفعيل:
                                        <input class="form-check-input" type="checkbox" name="cat<?php echo $cat['id']; ?>" onchange="change_cat_active(<?php echo $cat['id']; ?>,this)" style="cursor:pointer;" <?php echo ($cat['active'] == 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label text-bold text-white mx-1" for="flexRadioDefault1">
                                    </p>
                                <?php endif; ?>
                                <div class="vr"></div>
                                <?php if (check_user_perm(['categories-edit']) || check_user_perm(['categories-remove'])) : ?>
                                    <div class="d-flex justify-content-between">
                                        <?php if (check_user_perm(['categories-edit'])) : ?>
                                            <button class="btn bg-gradient-success text-white my-1 py-1" onclick="show_edit_category_modal(this)">تعديل</button>
                                        <?php endif; ?>
                                        <?php if (check_user_perm(['categories-remove'])) : ?>
                                            <button class="btn bg-gradient-danger text-white my-1 py-1 mx-2" onclick="remove_category(<?php echo $cat['id']; ?>,this)">حذف</button>
                                        <?php endif; ?>
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

    <!-- Start Add New Category modal -->
    <div class="modal fade" data-bs-backdrop="static" id="add_new_category_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة تصنيف جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-wrap justify-content-start column-gap-1">
                    <div class="px-1 col-12" style='flex-grow:1'>
                        <label class="text-bold">اسم التصنيف:</label>
                        <input name="category_name" class="form-control border px-2" type="text" placeholder="اسم التصنيف">
                    </div>
                    <div class="px-1 col-12 form-check mt-2" style='flex-grow:1'>
                        <label class="text-bold">الحالة الابتدائية:</label>
                        <input class="form-check-input" type="checkbox" name="add_category_active" CHECKED>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="add_new_category()">اضافة</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add New Category modal -->


    <!-- Start Edit Social modal -->
    <div class="modal fade" data-bs-backdrop="static" id="edit_category_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تعديل التصنيف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-wrap justify-content-start column-gap-1">
                    <div class="px-1 col-12" style='flex-grow:1'>
                        <label class="text-bold">اسم التصنيف:</label>
                        <input name="category_name" class="form-control border px-2" type="text" placeholder="اسم التصنيف">
                    </div>
                    <div class="px-1 col-12 form-check mt-2" style='flex-grow:1'>
                        <label class="text-bold">الحالة الابتدائية:</label>
                        <input class="form-check-input" type="checkbox" name="category_active">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="update_category()">حفظ</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Edit Social modal -->

    <?php include 'temps/jslibs.php'; ?>
    <script>
        function show_edit_category_modal(me) {
            var id = $(me).parent().parent().parent().parent().attr("data-id")
            var name = $(me).parent().parent().find("p").eq(0).text().trim()
            $("#edit_category_modal").attr("data-id", id)
            $(`#edit_category_modal input[name='category_name']`).val(name)
            $("#edit_category_modal").modal("show")
        }

        function update_category() {
            var id = $("#edit_category_modal").attr("data-id")
            var name = $(`#edit_category_modal input[name='category_name']`).val()
            $.post("ajax/update_category.php", {
                id: id,
                name: name
            }, function(res) {
                if (res != "error") {
                    $(`#sortable > div[data-id='${id}'] p`).eq(0).text(name)
                }
            })
        }

        function add_new_category() {
            var name = $("#add_new_category_modal").find("input[name='category_name']").val()
            var active = $("#add_new_category_modal").find("input[name='add_category_active']").prop("checked")
            $.post("ajax/add_new_category.php", {
                name: name,
                active: active
            }, function(res) {
                if (res !== "error") {
                    $("#sortable").append(res)
                }
            })
        }
        <?php if (check_user_perm(['categories-edit'])) : ?>
            $(function() {
                $("#sortable").sortable({
                    handle: "span.badge",
                    update: function(event, ui) {
                        for (var i = 0; i < $("#sortable > div").length; i++) {
                            var id = $(`#sortable > div`).eq(i).attr("data-id")
                            var sort = $(`#sortable > div`).eq(i).attr("data-sort")
                            if (sort != (i + 1)) {
                                // Remember to valid errors
                                $.post("ajax/update-category-order.php", {
                                    id: id,
                                    sort: (i + 1)
                                })
                            }
                        }
                        category_re_order()
                    }
                });
                $("#sortable span").disableSelection();

            });
        <?php endif; ?>

        function category_re_order() {
            for (var i = 0; i < $("#sortable > div").length; i++) {
                $(`#sortable > div`).eq(i).find("span.order").text(i + 1)
                $(`#sortable > div`).eq(i).attr("data-sort", i + 1)
            }
        }

        function remove_category(id, me) {
            $.post("ajax/remove-category.php", {
                id: id
            }, function(res) {
                //Rembmer to valid errors
                if (res != "error") {
                    $(me).parent().parent().parent().parent().remove()
                    for (var i = 0; i < $("#sortable > div").length; i++) {
                        var id = $(`#sortable > div`).eq(i).attr("data-id")
                        var sort = $(`#sortable > div`).eq(i).attr("data-sort")
                        if (sort != (i + 1)) {
                            // Remember to valid errors
                            $.post("ajax/update-category-order.php", {
                                id: id,
                                sort: (i + 1)
                            })
                        }
                    }
                    category_re_order()
                }
            })
        }
    </script>
</body>

</html>