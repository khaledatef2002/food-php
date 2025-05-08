<?php include "temps/settings.php"; ?>
<?php include "includes/functions.php"; ?>
<!DOCTYPE html>

<html lang="<?php echo $site_setting['lang']; ?>" dir="<?php echo $site_setting['dir']; ?>">

<head>
  <?php include "temps/head.php"; ?>
  <title><?php echo $site_setting['site-title']; ?> - Order Page</title>
  <?php if (isset($_POST['url']) && $site_setting['wh_av']) { ?>
    <script>
      setInterval(() => {
        if (parseInt($(".counter").text()) > 0) {
          $(".counter").text(parseInt($(".counter").text()) - 1)
        }
      }, 1000);
      setTimeout(() => {
        location.href = "<?php echo $_POST['url']; ?>"
      }, 6000);
    </script>
  <?php } ?>
</head>

<body>
  <?php include "temps/header.php"; ?>
  <div class="sections order-page col-lg-8 col-12 mx-auto">
    <div class="row col-lg-10 col-md-10 col-sm-10 col-12 mx-auto">
      <div class="item soon">
        <div style="background: var(--icon-back-color);color: var(--icon-color);border-radius: 7px;text-align: center;padding: 10px 45px;font-size:20px;">
          <i class="glyphicon glyphicon-ok-circle" style="color: green;font-weight: bold;font-size: 40px;"></i>
          <br>
          <?php echo __('we_received_your_order'); ?>.
          <?php
          if ($site_setting['wh_av']) {
          ?>
            <br> <?php echo __('redirecting'); ?> ... <span class="counter" style="background: var(--secondary-color);padding: 0px 10px;border-radius: 5px;">6</span> ... <?php echo __('please_enter_send'); ?>
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