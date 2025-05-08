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
  $get_settings = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='currency'");
  $fetch = mysqli_fetch_assoc($get_settings);
  $currency = $fetch['value'];
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
      <?php if (check_user_perm(['reports-view'])) : ?>
        <div class="row mb-3 d-flex justify-content-between px-md-7" style="row-gap: 30px;">
          <div class="col-lg-4 col-sm-6 mb-lg-0 mb-4">
            <div class="card" dir="ltr">
              <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                  <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="text-end pt-1">
                  <p class="text-sm mb-0 text-capitalize">إجمالي الربح هذا الشهر</p>
                  <h4 class="mb-0">
                    <?php
                    $earnAll = 0;
                    $earnToday = 0;

                    $thisMonthStart = strtotime('first day of this month');
                    $thisDayStart = strtotime('midnight today');

                    $getSuccessOrders = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE marked=1 AND ordered_date >= '$thisMonthStart'");
                    $getSuccessOrdersToday = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE marked=1 AND ordered_date >= '$thisDayStart'");

                    while ($fetch = mysqli_fetch_assoc($getSuccessOrders)) {
                      $earnAll += (get_total_order_price($fetch['id']) + $fetch['tax'] + $fetch['address_price'] - $fetch['delivery_discount'] - $fetch['total_discount']);
                    }
                    while ($fetch = mysqli_fetch_assoc($getSuccessOrdersToday)) {
                      $earnToday += (get_total_order_price($fetch['id']) + $fetch['tax'] + $fetch['address_price'] - $fetch['delivery_discount'] - $fetch['total_discount']);
                    }

                    echo number_format($earnAll) . "$";
                    ?>
                  </h4>
                </div>
              </div>
              <hr class="dark horizontal my-0">
              <div class="card-footer p-3">
                <p class="mb-0 text-end">
                  <span class="badge bg-gradient-primary" dir="rtl">اليوم فقط: <bdi><?php echo number_format($earnToday) . "$"; ?></bdi></span>
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6 mb-lg-0 mb-4">
            <div class="card" dir="ltr">
              <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                  <i class="fas fa-cart-arrow-down"></i>
                </div>
                <div class="text-end pt-1">
                  <p class="text-sm mb-0 text-capitalize">إجمالي الاوردرات</p>
                  <h4 class="mb-0">
                    <?php
                    $getTotalOrders = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders");
                    echo number_format(mysqli_num_rows($getTotalOrders));
                    ?>
                  </h4>
                </div>
              </div>
              <hr class="dark horizontal my-0">
              <div class="card-footer p-3 d-flex justify-content-evenly">
                <p class="mb-0 text-start">
                  <?php $getSuccessOrders = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE marked=1"); ?>
                  <span class="badge bg-gradient-success">مقبول: <?php echo number_format(mysqli_num_rows($getSuccessOrders)); ?></span>
                </p>
                <p class="mb-0 text-start">
                  <?php $getCanceledOrders = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE marked=2"); ?>
                  <span class="badge bg-gradient-danger">ملغي: <?php echo number_format(mysqli_num_rows($getCanceledOrders)); ?></span>
                </p>
                <p class="mb-0 text-start">
                  <?php $getWaitingOrders = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE marked=0"); ?>
                  <span class="badge bg-gradient-warning">انتظار: <?php echo number_format(mysqli_num_rows($getWaitingOrders)); ?></span>
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6 mb-lg-0 mb-4">
            <div class="card" dir="ltr">
              <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                  <i class="fas fa-users"></i>
                </div>
                <div class="text-end pt-1">
                  <p class="text-sm mb-0 text-capitalize">إجمالي العملاء</p>
                  <h4 class="mb-0">
                    <?php
                    $getTotalOrders = mysqli_query($GLOBALS['conn'], "SELECT DISTINCT client_phone FROM food_orders");
                    echo number_format(mysqli_num_rows($getTotalOrders));
                    ?>
                  </h4>
                </div>
              </div>
              <hr class="dark horizontal my-0">
              <div class="card-footer p-3">
                <p class="mb-0 text-start d-flex justify-content-evenly">
                  <?php
                  $thisMonthStart = strtotime('first day of this month');
                  $thisYearStart = strtotime('first day of January this year');
                  $thisDay = strtotime('midnight today');
                  $getTotalOrders = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders GROUP BY client_phone having ordered_date >='$thisMonthStart' ORDER BY id DESC");
                  $getTotalOrdersToday = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders  GROUP BY client_phone having ordered_date >='$thisDay' ORDER BY id DESC");
                  $getTotalOrdersYear = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders GROUP BY client_phone having ordered_date >='$thisYearStart' ORDER BY id DESC");
                  ?>
                  <span class="badge bg-gradient-warning">هذه السنة: <?php echo number_format(mysqli_num_rows($getTotalOrdersYear)); ?></span>
                  <span class="badge bg-gradient-primary">هذا الشهر: <?php echo number_format(mysqli_num_rows($getTotalOrders)); ?></span>
                  <span class="badge bg-gradient-success">هذا اليوم: <?php echo number_format(mysqli_num_rows($getTotalOrdersToday)); ?></span>
                </p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-sm-6 mb-lg-0 mb-4">
            <div class="card" dir="ltr">
              <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                  <i class="fas fa-signal"></i>
                </div>
                <div class="text-end pt-1">
                  <p class="text-sm mb-0 text-capitalize">زيارات الموقع (طوال الوقت)</p>
                  <h4 class="mb-0">
                    <?php
                    $getTotalOrders = mysqli_query($GLOBALS['conn'], "SELECT * FROM visits");
                    echo number_format(mysqli_num_rows($getTotalOrders));
                    ?>
                  </h4>
                </div>
              </div>
              <hr class="dark horizontal my-0">
              <div class="card-footer p-3">
                <p class="mb-0 text-end">
                  <?php
                  $visits = mysqli_query($GLOBALS['conn'], "SELECT DISTINCT ip FROM visits");
                  $visits = number_format(mysqli_num_rows($visits));
                  ?>
                  <span class="badge bg-gradient-primary">عدد الزائرين: <?php echo $visits; ?></span>
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6 mb-lg-0 mb-4">
            <div class="card" dir="ltr">
              <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                  <i class="fas fa-signal"></i>
                </div>
                <div class="text-end pt-1">
                  <p class="text-sm mb-0 text-capitalize">زيارات الموقع (طوال الشهر)</p>
                  <h4 class="mb-0">
                    <?php
                    $d = date("Y-m-d H:i:s", strtotime("first day of this month"));
                    $getTotalOrders = mysqli_query($GLOBALS['conn'], "SELECT * FROM visits WHERE created_at > '$d'");
                    echo number_format(mysqli_num_rows($getTotalOrders));
                    ?>
                  </h4>
                </div>
              </div>
              <hr class="dark horizontal my-0">
              <div class="card-footer p-3">
                <p class="mb-0 text-end">
                  <?php
                  $visits = mysqli_query($GLOBALS['conn'], "SELECT DISTINCT ip FROM visits WHERE created_at > '$d'");
                  $visits = number_format(mysqli_num_rows($visits));
                  ?>
                  <span class="badge bg-gradient-primary">عدد الزائرين: <?php echo $visits; ?></span>
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6 mb-lg-0 mb-4">
            <div class="card" dir="ltr">
              <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                  <i class="fas fa-signal"></i>
                </div>
                <div class="text-end pt-1">
                  <p class="text-sm mb-0 text-capitalize">زيارات الموقع (طوال السنة)</p>
                  <h4 class="mb-0">
                    <?php
                    $d = date("Y-m-d H:i:s", strtotime("first day on january this year"));
                    $getTotalOrders = mysqli_query($GLOBALS['conn'], "SELECT * FROM visits WHERE created_at > '$d'");
                    echo number_format(mysqli_num_rows($getTotalOrders));
                    ?>
                  </h4>
                </div>
              </div>
              <hr class="dark horizontal my-0">
              <div class="card-footer p-3">
                <p class="mb-0 text-end">
                  <?php
                  $visits = mysqli_query($GLOBALS['conn'], "SELECT DISTINCT ip FROM visits WHERE created_at > '$d'");
                  $visits = number_format(mysqli_num_rows($visits));
                  ?>
                  <span class="badge bg-gradient-primary">عدد الزائرين: <?php echo $visits; ?></span>
                </p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-sm-6 mb-lg-0 mb-4">
            <div class="card" dir="ltr">
              <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                  <i class="fas fa-signal"></i>
                </div>
                <div class="text-end pt-1">
                  <p class="text-sm mb-0 text-capitalize">زيارات المنيو (طوال الوقت)</p>
                  <h4 class="mb-0">
                    <?php
                    $getTotalOrders = mysqli_query($GLOBALS['conn'], "SELECT * FROM visits WHERE page='menu'");
                    echo number_format(mysqli_num_rows($getTotalOrders));
                    ?>
                  </h4>
                </div>
              </div>
              <hr class="dark horizontal my-0">
              <div class="card-footer p-3">
                <p class="mb-0 text-end">
                  <?php
                  $visits = mysqli_query($GLOBALS['conn'], "SELECT DISTINCT ip FROM visits WHERE page='menu'");
                  $visits = number_format(mysqli_num_rows($visits));
                  ?>
                  <span class="badge bg-gradient-primary">عدد الزائرين: <?php echo $visits; ?></span>
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6 mb-lg-0 mb-4">
            <div class="card" dir="ltr">
              <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                  <i class="fas fa-signal"></i>
                </div>
                <div class="text-end pt-1">
                  <p class="text-sm mb-0 text-capitalize">زيارات المنيو (طوال الشهر)</p>
                  <h4 class="mb-0">
                    <?php
                    $d = date("Y-m-d H:i:s", strtotime("first day of this month"));
                    $getTotalOrders = mysqli_query($GLOBALS['conn'], "SELECT * FROM visits WHERE page='menu' AND created_at > '$d'");
                    echo number_format(mysqli_num_rows($getTotalOrders));
                    ?>
                  </h4>
                </div>
              </div>
              <hr class="dark horizontal my-0">
              <div class="card-footer p-3">
                <p class="mb-0 text-end">
                  <?php
                  $visits = mysqli_query($GLOBALS['conn'], "SELECT DISTINCT ip FROM visits WHERE page='menu' AND created_at > '$d'");
                  $visits = number_format(mysqli_num_rows($visits));
                  ?>
                  <span class="badge bg-gradient-primary">عدد الزائرين: <?php echo $visits; ?></span>
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6 mb-lg-0 mb-4">
            <div class="card" dir="ltr">
              <div class="card-header p-3 pt-2">
                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                  <i class="fas fa-signal"></i>
                </div>
                <div class="text-end pt-1">
                  <p class="text-sm mb-0 text-capitalize">زيارات المنيو (طوال السنة)</p>
                  <h4 class="mb-0">
                    <?php
                    $d = date("Y-m-d H:i:s", strtotime("first day on january this year"));
                    $getTotalOrders = mysqli_query($GLOBALS['conn'], "SELECT * FROM visits WHERE page='menu' AND created_at > '$d'");
                    echo number_format(mysqli_num_rows($getTotalOrders));
                    ?>
                  </h4>
                </div>
              </div>
              <hr class="dark horizontal my-0">
              <div class="card-footer p-3">
                <p class="mb-0 text-end">
                  <?php
                  $visits = mysqli_query($GLOBALS['conn'], "SELECT DISTINCT ip FROM visits WHERE page='menu' AND created_at > '$d'");
                  $visits = number_format(mysqli_num_rows($visits));
                  ?>
                  <span class="badge bg-gradient-primary">عدد الزائرين: <?php echo $visits; ?></span>
                </p>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <div class="row my-4 justify-content-between px-md-7">
        <?php if (check_user_perm(['reports-view'])) : ?>
          <div class="col-lg-4 col-md-6">
            <div class="card">
              <div class="card-header pb-0">
                <h6>الاكثر مبيعاً</h6>
                <p class="text-sm">
                  هذا اليوم
                </p>
              </div>
              <div class="card-body p-3">
                <div class="timeline timeline-one-side">
                  <?php
                  $thisDayStart = strtotime('today');

                  $getBestTodaysOrders = mysqli_query($GLOBALS['conn'], "SELECT SUM(c.item_count) AS count, c.id, c.item_id, c.item_name, c.item_count, o.ordered_date FROM food_order_cart c LEFT join food_orders o on o.id = c.order_id  WHERE o.ordered_date >= '$thisDayStart' AND o.marked=1 GROUP BY c.item_id ORDER BY COUNT DESC LIMIT 7");

                  if (mysqli_num_rows($getBestTodaysOrders)) {
                    while ($order = mysqli_fetch_assoc($getBestTodaysOrders)) {
                      $getItemInfo = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items WHERE id={$order['item_id']}");
                      $itemInfo = mysqli_fetch_assoc($getItemInfo);
                  ?>
                      <div class="timeline-block mb-5">
                        <span class="timeline-step">
                          <img src="../../<?php echo $itemInfo['img']; ?>" width="50">
                        </span>
                        <div class="timeline-content">
                          <h6 class="text-dark text-sm font-weight-bold mb-0"><?php echo $itemInfo['title']; ?> (<?php echo $order['count']; ?>)</h6>
                        </div>
                      </div>
                    <?php } ?>
                  <?php } else { ?>
                    <div class="timeline-block mb-3">
                      <div class="timeline-content">
                        <h6 class="text-dark text-sm font-weight-bold mb-0">لا يوجد نتائج</h6>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="card mt-3">
              <div class="card-header pb-0">
                <h6>الاكثر مبيعاً</h6>
                <p class="text-sm">
                  هذا الشهر
                </p>
              </div>
              <div class="card-body p-3">
                <div class="timeline timeline-one-side">
                  <?php
                  $thisMonthStart = strtotime('first day of this month');

                  $getBestTodaysOrders = mysqli_query($GLOBALS['conn'], "SELECT SUM(c.item_count) AS count, c.id, c.item_id, c.item_name, c.item_count, o.ordered_date FROM food_order_cart c LEFT join food_orders o on o.id = c.order_id  WHERE o.ordered_date >= '$thisMonthStart' AND o.marked=1 GROUP BY c.item_id ORDER BY count DESC LIMIT 5");

                  if (mysqli_num_rows($getBestTodaysOrders)) {
                    while ($order = mysqli_fetch_assoc($getBestTodaysOrders)) {
                      $getItemInfo = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items WHERE id={$order['item_id']}");
                      $itemInfo = mysqli_fetch_assoc($getItemInfo);
                  ?>
                      <div class="timeline-block mb-5">
                        <span class="timeline-step">
                          <img src="../../<?php echo $itemInfo['img']; ?>" width="50" class="rounded">
                        </span>
                        <div class="timeline-content">
                          <h6 class="text-dark text-sm font-weight-bold mb-0"><?php echo $itemInfo['title']; ?> (<?php echo $order['count']; ?>)</h6>
                        </div>
                      </div>
                    <?php } ?>
                  <?php } else { ?>
                    <div class="timeline-block mb-3">
                      <div class="timeline-content">
                        <h6 class="text-dark text-sm font-weight-bold mb-0">لا يوجد نتائج</h6>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="card mt-3">
              <div class="card-header pb-0">
                <h6>الاكثر مبيعاً</h6>
                <p class="text-sm">
                  هذه السنة
                </p>
              </div>
              <div class="card-body p-3">
                <div class="timeline timeline-one-side">
                  <?php
                  $thisYearStart = strtotime('first day of January this year');

                  $getBestTodaysOrders = mysqli_query($GLOBALS['conn'], "SELECT SUM(c.item_count) AS count, c.id, c.item_id, c.item_name, c.item_count, o.ordered_date FROM food_order_cart c LEFT join food_orders o on o.id = c.order_id  WHERE o.ordered_date >= '$thisYearStart' AND o.marked=1 GROUP BY c.item_id ORDER BY count DESC LIMIT 7");

                  if (mysqli_num_rows($getBestTodaysOrders)) {
                    while ($order = mysqli_fetch_assoc($getBestTodaysOrders)) {
                      $getItemInfo = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items WHERE id={$order['item_id']}");
                      $itemInfo = mysqli_fetch_assoc($getItemInfo);
                  ?>
                      <div class="timeline-block mb-5">
                        <span class="timeline-step">
                          <img src="../../<?php echo $itemInfo['img']; ?>" width="50" class="rounded">
                        </span>
                        <div class="timeline-content">
                          <h6 class="text-dark text-sm font-weight-bold mb-0"><?php echo $itemInfo['title']; ?> (<?php echo $order['count']; ?>)</h6>
                        </div>
                      </div>
                    <?php } ?>
                  <?php } else { ?>
                    <div class="timeline-block mb-3">
                      <div class="timeline-content">
                        <h6 class="text-dark text-sm font-weight-bold mb-0">لا يوجد نتائج</h6>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
        <div class="col-lg-6 col-md-6 mb-md-0 mb-4 flex-fill">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row mb-3">
                <div class="col-6">
                  <?php $orders = get_last_orders(); ?>
                  <h6>اخر الطلبات (<?php echo mysqli_num_rows($orders); ?>)</h6>
                </div>
              </div>
            </div>
            <div class="card-body p-0 pb-2">
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7">اسم العميل</th>
                      <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7">رقم هاتف العميل</th>
                      <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7">التكلفة الإجمالية</th>
                      <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7">الحالة</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    while ($order = mysqli_fetch_assoc($orders)) {
                    ?>
                      <tr>
                        <td class="align-middle text-center">
                          <?php echo $order['client_name']; ?>
                        </td>
                        <td class="align-middle text-center text-sm">
                          <span class="text-xs font-weight-bold"> <?php echo $order['client_phone']; ?> </span>
                        </td>
                        <td class="align-middle text-center">
                          <?php echo get_total_order_price($order['id']) . " " . $currency; ?>
                        </td>
                        <td class="align-middle text-center">
                          <?php
                          if ($order['marked'] == 0) {
                            echo '<span class="badge badge-sm bg-gradient-warning">انتظار القبول</span>';
                          } else if ($order['marked'] == 1) {
                            echo '<span class="badge badge-sm bg-gradient-success">تم القبول</span>';
                          } else {
                            echo '<span class="badge badge-sm bg-gradient-danger">تم الالغاء</span>';
                          }
                          ?>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
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
</body>

</html>