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
    if (!check_user_perm(['qrcode-view'])) :
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
            <div id="sortable" class="row my-4 justify-content-center">

                <div class="mb-2 w-auto">
                    <div class="card mb-2">
                        <div class="card-body p-3 d-flex flex-column">
                            <div id="websiteQR" class="d-flex justify-content-center"></div>
                            <div class="d-flex flex-column">
                                <p class="fw-bold text-dark text-center mb-0">
                                    Main Website Link
                                </p>
                                <a href="<?php echo "https://" . $_SERVER['HTTP_HOST']; ?>">
                                    <?php echo "https://" . $_SERVER['HTTP_HOST']; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-2 w-auto">
                    <div class="card mb-2">
                        <div class="card-body p-3 d-flex flex-column">
                            <div id="menuQR" class="d-flex justify-content-center"></div>
                            <div class="d-flex flex-column">
                                <p class="fw-bold text-dark text-center mb-0">
                                    Menu Link
                                </p>
                                <a href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . "/menu"; ?>">
                                    <?php echo "https://" . $_SERVER['HTTP_HOST'] . "/menu"; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-2 w-auto">
                    <div class="card mb-2">
                        <div class="card-body p-3 d-flex flex-column">
                            <div id="orderQR" class="d-flex justify-content-center"></div>
                            <div class="d-flex flex-column">
                                <p class="fw-bold text-dark text-center mb-0">
                                    Order Link
                                </p>
                                <a href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . "/order"; ?>">
                                    <?php echo "https://" . $_SERVER['HTTP_HOST'] . "/order"; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-2 w-auto">
                    <div class="card mb-2">
                        <div class="card-body p-3 d-flex flex-column">
                            <div id="orderOnlineQR" class="d-flex justify-content-center"></div>
                            <div class="d-flex flex-column">
                                <p class="fw-bold text-dark text-center mb-0">
                                    Order Online Link
                                </p>
                                <a href="<?php echo "https://" . $_SERVER['HTTP_HOST'] . "/order-online"; ?>">
                                    <?php echo "https://" . $_SERVER['HTTP_HOST'] . "/order-online"; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php include 'temps/footer.php'; ?>
        </div>
    </main>

    <?php include 'temps/jslibs.php'; ?>
    <script src="libs/qrcode.min.js"></script>

    <script>
        new QRCode(
            document.getElementById("websiteQR"), {
                text: "<?php echo "https://" . $_SERVER['HTTP_HOST']; ?>",
                height: 170,
                width: 170
            }
        );
        new QRCode(
            document.getElementById("menuQR"), {
                text: "<?php echo "https://" . $_SERVER['HTTP_HOST'] . "/menu"; ?>",
                height: 170,
                width: 170
            }
        );
        new QRCode(
            document.getElementById("orderQR"), {
                text: "<?php echo "https://" . $_SERVER['HTTP_HOST'] . "/order"; ?>",
                height: 170,
                width: 170
            }
        );
        new QRCode(
            document.getElementById("orderOnlineQR"), {
                text: "<?php echo "https://" . $_SERVER['HTTP_HOST'] . "/order-online"; ?>",
                height: 170,
                width: 170
            }
        );
    </script>
</body>

</html>