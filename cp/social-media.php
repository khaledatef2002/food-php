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
    if (!check_user_perm(['social-page-view'])) :
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
            <?php if (check_user_perm(['social-page-add'])) : ?>
                <div class="row my-4 justify-content-center">
                    <a class="btn btn-info py-2" style="width:fit-content;" data-bs-toggle="modal" data-bs-target="#add_new_social_modal">اضافة جديد</a>
                </div>
            <?php endif; ?>
            <div id="sortable" class="row my-4 justify-content-center">
                <?php
                $get_social = mysqli_query($GLOBALS['conn'], "SELECT * FROM social_media ORDER BY SORT ASC");
                while ($social = mysqli_fetch_assoc($get_social)) {
                ?>
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-2" data-id="<?php echo $social['id']; ?>" data-sort="<?php echo $social['sort']; ?>">
                        <div class="card mb-2 h-100">
                            <div class="card-body p-3 d-flex flex-column">
                                <div class="my-0">
                                    <span class="badge badge-sm bg-gradient-primary mx-auto mb-1 d-block cursor-pointer">
                                        <i class="fas fa-grip-horizontal"></i>
                                        <span class="order"><?php echo $social['sort']; ?></span>
                                        <i class="fas fa-grip-horizontal"></i>
                                    </span>
                                    <div class="icon" style="width:100%;height:fit-content;">
                                        <?php include "../" . $social['img_url']; ?>
                                    </div>
                                </div>
                                <p class="font-weight-bold my-0 fs-6 d-flex align-items-center justify-content-center flex-fill text-break text-center">
                                    <a href="<?php echo $social['link']; ?>"><?php echo $social['link']; ?></a>
                                </p>
                                <?php if (check_user_perm(['social-page-edit']) || check_user_perm(['social-page-remove'])) : ?>
                                    <div class="d-flex justify-content-between">
                                        <?php if (check_user_perm(['social-page-edit'])) : ?>
                                            <button class="btn bg-gradient-success text-white my-1 py-1" onclick="show_edit_social_modal(<?php echo $social['id']; ?>, '<?php echo trim($social['img_url']); ?>', '<?php echo trim($social['link']); ?>')">تعديل</button>
                                        <?php endif; ?>
                                        <?php if (check_user_perm(['social-page-remove'])) : ?>
                                            <button class="btn bg-gradient-danger text-white my-1 py-1" onclick="remove_social(<?php echo $social['id']; ?>,this)">حذف</button>
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

    <!-- Start Add New Social modal -->
    <div class="modal fade" data-bs-backdrop="static" id="add_new_social_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة وسيلة تواصل جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-wrap justify-content-start column-gap-1">
                    <div class="px-1 col-sm-6" style='flex-grow:1'>
                        <label class="text-bold">اختر وسيلة:</label>
                        <select name="social-icon" class="form-select mb-2 border">
                            <option value="imgs/tik-tok.svg">tik tok</option>
                            <option value="imgs/twitter.svg">twitter</option>
                            <option value="imgs/instagram.svg">instagram</option>
                            <option value="imgs/facebook-group.svg">facebook group</option>
                            <option value="imgs/facebook.svg">facebook</option>
                            <option value="imgs/telegram.svg">telegram</option>
                        </select>
                        <input name="social-url" class="form-control border px-2" type="url" placeholder="الرابط الالكتروني">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="add_new_social()">اضافة</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add New Social modal -->


    <!-- Start Edit Social modal -->
    <div class="modal fade" data-bs-backdrop="static" id="edit_social_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تعديل وسيلة التواصل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-wrap justify-content-start column-gap-1">
                    <div class="px-1 col-sm-6" style='flex-grow:1'>
                        <label class="text-bold">اختر وسيلة:</label>
                        <select name="social-icon" class="form-select mb-2 border">
                            <option value="imgs/tik-tok.svg">tik tok</option>
                            <option value="imgs/twitter.svg">twitter</option>
                            <option value="imgs/instagram.svg">instagram</option>
                            <option value="imgs/facebook-group.svg">facebook group</option>
                            <option value="imgs/facebook.svg">facebook</option>
                            <option value="imgs/telegram.svg">telegram</option>
                        </select>
                        <input name="social-url" class="form-control border px-2" type="url" placeholder="الرابط الالكتروني">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="update_social()">حفظ</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Edit Social modal -->

    <?php include 'temps/jslibs.php'; ?>
    <script>
        function show_edit_social_modal(id, img, link) {
            $("#edit_social_modal").attr("data-id", id)
            $("#edit_social_modal select option:selected").removeAttr("selected")
            $(`#edit_social_modal select option[value='${img}']`).attr("selected", "selected")
            $(`#edit_social_modal input[name='social-url']`).val(link)
            $("#edit_social_modal").modal("show")
        }

        function update_social() {
            var id = $("#edit_social_modal").attr("data-id")
            var icon = $("#edit_social_modal").find("select[name='social-icon'] option:selected").val()
            var link = $("#edit_social_modal").find("input[name='social-url']").val()
            $.post("ajax/update_social.php", {
                id: id,
                icon: icon,
                link: link
            }, function(res) {
                if (res != "error") {
                    $(`#sortable > div[data-id='${id}'] div.icon`).html(res)
                    $(`#sortable > div[data-id='${id}'] p a`).attr("href", link)
                    $(`#sortable > div[data-id='${id}'] p a`).text(link)
                }
            })
        }

        function add_new_social() {
            var icon = $("#add_new_social_modal").find("select[name='social-icon'] option:selected").val()
            var link = $("#add_new_social_modal").find("input[name='social-url']").val()
            $.post("ajax/add_new_social.php", {
                icon: icon,
                link: link
            }, function(res) {
                if (res !== "error") {
                    $("#sortable").append(res)
                }
            })
        }
        $(function() {
            $("#sortable").sortable({
                handle: "span.badge",
                update: function(event, ui) {
                    for (var i = 0; i < $("#sortable > div").length; i++) {
                        var id = $(`#sortable > div`).eq(i).attr("data-id")
                        var sort = $(`#sortable > div`).eq(i).attr("data-sort")
                        if (sort != (i + 1)) {
                            // Remember to valid errors
                            $.post("ajax/update-social-order.php", {
                                id: id,
                                sort: (i + 1)
                            })
                        }
                    }
                    social_re_order()
                }
            });
            $("#sortable span").disableSelection();

        });

        function social_re_order() {
            for (var i = 0; i < $("#sortable > div").length; i++) {
                $(`#sortable > div`).eq(i).find("span.order").text(i + 1)
                $(`#sortable > div`).eq(i).attr("data-sort", i + 1)
            }
        }

        function remove_social(id, me) {
            $.post("ajax/remove-social.php", {
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
                            $.post("ajax/update-social-order.php", {
                                id: id,
                                sort: (i + 1)
                            })
                        }
                    }
                    social_re_order()
                }
            })
        }
    </script>
</body>

</html>