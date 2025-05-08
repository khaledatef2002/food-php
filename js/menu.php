<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "temps/head.php"; ?>
  <title><?php echo $site_setting['site-title']; ?> - Menu</title>
  <style>
    .ui-loader {
      display: none;
    }

    .paper-slide {
      background-color: white !important;
    }
  </style>
</head>

<body>
  <?php include 'temps/header.php'; ?>
  <?php include "includes/functions.php"; ?>
  <?php
  add_visit('menu');
  $select_menus = mysqli_query($GLOBALS['conn'], "SELECT * FROM menu ORDER BY sort ASC");
  $menu_page_num = mysqli_num_rows($select_menus);
  $order = 1;
  ?>
  <div class="sections col-12" style="  height: calc(100% - 110px - 17% - 60px);
    margin: auto;
    position: relative;
    top: 62px;
    margin-bottom: 65px;">
    <span class="nPage">
      <span id="currentPage">1</span> /
      <span id="totalPages"><?php echo $menu_page_num; ?></span>
    </span>
    <div id="papers" class="col-lg-6 col-md-6 col-sm-8 col-12 mx-auto">
      <!-- one paper slider unit -->
      <?php
      while ($menu_page = mysqli_fetch_assoc($select_menus)) {
      ?>
        <div>
          <img src="<?php echo $menu_page['url']; ?>" alt="Menu page <?php echo $order; ?>" title="Menu page <?php echo $order; ?>">
        </div>
      <?php
        $order++;
      }
      ?>
    </div>

  </div>
  <a style="margin: auto;
    display: flex;
    background: var(--button-back);
    color: var(--button-color) !important;
    border: 0;
    font-size: 20px;
    padding: 5px 20px;
    border-radius: 5px;
    font-weight: 900;
    text-decoration: none;
    cursor: pointer;
    justify-content: space-evenly;
    align-items: center;
    column-gap: 5px;
    box-shadow: none;" class="order"><i class="glyphicon glyphicon-share-alt"></i> اطلب الان</a>
  <div class="footer" style="    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;">
    <?php include 'temps/footer.php'; ?>
  </div>
  <?php include 'temps/jslibs.php'; ?>

  <script src="libs/jquery/jquery.mobile-1.4.5.min.js"></script>

</body>

</html>