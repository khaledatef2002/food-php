<?php include "temps/settings.php"; ?>
<!DOCTYPE html>

<html lang="<?php echo $site_setting['lang']; ?>" dir="<?php echo $site_setting['dir']; ?>">

<head>
  <?php include "temps/head.php"; ?>
  <?php include "includes/functions.php"; ?>
  <title><?php echo $site_setting['site-title']; ?> - Order Page</title>
</head>

<body>
  <?php include "temps/header.php"; ?>
  <?php
  add_visit('order');
  ?>
  <div class="sections order-page col-lg-7 col-12 mx-auto">
    <?php
    $get_order = mysqli_query($GLOBALS['conn'], "SELECT min(id),type,value FROM order_page group by type");
    while ($fetch = mysqli_fetch_assoc($get_order)) {
      switch ($fetch['type']) {
        case 'order_online':
    ?>
          <div class="row col-lg-10 col-md-10 col-sm-10 col-12 mx-auto">
            <div class="item soon col-lg-3 col-md-4 col-sm-6 col-6">
              <a href="order-online">
                <div>
                  <?php include 'imgs/wb-esite.svg'; ?>
                </div>
              </a>
            </div>
          </div>
        <?php
          break;
        case 'phone': ?>
          <div class="row col-lg-10 col-md-10 col-sm-10 col-12 mx-auto">
            <div class="item col-lg-3 col-md-4 col-sm-6 col-6">
              <a data-bs-toggle="modal" data-bs-target="#myModal">
                <div>
                  <?php include 'imgs/newhot.svg'; ?>
                </div>
              </a>
            </div>
          </div>
        <?php
          break;
        case 'whatsapp': ?>
          <div class="row col-lg-10 col-md-10 col-sm-10 col-12 mx-auto">
            <div class="item col-lg-3 col-md-4 col-sm-6 col-6">
              <a href="https://api.whatsapp.com/send?phone=<?php echo $fetch['value'] ?>">
                <div>
                  <?php include 'imgs/whatsapp.svg'; ?>
                </div>
              </a>
            </div>
          </div>
    <?php
          break;
      }
    }
    ?>
  </div>
  <?php if ($site_setting['visa_av'] == 1) : ?>
    <ul class="col-12 position-absolute d-flex justify-content-center" style="bottom:45px;column-gap:35px;">
      <li class="d-inline-block" style="list-style: none;width: fit-content"><?php echo __('privacy_policy'); ?></li>
      <li class="d-inline-block" style="list-style: none;width: fit-content"><?php echo __('refund_policy'); ?></li>
    </ul>
  <?php endif; ?>


  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="margin:0 auto;width: 22%;min-width:300px;;transform: translateY(-50%);top: 50%;">
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between fw-bold fs-5" style="background: var(--modal-header-back);padding: 10px 10px;border: 0px;color: var(--modal-header-color);">
          <?php echo __('contact_phones'); ?> <div type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color: var(--modal-header-color);opacity: 1;padding: 5px 5px;"><span aria-hidden="true" style="color: var(--modal-header-color);;">×</span></div>
        </div>
        <div class="modal-body" style="padding:0;background:white;">
          <ul style="list-style: none;display: flex;flex-direction: column;gap: 7px;padding: 10px 0;align-items: center;text-align: center;">
            <?php
            $get_phones = mysqli_query($GLOBALS['conn'], "SELECT * FROM order_page WHERE type = 'phone'");
            while ($fetch = mysqli_fetch_assoc($get_phones)) {
            ?>
              <li>
                <a href="tel:<?php echo $fetch['value']; ?>" style="background: var(--button-back);color: var(--button-color);border-radius: 7px;width: 130px;display: inline-block;height: 35px;padding: 9px 5px;"><?php echo $fetch['value']; ?></a>
              </li>
            <?php
            }
            ?>
          </ul>
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