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
    if (!check_user_perm(['roles-view'])) :
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
            <?php if (check_user_perm(['roles-add'])) : ?>
                <div class="row my-4 justify-content-center">
                    <a class="btn btn-info py-2" style="width:fit-content;" href="add-roles.php">اضافة جديد</a>
                </div>
            <?php endif; ?>
            <div id="sortable" class="row my-4 justify-content-center">
                <?php
                $get_roles = mysqli_query($GLOBALS['conn'], "SELECT * FROM roles");
                while ($role = mysqli_fetch_assoc($get_roles)) {
                ?>
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-2" data-id="<?php echo $role['id']; ?>">
                        <div class="card mb-2 h-100">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <span class="font-weight-bold text-dark">
                                        مسمى الدور:
                                    </span>
                                    <span>
                                        <?php echo $role['name']; ?>
                                    </span>
                                </div>
                                <?php
                                $get_accounts_count = mysqli_query($GLOBALS['conn'], "SELECT * FROM panel_user WHERE role_id={$role['id']}");
                                ?>
                                <div>
                                    <span class="font-weight-bold text-dark">
                                        عدد الحسابات:
                                    </span>
                                    <span>
                                        (<?php echo mysqli_num_rows($get_accounts_count); ?>)
                                    </span>
                                </div>
                                <?php if (check_user_perm(['roles-edit']) || check_user_perm(['roles-remove'])) : ?>
                                    <div class="d-flex justify-content-between">
                                        <?php if (check_user_perm(['roles-edit'])) : ?>
                                            <button class="btn bg-gradient-success text-white my-1 py-1"><a href="edit-role.php?id=<?php echo $role['id']; ?>" class="text-white">تعديل</a></button>
                                        <?php endif; ?>
                                        <?php if (check_user_perm(['roles-remove'])) : ?>
                                            <button class="btn bg-gradient-danger text-white my-1 py-1" onclick="remove_role(<?php echo $role['id']; ?>,this)">حذف</button>
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

    <?php include 'temps/jslibs.php'; ?>
    <script>
        function remove_role(id, me) {
            $.post("ajax/remove-role.php", {
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