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
    if (!check_user_perm(['safwa-card-view'])) :
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
            <?php if (check_user_perm(['safwa-card-add'])) : ?>
                <div class="row my-4 justify-content-center">
                    <a class="btn btn-info py-2" style="width:fit-content;" data-bs-toggle="modal" data-bs-target="#add_new_offer_modal">اضافة جديد</a>
                </div>
            <?php endif; ?>
            <div id="sortable" class="row my-4 justify-content-center">
                <?php
                $get_offer = mysqli_query($GLOBALS['conn'], "SELECT * FROM safwa_cards ORDER BY last_date DESC");
                while ($offer = mysqli_fetch_assoc($get_offer)) {
                ?>
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-2" data-id="<?php echo $offer['id']; ?>">
                        <div class="card mb-0 h-100">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div class="icon" style="width:100%;height:fit-content;">
                                    <img src="<?php echo "../" . $offer['url']; ?>" style="width:100%;">
                                </div>
                                <div>
                                    <p class="text-center font-weight-bold text-dark mb-0">
                                        <?php echo $offer['title']; ?>
                                    </p>
                                    <p class="text-center">
                                        <?php echo $offer['description']; ?>
                                    </p>
                                    <p class="text-right">
                                        <label class="text-dark font-weight-bold">يبدأ من: </label>
                                        <span><bdi><?php echo date("Y-m-d", $offer['start_date']); ?></bdi></span>
                                    </p>
                                    <p class="text-right">
                                        <label class="text-dark font-weight-bold">ينتهي في: </label>
                                        <span><bdi><?php echo date("Y-m-d", $offer['last_date']); ?></bdi></span>
                                    </p>
                                    <p class="text-right">
                                        <label class="text-dark font-weight-bold">الحالة: </label>
                                        <span><?php echo ($offer['active']) ? 'مُفعل' : 'غير مُفعل'; ?></span>
                                    </p>
                                </div>
                                <?php if (check_user_perm(['offers-card-edit']) || check_user_perm(['offers-card-remove'])) : ?>
                                    <div class="d-flex justify-content-between">
                                        <?php if (check_user_perm(['offers-card-edit'])) : ?>
                                            <button class="btn bg-gradient-success text-white my-0 py-1" onclick="show_edit_offer_modal(<?php echo $offer['id']; ?>, '<?php echo $offer['url']; ?>', '<?php echo date('Y-m-d', $offer['start_date']); ?>', '<?php echo date('Y-m-d', $offer['last_date']); ?>', <?php echo $offer['active']; ?> , this)">تعديل</button>
                                        <?php endif; ?>
                                        <?php if (check_user_perm(['offers-card-remove'])) : ?>
                                            <button class="btn bg-gradient-danger text-white my-0 py-1" onclick="remove_offer(<?php echo $offer['id']; ?>,this)">حذف</button>
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
    <div class="modal fade" data-bs-backdrop="static" id="add_new_offer_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة عرض جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="loading" style="flex-direction:column;background: #000000c2;height: 100%;position: absolute;top: 0;left: 0;z-index: 9;width: 100%;display: none;justify-content: center;align-items: center;">
                    <p class="text-white font-weight-bold">يتم رفع الصورة...</p>
                    <div class="progress" style="height: 10px;width: 70%;">
                        <div class="progress-bar" role="progressbar" style="height:10px;"></div>
                    </div>
                </div>
                <div class="modal-body d-flex flex-wrap justify-content-start column-gap-1">
                    <div class="px-1 col-12" style='flex-grow:1'>
                        <label class="text-bold">العنوان:</label>
                        <input name="title" class="form-control border px-2" type="text" placeholder="العنوان">
                    </div>
                    <div class="px-1 col-12 mb-2" style='flex-grow:1'>
                        <label class="text-bold">الوصف:</label>
                        <textarea name="description" class="form-control border px-2" type="text"></textarea>
                    </div>
                    <div class="px-1 col-12 mb-2" style='flex-grow:1'>
                        <label class="text-bold">بداية:</label>
                        <input name="start" class="form-control border px-2" min="<?php echo time(); ?>" type="date" placeholder="البداية">
                    </div>
                    <div class="px-1 col-12 mb-2" style='flex-grow:1'>
                        <label class="text-bold">نهاية:</label>
                        <input name="end" class="form-control border px-2" min="<?php echo time(); ?>" type="date" placeholder="النهاية">
                    </div>
                    <div class="px-1 col-12 mb-2" style='flex-grow:1'>
                        <div class="form-check bg-gradient-dark mt-2 px-3 radius-2 py-1 pb-0 rounded" style="width:fit-content">
                            <input class="form-check-input" type="checkbox" name="active" style="cursor:pointer;">
                            <label class="form-check-label text-bold text-white mx-1" for="flexRadioDefault1">
                                التفعيل
                            </label>
                        </div>
                    </div>
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
                    <button type="button" class="btn btn-primary" onclick="add_new_offer()">اضافة</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add New Social modal -->


    <!-- Start Edit Social modal -->
    <div class="modal fade" data-bs-backdrop="static" id="edit_offer_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <div class="px-1 col-12" style='flex-grow:1'>
                        <label class="text-bold">العنوان:</label>
                        <input name="title" class="form-control border px-2" type="text" placeholder="العنوان">
                    </div>
                    <div class="px-1 col-12 mb-2" style='flex-grow:1'>
                        <label class="text-bold">الوصف:</label>
                        <textarea name="description" class="form-control border px-2" placeholder="الوصف"></textarea>
                    </div>
                    <div class="px-1 col-12 mb-2" style='flex-grow:1'>
                        <label class="text-bold">بداية:</label>
                        <input name="start" class="form-control border px-2" type="date" placeholder="البداية">
                    </div>
                    <div class="px-1 col-12 mb-2" style='flex-grow:1'>
                        <label class="text-bold">نهاية:</label>
                        <input name="end" class="form-control border px-2" type="date" placeholder="النهاية">
                    </div>
                    <div class="px-1 col-12 mb-2" style='flex-grow:1'>
                        <div class="form-check bg-gradient-dark mt-2 px-3 radius-2 py-1 pb-0 rounded" style="width:fit-content">
                            <input class="form-check-input" type="checkbox" name="active" style="cursor:pointer;">
                            <label class="form-check-label text-bold text-white mx-1" for="flexRadioDefault1">
                                التفعيل
                            </label>
                        </div>
                    </div>
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
                                        <input type="file" name="image" class="form-control d-none" id="customFile2" accept=".jpg, .png, .jpeg" onchange="displaySelectedImage(event, this)" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" onclick="update_offer()">حفظ</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Edit Social modal -->

    <?php include 'temps/jslibs.php'; ?>
    <script>
        function show_edit_offer_modal(id, url, start, end, active, me) {
            $("#edit_offer_modal").attr("data-id", id)
            var img = $(me).parent().parent().find("> div:nth-of-type(1) img").clone().attr("id", "selectedImage").css("width", "300px")
            $("#edit_offer_modal .modal-body > div:last-of-type > div > div:first-of-type").html(img)

            var title = $(me).parent().parent().find("> div").eq(1).find("> p").eq(0).text().trim()
            var description = $(me).parent().parent().find("> div").eq(1).find("> p").eq(1).text().trim()

            $("#edit_offer_modal .modal-body > div:first-of-type input").val(title)
            $("#edit_offer_modal .modal-body > div:nth-of-type(2) textarea").val(description)
            $("#edit_offer_modal .modal-body > div:nth-of-type(3) input").val(start)
            $("#edit_offer_modal .modal-body > div:nth-of-type(4) input").val(end)
            $("#edit_offer_modal .modal-body > div:nth-of-type(5) input").prop("checked", active)

            $("#edit_offer_modal").modal("show")
        }

        function update_offer() {
            $('#edit_offer_modal .progress-bar').text('0%');
            $('#edit_offer_modal .progress-bar').width('0%');
            var uploadInput = $('#edit_offer_modal input[type="file"]');

            var id = $('#edit_offer_modal').attr('data-id');
            var title = $("#edit_offer_modal .modal-body > div:first-of-type input").val()
            var des = $("#edit_offer_modal .modal-body textarea").val()
            var start = $("#edit_offer_modal .modal-body > div:nth-of-type(3) input").val()
            var end = $("#edit_offer_modal .modal-body > div:nth-of-type(4) input").val()
            var active = $("#edit_offer_modal .modal-body > div:nth-of-type(5) input").prop("checked")

            var formData = new FormData();
            formData.append('image', uploadInput[0].files[0]);
            formData.append('id', id);
            formData.append('title', title);
            formData.append('des', des);
            formData.append('start', start);
            formData.append('end', end);
            formData.append('active', active);

            $.ajax({
                url: 'ajax/update-safwa-card.php',
                type: 'POST',
                data: formData,
                //processType: false, WRONG syntax
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#edit_offer_modal .loading").show("slow")
                    $("#edit_offer_modal .loading").css("display", "flex")
                },
                success: function(data) {
                    console.log(data)
                    $("#edit_offer_modal .loading").hide("slow")
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
                        $(`#sortable div[data-id='${id}'] div.card-body > div:nth-child(2) > p:first-of-type`).text(title)
                        $(`#sortable div[data-id='${id}'] div.card-body > div:nth-child(2) > p:nth-child(2)`).text(des)
                        $(`#sortable div[data-id='${id}'] div.card-body > div:nth-child(2) > p:nth-child(3) span`).text(start)
                        $(`#sortable div[data-id='${id}'] div.card-body > div:nth-child(2) > p:nth-child(4) span`).text(end)
                        $(`#sortable div[data-id='${id}'] div.card-body > div:nth-child(2) > p:nth-child(5) span`).text((active) ? 'مُفعل' : 'غير مٌفعل')
                        $("#edit_offer_modal").modal("hide")
                    }
                },
                xhr: function() {
                    var xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(e) {
                        if (e.lengthComputable) {
                            //var uploadPercent = e.loaded / e.total; typo uploadpercent (all lowercase)
                            var uploadpercent = e.loaded / e.total;
                            uploadpercent = (uploadpercent * 100); //optional Math.round(uploadpercent * 100)
                            $('#edit_offer_modal .progress-bar').text(uploadpercent + '%');
                            $('#edit_offer_modal .progress-bar').width(uploadpercent + '%');
                            if (uploadpercent == 100) {
                                $('#edit_offer_modal .progress-bar').text('Completed');
                            }
                        }
                    }, false);

                    return xhr;
                }
            })
        }

        function remove_offer(id, me) {
            $.post("ajax/remove-safwa-card.php", {
                id: id
            }, function(res) {
                //Rembmer to valid errors
                if (res != "error") {
                    $(me).parent().parent().parent().parent().remove()
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
        $('#edit-menu-form input').change(function() {
            $('.progress-bar').text('0%');
            $('.progress-bar').width('0%');
        });

        function add_new_offer() {
            $('#add_new_offer_modal .progress-bar').text('0%');
            $('#add_new_offer_modal .progress-bar').width('0%');
            var uploadInput = $('#add_new_offer_modal input[type="file"]');

            if (uploadInput[0].files[0] != undefined) {
                var formData = new FormData();
                formData.append('image', uploadInput[0].files[0]);

                var title = $("#add_new_offer_modal input[name='title']").val()
                var des = $("#add_new_offer_modal textarea[name='description']").val()
                var start = $("#add_new_offer_modal input[name='start']").val()
                var end = $("#add_new_offer_modal input[name='end']").val()
                var active = $("#add_new_offer_modal input[name='active']").prop("checked")

                formData.append('title', title);
                formData.append('des', des);
                formData.append('start', start);
                formData.append('end', end);
                formData.append('active', active);


                $.ajax({
                    url: 'ajax/add_safwa-card.php',
                    type: 'POST',
                    data: formData,
                    //processType: false, WRONG syntax
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $("#add_new_offer_modal .loading").show("slow")
                        $("#add_new_offer_modal .loading").css("display", "flex")
                    },
                    success: function(data) {
                        console.log(data)
                        $("#add_new_offer_modal .loading").hide("slow")
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
                            $("#sortable").html(data)
                            $("#add_new_offer_modal").modal("hide")
                        }
                    },
                    xhr: function() {
                        var xhr = new XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(e) {
                            if (e.lengthComputable) {
                                //var uploadPercent = e.loaded / e.total; typo uploadpercent (all lowercase)
                                var uploadpercent = e.loaded / e.total;
                                uploadpercent = (uploadpercent * 100); //optional Math.round(uploadpercent * 100)
                                $('#add_new_offer_modal .progress-bar').text(uploadpercent + '%');
                                $('#add_new_offer_modal .progress-bar').width(uploadpercent + '%');
                                if (uploadpercent == 100) {
                                    $('#add_new_offer_modal .progress-bar').text('Completed');
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

        function change_offer_active(id, me) {
            var active = $(me).prop("checked")
            $.post("ajax/change_safwa_card_active.php", {
                id: id,
                active: active
            }, function(res) {
                if (res == "error") {
                    Swal.fire({
                        icon: "error",
                        text: "حدث خطاء اثناء محاولة التغيير",
                    });
                }
            })
        }
    </script>
</body>

</html>