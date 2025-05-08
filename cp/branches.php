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
    if (!check_user_perm(['branches-view'])) :
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
            <div class="row my4 justify-content-center">
                <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
                    <div class="card text-center">
                        <h3 class="my-0 font-weight-bold py-2">الــفــروع</h3>
                    </div>
                </div>
            </div>
            <?php if (check_user_perm(['branches-view'])) : ?>
                <div class="row mt-4 justify-content-center">
                    <a class="btn btn-info py-2" style="width:fit-content;" data-bs-toggle="modal" data-bs-target="#add_new_branch">اضافة جديد</a>
                </div>
            <?php endif; ?>
            <div id="parent" class="row my-4 justify-content-center">
                <?php
                $get_branch = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_branches ORDER BY id DESC");
                while ($branch = mysqli_fetch_assoc($get_branch)) {
                ?>
                    <div data-id="<?php echo $branch['id']; ?>" class="col-lg-3 col-md-4 col-sm-6 mb-2">
                        <div class="card mb-2 h-100 align-content-between">
                            <div class="card-body p-3 pb-1 d-flex flex-column justify-content-between">
                                <p class="font-weight-bold my-0">
                                    اسم الفرع:
                                    <input name="branch_name" class="form-control border px-3" type="text" value="<?php echo $branch['branch_name']; ?>">
                                </p>
                                <?php if (check_user_perm(['branches-edit']) || check_user_perm(['branches-remove']) || check_user_perm(['locations-view'])) : ?>
                                    <div class="m-auto mb-0 mt-1">
                                        <?php if (check_user_perm(['locations-view'])) : ?>
                                            <a href="delivery-areas.php?branch=<?php echo $branch['id']; ?>" class="btn btn-primary mx-1">عرض المناطق</a>
                                        <?php endif; ?>
                                        <?php if (check_user_perm(['branches-remove'])) : ?>
                                            <button class="btn btn-danger mx-1" onclick="remove_branch(<?php echo $branch['id']; ?>, this)">إزالة</button>
                                        <?php endif; ?>
                                        <?php if (check_user_perm(['branches-edit'])) : ?>
                                            <button class="btn btn-success mx-1" onclick="save_branch(<?php echo $branch['id']; ?>, this)">حفظ</button>
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
    <div class="modal fade" data-bs-backdrop="static" id="add_new_branch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة فرع جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="font-weight-bold my-0">
                        <label for="branch_name">اسم الفرع</label>
                        <input id="branch_name" name="branch_name" class="form-control border px-1" type="text">
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" onclick="add_new_branch()">اضافة</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add New Social modal -->
    <?php include 'temps/jslibs.php'; ?>
    <script>
        function save_branch(id, me) {
            var name = $(me).parent().parent().find("input[name='branch_name']").val()

            $.post("ajax/save-branch.php", {
                id: id,
                name: name,
            }, function(res) {
                if (res != "error") {
                    Swal.fire({
                        icon: "success",
                        text: "تم تحديث البيانات بنجاح"
                    })
                } else {
                    Swal.fire({
                        icon: "error",
                        text: "فشل تحديث البيانات"
                    })
                }
            })
        }

        function remove_branch(id, me) {
            $.post("ajax/remove-branch.php", {
                id: id,
            }, function(res) {
                if (res == "error") {
                    Swal.fire({
                        icon: "error",
                        text: "فشل إزالة ألفرع"
                    })
                } else {
                    Swal.fire({
                        icon: "success",
                        text: "تم إزالة الفرع بنجاح"
                    })
                    $(me).parent().parent().parent().parent().remove()
                }
            })
        }

        function add_new_branch() {

            var branch_name = $("#add_new_branch input#branch_name").val()

            $.post("ajax/add-branch.php", {
                branch_name: branch_name,
            }, function(res) {
                if (res == "error") {
                    Swal.fire({
                        icon: "error",
                        text: "فشل اضافة الفرع"
                    })
                } else {
                    Swal.fire({
                        icon: "success",
                        text: "تم اضافة الفرع"
                    })
                    $("#add_new_branch input#branch_name").val("")
                    $("#add_new_branch").modal("hide")
                    $("#parent").prepend(`
                        <div data-id="${res}" class="col-lg-3 col-md-4 col-sm-6 mb-2">
                            <div class="card mb-2 h-100 align-content-between">
                                <div class="card-body p-3 pb-1 d-flex flex-column justify-content-between">
                                    <p class="font-weight-bold my-0">
                                        اسم الفرع:
                                        <input name="branch_name" class="form-control border px-3" type="text" value="${branch_name}">
                                    </p>
                                    <div class="m-auto mb-0 mt-1">
                                        <a href="delivery-areas.php?branch=${res}" class="btn btn-primary mx-1">عرض المناطق</a>
                                        <button class="btn btn-danger mx-1" onclick="remove_branch(${res}, this)">إزالة</button>
                                        <button class="btn btn-success mx-1" onclick="save_branch(${res}, this)">حفظ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `)
                }
            })
        }
    </script>
</body>

</html>