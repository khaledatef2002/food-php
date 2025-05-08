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
    if (!check_user_perm(['items-add'])) :
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
            <div class="col-lg-8 col-md-8 col-sm-12 mb-md-0 mb-4 mx-auto">
                <div class="card text-center">
                    <h3 class="my-0 font-weight-bold py-2">اضافة صنف</h3>
                </div>
            </div>
            <form action="POST" id="add-item">
                <div id="sortable" class="row my-4 justify-content-center">
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-2">
                        <div class="card mb-2">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <p>
                                        <label for="item_name" class="font-weight-bold text-dark">اسم الصنف:</label>
                                        <input name="item_name" id="item_name" type="text" class="form-control border px-2" placeholder="اسم الصنف">
                                    </p>
                                    <div class="d-flex flex-row justify-content-between gap-2">
                                        <p class="font-weight-bold text-dark flex-fill">
                                            <label for="item_price" class="font-weight-bold text-dark">سعر الصنف:</label>
                                            <input name="item_price" id="item_price" type="number" min="0" step="0.01" class="form-control border px-2" placeholder="سعر الصنف">
                                        </p>
                                        <p class="font-weight-bold text-dark flex-fill">
                                            <label for="item_price_before" class="font-weight-bold text-dark">بدلاً من:</label>
                                            <input name="item_price_before" id="item_price_before" type="number" min="0" step="0.01" class="form-control border px-2" placeholder="قبل الخصم">
                                        </p>
                                    </div>
                                    <p class="font-weight-bold text-dark">
                                        <label for="item_tax" class="font-weight-bold text-dark">الضريبة (%):</label>
                                        <input name="item_tax" id="item_tax" type="number" min="0" step="0.01" class="form-control border px-2" placeholder="قبل الخصم">
                                    </p>
                                    <p class="font-weight-bold text-dark">
                                        <label for="item-category" class="font-weight-bold text-dark">التصنيف:</label>
                                        <select id="item-category" class="form-control border px-2" name="item-category">
                                            <?php
                                            $get_cats = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_categories");
                                            while ($cat = mysqli_fetch_assoc($get_cats)) {
                                            ?>
                                                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['category_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </p>
                                    <p class="font-weight-bold text-dark">
                                        <label for="item-discrption" class="font-weight-bold text-dark">الوصف:</label>
                                        <textarea name="item-discrption" id="item-discrption" class="form-control border px-2"></textarea>
                                    </p>
                                    <div class="d-flex justify-content-evenly hide-radio" data-toggle="buttons">
                                        <label class="btn btn-secondary" for="active1">
                                            <input type="radio" name="active" id="active1" value="1" CHECKED> مفعل
                                        </label>
                                        <label class="btn btn-secondary" for="active2">
                                            <input type="radio" name="active" id="active2" value="2"> مفعل خلال فترة
                                        </label>
                                        <label class="btn btn-secondary" for="active3">
                                            <input type="radio" name="active" id="active3" value="0"> غير مفعل
                                        </label>
                                    </div>
                                    <div id="active_period" class="flex-row justify-content-between gap-2" style="display:none;">
                                        <p class="font-weight-bold text-dark flex-fill">
                                            <label for="item_start" class="font-weight-bold text-dark">تاريخ البداية:</label>
                                            <input name="item_start_date" id="item_start" type="date" class="form-control border px-2 mb-1">
                                            <input name="item_start_time" id="item_start" type="time" class="form-control border px-2">
                                        </p>
                                        <p class="font-weight-bold text-dark flex-fill">
                                            <label for="item_end" class="font-weight-bold text-dark">تاريخ النهاية:</label>
                                            <input name="item_end_date" id="item_end" type="date" class="form-control border px-2 mb-1">
                                            <input name="item_end_time" id="item_end" type="time" class="form-control border px-2">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2 clear-after-success">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <p class="text-center fw-bold text-dark">صورة الصنف</p>
                                <div>
                                    <div class="mb-4 d-flex justify-content-center">
                                        <img id="selectedImage" src="https://mdbootstrap.com/img/Photos/Others/placeholder.jpg" alt="example placeholder" style="width: 300px;" />
                                    </div>
                                    <p class="text-center">صور بامتداد (png, jpg, jpeg) فقط.</p>
                                    <div class="d-flex justify-content-center">
                                        <div class="btn btn-primary btn-rounded-1 p-1 px-2">
                                            <label class="form-label text-white m-1" for="customFile1" role="button">اختر صورة</label>
                                            <input type="file" name="image" class="form-control d-none" id="customFile1" accept=".jpg, .png, .jpeg" onchange="displaySelectedImage(event, this)" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-2">
                        <div class="card mb-2 clear-after-success">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <p class="text-center fw-bold text-dark">الاحجام</p>
                                    <div class="d-flex justify-content-evenly hide-radio" data-toggle="buttons">
                                        <label class="btn btn-secondary" for="size_type1">
                                            <input type="radio" name="size_type" id="size_type1" value="0"> مفعل
                                        </label>
                                        <label class="btn btn-secondary" for="size_type2">
                                            <input type="radio" name="size_type" id="size_type2" value="1" checked> غير مفعل
                                        </label>
                                    </div>
                                    <div id="sizes_div" style="display:none;">
                                        <hr class="bg-dark">
                                        <div class="size-header d-flex flex-row">
                                            <p class="text-center col-6 text-dark fw-bold">اسم الاختيار</p>
                                            <p class="text-center col-6 text-dark fw-bold">سعر الاختيار</p>
                                        </div>
                                        <div class="size-body">

                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-success col-12 mt-3 mb-0" onclick="add_size(this)">اضافة حجم</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-4 mx-auto">
                            <div class="card text-center d-flex flex-row justify-content-between align-items-center px-4">
                                <h3 class="my-0 font-weight-bold py-2 fs-4">الخصائص</h3>
                                <i onclick="add_option(this)" class="fas fa-plus-square text-success fs-3 mb-0" role="button"></i>
                            </div>
                        </div>
                        <div id="options-parent"></div>
                    </div>
                </div>
                <div class="d-flex justify-content-center col-12">
                    <button type="submit" class="spinner-button-loading btn bg-gradient-success text-white my-1 py-1 col-lg-8 col-md-8 col-sm-6 fw-bold fs-5"><span class="content-button-loading">اضافة</span>
                        <div class="lds-dual-ring"></div>
                    </button>
                </div>
            </form>
        </div>
        <?php include 'temps/footer.php'; ?>
    </main>

    <?php include 'temps/jslibs.php'; ?>
    <script>
        $("form#add-item").submit(function(e) {
            e.preventDefault()
            var form = this
            var data = new FormData(form)
            $.ajax({
                url: 'ajax/add-item.php',
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
                    $(form).find("button[type='submit']").prop("disabled", false)
                    if (response.msg == "success") {
                        Swal.fire({
                            icon: "success",
                            text: "تم اضافة الصنف بنجاح"
                        })
                        $(form).find("input:not([type='radio'])").val("")
                        $(form).find("input[type='radio'][value='0']").prop("checked", true)
                        $(form).find("input[type='radio'][name='size_type'][value='1']").prop("checked", true)
                        $(form).find("#selectedImage").attr("src", "https://mdbootstrap.com/img/Photos/Others/placeholder.jpg")
                        $("input[name='item_price']").prop("readonly", false)
                        $("#options-parent div").remove()
                        $("div#sizes_div").slideUp("slow", function() {
                            $("div.size-body").html("")
                        })
                        $("#active_period").css("display", "none")
                        $("#options").remove()
                    } else if (response.msg == "error") {
                        Swal.fire({
                            icon: "error",
                            text: response.body
                        })
                    }
                }
            })
        })

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

        var option = 0;

        function add_option(me) {
            $("#options-parent").append(`
            <div id="options" class="card mb-2 clear-after-success">
                <div class="card-header p-3 pb-0 d-flex flex-column justify-content-between">
                    <p class="font-weight-bold text-dark d-flex flex-row justify-content-center align-items-center">
                        <label for="option_name${option}" class="font-weight-bold text-dark my-0">اسم الخاصية:</label>
                        <input name="option[${option}][name]" id="option_name${option}" type="text" class="form-control border px-2" placeholder="اسم الخاصية">                        
                    </p>
                    <p class="font-weight-bold text-dark d-flex flex-row justify-content-center align-items-center">
                        <div class="d-flex justify-content-evenly hide-radio" data-toggle="buttons">
                            <label class="btn btn-secondary px-5 fw-bold" for="option_type${option}0">
                                <input type="radio" name="option[${option}][type]" value="0" id="option_type${option}0" checked> اجباري
                            </label>
                            <label class="btn btn-secondary px-5 fw-bold" for="option_type${option}1">
                                <input type="radio" name="option[${option}][type]" value="1" id="option_type${option}1"> اختياري
                            </label>
                        </div>
                    </p>
                </div>
                <hr class="bg-dark mt-0">
                <div class="card-body p-3 pt-0 my-0 d-flex flex-column justify-content-between">
                    <div>
                        <div class="option-header d-flex flex-row">
                            <p class="text-center col-6 text-dark fw-bold">اسم الاختيار</p>
                            <p class="text-center col-6 text-dark fw-bold">سعر الاختيار</p>
                        </div>
                        <div class="option-body d-flex flex-column">
                            <div class="d-flex flex-row col-12 justify-content-evenly align-items-center">
                                <input name="option[${option}][values_name][]" type="text" class="border px-2 py-2 col-md-5 col-sm-11" style="outline:none;" placeholder="اسم الاختيار">
                                <div class="col-5 d-flex flex-row align-items-center">
                                    <input name="option[${option}][values_price][]" type="price" step="0.01" class="border px-2 py-2 col-11" style="outline:none;" placeholder="سعر الاختيار">
                                </div>        
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-evenly">
                            <button type="button" class="btn btn-success col-5 mt-3 mb-0" onclick="add_option_row(this,${option})">اضافة اختيار</button>
                            <button type="button" class="btn btn-danger col-5 mt-3 mb-0" onclick="$(this).parent().parent().parent().parent().remove()">إزالة</button>
                        </div>
                    </div>
                </div>
            </div>
        `)
            option++;
        }

        $("input[name='size_type'").change(function() {
            var desired = $(this).val()
            if (desired == 0) {
                $("div.size-body").html(`
                <div class="d-flex flex-row col-12 justify-content-evenly align-items-center">
                    <input name="size_name[]" id="size_name" type="text" class="border px-2 py-2 col-md-5 col-sm-11" style="outline:none;" placeholder="اسم الحجم">
                    <div class="col-5 d-flex flex-row align-items-center">
                        <input oninput="set_price(this)" name="size_price[]" id="size_price" type="price" step="0.01" class="border px-2 py-2 col-11" style="outline:none;" placeholder="سعر الحجم">
                    </div>        
                </div>
            `)
                $("input[name='item_price']").prop("readonly", true)
                $("div#sizes_div").slideDown("slow")
            } else {
                $("input[name='item_price']").prop("readonly", false)
                $("div#sizes_div").slideUp("slow", function() {
                    $("div.size-body").html("")
                })

            }
        })

        function add_size(me) {
            $(me).parent().parent().find("div.size-body").append(`
            <div class="d-flex flex-row col-12 justify-content-evenly align-items-center my-2">
                <input name="size_name[]" id="size_name" type="text" class="border px-2 py-2 col-md-5 col-sm-11" style="outline:none;" placeholder="اسم الحجم">
                <div class="col-5 d-flex flex-row align-items-center">
                    <input name="size_price[]" id="size_price" type="price" step="0.01" class="border px-2 py-2 col-11" style="outline:none;" placeholder="سعر الحجم">
                    <i class="fas fa-remove me-2 text-danger" role="button" onclick="$(this).parent().parent().remove()"></i>
                </div>
            </div>
        `)
        }

        function add_option_row(me, option_id) {
            $(me).parent().parent().find("div.option-body").append(`
            <div class="d-flex flex-row col-12 mt-2 justify-content-evenly align-items-center">
                <input name="option[${option_id}][values_name][]" type="text" class="border px-2 py-2 col-md-5 col-sm-11" style="outline:none;" placeholder="اسم الاختيار">
                <div class="col-5 d-flex flex-row align-items-center">
                    <input name="option[${option_id}][values_price][]" type="price" step="0.01" class="border px-2 py-2 col-11" style="outline:none;" placeholder="سعر الاختيار">
                    <i class="fas fa-remove me-2 text-danger" role="button" onclick="$(this).parent().parent().remove()"></i>
                </div>        
            </div>
        `)
        }

        function set_price(me) {
            $("input[name='item_price']").val($(me).val())
        }
        $("input[name='active']").click(function() {
            var vl = $(this).val()
            if (vl == 2) {
                $("#active_period").css("display", "flex")
            } else {
                $("#active_period").css("display", "none")
            }
        })
    </script>
</body>

</html>