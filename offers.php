<?php include "temps/settings.php"; ?>
<!DOCTYPE html>
<html lang="<?php echo $site_setting['lang']; ?>" dir="<?php echo $site_setting['dir']; ?>">

<head>
  <?php include "temps/head.php"; ?>
  <title><?php echo $site_setting['site-title']; ?> - Offers</title>
</head>

<body>
  <?php include "temps/header.php"; ?>

  <!-- Starting main page header Carousel -->
  <?php
  $carousel_query = mysqli_query($GLOBALS['conn'], "SELECT * FROM offers_page_header ORDER BY sort ASC");
  $carousel_items_nums = mysqli_num_rows($carousel_query);
  ?>
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <?php
      for ($i = 0; $i < $carousel_items_nums; $i++) {
      ?>
        <li data-target="#myCarousel" data-slide-to="<?php echo $i; ?>" <?php if ($i == 0) {
                                                                          echo 'class="active"';
                                                                        } ?>></li>
      <?php
      }
      ?>
    </ol>
    <!-- Wrapper for slides -->
    <div class="carousel-inner">
      <?php
      $first = true;
      while ($carousel_item = mysqli_fetch_assoc($carousel_query)) {
      ?>

        <div class="item <?php if ($first == true) {
                            echo 'active';
                            $first = false;
                          }; ?>">
          <img src="<?php echo $carousel_item['url']; ?>" alt="<?php echo $carousel_item['description']; ?>" title="<?php echo $carousel_item['title']; ?>">
        </div>

      <?php } ?>
    </div>
  </div>
  <!-- End of main page header Carousel -->

  <div class="offers col-lg-8 col-lg-push-2 col-xs-12">
    <?php
    $time = time();
    $offers_query = mysqli_query($GLOBALS['conn'], "SELECT * FROM offers WHERE start_date <= '" . $time . "' AND last_date >= '" . $time . "' AND active = 1  ORDER BY id DESC");
    $offers_items_nums = mysqli_num_rows($offers_query);
    while ($offers_item = mysqli_fetch_assoc($offers_query)) {
    ?>

      <div class="col-lg-10 col-lg-push-1 col-xs-12">
        <div class="item-container col-lg-4 col-md-4 col-sm-6 col-xs-6" style="padding: 0 5px;">
          <div class="card item show_offer_det col-xs-12" data-id="<?php echo $offers_item['id']; ?>">
            <div class="card-header">
              <h2 style="text-align:center;display: flex;flex: 1;align-items: center;justify-content: center;"><?php echo $offers_item['title']; ?></h2>
            </div>
            <div class="card-body">
              <div class="col-xs-12" style="padding:0 5px;">
                <img src="<?php echo $offers_item['url']; ?>">
              </div>
              <div class="discount" style="border-width:2px;">
                <span><?php echo $offers_item['price']; ?> <?php echo $site_settings['currency']; ?></span>
              </div>
              <div style="text-align: center;width: 100%;background: var(--icon-back-color);color: white;padding: 5px 0;align-self: end;">
                <span style="font-weight: bold;font-size: 1.3em;"><?php echo __('show_details'); ?></span>
              </div>
            </div>
          </div>
        </div>
        <?php if ($offers_item = mysqli_fetch_assoc($offers_query)) { ?>

          <div class="item-container col-lg-4 col-md-4 col-sm-6 col-xs-6" style="padding: 0 5px;">
            <div class="card item show_offer_det col-xs-12" data-id="<?php echo $offers_item['id']; ?>">
              <div class="card-header">
                <h2 style="text-align:center;display: flex;flex: 1;align-items: center;justify-content: center;"><?php echo $offers_item['title']; ?></h2>
              </div>
              <div class="card-body">
                <div class="col-xs-12" style="padding:0 5px;">
                  <img src="<?php echo $offers_item['url']; ?>">
                </div>
                <div class="discount" style="border-width:2px;">
                  <span><?php echo $offers_item['price']; ?> <?php echo $site_settings['currency']; ?></span>
                </div>
                <div style="text-align: center;width: 100%;background: var(--icon-back-color);color: white;padding: 5px 0;align-self: end;">
                  <span style="font-weight: bold;font-size: 1.3em;"><?php echo __('show_details'); ?></span>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>

    <?php } ?>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="margin:0 auto;width: 42vh;min-width:26%;transform: translateY(-50%);top: 50%;">
      <div class="modal-content">
        <div class="modal-body" style="padding:0;">
          <div class="item col-xs-12">
            <div class="col-xs-12">
              <h2 style="position: relative;
    text-align: center;
    font-size: 2em;
    font-weight: bold;
    width: 100%;
    background: var(--icon-back-color);
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #cccccc;
    color: #fff;"> <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="opacity:1;float:left;">
                  <span aria-hidden="true" style="color: #fff;
    font-size: 2em;
    font-weight: bold;
    line-height: 0.79;">&times;</span></button></h2>
            </div>
            <div class="col-xs-12">
              <img src="imgs/offer2.jpg" style="width: 100%;">
            </div>
            <div class="discount" style="border-width:2px;display: block;
    text-align: center;
    direction: rtl;
    margin: 5px 0;
    font-size: 30px;">
              <span style="font-weight: bold;
    color: #a5342f;font-size: 1em;"></span>
              <pre style="font-weight: bold;
    font-size: 0.5em;
    text-align: right;
    background: var(--icon-back-color);
    color: white;
    padding: 10px;">
    - 2 بيتزا لارج
    - 2 باستا لارج
    - 1 تشيرز فرايز
    - 1 بيبسي
    </pre>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="text-align:center;">
          <a href="order.php" style="margin: auto;
    display: inline-block;
    background: var(--main-color);
    color: var(--text);
    border: 0;
    font-size: 20px;
    padding: 5px 20px;
    border-radius: 5px;
    font-weight: 900;text-decoration:none;"><i class="glyphicon glyphicon-share-alt"></i> اطلب الان</a>
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