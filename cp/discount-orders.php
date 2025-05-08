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
if (!check_user_perm(['discounts-view-orders'])) :
    header('Location: 403.php');
    exit;
endif;
if (!isset($_GET['id'])) :
    exit("Invalid request!");
else :
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $get_discount = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_discounts WHERE id=$id");
    //Check exist
    if (mysqli_num_rows($get_discount) > 0) :
        $discount_info = mysqli_fetch_assoc($get_discount);
    else :
        exit("Discount id not exist");
    endif;
endif;
?>

<body class="g-sidenav-show rtl bg-gray-200">
    <?php include 'temps/sidebar.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
        <?php include 'temps/navbar.php'; ?>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row my4 justify-content-center">
                <div class="col-lg-112 col-md-12 mb-md-0 mb-4">
                    <div class="card text-center">
                        <h4 class="my-0 font-weight-bold py-2">سجل طلبات الخصم (<?php echo $discount_info['name']; ?>)</h4>
                    </div>
                </div>
            </div>
            <div class="row my-4 justify-content-center">
                <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
                    <div class="card rounded-big overflow-hidden">
                        <div class="card-body p-0 pb-2">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0 table-striped">
                                    <thead>
                                        <tr class="bg-gradient bg-dark">
                                            <th class="text-center text-white text-xs font-weight-bolder">م.</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">اسم العميل</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">رقم العميل</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">الفرع</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">منطقة التوصيل</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">العنوان</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">ملاحظات</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">طريقة الدفع</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">تاريخ الطلب</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">حالة الطلب</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">الضريبة</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">السلة</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">التوصيل</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">خصم</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">سبب الخصم</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">إجمالي</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">تاريخ القبول</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">تم القبول من</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">تاريخ الرفض</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">تم الرفض من</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">إجراء</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                    </tbody>
                                    <tfoot>
                                        <tr class="">
                                            <th class="text-center text-white text-xs font-weight-bolder">م.</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">اسم العميل</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">رقم العميل</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">الفرع</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">منطقة التوصيل</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">العنوان</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">ملاحظات</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">طريقة الدفع</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">تاريخ الطلب</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">حالة الطلب</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">الضريبة</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">السلة</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">التوصيل</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">خصم</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">سبب الخصم</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">إجمالي</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">تاريخ القبول</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">تم القبول من</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">تاريخ الرفض</th>
                                            <th class="text-center text-white text-xs font-weight-bolder">تم الرفض من</th>
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
            "ajax": "tables/discountOrdersData.php?id=<?php echo $id; ?>",
            "dom": '<i<t>lp>',
            initComplete: function() {
                this.api()
                    .columns()
                    .every(function() {
                        let column = this;
                        let title = column.footer().textContent;
                        if (title == "حالة الطلب") {
                            let select = document.createElement('select')
                            select.setAttribute("class", "form-control text-center border")

                            let default_option0 = document.createElement('option')
                            default_option0.setAttribute("value", "")
                            default_option0.innerText = "الكل"
                            select.appendChild(default_option0)

                            let default_option = document.createElement('option')
                            default_option.setAttribute("value", "0")
                            default_option.innerText = "انتظار القبول"
                            select.appendChild(default_option)

                            let option1 = document.createElement('option')
                            option1.setAttribute("value", "1")
                            option1.innerText = "تم القبول"
                            select.appendChild(option1)

                            let option2 = document.createElement('option')
                            option2.setAttribute("value", "2")
                            option2.innerText = "تم الالغاء"
                            select.appendChild(option2)

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
            }
        }

        $.fn.dataTable.ext.errMode = function(settings, helpPage, message) {
            console.log(message);
        };
        var table = $("table").DataTable(DataTableOptions);
    </script>
</body>

</html>