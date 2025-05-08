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
    <title>
        Powered by diafh
    </title>
</head>
<?php
if (!check_user_perm(['switch-items'])) :
    header('Location: 403.php');
    exit;
endif;
?>

<body class="g-sidenav-show rtl bg-gray-200">
    <?php include 'temps/sidebar.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
        <?php include 'temps/navbar.php'; ?>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row my4 justify-content-center mb-2">
                <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
                    <div class="card text-center">
                        <h3 class="my-0 font-weight-bold py-2">الاصناف</h3>
                    </div>
                </div>
            </div>
            <div class="row my-0 justify-content-center">
                <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
                    <div class="card rounded-big overflow-hidden">
                        <div class="card-body p-0 pb-2">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0 table-striped">
                                    <thead>
                                        <tr class="bg-gradient bg-dark">
                                            <th class="text-center text-white text-xs font-weight-bolder">م.</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">اسم الصنف</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">التصنيف</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">نوع الحالة</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">الحالة</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">إجراء</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gradient">
                                            <th class="text-center text-white text-xs font-weight-bolder">م.</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">اسم الصنف</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">التصنيف</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">نوع الحالة</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">الحالة</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">إجراء</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php include 'temps/footer.php'; ?>
        </div>
    </main>

    <!-- Start Modal -->
    <div class="modal fade" id="switch-modal" data-order-id="" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">تعطيل/تفعيل (<span class="item_name"></span>)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="d-flex justify-content-evenly hide-radio" data-toggle="buttons">
                        <label class="btn btn-secondary" for="active1">
                            <input type="radio" name="active" id="active1" value="1"> مفعل
                        </label>
                        <label class="btn btn-secondary" for="active2">
                            <input type="radio" name="active" id="active2" value="2"> مفعل خلال فترة
                        </label>
                        <label class="btn btn-secondary" for="active3">
                            <input type="radio" name="active" id="active3" value="0"> غير مفعل
                        </label>
                    </div>
                    <div id="active_period" class="flex-row justify-content-between gap-2">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button id="save_switch" type="button" class="btn btn-primary spinner-button-loading"><span class="content-button-loading">تأكيد</span>
                        <div class="lds-dual-ring"></div>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php include 'temps/jslibs.php'; ?>
    <script>
        var DataTableOptions = {
            "processing": true,
            "autoWidth": false,
            "serverSide": true,
            "ajax": "tables/switchItems.php",
            "dom": '<i<t>lp>',
            // "order": false,
            initComplete: function() {
                this.api()
                    .columns()
                    .every(function() {
                        let column = this;
                        let title = column.footer().textContent;
                        if (title == "التصنيف") {
                            let select = document.createElement('select')
                            select.setAttribute("class", "form-control text-center border")

                            let default_option0 = document.createElement('option')
                            default_option0.setAttribute("value", "")
                            default_option0.innerText = "الكل"
                            select.appendChild(default_option0)

                            <?php
                            $get_cats = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_categories ORDER BY sort ASC");
                            while ($cat = mysqli_fetch_assoc($get_cats)) {
                            ?>
                                let default_option<?php echo $cat['id']; ?> = document.createElement('option')
                                default_option<?php echo $cat['id']; ?>.setAttribute("value", "<?php echo $cat['id']; ?>")
                                default_option<?php echo $cat['id']; ?>.innerText = "<?php echo $cat['category_name']; ?>"
                                select.appendChild(default_option<?php echo $cat['id']; ?>)
                            <?php } ?>

                            column.footer().replaceChildren(select);

                            // Event listener for user input
                            select.addEventListener('change', () => {
                                if (column.search() !== select.value) {
                                    column.search(select.value).draw();
                                }
                            });

                        } else if (title == "الحالة") {
                            let select = document.createElement('select')
                            select.setAttribute("class", "form-control text-center border")

                            let default_option0 = document.createElement('option')
                            default_option0.setAttribute("value", "")
                            default_option0.innerText = "الكل"
                            select.appendChild(default_option0)

                            let default_option = document.createElement('option')
                            default_option.setAttribute("value", "0")
                            default_option.innerText = "لا يعمل"
                            select.appendChild(default_option)

                            let option1 = document.createElement('option')
                            option1.setAttribute("value", "1")
                            option1.innerText = "يعمل"
                            select.appendChild(option1)

                            column.footer().replaceChildren(select);

                            // Event listener for user input
                            select.addEventListener('change', () => {
                                if (column.search() !== select.value) {
                                    column.search(select.value).draw();
                                }
                            });

                        } else if (title == "العمل") {
                            let select = document.createElement('select')
                            select.setAttribute("class", "form-control text-center border")

                            let default_option0 = document.createElement('option')
                            default_option0.setAttribute("value", "")
                            default_option0.innerText = "الكل"
                            select.appendChild(default_option0)

                            let default_option = document.createElement('option')
                            default_option.setAttribute("value", "0")
                            default_option.innerText = "غير مٌفعل"
                            select.appendChild(default_option)

                            let option1 = document.createElement('option')
                            option1.setAttribute("value", "1")
                            option1.innerText = "مُفعل دائماً"
                            select.appendChild(option1)

                            let option2 = document.createElement('option')
                            option2.setAttribute("value", "2")
                            option2.innerText = "مُفعل خلال فترة"
                            select.appendChild(option2)

                            column.footer().replaceChildren(select);

                            // Event listener for user input
                            select.addEventListener('change', () => {
                                if (column.search() !== select.value) {
                                    column.search(select.value).draw();
                                }
                            });

                        } else if (title != "إجراء" && title != "الصورة") {
                            // Create input element
                            let input = document.createElement('input');
                            input.placeholder = title;
                            input.setAttribute("class", "form-control text-center border")
                            column.footer().replaceChildren(input);

                            // Event listener for user input
                            input.addEventListener('keyup', () => {
                                if (column.search() !== this.value) {
                                    column.search(input.value).draw();
                                }
                            });
                        }
                    });
            },
            language: {
                'paginate': {
                    'previous': '<span class="prev-icon">السابق</span>',
                    'next': '<span class="next-icon">التالي</span>',
                },
                "info": 'يتم اظهار عدد _PAGE_ صفحة من اصل _PAGES_ صفحة',
                "lengthMenu": "اظهار _MENU_ نتيجة لكل صفحة",
                "zeroRecords": "لا يوجد نتائج",
                "infoEmpty": "لا يوجد نتائج",
                "infoFiltered": "(تم البحث في _MAX_ نتيجة اجمالية)"
            },
        }

        $.fn.dataTable.ext.errMode = function(settings, helpPage, message) {
            console.log(message);
        };
        var table = $("table").DataTable(DataTableOptions);

        function open_switch(id)
        {
            $.get("ajax/get_item_info.php?id=" + id, function(res){
                res = JSON.parse(res)
                console.log(res)

                $("#switch-modal input[name='id']").val(id)
                $(`#switch-modal input[name='active']`).prop("checked", false)
                if(res.body.active == 2)
                {
                    $("#active_period").css("display", "flex")
                    $("#switch-modal input[name='item_start_date']").val(res.body.date_from)
                    $("#switch-modal input[name='item_start_time']").val(res.body.time_from)
                    $("#switch-modal input[name='item_end_date']").val(res.body.date_to)
                    $("#switch-modal input[name='item_end_time']").val(res.body.time_to)
                }
                else
                {
                    $("#active_period").css("display", "none")
                }

                $("#switch-modal .item_name").text(res.body.title)
                $(`#switch-modal input[name='active'][value='${res.body.active}']`).prop("checked", true)
                
            })
            $("#switch-modal").modal("show")
        }

        $("#save_switch").click(function(){
            var button = this
            var id = $("#switch-modal input[name='id']").val()
            var active =$("#switch-modal input[name='active']:checked").val()
            var item_start_date = $("#switch-modal input[name='item_start_date']").val()
            var item_start_time = $("#switch-modal input[name='item_start_time']").val()
            var item_end_date = $("#switch-modal input[name='item_end_date']").val()
            var item_end_time =$("#switch-modal input[name='item_end_time']").val()

            $(button).prop("disabled", true)
            $.post("ajax/save_switch.php", {
                id,active,item_start_date,item_start_time,item_end_date,item_end_time
            }, function(res){
                res = JSON.parse(res)
                console.log(res)
                if (res.msg == "success") {
                    Swal.fire({
                        icon: "success",
                        text: "تم حفظ التعديل بنجاح"
                    })
                    table.draw()
                } else if (res.msg == "error") {
                    Swal.fire({
                        icon: "error",
                        text: res.body
                    })
                }
                $(button).prop("disabled", false)
            })
        })

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