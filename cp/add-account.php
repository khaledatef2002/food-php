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
    if (!check_user_perm(['accounts-add'])) :
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
                <h4>اضافة حساب جديد</h4>
            </div>
            <div id="sortable" class="row my-4 justify-content-center">
                <div class="col-lg-4 col-md-4 col-sm-6 mb-2">
                    <div class="card mb-2 h-100">
                        <form action="POST" id="add-account">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <p>
                                        <label for="username" class="font-weight-bold text-dark">اسم المستخدم:</label>
                                        <input name="username" id="username" type="text" class="form-control border px-2" placeholder="اسم المسخدم">
                                    </p>
                                    <p class="font-weight-bold text-dark">
                                        <label for="password" class="font-weight-bold text-dark">كلمة المرور:</label>
                                        <input name="password" id="password" type="text" class="form-control border px-2" placeholder="كلمة المرور">
                                    </p>
                                    <p class="font-weight-bold text-dark">
                                        <label for="nickname" class="font-weight-bold text-dark">اسم الموظف:</label>
                                        <input name="nickname" id="nickname" type="text" class="form-control border px-2" placeholder="اسم الموظف">
                                    </p>
                                    <p class="font-weight-bold text-dark">
                                        <label for="role" class="font-weight-bold text-dark">الدور:</label>
                                        <select name="role" id="role" class="form-control border px-2">
                                            <?php
                                            $get_all_roles = mysqli_query($GLOBALS['conn'], "SELECT * FROM roles");
                                            while ($role = mysqli_fetch_assoc($get_all_roles)) {
                                            ?>
                                                <option value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </p>
                                </div>
                                <div>
                                    <div class="mb-4 d-flex justify-content-center">
                                        <img id="selectedImage" src="https://mdbootstrap.com/img/Photos/Others/placeholder.jpg" alt="example placeholder" style="width: 300px;" />
                                    </div>
                                    <p class="text-center">صور بامتداد (png, jpg, jpeg) فقط.</p>
                                    <div class="d-flex justify-content-center">
                                        <div class="btn btn-primary btn-rounded-1 p-1 px-2">
                                            <form id="edit-menu-form" action="" method="POST" enctype="multipart/form-data">
                                                <label class="form-label text-white m-1" for="customFile2" role="button">اختر صورة</label>
                                                <input type="file" name="image" class="form-control d-none" id="customFile2" accept=".jpg, .png, .jpeg" onchange="displaySelectedImage(event, this)" />
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between col-12">
                                    <button type="submit" class="btn bg-gradient-success text-white my-1 py-1 col-12 fw-bold fs-5 spinner-button-loading"><span class="content-button-loading">اضافة</span>
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
        function displaySelectedImage(event, me) {
            const selectedImage = $(me).parent().parent().parent().parent().find("img")[0];
            const fileInput = event.target;

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    selectedImage.src = e.target.result;
                };

                reader.readAsDataURL(fileInput.files[0]);
            }
        }
        $("form#add-account").submit(function(e) {
            e.preventDefault()
            var form = this
            var data = new FormData(form)
            var uploadInput = $('form#add-account input[name="image"]');
            if (uploadInput[0].files[0] != undefined) {
                $.ajax({
                    url: 'ajax/add-account.php',
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
                            location.href = "/cp/edit-account.php?id=" + response.body
                        } else if (response.msg == "error") {
                            $(form).find("button[type='submit']").prop("disabled", false)
                            Swal.fire({
                                icon: "error",
                                text: response.body
                            })
                        }
                    }
                })
            } else {
                Swal.fire({
                    icon: "error",
                    text: "يرجى اختيار صورة"
                })
            }
        })
    </script>
</body>

</html>