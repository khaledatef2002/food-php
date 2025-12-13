<?php include "temps/settings.php"; ?>
<?php include "includes/functions.php"; ?>
<?php
  $get_payment_provider = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='selected_payment_method_providor'");
  $payment_provider = mysqli_fetch_assoc($get_payment_provider)['value'];
  if ( $payment_provider === 'qnb') {
    include "payments/qnb/functions.php";
  } else if ( $payment_provider === 'paymob') {
    include "payments/paymob/functions.php";
  }
?>
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

    $orderIndicator;
    if($payment_provider == 'qnb') {
      if (isset($_GET['resultIndicator'])) {
        $orderIndicator = $_GET['resultIndicator'];
      }
    } else if ($payment_provider == 'paymob') {
      $success = validate_redirect_payment_response($_GET) && $_GET['success'] == 'true';
      $orderIndicator = $_GET['order'];
    }

    $order_id = save_visa_order($orderIndicator);
    $success = $order_id != -1;

    $get_wh = mysqli_query($GLOBALS['conn'], "SELECT * FROM website_settings WHERE title='wh_order'");
    $wh = mysqli_fetch_assoc($get_wh)['value'];
    if ($success && $site_setting['wh_av']):
      $msg = generate_order_whatsapp_message($order_id);
  ?>
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
  <?php endif; ?>

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