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
    if (!check_user_perm(['main-page-icon-view'])) :
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
            <div id="parent" class="row my-4 justify-content-center">
                <?php
                $main_page_icon = mysqli_query($GLOBALS['conn'], "SELECT * FROM main_page_icons");
                while ($icon = mysqli_fetch_assoc($main_page_icon)) {
                ?>
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-2" data-id="<?php echo $icon['id']; ?>">
                        <div class="card mb-2 h-100">
                            <div class="card-body p-3 pb-1 d-flex flex-column">
                                <div class="my-0">
                                    <div class="icon" style="width:100%;height:fit-content;">
                                        <?php
                                        switch ($icon['icon_name']) {
                                            case 'order':
                                                include "../imgs/Delivery2.svg";
                                                break;
                                            case 'offers':
                                                include '../imgs/Offer.svg';
                                                break;
                                            case 'menu':
                                                include '../imgs/menu.svg';
                                                break;
                                            case 'social':
                                                include "../imgs/socialmedia.svg";
                                                break;
                                            case 'safwa':
                                                include '../imgs/safwa.svg';
                                                break;
                                            case 'comments':
                                                include '../imgs/comments.svg';
                                                break;
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php if (check_user_perm(['main-page-icon-edit'])) : ?>
                                    <p class="my-0 mt-1 form-check px-0 mx-auto">
                                        التفعيل:
                                        <input id="icon<?php echo $icon['id']; ?>" class="form-check-input" type="checkbox" onchange="change_icon_active(<?php echo $icon['id']; ?>,this)" style="cursor:pointer;" <?php echo ($icon['icon_active'] == '1') ? 'checked' : '' ?>>
                                        <label class="form-check-label text-bold text-white mx-1" for="icon<?php echo $icon['id']; ?>">
                                    </p>
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
        function change_icon_active(id, me) {
            var formData = new FormData();
            formData.append("id", id)
            formData.append("active", $(me).prop("checked"))
            $.ajax({
                url: 'ajax/change-main-page-icon-active.php',
                type: 'POST',
                data: formData,
                //processType: false, WRONG syntax
                processData: false,
                contentType: false,
                success: function(data) {
                    console.log(data)
                },
            })
        }
    </script>
</body>

</html>