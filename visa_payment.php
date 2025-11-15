<?php include "temps/settings.php"; ?>
<?php include "includes/functions.php"; ?>
<!DOCTYPE html>
<html lang="<?php echo $site_setting['lang']; ?>" dir="<?php echo $site_setting['dir']; ?>">

<head>
  <?php include "temps/head.php"; ?>
  <title><?php echo $site_setting['site-title']; ?> - Order Page</title>
</head>

<body>
  <?php include "temps/header.php"; ?>
  <?php
  $success = false;

  $order_from_branch = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='order_from_branch'");
  $order_from_branch = mysqli_fetch_assoc($order_from_branch);

  $branches = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_branches");
  $branches_count = mysqli_num_rows($branches);

  __('name');

  if (isset($_GET['resultIndicator']))
  { 
    $order = $_GET['resultIndicator']; // e10bcfecd3724f1a
    $get_order_det = mysqli_query($GLOBALS['conn'], "SELECT * FROM visa_orders_req WHERE operation_id = '" . $order . "' AND status = 0");
    
    if (mysqli_num_rows($get_order_det) > 0) {
      save_visa_order($order);
      $success = true;
      $fetch = mysqli_fetch_assoc($get_order_det);

      $get_id = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE transaction_id='" . $order . "'");
      $order_data = mysqli_fetch_assoc($get_id);
      $id = $order_data['id'];

      $get_wh = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='wh_order'");
      $wh = mysqli_fetch_assoc($get_wh)['value'];

      $msg_header = <<<MSG
          {$lang['order_approved']} #{$id}
          ----------------
          {$lang['name']}: {$fetch['client_name']}
          {$lang['phone']}: {$fetch['client_phone']}
          MSG;

      if ($order_from_branch['value'] == 1 || $order_data['order_type'] == 'branch') {
        $order_type = $order_data['order_type'] == 'branch' ? "استلام من الفرع" : "توصيل للمنزل";
        $msg_header .= <<<MSG
        
          نوع الطلب: {$order_type}
          MSG;
      }

      $msg_order_det = <<<MSG
        {$lang['payment_method']}: {$lang['payment_with_visa']}
        MSG;

      if ($branches_count > 1) {
        $msg_header .= <<<MSG
        
          الفرع: {$order_data['client_branch']}
          MSG;
      }

      $msg_header .= <<<MSG
      
          {$lang['area']}: {$fetch['client_area_name']}
          {$lang['address']}: {$fetch['client_address']}
          {$lang['payment_method']}: {$lang['payment_with_visa']}
          MSG;


      $msg_order_det_header = <<<MSG

          ----------------
          {$lang['order_details']}
          ----------------
          MSG;

      $msg_order_det = "";
      $get_cart_det = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_order_cart WHERE order_id='" . $id . "'");
      while ($cart = mysqli_fetch_assoc($get_cart_det)) {
        $item_price = $cart['item_price'] * $cart['item_count'];
        $det = <<<MSG
            
              {$cart['item_count']} × {$cart['item_name']} - {$item_price}
            MSG;

        if (!empty($cart['item_size_name'])) {
          $det .= <<<MSG

                  - {$lang['size']}: {$cart['item_size_name']}
              MSG;
        }

        $get_options_titles = mysqli_query($GLOBALS['conn'], "SELECT DISTINCT(option_id), option_name FROM food_order_options WHERE order_card_id='" . $cart['id'] . "'");
        echo mysqli_num_rows($get_options_titles);
        while ($option = mysqli_fetch_assoc($get_options_titles)) {
          $det .= <<<MSG

                  - {$option['option_name']}:
              MSG;

          $get_values = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_order_options WHERE option_id='" . $option['option_id'] . "' AND order_card_id='" . $cart['id'] . "'");
          while ($value = mysqli_fetch_assoc($get_values)) {
            $price = $cart['item_count'] * $value['option_price'];
            $price_det = ($price > 0) ? '  -  ' . $price . ' ' . $site_setting['currency'] : '';
            $det .= <<<MSG

                      {$cart['item_count']} × {$value['option_value']} {$price_det}
                MSG;
          }
        }

        $msg_order_det .= $det;
      }


      $total_disc = "";
      $del_disc   = "";
      if ($order_data['total_discount'] > 0) {
        $total_disc = <<<MSG
              {$lang['order_discount']}: {$order_data['total_discount']}
            MSG;
      }
      if ($order_data['order_type'] == 'branch' && $order_data['delivery_discount'] > 0) {
        $del_disc = <<<MSG
              {$lang['delivery_discount']}: {$order_data['delivery_discount']}
            MSG;
      }

      $total_order = get_total_order_price($id);
      if($order_data['order_type'] == 'branch') {
        $order_data['address_price'] = 0;
      }
      $final_total = $total_order + $order_data['address_price'] - $order_data['total_discount'] - $order_data['delivery_discount'] + $order_data['tax'];
      $msg_footer = <<<MSG

          ----------------
          {$lang['pay_info']}
          ----------------
          {$lang['sum']}: {$total_order} {$site_setting['currency']}{$total_disc}
          MSG;
      if($order_data['order_type'] == 'delivery') {
          $msg_footer .= <<<MSG

          {$lang['delivery']}: {$order_data['address_price']} {$site_setting['currency']}{$del_disc}
          MSG;
      }
      $msg_footer .= <<<MSG

          {$lang['tax']}: {$order_data['tax']}
          {$lang['total_cost']}: {$final_total} {$site_setting['currency']}
          تم السداد
          MSG;

      $msg = $msg_header . $msg_order_det_header . $msg_order_det . $msg_footer;
  ?>

      <?php if ($site_setting['wh_av']) { ?>
        <script>
          setInterval(() => {
            if (parseInt($('.counter').text()) > 0) {
              $('.counter').text(parseInt($('.counter').text()) - 1)
            }
          }, 1000);
          setTimeout(() => {
            location.href = '<?php echo 'https://wa.me/' . $wh . '?text=' . urlencode($msg); ?>'
          }, 6000);
        </script>
      <?php } ?>
  <?php
    }
  }
  ?>
  <div class="sections order-page col-lg-8 col-12 mx-auto">
    <div class="row col-lg-10 col-md-10 col-sm-10 col-12 mx-auto">
      <div class="item soon">
        <div style="background: var(--icon-back-color);color: var(--icon-color);border-radius: 7px;text-align: center;padding: 10px 45px;font-size:20px;">
          <?php if ($success) { ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="green" class="bi bi-bag-check mb-2" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M10.854 8.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
              <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>
            </svg>
            <br>
            <?php echo __('we_received_your_order'); ?>.
            <?php if ($site_setting['wh_av']) { ?>
              <br>
              <?php echo __('redirecting'); ?> ...
              <span class="counter" style="background: var(--secondary-color);padding: 0px 10px;border-radius: 5px;">6</span>
              <?php echo __('please_enter_send'); ?>
            <?php } ?>
          <?php } else { ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="red" class="bi bi-bag-x mb-2" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M6.146 8.146a.5.5 0 0 1 .708 0L8 9.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 10l1.147 1.146a.5.5 0 0 1-.708.708L8 10.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 10 6.146 8.854a.5.5 0 0 1 0-.708"/>
              <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>
            </svg>
            <br>
            <?php echo __('visa_payment_error'); ?>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>

  <div class="footer" style="    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;">
    <?php include 'temps/footer.php'; ?>
  </div>
  <?php include 'temps/jslibs.php'; ?>
</body>

</html>