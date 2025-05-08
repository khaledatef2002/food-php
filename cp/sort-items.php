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
    if (!check_user_perm(['items-edit'])) :
        header('Location: 403.php');
        exit;
    endif;
    if (isset($_GET['cat'])) {
        $id = filter_var($_GET['cat'], FILTER_SANITIZE_NUMBER_INT);
        $check_cat_exist = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_categories WHERE id=$id");
        if (mysqli_num_rows($check_cat_exist) == 0) {
            exit("No category exist");
        } else {
            $cat_info = mysqli_fetch_assoc($check_cat_exist);
        }
    } else {
        exit("No id");
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
            <div class="row my4 justify-content-center">
                <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
                    <div class="card text-center">
                        <h3 class="my-0 font-weight-bold py-2 fs-4">اصناف <?php echo $cat_info['category_name']; ?></h3>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row my-4 justify-content-center gap-2">
                    <a class="btn btn-info py-2" style="width:fit-content;" href="show-items.php">العودة</a>
                    <a class="btn btn-warning py-2" style="width:fit-content;" data-bs-toggle="modal" data-bs-target="#items-order-choose">تصنيف اخر</a>
                </div>
                <div id="sortable" class="row mb-4 justify-content-center">
                    <?php
                    $get_item = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items WHERE cat_id=$id ORDER BY SORT ASC");
                    while ($item = mysqli_fetch_assoc($get_item)) {
                    ?>
                        <div class="row mb-1" data-id="<?php echo $item['id']; ?>" data-sort="<?php echo $item['sort']; ?>">
                            <div class="card mb-2">
                                <div class="card-body p-3 py-1 d-flex flex-row align-items-center justify-content-between">
                                    <span class="badge badge-sm bg-gradient-primary mb-1 cursor-pointer">
                                        <i class="fas fa-grip-horizontal"></i>
                                        <span class="order"><?php echo $item['sort']; ?></span>
                                        <i class="fas fa-grip-horizontal"></i>
                                    </span>
                                    <div class="vr"></div>
                                    <p class="my-0">
                                        <img style='height:40px;border-radius:10px;margin-left:10px;' src='../<?php echo $item['img']; ?>'>
                                        <?php echo $item['title']; ?>
                                    </p>
                                    <div class="vr"></div>
                                    <p class="my-0">
                                        <?php echo ($item['active'] == 1) ? '<span class="badge bg-success px-3">يعمل</span>' : '<span class="badge bg-danger px-3">لا يعمل</span>'; ?>
                                    </p>
                                    <div class="vr"></div>
                                    <div class="d-flex justify-content-between">
                                        <a class="btn bg-gradient-success text-white my-1 py-1" href="edit-item.php?id=<?php echo $item['id']; ?>">تعديل</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>
                <?php include 'temps/footer.php'; ?>
            </div>
    </main>

    <!-- Start Modal -->
    <di id="items-order-choose" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">اختر التصنيف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="height:70vh;overflow:auto;">
                    <?php
                    $get_cats = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_categories WHERE active = 1 ORDER BY sort ASC");
                    if (mysqli_num_rows($get_cats) > 0) {
                    ?>
                        <ul list-style="none">
                            <h3>مُفعل:</h3>
                            <?php
                            while ($cat = mysqli_fetch_assoc($get_cats)) {
                            ?>
                                <a class="btn btn-info d-block" href="sort-items.php?cat=<?php echo $cat['id']; ?>">
                                    <li><?php echo $cat['category_name']; ?></li>
                                </a>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                    <?php
                    $get_cats = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_categories WHERE active = 0 ORDER BY sort ASC");
                    if (mysqli_num_rows($get_cats) > 0) {
                    ?>
                        <ul list-style="none">
                            <h3>مُفعل:</h3>
                            <?php
                            while ($cat = mysqli_fetch_assoc($get_cats)) {
                            ?>
                                <a class="btn btn-info d-block" href="sort-items.php?cat=<?php echo $cat['id']; ?>">
                                    <li><?php echo $cat['category_name']; ?></li>
                                </a>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
        </div>
        <!-- End Modal -->

        <?php include 'temps/jslibs.php'; ?>
        <script>
            $(function() {
                $("#sortable").sortable({
                    handle: "span.badge",
                    update: function(event, ui) {
                        for (var i = 0; i < $("#sortable > div").length; i++) {
                            var id = $(`#sortable > div`).eq(i).attr("data-id")
                            var sort = $(`#sortable > div`).eq(i).attr("data-sort")
                            if (sort != (i + 1)) {
                                // Remember to valid errors
                                $.post("ajax/update-item-sort.php", {
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

            function category_re_order() {
                for (var i = 0; i < $("#sortable > div").length; i++) {
                    $(`#sortable > div`).eq(i).find("span.order").text(i + 1)
                    $(`#sortable > div`).eq(i).attr("data-sort", i + 1)
                }
            }
        </script>
</body>

</html>