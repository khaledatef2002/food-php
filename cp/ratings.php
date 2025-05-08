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
if (!check_user_perm(['rating-page-view'])) :
  header('Location: 403.php');
  exit;
endif;
?>
<?php
$total_orders = mysqli_query($GLOBALS['conn'], "SELECT * FROM ratings");

$limit = filter_var($_GET['limit'] ?? 10, FILTER_SANITIZE_NUMBER_INT);
$page = filter_var($_GET['page'] ?? 1, FILTER_SANITIZE_NUMBER_INT);

$start = ($page - 1) * $limit;

$get_orders = mysqli_query($GLOBALS['conn'], "SELECT * FROM ratings ORDER BY ID DESC LIMIT " . $start . "," . $limit . "");

$pages_num = ceil(mysqli_num_rows($total_orders) / $limit);
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
            <h3 class="my-0 font-weight-bold py-2">سجل الطلبات</h3>
          </div>
        </div>
      </div>
      <div class="row my-4 justify-content-center">
        <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
          <div class="card rounded-big overflow-hidden">
            <div class="card-body p-0 pb-2">
              <div class="p-2">
                عرض:
                <form id="change_limit" class="d-inline-block" action="" method="get">
                  <select name="limit">
                    <option value="10" <?php echo ($limit == 10) ? 'SELECTED' : ''; ?>>10 نتائج</option>
                    <option value="20" <?php echo ($limit == 20) ? 'SELECTED' : ''; ?>>20 نتائج</option>
                    <option value="50" <?php echo ($limit == 50) ? 'SELECTED' : ''; ?>>50 نتائج</option>
                    <option value="100" <?php echo ($limit == 100) ? 'SELECTED' : ''; ?>>100 نتائج</option>
                    <option value="250" <?php echo ($limit == 250) ? 'SELECTED' : ''; ?>>250 نتائج</option>
                  </select>
                </form>
              </div>
              <div class="table-responsive">
                <table class="table align-items-center mb-0 table-striped">
                  <thead>
                    <tr class="bg-gradient bg-dark">

                      <th class="text-center text-white text-xs font-weight-bolder">م.</th>
                      <th class="text-center text-white text-xs font-weight-bolder">اسم العميل</th>
                      <th class="text-center text-white text-xs font-weight-bolder">رقم العميل</th>
                      <th class="text-center text-white text-xs font-weight-bolder">عمر العميل</th>
                      <th class="text-center text-white text-xs font-weight-bolder">العنوان</th>
                      <th class="text-center text-white text-xs font-weight-bolder">تاريخ الطلب</th>
                      <th class="text-center text-white text-xs font-weight-bolder">رقم الطلب</th>
                      <th class="text-center text-white text-xs font-weight-bolder">تقييم الطعم</th>
                      <th class="text-center text-white text-xs font-weight-bolder">تقييم السرعة</th>
                      <th class="text-center text-white text-xs font-weight-bolder">تقييم الخدمة</th>
                      <th class="text-center text-white text-xs font-weight-bolder">تقييم النظافة</th>
                      <th class="text-center text-white text-xs font-weight-bolder">تقييم الجو العام</th>
                      <th class="text-center text-white text-xs font-weight-bolder">عرفتنا منين؟</th>
                      <th class="text-center text-white text-xs font-weight-bolder">هل ديه اول تجربة للمطعم؟</th>
                      <th class="text-center text-white text-xs font-weight-bolder">في حالة وجود مشكلة هل تم توضيحها لمدير المطعم؟</th>
                      <th class="text-center text-white text-xs font-weight-bolder">هل التواصل كان بالشكل المطلوب؟</th>
                      <th class="text-center text-white text-xs font-weight-bolder">تقترح علينا ايه؟</th>
                      <th class="text-center text-white text-xs font-weight-bolder">اجراء</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($rate = mysqli_fetch_assoc($get_orders)) { ?>
                      <tr>
                        <td class="text-center"><?php echo $rate['id']; ?></td>
                        <td class="text-center"><?php echo $rate['client_name']; ?></td>
                        <td class="text-center"><?php echo $rate['client_phone']; ?></td>
                        <td class="text-center"><?php echo $rate['client_age']; ?></td>
                        <td class="text-center"><?php echo $rate['client_address']; ?></td>
                        <td class="text-center"><?php echo date("Y-m-d", $rate['order_date']); ?></td>
                        <td class="text-center"><?php echo $rate['order_num']; ?></td>
                        <td class="text-center"><?php echo $rate['taste']; ?></td>
                        <td class="text-center"><?php echo $rate['speed']; ?></td>
                        <td class="text-center"><?php echo $rate['service']; ?></td>
                        <td class="text-center"><?php echo $rate['clean']; ?></td>
                        <td class="text-center"><?php echo $rate['general']; ?></td>
                        <td class="text-center"><?php echo $rate['referal']; ?></td>
                        <td class="text-center"><?php echo $rate['yes_no_1']; ?></td>
                        <td class="text-center"><?php echo $rate['yes_no_2']; ?></td>
                        <td class="text-center"><?php echo $rate['yes_no_3']; ?></td>
                        <td class="text-center"><?php echo $rate['advice']; ?></td>

                        <td class="text-center align-items-center">
                          <button class="btn bg-gradient bg-danger text-white py-1 m-0" onclick="remove_comment(<?php echo $rate['id']; ?>, this)">حذف</button>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <div class="p-2 d-flex justify-content-between align-items-center">
                <div>
                  <?php
                  echo "يتم عرض <span class='font-weight-bold'>" . mysqli_num_rows($get_orders) . "/" . mysqli_num_rows($total_orders) . "</span> نتيجة";
                  ?>
                </div>
                <div class="d-flex flex-column">
                  <div class="text-center mb-1">
                    صفحة <?php echo $page; ?> من <?php echo $pages_num; ?>
                  </div>
                  <div>
                    <?php
                    if ($page > 1) {
                      echo '<a class="mx-1" href="?limit=' . $limit . '&page=' . ($page - 1) . '"><span class="btn bg-gradient bg-dark text-white">السابق</span></a>';
                    }
                    $next_page = $page + 1;
                    $prev_page = $page - 1;

                    if ($prev_page == 1) {
                      echo '<a class="mx-1" href="?limit=' . $limit . '&page=' . $prev_page . '"><span class="btn bg-gradient bg-dark text-white">' . $prev_page . '</span></a>';
                    } else if ($prev_page > 1) {
                      echo '<a class="mx-1" href="?limit=' . $limit . '&page=1"><span class="btn bg-gradient bg-dark text-white">1</span></a>';
                      echo "....";
                      echo '<a class="mx-1" href="?limit=' . $limit . '&page=' . $prev_page . '"><span class="btn bg-gradient bg-dark text-white">' . $prev_page . '</span></a>';
                    }

                    echo '<a class="mx-1" href="?limit=' . $limit . '&page=' . $page . '"><span class="btn bg-gradient bg-dark text-white">' . $page . '</span></a>';

                    if ($next_page == $pages_num) {
                      echo '<a class="mx-1" href="?limit=' . $limit . '&page=' . $next_page . '"><span class="btn bg-gradient bg-dark text-white">' . $next_page . '</span></a>';
                    } else if ($next_page < $pages_num) {
                      echo '<a class="mx-1" href="?limit=' . $limit . '&page=' . $next_page . '"><span class="btn bg-gradient bg-dark text-white">' . $next_page . '</span></a>';
                      echo "....";
                      echo '<a class="mx-1" href="?limit=' . $limit . '&page=' . $pages_num . '"><span class="btn bg-gradient bg-dark text-white">' . $pages_num . '</span></a>';
                    }


                    if ($page < $pages_num) {
                      echo '<a class="mx-1" href="?limit=' . $limit . '&page=' . ($page + 1) . '"><span class="btn bg-gradient bg-dark text-white">التالي</span></a>';
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include 'temps/footer.php'; ?>
    </div>
  </main>

  <?php include 'temps/jslibs.php'; ?>
</body>

</html>