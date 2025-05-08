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
    if (!check_user_perm(['accounts-view'])) :
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
            <?php if (check_user_perm(['accounts-add'])) : ?>
                <div class="row my-4 justify-content-center">
                    <a class="btn btn-info py-2" style="width:fit-content;" href="add-account.php">اضافة جديد</a>
                </div>
            <?php endif; ?>
            <div id="sortable" class="row my-4 justify-content-center">
                <?php
                $get_accounts = mysqli_query($GLOBALS['conn'], "SELECT * FROM panel_user");
                while ($account = mysqli_fetch_assoc($get_accounts)) {
                ?>
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-2" data-id="<?php echo $account['id']; ?>">
                        <div class="card mb-2 h-100">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div class="icon" style="width:100%;height:fit-content;">
                                    <img src="<?php echo "../" . $account['img']; ?>" style="width:100%;">
                                </div>
                                <div>
                                    <p class="text-center font-weight-bold text-dark mb-0">
                                        <?php echo $account['username']; ?>
                                    </p>
                                    <p class="text-center">
                                        <?php echo $account['nickname']; ?>
                                    </p>
                                    <p class="text-center font-weight-bold text-dark">
                                        <?php
                                        if ($account['role_id']) {
                                            $get_role_info = mysqli_query($GLOBALS['conn'], "SELECT * FROM roles WHERE id={$account['role_id']}");
                                            $role_info = mysqli_fetch_assoc($get_role_info);
                                            echo $role_info['name'];
                                        }
                                        ?>
                                    </p>
                                    <p class="text-center">
                                        <?php echo (isset($account['last_online']) && !empty($account['last_online']) && $account['last_online'] >= time()) ? '<span class="text-success">Online</span>' : '<span class="text-dark fw-bold">Last Seen:</span> ' . date("Y-m-d h:i:s a", $account['last_online']); ?>
                                        <br><?php echo (isset($account['last_online']) && !empty($account['last_online']) && $account['last_online'] >= time()) ? $account['page'] : ''; ?>
                                    </p>
                                </div>
                                <?php if (check_user_perm(['accounts-edit']) || check_user_perm(['accounts-remove'])) : ?>
                                    <div class="d-flex justify-content-between">
                                        <?php if (check_user_perm(['accounts-edit'])) : ?>
                                            <button class="btn bg-gradient-success text-white my-1 py-1"><a href="edit-account.php?id=<?php echo $account['id']; ?>" class="text-white">تعديل</a></button>
                                        <?php endif; ?>
                                        <?php if (check_user_perm(['accounts-remove'])) : ?>
                                            <button class="btn bg-gradient-danger text-white my-1 py-1" onclick="remove_account(<?php echo $account['id']; ?>,this)">حذف</button>
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
        function show_edit_offer_modal(id, url, start, end, me) {
            $("#edit_offer_modal").attr("data-id", id)
            var img = $(me).parent().parent().find("> div:nth-of-type(1) img").clone().attr("id", "selectedImage").css("width", "300px")
            $("#edit_offer_modal .modal-body > div:last-of-type > div > div:first-of-type").html(img)

            var title = $(me).parent().parent().find("> div").eq(1).find("> p").eq(0).text().trim()
            var description = $(me).parent().parent().find("> div").eq(1).find("> p").eq(1).text().trim()
            var price = $(me).parent().parent().find("> div").eq(1).find("> p").eq(2).find("span").eq(0).text().trim()

            $("#edit_offer_modal .modal-body > div:first-of-type input").val(title)
            $("#edit_offer_modal .modal-body > div:nth-of-type(2) input").val(description)
            $("#edit_offer_modal .modal-body > div:nth-of-type(3) input").val(price)
            $("#edit_offer_modal .modal-body > div:nth-of-type(4) input").val(start)
            $("#edit_offer_modal .modal-body > div:nth-of-type(5) input").val(end)

            $("#edit_offer_modal").modal("show")
        }

        function update_offer() {
            $('#edit_offer_modal .progress-bar').text('0%');
            $('#edit_offer_modal .progress-bar').width('0%');
            var uploadInput = $('#edit_offer_modal input[type="file"]');

            var id = $('#edit_offer_modal').attr('data-id');
            var title = $("#edit_offer_modal .modal-body > div:first-of-type input").val()
            var des = $("#edit_offer_modal .modal-body > div:nth-of-type(2) input").val()
            var price = $("#edit_offer_modal .modal-body > div:nth-of-type(3) input").val()
            var start = $("#edit_offer_modal .modal-body > div:nth-of-type(4) input").val()
            var end = $("#edit_offer_modal .modal-body > div:nth-of-type(5) input").val()

            var formData = new FormData();
            formData.append('image', uploadInput[0].files[0]);
            formData.append('id', id);
            formData.append('title', title);
            formData.append('des', des);
            formData.append('price', price);
            formData.append('start', start);
            formData.append('end', end);

            $.ajax({
                url: 'ajax/update-offer.php',
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
                        $(`#sortable div[data-id='${id}'] div.card-body > div:nth-child(2) > p:nth-child(3) span:first-of-type`).text(price)
                        $(`#sortable div[data-id='${id}'] div.card-body > div:nth-child(2) > p:nth-child(4) span`).text(start)
                        $(`#sortable div[data-id='${id}'] div.card-body > div:nth-child(2) > p:nth-child(5) span`).text(end)
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

        function remove_account(id, me) {
            $.post("ajax/remove-account.php", {
                id: id
            }, function(res) {
                console.log(res)
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

        function add_new_offer() {
            $('#add_new_offer_modal .progress-bar').text('0%');
            $('#add_new_offer_modal .progress-bar').width('0%');
            var uploadInput = $('#add_new_offer_modal input[type="file"]');

            if (uploadInput[0].files[0] != undefined) {
                var formData = new FormData();
                formData.append('image', uploadInput[0].files[0]);

                var title = $("#add_new_offer_modal input[name='title']").val()
                var des = $("#add_new_offer_modal input[name='description']").val()
                var price = $("#add_new_offer_modal input[name='price']").val()
                var start = $("#add_new_offer_modal input[name='start']").val()
                var end = $("#add_new_offer_modal input[name='end']").val()
                var active = $("#add_new_offer_modal input[name='active']").prop("checked")

                formData.append('title', title);
                formData.append('des', des);
                formData.append('price', price);
                formData.append('start', start);
                formData.append('end', end);
                formData.append('active', active);


                $.ajax({
                    url: 'ajax/add_offer.php',
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
            $.post("ajax/change_offer_active.php", {
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