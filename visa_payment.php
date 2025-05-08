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
  __('name');
  $get_settings_api = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='API_KEY'");
  $API_KEY = mysqli_fetch_assoc($get_settings_api)['value'];

  if (isset($_GET['paymentStatus']) && $_GET['paymentStatus'] == "SUCCESS" && check_signature()) {
    $order = filter_var($_GET['merchantOrderId'], FILTER_SANITIZE_NUMBER_INT);
    $kasheir = $_GET['orderId'];
    $get_order_det = mysqli_query($GLOBALS['conn'], "SELECT * FROM visa_orders_req WHERE id='" . $order . "'");
    if (mysqli_num_rows($get_order_det) > 0) {
      $fetch = mysqli_fetch_assoc($get_order_det);

      $get_id = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_orders WHERE transaction_id='" . $kasheir . "'");
      $id = mysqli_fetch_assoc($get_id)['id'];

      $get_wh = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='wh_order'");
      $wh = mysqli_fetch_assoc($get_wh)['value'];

      $msg_header = <<<MSG
          {$lang['order_approved']} #{$id}
          ----------------
          {$lang['name']}: {$fetch['client_name']}
          {$lang['phone']}: {$fetch['client_phone']}
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
      $get_cart_det = mysqli_query($GLOBALS['conn'], "SELECT * FROM visa_cart_req WHERE order_id='" . $order . "'");
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
        while ($option = mysqli_fetch_assoc($get_options_titles)) {
          $det .= <<<MSG

                  - {$option['option_name']}:
              MSG;

          $get_values = mysqli_query($GLOBALS['conn'], "SELECT * FROM visa_options_req WHERE option_id='" . $option['option_id'] . "' AND order_card_id='" . $cart['id'] . "'");
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
      if ($fetch['total_discount'] > 0) {
        $total_disc = <<<MSG
              {$lang['order_discount']}: {$fetch['total_discount']}
            MSG;
      }
      if ($fetch['delivery_discount'] > 0) {
        $del_disc = <<<MSG
              {$lang['delivery_discount']}: {$fetch['delivery_discount']}
            MSG;
      }

      $total_order = get_total_visa_order_price($order);
      $final_total = $total_order + $fetch['address_price'] - $fetch['total_discount'] - $fetch['delivery_discount'];
      $msg_footer = <<<MSG

          ----------------
          {$lang['pay_info']}
          ----------------
          {$lang['sum']}: {$total_order} {$site_setting['currency']}{$total_disc}
          {$lang['delivery']}: {$fetch['address_price']} {$site_settings['currency']}{$del_disc}
          {$lang['total_cost']}: {$final_total} {$site_setting['currency']}
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

  function check_signature()
  {
    $queryString = "";
    $secret = $GLOBALS['API_KEY'];
    foreach ($_GET as $key => $value) {
      if ($key == "signature" || $key == "mode") {
        continue;
      }
      $queryString = $queryString . "&" . $key . "=" . $value;
    }
    $queryString = ltrim($queryString, $queryString[0]);
    $signature = hash_hmac('sha256', $queryString, $secret, false);
    if ($signature == $_GET["signature"]) {
      return true;
    } else {
      return false;
    }
  }
  ?>
  <div class="sections order-page col-lg-8 col-12 mx-auto">
    <div class="row col-lg-10 col-md-10 col-sm-10 col-12 mx-auto">
      <div class="item soon">
        <div style="background: var(--icon-back-color);color: var(--icon-color);border-radius: 7px;text-align: center;padding: 10px 45px;font-size:20px;">
          <i class="glyphicon <?php echo ($_GET['paymentStatus'] == "SUCCESS") ? 'glyphicon-ok-circle' : 'glyphicon-remove-circle'; ?>" style="<?php echo ($_GET['paymentStatus'] == "SUCCESS") ? 'color: green' : 'color: red'; ?>;font-weight: bold;font-size: 40px;"></i>
          <?php if ($_GET['paymentStatus'] == "SUCCESS") { ?>
            <br>
            <?php echo __('we_received_your_order'); ?>.
            <?php if ($site_setting['wh_av']) { ?>
              <br>
              <?php echo __('redirecting'); ?> ...
              <span class="counter" style="background: var(--secondary-color);padding: 0px 10px;border-radius: 5px;">6</span>
              <?php echo __('please_enter_send'); ?>
            <?php } ?>
          <?php } else { ?>
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