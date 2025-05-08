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
    if (!check_user_perm(['manual-menu-view'])) :
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
            <?php if (check_user_perm(['manual-menu-add'])) : ?>
                <div class="row my-4 justify-content-center">
                    <a class="btn btn-info py-2" style="width:fit-content;" data-bs-toggle="modal" data-bs-target="#add_new_menu_modal">اضافة جديد</a>
                </div>
            <?php endif; ?>
            <div id="sortable" class="row my-4 justify-content-center">
                <?php
                $get_menu = mysqli_query($GLOBALS['conn'], "SELECT * FROM menu ORDER BY SORT ASC");
                while ($menu = mysqli_fetch_assoc($get_menu)) {
                ?>
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-2" data-id="<?php echo $menu['id']; ?>" data-sort="<?php echo $menu['sort']; ?>">
                        <div class="card mb-2 h-100">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div class="my-0">
                                    <span class="badge badge-sm bg-gradient-primary mx-auto mb-1 d-block cursor-pointer">
                                        <i class="fas fa-grip-horizontal"></i>
                                        <span class="order"><?php echo $menu['sort']; ?></span>
                                        <i class="fas fa-grip-horizontal"></i>
                                    </span>
                                </div>
                                <div class="icon" style="width:100%;height:fit-content;">
                                    <img src="<?php echo "../" . $menu['url']; ?>" style="width:100%;">
                                </div>
                                <?php if (check_user_perm(['manual-menu-edit']) || check_user_perm(['manual-menu-remove'])) : ?>
                                    <div class="d-flex justify-content-between">
                                        <?php if (check_user_perm(['manual-menu-edit'])) : ?>
                                            <button class="btn bg-gradient-success text-white my-1 py-1" onclick="show_edit_menu_modal(<?php echo $menu['id']; ?>, '<?php echo $menu['url']; ?>', this)">تعديل</button>
                                        <?php endif; ?>
                                        <?php if (check_user_perm(['manual-menu-remove'])) : ?>
                                            <button class="btn bg-gradient-danger text-white my-1 py-1" onclick="remove_menu(<?php echo $menu['id']; ?>,this)">حذف</button>
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
    <div class="modal fade" data-bs-backdrop="static" id="add_new_menu_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة قائمة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="loading" style="flex-direction:column;background: #000000c2;height: 100%;position: absolute;top: 0;left: 0;z-index: 9;width: 100%;display: none;justify-content: center;align-items: center;">
                    <p class="text-white font-weight-bold">يتم رفع الصورة...</p>
                    <div class="progress" style="height: 10px;width: 70%;">
                        <div class="progress-bar" role="progressbar" style="height:10px;"></div>
                    </div>
                </div>
                <div class="modal-body d-flex flex-wrap justify-content-start column-gap-1">
                    <div class="px-1 col-sm-6" style='flex-grow:1'>
                        <div>
                            <div class="mb-4 d-flex justify-content-center">
                                <img id="selectedImage" src="https://mdbootstrap.com/img/Photos/Others/placeholder.jpg" alt="example placeholder" style="width: 300px;" />
                            </div>
                            <p class="text-center">صور بامتداد (png, jpg, jpeg) فقط.</p>
                            <div class="d-flex justify-content-center">
                                <div class="btn btn-primary btn-rounded-1 p-1 px-2">
                                    <form id="add-menu-form" action="" method="POST" enctype="multipart/form-data">
                                        <label class="form-label text-white m-1" for="customFile1" role="button">اختر صورة</label>
                                        <input type="file" name="image" class="form-control d-none" id="customFile1" accept=".jpg, .png, .jpeg" onchange="displaySelectedImage(event, this)" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" onclick="add_new_menu()">اضافة</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add New Social modal -->


    <!-- Start Edit Social modal -->
    <div class="modal fade" data-bs-backdrop="static" id="edit_menu_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تعديل وسيلة التواصل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="loading" style="flex-direction:column;background: #000000c2;height: 100%;position: absolute;top: 0;left: 0;z-index: 9;width: 100%;display: none;justify-content: center;align-items: center;">
                    <p class="text-white font-weight-bold">يتم رفع الصورة...</p>
                    <div class="progress" style="height: 10px;width: 70%;">
                        <div class="progress-bar" role="progressbar" style="height:10px;"></div>
                    </div>
                </div>
                <div class="modal-body d-flex flex-wrap justify-content-start column-gap-1">
                    <div class="px-1 col-sm-6" style='flex-grow:1'>
                        <div>
                            <div class="mb-4 d-flex justify-content-center">
                                <img id="selectedImage" src="https://mdbootstrap.com/img/Photos/Others/placeholder.jpg" alt="example placeholder" style="width: 300px;" />
                            </div>
                            <p class="text-center">صور بامتداد (png, jpg, jpeg) فقط.</p>
                            <div class="d-flex justify-content-center">
                                <div class="btn btn-primary btn-rounded-1 p-1 px-2">
                                    <form id="edit-menu-form" action="" method="POST" enctype="multipart/form-data">
                                        <label class="form-label text-white m-1" for="customFile2" role="button">اختر صورة</label>
                                        <input type="file" name="image2" class="form-control d-none" id="customFile2" accept=".jpg, .png, .jpeg" onchange="displaySelectedImage(event, this)" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" onclick="update_menu()">حفظ</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Edit Social modal -->

    <?php include 'temps/jslibs.php'; ?>
    <script>
        function show_edit_menu_modal(id, url, me) {
            $("#edit_menu_modal").attr("data-id", id)
            var img = $(me).parent().parent().find("> div:nth-of-type(2) img").clone().attr("id", "selectedImage").css("width", "300px")
            $("#edit_menu_modal .modal-body > div:first-of-type > div > div:first-of-type").html(img)
            $("#edit_menu_modal").modal("show")
        }

        function update_menu() {
            $('#edit_menu_modal .progress-bar').text('0%');
            $('#edit_menu_modal .progress-bar').width('0%');
            var uploadInput = $('#edit-menu-form input');
            var id = $('#edit_menu_modal').attr('data-id');

            if (uploadInput[0].files[0] != undefined) {
                var formData = new FormData();
                formData.append('image', uploadInput[0].files[0]);
                formData.append('id', id);

                $.ajax({
                    url: 'ajax/update-menu.php',
                    type: 'POST',
                    data: formData,
                    //processType: false, WRONG syntax
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $("#edit_menu_modal .loading").show("slow")
                        $("#edit_menu_modal .loading").css("display", "flex")
                    },
                    success: function(data) {
                        $("#edit_menu_modal .loading").hide("slow")
                        if (data == "FILE_TYPE_ERROR") {
                            Swal.fire({
                                icon: "error",
                                text: "هذا الامتداد غير مسموح به!",
                            });
                        } else if (data == "FILE_SIZE_ERROR") {
                            Swal.fire({
                                icon: "error",
                                text: "حجم الصورة اكبر من الحجم المسموح به!",
                            });
                        } else {
                            $(`#sortable div[data-id='${id}'] img`).attr("src", data)
                            $("#edit_menu_modal").modal("hide")
                        }
                    },
                    xhr: function() {
                        var xhr = new XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(e) {
                            if (e.lengthComputable) {
                                //var uploadPercent = e.loaded / e.total; typo uploadpercent (all lowercase)
                                var uploadpercent = e.loaded / e.total;
                                uploadpercent = (uploadpercent * 100); //optional Math.round(uploadpercent * 100)
                                $('#edit_menu_modal .progress-bar').text(uploadpercent + '%');
                                $('#edit_menu_modal .progress-bar').width(uploadpercent + '%');
                                if (uploadpercent == 100) {
                                    $('#edit_menu_modal .progress-bar').text('Completed');
                                }
                            }
                        }, false);

                        return xhr;
                    }
                })
            } else {
                Swal.fire({
                    icon: "error",
                    text: "يرجى اختيار صورة لرفعها"
                })
            }
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
                            $.post("ajax/update-menu-order.php", {
                                id: id,
                                sort: (i + 1)
                            })
                        }
                    }
                    menu_re_order()
                }
            });
            $("#sortable span").disableSelection();

        });

        function menu_re_order() {
            for (var i = 0; i < $("#sortable > div").length; i++) {
                $(`#sortable > div`).eq(i).find("span.order").text(i + 1)
                $(`#sortable > div`).eq(i).attr("data-sort", i + 1)
            }
        }

        function remove_menu(id, me) {
            $.post("ajax/remove-menu.php", {
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
                            $.post("ajax/update-menu-order.php", {
                                id: id,
                                sort: (i + 1)
                            })
                        }
                    }
                    menu_re_order()
                }
            })
        }

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
        $('#add-menu-form input').change(function() {
            $('.progress-bar').text('0%');
            $('.progress-bar').width('0%');
        });

        function add_new_menu() {
            $('#add_new_menu_modal .progress-bar').text('0%');
            $('#add_new_menu_modal .progress-bar').width('0%');
            var uploadInput = $('#add-menu-form input');

            if (uploadInput[0].files[0] != undefined) {
                var formData = new FormData();
                formData.append('image', uploadInput[0].files[0]);

                $.ajax({
                    url: 'ajax/add-menu.php',
                    type: 'POST',
                    data: formData,
                    //processType: false, WRONG syntax
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $("#edit_menu_modal .loading").show("slow")
                        $("#edit_menu_modal .loading").css("display", "flex")
                    },
                    success: function(data) {
                        $("#edit_menu_modal .loading").hide("slow")
                        if (data == "FILE_TYPE_ERROR") {
                            Swal.fire({
                                icon: "error",
                                text: "هذا الامتداد غير مسموح به!",
                            });
                        } else if (data == "FILE_SIZE_ERROR") {
                            Swal.fire({
                                icon: "error",
                                text: "حجم الصورة اكبر من الحجم المسموح به!",
                            });
                        } else {
                            $("#sortable").append(data)
                            $("#add_new_menu_modal").modal("hide")
                        }
                    },
                    xhr: function() {
                        var xhr = new XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(e) {
                            if (e.lengthComputable) {
                                //var uploadPercent = e.loaded / e.total; typo uploadpercent (all lowercase)
                                var uploadpercent = e.loaded / e.total;
                                uploadpercent = (uploadpercent * 100); //optional Math.round(uploadpercent * 100)
                                $('#add_new_menu_modal .progress-bar').text(uploadpercent + '%');
                                $('#add_new_menu_modal .progress-bar').width(uploadpercent + '%');
                                if (uploadpercent == 100) {
                                    $('#add_new_menu_modal .progress-bar').text('Completed');
                                }
                            }
                        }, false);

                        return xhr;
                    }
                })
            } else {
                Swal.fire({
                    icon: "error",
                    text: "يرجى اختيار صورة لرفعها"
                })
            }
        }
    </script>
</body>

</html>