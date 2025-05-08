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
if (!check_user_perm(['discounts-view'])) :
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
            <div class="row my4 justify-content-center">
                <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
                    <div class="card text-center">
                        <h3 class="my-0 font-weight-bold py-2">الخصومات</h3>
                    </div>
                </div>
            </div>
            <?php if (check_user_perm(['discounts-add'])) : ?>
                <div class="row my-3 mb-0 justify-content-center">
                    <a class="btn btn-info py-2" style="width:fit-content;" href="add-discount.php">اضافة جديد</a>
                </div>
            <?php endif; ?>
            <div class="row my-0 justify-content-center">
                <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
                    <div class="card rounded-big overflow-hidden">
                        <div class="card-body p-0 pb-2">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0 table-striped">
                                    <thead>
                                        <tr class="bg-gradient bg-dark">
                                            <th class="text-center text-white text-xs font-weight-bolder">م.</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">كود الخصم</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">مسمى الخصم</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">الحد الادنى</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">اقصى عدد استخدامات</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">اقصى عدد استخدامات للعميل الواحد</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">اقصى مبلغ للخصم</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">وقت العمل</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">الحالة</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">بداية</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">نهاية</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">شروط على المناطق</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">شروط على التصنيفات</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">شروط على الاصناف</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">شروط على المستخدمين</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">نوع الخصم</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">قيمة الخصم</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">عدد الاستخدامات الكلية</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">عدد المستخدمين</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">إجراء</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gradient">
                                            <th class="text-center text-white text-xs font-weight-bolder">م.</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">كود الخصم</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">مسمى الخصم</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">الحد الادنى</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">اقصى عدد استخدامات</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">اقصى عدد استخدامات للعميل الواحد</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">اقصى مبلغ للخصم</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">وقت العمل</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">الحالة</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">بداية</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">نهاية</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">شروط على المناطق</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">شروط على التصنيفات</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">شروط على الاصناف</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">شروط على المستخدمين</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">نوع الخصم</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">قيمة الخصم</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">عدد الاستخدامات الكلية</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">عدد المستخدمين</th>
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
    <?php include 'temps/jslibs.php'; ?>
    <script>
        var DataTableOptions = {
            "processing": true,
            "autoWidth": false,
            "serverSide": true,
            "ajax": "tables/discountsData.php",
            "dom": '<i<t>lp>',
            // "order": false,
            initComplete: function() {
                this.api()
                    .columns()
                    .every(function() {
                        let column = this;
                        let title = column.footer().textContent;
                        if (title == "شروط على المناطق") {
                            let select = document.createElement('select')
                            select.setAttribute("class", "form-control text-center border")

                            let default_option0 = document.createElement('option')
                            default_option0.setAttribute("value", "")
                            default_option0.innerText = "الكل"
                            select.appendChild(default_option0)

                            let default_option = document.createElement('option')
                            default_option.setAttribute("value", "0")
                            default_option.innerText = "بدون شروط"
                            select.appendChild(default_option)

                            let option1 = document.createElement('option')
                            option1.setAttribute("value", "1")
                            option1.innerText = "متاح لمناطق محددة"
                            select.appendChild(option1)

                            let option2 = document.createElement('option')
                            option2.setAttribute("value", "2")
                            option2.innerText = "غير متاح لمانطق محددة"
                            select.appendChild(option2)

                            column.footer().replaceChildren(select);

                            // Event listener for user input
                            select.addEventListener('change', () => {
                                if (column.search() !== select.value) {
                                    column.search(select.value).draw();
                                }
                            });

                        } else if (title == "شروط على التصنيفات") {
                            let select = document.createElement('select')
                            select.setAttribute("class", "form-control text-center border")

                            let default_option0 = document.createElement('option')
                            default_option0.setAttribute("value", "")
                            default_option0.innerText = "الكل"
                            select.appendChild(default_option0)

                            let default_option = document.createElement('option')
                            default_option.setAttribute("value", "0")
                            default_option.innerText = "بدون شروط"
                            select.appendChild(default_option)

                            let option1 = document.createElement('option')
                            option1.setAttribute("value", "1")
                            option1.innerText = "متاح لتصنيفات محددة"
                            select.appendChild(option1)

                            let option2 = document.createElement('option')
                            option2.setAttribute("value", "2")
                            option2.innerText = "غير متاح لتصنيفات محددة"
                            select.appendChild(option2)

                            column.footer().replaceChildren(select);

                            // Event listener for user input
                            select.addEventListener('change', () => {
                                if (column.search() !== select.value) {
                                    column.search(select.value).draw();
                                }
                            });

                        } else if (title == "شروط على الاصناف") {
                            let select = document.createElement('select')
                            select.setAttribute("class", "form-control text-center border")

                            let default_option0 = document.createElement('option')
                            default_option0.setAttribute("value", "")
                            default_option0.innerText = "الكل"
                            select.appendChild(default_option0)

                            let default_option = document.createElement('option')
                            default_option.setAttribute("value", "0")
                            default_option.innerText = "بدون شروط"
                            select.appendChild(default_option)

                            let option1 = document.createElement('option')
                            option1.setAttribute("value", "1")
                            option1.innerText = "متاح لاصناف محددة"
                            select.appendChild(option1)

                            let option2 = document.createElement('option')
                            option2.setAttribute("value", "2")
                            option2.innerText = "غير متاح لاصناف محددة"
                            select.appendChild(option2)

                            column.footer().replaceChildren(select);

                            // Event listener for user input
                            select.addEventListener('change', () => {
                                if (column.search() !== select.value) {
                                    column.search(select.value).draw();
                                }
                            });

                        } else if (title == "شروط على المستخدمين") {
                            let select = document.createElement('select')
                            select.setAttribute("class", "form-control text-center border")

                            let default_option0 = document.createElement('option')
                            default_option0.setAttribute("value", "")
                            default_option0.innerText = "الكل"
                            select.appendChild(default_option0)

                            let default_option = document.createElement('option')
                            default_option.setAttribute("value", "0")
                            default_option.innerText = "بدون شروط"
                            select.appendChild(default_option)

                            let option1 = document.createElement('option')
                            option1.setAttribute("value", "1")
                            option1.innerText = "متاح لعملاء محددين"
                            select.appendChild(option1)

                            let option2 = document.createElement('option')
                            option2.setAttribute("value", "2")
                            option2.innerText = "غير متاح لعملاء محددين"
                            select.appendChild(option2)

                            column.footer().replaceChildren(select);

                            // Event listener for user input
                            select.addEventListener('change', () => {
                                if (column.search() !== select.value) {
                                    column.search(select.value).draw();
                                }
                            });

                        } else if (title == "نوع الخصم") {
                            let select = document.createElement('select')
                            select.setAttribute("class", "form-control text-center border")

                            let default_option = document.createElement('option')
                            default_option.setAttribute("value", "")
                            default_option.innerText = "الكل"
                            select.appendChild(default_option)

                            let option1 = document.createElement('option')
                            option1.setAttribute("value", "0")
                            option1.innerText = "خصم الطلب"
                            select.appendChild(option1)

                            let option2 = document.createElement('option')
                            option2.setAttribute("value", "1")
                            option2.innerText = "خصم التوصيل"
                            select.appendChild(option2)

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

                            let default_option = document.createElement('option')
                            default_option.setAttribute("value", "")
                            default_option.innerText = "الكل"
                            select.appendChild(default_option)

                            let option1 = document.createElement('option')
                            option1.setAttribute("value", "0")
                            option1.innerText = "لا يعمل"
                            select.appendChild(option1)

                            let option2 = document.createElement('option')
                            option2.setAttribute("value", "1")
                            option2.innerText = "يعمل"
                            select.appendChild(option2)

                            column.footer().replaceChildren(select);

                            // Event listener for user input
                            select.addEventListener('change', () => {
                                if (column.search() !== select.value) {
                                    column.search(select.value).draw();
                                }
                            });

                        } else if (title == "وقت العمل") {
                            let select = document.createElement('select')
                            select.setAttribute("class", "form-control text-center border")

                            let default_option = document.createElement('option')
                            default_option.setAttribute("value", "")
                            default_option.innerText = "الكل"
                            select.appendChild(default_option)

                            let option1 = document.createElement('option')
                            option1.setAttribute("value", "0")
                            option1.innerText = "غير مٌفعل"
                            select.appendChild(option1)

                            let option2 = document.createElement('option')
                            option2.setAttribute("value", "1")
                            option2.innerText = "مُفعل دائماً"
                            select.appendChild(option2)

                            let option3 = document.createElement('option')
                            option3.setAttribute("value", "2")
                            option3.innerText = "مُفعل خلال فترة"
                            select.appendChild(option3)

                            column.footer().replaceChildren(select);

                            // Event listener for user input
                            select.addEventListener('change', () => {
                                if (column.search() !== select.value) {
                                    column.search(select.value).draw();
                                }
                            });

                        } else if (title != "إجراء") {
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
        $('table tbody').sortable({
            helper: fixHelperModified,
            stop: updateIndex,
            update: function(event, ui) {
                var sortedRows = $(this).find('tr');

                // Iterate through sorted rows
                sortedRows.each(function(index, element) {
                    var rowData = [];
                    $(element).find('td').each(function() {
                        // Capture cell content
                        rowData.push($(this).text());
                    });

                    // 'rowData' now contains the content of each cell in the sorted row
                    console.log("Sorted Row " + index + ": " + rowData.join(', '));
                });
            }
        }).disableSelection();

        function remove_discount(me, id) {
            $(me).prop("disabled", true)
            $.post("ajax/remove-discount.php", {
                id: id
            }, function(res) {
                console.log(res)
                if (res == "success") {
                    table.row($(me).parents('tr')).remove().draw()

                    Swal.fire({
                        "icon": "success",
                        "text": "تم إزالة الخصم بنجاح"
                    })
                } else {
                    $(me).prop("disabled", false)
                    Swal.fire({
                        "icon": "error",
                        "text": "فشل إزالة الخصم"
                    })
                }
            })
        }
    </script>
</body>

</html>