<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "temps/head.php"; ?>
  <title><?php echo $site_setting['site-title']; ?> - Safwa Card</title>
</head>

<body>
  <?php include "temps/header.php"; ?>

  <!-- Starting main page header Carousel -->
  <?php
  $carousel_query = mysqli_query($GLOBALS['conn'], "SELECT * FROM safwa_card_header ORDER BY sort ASC");
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
    $cards_query = mysqli_query($GLOBALS['conn'], "SELECT * FROM safwa_cards WHERE active = 1 ORDER BY id DESC");
    $cards_items_nums = mysqli_num_rows($cards_query);
    while ($cards_item = mysqli_fetch_assoc($cards_query)) {
    ?>

      <div class="col-lg-10 col-lg-push-1 col-xs-12">
        <div class="item-container col-lg-4 col-md-4 col-sm-6 col-xs-6" style="padding: 0 5px;">
          <div class="card item show_safwa_card_det col-xs-12" data-id="<?php echo $cards_item['id']; ?>">
            <div class="card-header text-center">
              <h2><?php echo $cards_item['title']; ?></h2>
            </div>
            <div class="card-body" style="    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;">
              <div class="col-xs-12" style="padding:0 5px;">
                <img src="<?php echo $cards_item['url']; ?>">
              </div>
            </div>
            <div class="card-footer">
              <span style="font-weight: bold;font-size: 1.6rem;display:block;text-align:center;">عرض التفاصيل</span>
            </div>
          </div>
        </div>
        <?php if ($cards_item = mysqli_fetch_assoc($cards_query)) { ?>

          <div class="item-container col-lg-4 col-md-4 col-sm-6 col-xs-6" style="padding: 0 5px;">
            <div class="card item show_safwa_card_det col-xs-12" data-id="<?php echo $cards_item['id']; ?>">
              <div class="card-header text-center">
                <h2><?php echo $cards_item['title']; ?></h2>
              </div>
              <div class="card-body">
                <div class="col-xs-12" style="padding:0 5px;">
                  <img src="<?php echo $cards_item['url']; ?>">
                </div>
              </div>
              <div class="card-footer">
                <span style="font-weight: bold;font-size: 1.6rem;display:block;text-align:center;">عرض التفاصيل</span>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>

    <?php } ?>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="margin:0 auto;width: 42vh;min-width:21%;transform: translateY(-50%);top: 50%;">
      <div class="modal-content">
        <div class="modal-header" style="background: var(--icon-back-color);display:flex;justify-content:center;">
          <h2 style="color: #fff;text-align:center;margin:0;display:flex;justify-content:space-between;align-items:center;width:100%;font-size:2.3rem;">
            <span class="content"></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="opacity:0.7;"><span aria-hidden="true" style="color: #fff;font-size:2.3rem;;font-weight: bold;margin:0">&times;</span></button>
          </h2>
        </div>
        <div class="modal-body" style="padding:0;">
          <div class="item col-xs-12" style="display: flex;flex-direction: column;margin: 0;padding: 0;">
            <div class="col-xs-12" style="margin:5px 0;">
              <img src="imgs/offer2.jpg" style="width: 100%;">
            </div>
            <div class="discount" style="border-width:2px;display: block;text-align: center;direction: rtl;font-size: 30px;padding:15px;">
              <pre style="margin-bottom:0;font-weight: bold;font-size: 0.5em;text-align: right;background: var(--icon-back-color);color: white;padding: 10px;white-space: break-spaces;word-break: break-word;">
                - 2 بيتزا لارج
                - 2 باستا لارج
                - 1 تشيرز فرايز
                - 1 بيبسي
                </pre>
            </div>
          </div>
        </div>
        <div class="modal-footer">
        </div>
      </div>
    </div>
  </div>
  <div class="footer" style="    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-weight: bold;">
    <?php include 'temps/footer.php'; ?>
  </div>
  <?php include 'temps/jslibs.php'; ?>
</body>

</html>