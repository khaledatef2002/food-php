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
    if (!check_user_perm(['working-period-view'])) :
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
            <?php if (check_user_perm(['working-period-add'])) : ?>
                <div class="row my-4 justify-content-center">
                    <a class="btn btn-info py-2" style="width:fit-content;" data-bs-toggle="modal" data-bs-target="#add_new_period">اضافة جديد</a>
                </div>
            <?php endif; ?>
            <div id="parent" class="row my-4 justify-content-center">
                <?php
                $get_periods = mysqli_query($GLOBALS['conn'], "SELECT * FROM work_periods ORDER BY id ASC");
                while ($period = mysqli_fetch_assoc($get_periods)) {
                    $day_name = ['السبت', 'الاحد', 'الاثنين', 'الثلاثاء', 'الاربعاء', 'الخميس', 'الجمعة'];
                    $day = [$period['from_date'][0], $period['to_date'][0]];
                    $hours = [$period['from_date'][1] . $period['from_date'][2], $period['to_date'][1] . $period['to_date'][2]];
                    $min = [$period['from_date'][3] . $period['from_date'][4], $period['to_date'][3] . $period['to_date'][4]];
                    $time = [$hours[0] . ":" . $min[0], $hours[1] . ":" . $min[1]];
                ?>
                    <div data-id="<?php echo $period['id']; ?>" class="col-lg-3 col-md-4 col-sm-6 mb-2">
                        <div class="card mb-2 h-100 align-content-between">
                            <div class="card-body p-3 pb-1 d-flex flex-column justify-content-between">
                                <p class="font-weight-bold my-0">
                                    من:
                                    <select name="from_day" class="form-control border px-1 mb-2">
                                        <option value="0" <?php echo ($day[0] == 0) ? 'SELECTED' : ''; ?>>السبت</option>
                                        <option value="1" <?php echo ($day[0] == 1) ? 'SELECTED' : ''; ?>>الاحد</option>
                                        <option value="2" <?php echo ($day[0] == 2) ? 'SELECTED' : ''; ?>>الاثنين</option>
                                        <option value="3" <?php echo ($day[0] == 3) ? 'SELECTED' : ''; ?>>الثلاثاء</option>
                                        <option value="4" <?php echo ($day[0] == 4) ? 'SELECTED' : ''; ?>>الاربعاء</option>
                                        <option value="5" <?php echo ($day[0] == 5) ? 'SELECTED' : ''; ?>>الخميس</option>
                                        <option value="6" <?php echo ($day[0] == 6) ? 'SELECTED' : ''; ?>>الجمعة</option>
                                    </select>
                                    <input name="from_time" class="form-control border px-1" type="time" value="<?php echo $time[0]; ?>">
                                </p>
                                <p class="font-weight-bold my-0">
                                    الى:
                                    <select name="to_day" class="form-control border px-1 mb-2">
                                        <option value="0" <?php echo ($day[1] == 0) ? 'SELECTED' : ''; ?>>السبت</option>
                                        <option value="1" <?php echo ($day[1] == 1) ? 'SELECTED' : ''; ?>>الاحد</option>
                                        <option value="2" <?php echo ($day[1] == 2) ? 'SELECTED' : ''; ?>>الاثنين</option>
                                        <option value="3" <?php echo ($day[1] == 3) ? 'SELECTED' : ''; ?>>الثلاثاء</option>
                                        <option value="4" <?php echo ($day[1] == 4) ? 'SELECTED' : ''; ?>>الاربعاء</option>
                                        <option value="5" <?php echo ($day[1] == 5) ? 'SELECTED' : ''; ?>>الخميس</option>
                                        <option value="6" <?php echo ($day[1] == 6) ? 'SELECTED' : ''; ?>>الجمعة</option>
                                    </select>
                                    <input name="to_time" class="form-control border px-1" type="time" value="<?php echo $time[1]; ?>">
                                </p>
                                <?php if (check_user_perm(['working-period-remove']) || check_user_perm(['working-period-edit'])) : ?>
                                    <div class="m-auto mb-0 mt-1">
                                        <?php if (check_user_perm(['working-period-edit'])) : ?>
                                            <button class="btn btn-success mx-1" onclick="save_period(<?php echo $period['id']; ?>, this)">حفظ</button>
                                        <?php endif; ?>
                                        <?php if (check_user_perm(['working-period-remove'])) : ?>
                                            <button class="btn btn-danger mx-1" onclick="remove_period(<?php echo $period['id']; ?>, this)">حذف</button>
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
    <div class="modal fade" data-bs-backdrop="static" id="add_new_period" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة فترة عمل جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="font-weight-bold my-0">
                        من:
                        <select name="from_day" class="form-control border px-1 mb-2">
                            <option value="0">السبت</option>
                            <option value="1">الاحد</option>
                            <option value="2">الاثنين</option>
                            <option value="3">الثلاثاء</option>
                            <option value="4">الاربعاء</option>
                            <option value="5">الخميس</option>
                            <option value="6">الجمعة</option>
                        </select>
                        <input name="from_time" class="form-control border px-1" type="time">
                    </p>
                    <p class="font-weight-bold my-0">
                        الى:
                        <select name="to_day" class="form-control border px-1 mb-2">
                            <option value="0">السبت</option>
                            <option value="1">الاحد</option>
                            <option value="2">الاثنين</option>
                            <option value="3">الثلاثاء</option>
                            <option value="4">الاربعاء</option>
                            <option value="5">الخميس</option>
                            <option value="6">الجمعة</option>
                        </select>
                        <input name="to_time" class="form-control border px-1" type="time">
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="add_new_period()">اضافة</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add New Social modal -->

    <?php include 'temps/jslibs.php'; ?>
    <script>
        function remove_period(id, me) {
            $.post("ajax/remove_periods.php", {
                id: id
            }, function(res) {
                if (res != "error") {
                    $(me).parent().parent().parent().parent().remove()
                }
            })
        }

        function add_new_period() {
            var from_day = $("#add_new_period select[name='from_day'] option:selected").val()
            var from_time = $("#add_new_period input[name='from_time']").val()
            var to_day = $("#add_new_period select[name='to_day'] option:selected").val()
            var to_time = $("#add_new_period input[name='to_time']").val()
            $.post("ajax/add-period.php", {
                fromD: from_day,
                fromT: from_time,
                toD: to_day,
                toT: to_time
            }, function(res) {
                if (res != "error") {
                    $("#parent").append(res)
                }
            })
        }

        function save_period(id, me) {
            var from_day = $(me).parent().parent().find("select[name='from_day'] option:selected").val()
            var from_time = $(me).parent().parent().find("input[name='from_time']").val()
            var to_day = $(me).parent().parent().find("select[name='to_day'] option:selected").val()
            var to_time = $(me).parent().parent().find("input[name='to_time']").val()

            $.post("ajax/save-period.php", {
                id: id,
                fromD: from_day,
                fromT: from_time,
                toD: to_day,
                toT: to_time
            }, function(res) {
                if (res != "error") {
                    $(me).parent().parent().parent().notify("تم التحديث بنجاح", {
                        className: 'success',
                        elementPosition: 'top right'
                    })
                }
            })
        }
    </script>
</body>

</html>