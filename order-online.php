<?php 
  session_start();
  include "temps/settings.php"; 
?>
<!DOCTYPE html>

<html lang="<?php echo $site_setting['lang']; ?>" dir="<?php echo $site_setting['dir']; ?>">

<head>
  <?php include "temps/head.php"; ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
  <title><?php echo $site_setting['site-title']; ?> - Order Page</title>
  <style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    .close {
      text-shadow: none !important;
    }

    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }

    input[type=radio] {
      border: 2px solid var(--radio-border);
      appearance: none;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      outline: none;
      line-height: 1;
      margin: 0 5px;
      cursor: pointer;

    }

    input[type="radio"]:checked,
    input[type="checkbox"]:checked {
      text-align: center;
      font-weight: bold;
      font-size: 7px;
      margin: 0px 5px;
      outline: none;
      position: relative;
      background-color: var(--radio-back);
    }

    input[type="radio"]:checked::before,
    input[type="checkbox"]:checked::before {
      content: "✓";
      color: var(--radio-color);
      position: absolute;
      top: 1px;
      left: 2px;
      font-size: 15px;
    }

    input[type=checkbox] {
      border: 2px solid var(--radio-border);
      appearance: none;
      width: 20px;
      height: 20px;
      outline: none;
      line-height: 1;
      margin: 0 5px;
      border-radius: 5px;
      cursor: pointer;
    }

    input[type=file]:focus,
    input[type=checkbox]:focus,
    input[type=radio]:focus {
      outline: none;
    }

    .selectize-dropdown {
      cursor: pointer;
    }
  </style>
</head>

<body>
  <?php
  include "temps/header.php";
  include "includes/functions.php";
  add_visit('order-online');
  $cart = $_SESSION['cart'] ?? array();
  ?>
  <div class="sections order-online-page col-lg-6 col-12 mx-auto position-relative">
    <div class="categories pb-1">
      <ul class="gap-2">
        <?php
        $get_cat = mysqli_query($GLOBALS['conn'], "SELECT DISTINCT food_categories.id,food_categories.category_name FROM food_categories INNER JOIN food_items WHERE food_categories.id=food_items.cat_id AND food_categories.active=1 AND (food_items.active=1 OR (food_items.active = 2 AND food_items.from <= '" . time() . "' AND food_items.to >= '" . time() . "')) ORDER BY food_categories.sort ASC");
        $i = 0;
        while ($cat = mysqli_fetch_assoc($get_cat)) { ?>
          <li class="rounded <?php echo ($i == 0) ? 'active' : ''; ?> px-3 py-2" data-id="<?php echo $cat['id']; ?>">
            <p class="mb-0"><?php echo $cat['category_name']; ?></p>
          </li>
        <?php $i = 1;
        } ?>
      </ul>
    </div>
    <!-- <div style="position: sticky;display: block;top: 45px;margin-bottom: -20px;z-index:5;">
      <button data-toggle="modal" data-target="#search_window" style="display: flex;justify-content: center;align-items: center;gap: 5px;float: left;font-weight: bold;border: 0;background: var(--cat-header-active-back);color: var(--cat-header-active-color);padding: 4px 15px;border-radius: 5px;">بـحـث <i class="glyphicon glyphicon-search"></i></button>
    </div> -->
    <div class="items d-flex flex-column gap-4 mt-3 px-1">
      <?php
      $get_cat = mysqli_query($GLOBALS['conn'], "SELECT DISTINCT food_categories.id,food_categories.category_name FROM food_categories INNER JOIN food_items WHERE food_categories.id=food_items.cat_id AND food_categories.active=1 AND (food_items.active=1 OR (food_items.active = 2 AND food_items.from <= '" . time() . "' AND food_items.to >= '" . time() . "')) ORDER by food_categories.sort ASC");
      while ($cat = mysqli_fetch_assoc($get_cat)) { ?>
      <div class="category-items-list" data-id="<?php echo $cat['id']; ?>">
        <div class="category-title d-flex justify-content-center">
          <h3 class="fw-bold mb-2 pb-1 position-relative"><?php echo $cat['category_name']; ?></h3>
        </div>
        <div class="item-list d-flex flex-column gap-2">
          <?php
          $get_items = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_items WHERE cat_id='" . $cat['id'] . "' AND (active=1 OR (active=2 AND  `from` <= '" . time() . "' AND  `to` >= '" . time() . "')) ORDER BY SORT ASC");
          while ($item = mysqli_fetch_assoc($get_items)) { ?>
            <div class="item food-item bg-white rounded-4 p-2" data-id="<?php echo $item['id']; ?>">
              <div class="right rounded-4">
                <img data-src="<?php echo $item['img']; ?>" class="lazy">
              </div>
              <div class="content ps-3 px-1 py-1">
                <h3 class="fs-4 fw-bold"><?php echo $item['title']; ?></h3>
                <p class="fs-6"><?php echo $item['description']; ?></p>
                <div class="item-footer d-flex align-items-center justify-content-between">
                  <span class="fw-bold"><?php echo ($item['price'] > 0) ? $item['price'] . " " . $site_setting['currency'] : '[' . __('according_to_choice') . ']'; ?> <?php echo ($item['before_discount'] > 0) ? "بدلاً من " . "<span class='text-danger' style='text-decoration:line-through'>" . $item['before_discount'] . " " . $site_setting['currency'] . "</span>"  : ''; ?></span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="30px" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M0 72C0 58.7 10.7 48 24 48L69.3 48C96.4 48 119.6 67.4 124.4 94L124.8 96L537.5 96C557.5 96 572.6 114.2 568.9 133.9L537.8 299.8C532.1 330.1 505.7 352 474.9 352L171.3 352L176.4 380.3C178.5 391.7 188.4 400 200 400L456 400C469.3 400 480 410.7 480 424C480 437.3 469.3 448 456 448L200.1 448C165.3 448 135.5 423.1 129.3 388.9L77.2 102.6C76.5 98.8 73.2 96 69.3 96L24 96C10.7 96 0 85.3 0 72zM160 528C160 501.5 181.5 480 208 480C234.5 480 256 501.5 256 528C256 554.5 234.5 576 208 576C181.5 576 160 554.5 160 528zM384 528C384 501.5 405.5 480 432 480C458.5 480 480 501.5 480 528C480 554.5 458.5 576 432 576C405.5 576 384 554.5 384 528zM336 142.4C322.7 142.4 312 153.1 312 166.4L312 200L278.4 200C265.1 200 254.4 210.7 254.4 224C254.4 237.3 265.1 248 278.4 248L312 248L312 281.6C312 294.9 322.7 305.6 336 305.6C349.3 305.6 360 294.9 360 281.6L360 248L393.6 248C406.9 248 417.6 237.3 417.6 224C417.6 210.7 406.9 200 393.6 200L360 200L360 166.4C360 153.1 349.3 142.4 336 142.4z"/></svg>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
      <?php } ?>
    </div>
    <div class="order-footer">
      <p style="text-align:center;margin:0;">
        <?php echo __('min_order'); ?>: <?php echo $site_setting['order_min']; ?> <?php echo $site_setting['currency']; ?>
      </p>
      <button class="spinner-button-loading fs-6" <?php echo (calc_total_count($cart) <= 0 || calc_total_price($cart) < $site_setting['order_min'] || !is_work() || is_disabled()) ? 'style=" filter: grayscale(100%);" disabled data-min' : ''; ?>>
        <span class="count"><?php echo calc_total_count($cart); ?></span>
        <p class="content-button-loading">
          <?php echo __('show_your_cart'); ?>
        </p>
        <div class="lds-dual-ring"></div>
        <span class="price"><?php echo calc_total_price($cart); ?> <?php echo $site_setting['currency']; ?></span>
      </button>
      <?php
      if (!is_work()) {
        echo "<p style=';text-align: center;font-weight: bold;margin: 10px 0;margin-bottom: 0;font-size: 15px;color: #830000;'><i class='glyphicon glyphicon-alert' style='color:#ffcb07;'></i> " . __('out_of_work_period') . "</p>";
      } else if (is_disabled()) {
        echo "<p style='text-align: center;font-weight: bold;margin: 10px 0;margin-bottom: 0;font-size: 15px;color: #830000;'><i class='glyphicon glyphicon-alert' style='color:#ffcb07;'></i> " . order_disabled_msg() . "</p>";
      }
      ?>
    </div>
  </div>


  <!-- Search Modal -->
  <div class="modal fade" id="search_window" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="margin: 0 auto;transform: translateY(-50%);top: 50%;width: 350px;max-width: 90%;">
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between fw-bold fs-5" style="background: var(--modal-header-back);padding: 10px 7px;border: 0px;color: var(--modal-header-color);">
          <span>البحث في القائمة</span>
          <div type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color: var(--modal-header-color);opacity: 1;padding: 5px 5px;"><span aria-hidden="true" style="color: var(--modal-header-color);">×</span></div>
        </div>
        <div style="padding:5px;">
          <input type=" text" placeholder="البحث" style="width: 100%;direction: rtl;padding: 2px 5px;border-radius: 5px;border: 2px solid #979797;font-size: 15px;">
        </div>
        <div class="modal-body" style="padding:0;height: 70vh;overflow:auto;">
          <div class="no-result" style="height:100%;display:flex;justify-content:center;align-items:center;">لا يوجد نتائج.</div>
        </div>
        <div class=" modal-footer" style="text-align: center;display: flex;flex-direction: row;padding: 7px 5px;justify-content: center;column-gap: 5px;">
          <button data-bs-dismiss="modal" style="background: #a7a7a7;color: white;border-radius: 5px;height: 35px;padding: 5px 5px;display: inline-block;margin: auto;font-weight: bold;font-size: 20px;border: 0;flex: 1;">اغلاق</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Item Info Modal -->
  <div class="modal fade" id="item_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="margin: 0 auto;transform: translateY(-50%);top: 50%;width: 350px;max-width: 90%;">
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between fw-bold fs-5" style="background: var(--modal-header-back);padding: 10px 10px;border: 0px;color: var(--modal-header-color);">
          <span class="item-name"></span>
          <div type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color: var(--modal-header-color);opacity: 1;padding: 5px 5px;"><span aria-hidden="true" style="color: var(--modal-header-color);">×</span></div>
        </div>
        <div class="modal-body" style="padding:0;max-height: 70vh;overflow:auto;">
          <img src="" style="width: 100%;">
          <pre style="white-space: break-spaces;word-break: normal;font-family: 'Noto Naskh Arabic', serif;color: black;font-size: 15px;word-spacing: 4px;text-align: center;overflow: hidden;padding: 5px 0;"></pre>
          <div style="overflow:hidden;margin-bottom:10px !important;width:90%;margin:auto;display: flex;justify-content: space-between;align-items: center;">
            <div class="item_price" style="display:flex;column-gap:4px;">
              <span>10</span>
              <span><?php echo $site_setting['currency']; ?> </span>
            </div>
            <div class="item_count_custom d-flex align-items-center" style="background: var(--button-back);color: var(--button-color);border-radius: 5px;padding: 3px;height:27px;direction:ltr;">
              <span class="add" style="font-size: 20px;margin: 0 10px;cursor:pointer;color:var(--button-color);font-weight:bold;">+</span>
              <span class="count" style="font-size:20px;margin:0 5px;">1</span>
              <span class="minus" style="font-size: 20px;margin: 0 10px;cursor:pointer;color:var(--button-color);font-weight:bold;">-</span>
            </div>
          </div>
          <div class="item-sizes" style="margin-bottom:10px !important;width:90%;margin:auto;display:none;">
            <hr>
            <h3 class="d-flex justify-content-between fs-5" style="font-weight: bold;position:relative;margin-bottom:15px;"><?php echo __('choose_size'); ?>: <span class="fs-6 fw-bold text-white" style="background: #e51212;border-radius: 6px;padding: 6px 7px;"><?php echo __('required'); ?></span></h3>
            <ul style="list-style:none;padding:0;">

            </ul>
          </div>
          <div class="options">

          </div>
        </div>
        <div class="modal-footer" style="text-align: center;display: flex;flex-direction: row;padding: 7px 5px;justify-content: center;column-gap: 5px;">
          <button data-bs-dismiss="modal" class=" d-flex justify-content-center align-items-center" style="background: #a7a7a7;color: white;border-radius: 5px;height: 35px;padding: 5px 5px;display: inline-block;margin: auto;font-weight: bold;font-size: 16px;border: 0;flex: 1;"><?php echo __('close'); ?></button>
          <button class="spinner-button-loading d-flex justify-content-center align-items-center" style="background: var(--button-back);color: var(--button-color);border-radius: 5px;height: 35px;padding: 5px 5px;display: inline-block;font-weight: bold;font-size: 16px;border: 0;flex: 1;">
            <span class="content-button-loading"><?php echo __('add'); ?></span>
            <div class="lds-dual-ring"></div>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Card info Modal -->
  <div class="modal fade" id="card_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="margin: 0 auto;transform: translateY(-50%);top: 50%;width: 400px;max-width: 90%;">
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between fw-bold fs-5" style="background: var(--modal-header-back);padding: 10px 10px;border: 0px;color: var(--modal-header-color);">
          <?php echo __('card_title'); ?> <div type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color: var(--modal-header-color);opacity: 1;padding: 5px 5px;"><span aria-hidden="true" style="color: var(--modal-header-color);;">×</span></div>
          </h2>
        </div>
        <div class="modal-body" style="padding:0;max-height: 70vh;overflow:auto;">
          <div class="cart"></div>
        </div>
        <div class="modal-footer" style="padding: 5px;">
          <?php
          if (!is_work()) {
            echo "<p style='margin-top:3px;width:100%;direction:rtl;text-align: center;font-weight: bold;margin:0;font-size: 15px;color: #830000;'><i class='glyphicon glyphicon-alert' style='color:#ffcb07;'></i> ". __('out_of_work_period') ."</p>";
            $type = "display:none;";
          } else if (is_disabled()) {
            echo "<p style='margin-top:3px;width:100%;direction:rtl;text-align: center;font-weight: bold;margin:0;font-size: 15px;color: #830000;'><i class='glyphicon glyphicon-alert' style='color:#ffcb07;'></i> " . order_disabled_msg() . "</p>";
            $type = "display:none;";
          } else {
            $type = "display: inline-block;";
          }
          ?>
          <div style="text-align: center;display: flex;justify-content: center;column-gap: 5px;width: 100%;">
            <button class="d-flex align-items-center justify-content-center"data-bs-dismiss="modal" style="background: #a7a7a7;color: white;border-radius: 5px;height: 35px;padding: 5px 5px;margin: auto;font-weight: bold;font-size: 16px;border: 0;flex: 1;"><?php echo __('return_to_shop'); ?></button>
            <button class="d-flex align-items-center justify-content-center"style="background: var(--button-back);color: var(--button-color);<?php echo $type; ?>border-radius: 5px;height: 35px;padding: 5px 5px;font-weight: bold;font-size: 16px;border: 0;flex: 1;"><?php echo __('continue'); ?></button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- User info Modal -->
  <div class="modal fade" id="user_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="margin: 0 auto;transform: translateY(-50%);top: 50%;width: 400px;max-width: 90%;">
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between fw-bold fs-5" style="background: var(--modal-header-back);padding: 10px 10px;border: 0px;color: var(--modal-header-color);">
          <?php echo __('enter_your_info'); ?> <div type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color: var(--modal-header-color);opacity: 1;padding: 5px 5px;"><span aria-hidden="true" style="color: var(--modal-header-color);;">×</span></div>
          </h2>
        </div>
        <div class="modal-body" style="padding:0;max-height: 70vh;overflow:auto;">
          <?php
          if (isset($_COOKIE['order_info'])) {
            $order_info = json_decode($_COOKIE['order_info']);
          }
          ?>
          <div class="justify-content-evenly align-items-center mt-3" style="<?php echo $site_setting['order_from_branch'] == 1 ? 'display: flex' : 'display: none'; ?>">
            <div role="button">
                <input type="radio" class="btn-check" name="order-type" id="order-type-delivery" value="delivery" autocomplete="off" checked>
                <label class="btn btn-secondary border-0 d-flex flex-column justify-content-center align-items-center gap-1" for="order-type-delivery">
                    <img class="p-2" src="imgs/delivery-icon.png" width="60px">
                    <span class="fw-bold">توصيل</span>
                </label>
            </div>
            <?php
              if ($site_setting['order_from_branch'] == 1) {
            ?>
            <div role="button">
                <input type="radio" class="btn-check" name="order-type" id="order-type-branch" value="branch" autocomplete="off">
                <label class="btn btn-secondary border-0 d-flex flex-column justify-content-center align-items-center gap-1" for="order-type-branch">
                    <img class="p-2" src="imgs/branch-icon.png" width="60px">
                    <span class="fw-bold">استلام من الفرع</span>
                </label>
            </div>
            <?php } ?>
          </div>
          <?php
            if ($site_setting['order_from_branch'] == 1) {
          ?>
            <hr> 
          <?php } ?>
          <div class="mt-3" style="margin:auto;width:80%;margin-bottom:10px;">
            <label><?php echo __('name'); ?> <span style="font-size: 10px;color: gray;">(<?php echo __('at_least_2'); ?>)</span></label>
            <input class="name form-control bg-light" type="text" style="width:100%;" value="<?php echo $order_info[0] ?? ''; ?>" placeholder="<?php __('enter_your_name'); ?>">
          </div>
          <div class="mt-3" style="margin:auto;width:80%;margin-bottom:10px;">
            <label><?php echo __('phone'); ?></label>
            <input class="phone form-control bg-light" type="number" style="width:100%;direction:unset;" value="<?php echo $order_info[1] ?? ''; ?>" placeholder="<?php echo __('enter_phone'); ?>">
          </div>

          <?php
          $query = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_branches ORDER BY branch_name ASC");
          ?>
          <div id="choose-branch-input" class="mt-3" style="margin:auto;width:80%;margin-bottom:10px; <?php echo (mysqli_num_rows($query) <= 1) ? 'display:none;' : ''; ?>">
            <label><?php echo __('choose_branch'); ?></label>
            <select class="form-select bg-light" style="width:100%;" id="del-branch" placeholder="<?php echo __('choose_branch'); ?>">
            </select>
          </div>
          <div id="choose-area-input" class="mt-3" style="margin:auto;width:80%;margin-bottom:10px;">
            <label><?php echo __('choose_area'); ?></label>
            <select class="bg-light" style="width:100%;" id="del-loc" placeholder="<?php echo __('choose_area'); ?>">
            </select>
          </div>
          <div id="choose-street-input" class="mt-3" style="margin:auto;width:80%;margin-bottom:10px;">
            <label><?php echo __('address'); ?></label>
            <textarea style="width:100%;" class="street form-control bg-light" placeholder="<?php echo __('enter_address'); ?>"><?php echo $order_info[3] ?? ''; ?></textarea>
          </div>
          <div class="mt-3" style="margin:auto;width:80%;margin-bottom:10px;">
            <label><?php echo __('notes'); ?></label>
            <textarea style="width:100%;" class="notice form-control bg-light"></textarea>
          </div>
          <div class="mt-3" style="margin:auto;width:80%;margin-bottom:10px;">
            <input class="rounded" id="save_my_info" type="checkbox" value="1" CHECKED>
            <label style="line-height: 20px;vertical-align: top;"><?php echo __('save_me'); ?></label>
          </div>
          <hr>
          <div class="mt-3 ms-4" style="margin:auto;margin-bottom:5px;<?php echo ($site_setting['visa_av'] == 0) ? 'display:none;' : ''; ?>">
            <label style="line-height: 20px;vertical-align: top;"><?php echo __('payment_method'); ?>: </label>
          </div>
          <div class="bg-white border-0 mb-2 rounded-3 align-items-center justify-content-between p-2 ms-4" role="button" style="<?php echo ($site_setting['visa_av'] == 0) ? 'display:none;' : 'display:flex;'; ?>">
            <label for="how_pay_1" role="button">
              <img src="imgs/cash.png" width="30px" class="me-2">
              الدفع عند الاستلام
            </label>
            <input name="how_pay" id="how_pay_1" type="radio" value="0" CHECKED>
          </div>
          <?php if ($site_setting['visa_av'] == 1) { ?>
            <div class="bg-white border-0 mb-2 rounded-3 align-items-center justify-content-between p-2 ms-4" role="button" style="<?php echo ($site_setting['visa_av'] == 0) ? 'display:none;' : 'display:flex;'; ?>">
              <label for="how_pay_2" role="button">
                <img src="imgs/visa.png" width="40px" class="me-2">
                بطاقة الائتمان
              </label>
              <input name="how_pay" id="how_pay_2" type="radio" value="1">
            </div>
            <hr>
            <div id="accept_terms" class="mt-1" style="margin:auto;width:80%;">
              <input name="acceptance" id="acceptance" type="checkbox" value="1">
              <label style="line-height: 20px;vertical-align: top;padding-top:3px;" for="acceptance" role="butotn">اوفق على <a href="terms" style="color:var(--radio-back);" target="_blank">الشروط والاحكام</a></label>
            </div>  
            <hr>
          <?php } ?>
          <div class="discounts_code" style="margin:auto;width:80%;margin-bottom:10px;">
            <span style="font-weight:bold;margin-bottom:15px;border-bottom: 1px solid black;"><?php echo __('discount_coupons'); ?>: </span> &nbsp; <span class="form-text">(<?php echo __('click_apply'); ?>)</span>
            <div class="coupon" style="margin:auto;width:100%;text-align:center;position:relative;margin-top:15px;">
              <input class="form-control bg-light" type="text" class="coupon_code form-contorl bg-light" placeholder="<?php echo __('enter_it_here'); ?>" style="padding: 3px 5px;padding-left:55px;font-size: 16px;width: 100%;text-align: right;outline:none;">
              <span id="check_discount" style="position: absolute;left: 10px;top: 7px;font-weight: bold;cursor: pointer;"><?php echo __('use'); ?> <i class="glyphicon glyphicon-hand-right" style="margin-right: 5px;"></i></span>
            </div>
            <div class="coupon_activated" style="display:none;margin: auto;width: 100%;text-align: center;position: relative;margin-top:15px;">
              <p class="code" style="background: #bdbdbd;padding: 3px 5px;padding-left: 55px;font-size: 16px;width: 100%;text-align: right;outline: none;border: 1px;"></p>
              <span class="remove" style="position: absolute;left: 10px;top: 7px;font-weight: bold;cursor: pointer;"><?php echo __('remove'); ?></span>
              <input type="hidden" name="coupon_code" class="form-control" readonly>
            </div>
          </div>

        </div>
        <div class="modal-footer" style="text-align: center;display: flex;flex-direction: row;padding: 7px 5px;justify-content: center;column-gap: 5px;">
          <button data-bs-dismiss="modal" class="d-flex justify-content-center align-items-center" onclick="new bootstrap.Modal(document.getElementById('card_info')).show();" style="background: #a7a7a7;color: white;border-radius: 5px;height: 35px;padding: 5px 5px;display: inline-block;margin: auto;font-weight: bold;font-size: 16px;border: 0;flex: 1;"><?php echo __('return_to_cart'); ?></button>
          <button class="spinner-button-loading d-flex justify-content-center align-items-center" style="background: var(--button-back);color: var(--button-color);border-radius: 5px;height: 35px;padding: 5px 5px;display: inline-block;font-weight: bold;font-size: 16px;border: 0;flex: 1;">
            <span class="content-button-loading"><?php echo __('confirm_info'); ?></span>
            <div class="lds-dual-ring"></div>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Final info Modal -->
  <div class="modal fade" id="final_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="margin: 0 auto;transform: translateY(-50%);top: 50%;width: 400px;max-width: 90%;">
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between fw-bold fs-5" style="background: var(--modal-header-back);padding: 10px 10px;border: 0px;color: var(--modal-header-color);">
          <?php echo __('order_details'); ?> <div type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color: var(--modal-header-color);opacity: 1;padding: 5px 5px;"><span aria-hidden="true" style="color: var(--modal-header-color);;">×</span></div>
          </h2>
        </div>
        <div class="modal-body" style="padding:0;max-height: 70vh;overflow:auto;">
          <br>
          <div class="mx-auto mb-2" style="width:80%;">
            <span style="font-weight:bold;border-bottom: 1px solid black;"><?php echo __('customer_info'); ?>: </span>
          </div>
          <div class="d-flex justify-content-between mx-auto" style="width:80%;">
            <span><?php echo __('name'); ?>: </span>
            <span class="name"></span>
          </div>
          <div class="d-flex justify-content-between mx-auto" style="width:80%;">
            <span><?php echo __('phone'); ?>: </span>
            <span class="phone"></span>
          </div>
          <div id="order_type" class="d-flex justify-content-between mx-auto" style="width:80%;">
            <span>نوع الطلب: </span>
            <span></span>
          </div>
          <div class="justify-content-between mx-auto" style="width:80%;">
            <span><?php echo __('area'); ?>: </span>
            <span class="del-loc"></span>
          </div>
          <div class="justify-content-between mx-auto" style="width:80%;">
            <span><?php echo __('address'); ?>: </span>
            <span class="street"></span>
          </div>
          <div class="d-flex justify-content-between mx-auto" style="width:80%;">
            <span><?php echo __('payment_method'); ?>: </span>
            <span class="how_pay"></span>
          </div>
          <hr>
          <div class="d-flex justify-content-between mx-auto" style="width:80%;">
            <span style="font-weight:bold;margin-bottom:15px;border-bottom: 1px solid black;"><?php echo __('order_details'); ?>: </span>
          </div>
          <div class="items">

          </div>
          <div class="d-flex justify-content-between mx-auto" style="width:80%;">
            <span><?php echo __('info_notes'); ?>: </span>
            <span class="notice"></span>
          </div>
          <hr>
          <div class="mx-auto mb-2" style="width:80%;">
            <span style="font-weight:bold;border-bottom: 1px solid black;"><?php echo __('pay_info'); ?>: </span>
          </div>
          <div class="mx-auto d-flex justify-content-between" style="width:80%;">
            <span><?php echo __('sum'); ?>: </span>
            <span class="price"></span>
          </div>
          <div id="order_discount" class="mx-auto justify-content-between" style="width:80%;color: #a90000;font-weight: bold;display:none;">
            <span><?php echo __('order_discount'); ?>: </span>
            <span></span>
          </div>
          <div class="mx-auto justify-content-between" style="width:80%;">
            <span><?php echo __('delivery'); ?>: </span>
            <span class="del"></span>
          </div>
          <div class="mx-auto justify-content-between" style="width:80%;">
            <span><?php echo __('tax'); ?>: </span>
            <span id="total_tax"></span>
          </div>
          <div class="mx-auto justify-content-between" style="width:80%;">
            <span><?php echo __('visa_tax'); ?>: </span>
            <span id="visa_tax"></span>
          </div>
          <div class="mx-auto justify-content-between" id="delivery_discount" style="width:80%;color: #a90000;font-weight: bold;">
            <span><?php echo __('delivery_discount'); ?>: </span>
            <span></span>
          </div>
          <div class="mx-auto d-flex justify-content-between" style="width:80%;">
            <span><?php echo __('total_cost'); ?>: </span>
            <span class="total_price"></span>
          </div>
          <div class="mx-auto flex-wrap justify-content-between" style="width:80%;">
            <span><?php echo __('delivery_time'); ?>: </span>
            <span class="del_time"></span>
          </div>
          <br>
        </div>
        <div class="modal-footer" style="text-align: center;display: flex;flex-direction: row;padding: 7px 5px;justify-content: center;column-gap: 5px;">
          <button data-bs-dismiss="modal" class="d-flex justify-content-center align-items-center" onclick="new bootstrap.Modal(document.getElementById('user_info')).show();" style="background: #a7a7a7;color: white;color: white;border-radius: 5px;height: 35px;padding: 5px 5px;display: inline-block;margin: auto;font-weight: bold;font-size: 16px;border: 0;flex: 1;"><?php echo __('return_to_edit'); ?></button>
          <button class="spinner-button-loading d-flex justify-content-center align-items-center" style="background: var(--button-back);color: var(--button-color);border-radius: 5px;height: 35px;padding: 5px 5px;display: inline-block;font-weight: bold;font-size: 16px;border: 0;flex: 1;">
            <span class="content-button-loading"><?php echo __('send_order'); ?></span>
            <div class="lds-dual-ring"></div>
          </button>
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
  <script>
    var items_info = JSON.parse(<?php echo json_encode(get_items_data()); ?>);
    var currency = "<?php echo $site_setting['currency']; ?>"
    var lang = <?php echo json_encode(include 'language/'. $site_setting['lang'] .'.php'); ?>;
  </script>
  <?php include 'temps/jslibs.php'; ?>
  <script src="js/order-online.js"></script>
  <script src="https://qnbalahli.gateway.mastercard.com/static/checkout/checkout.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
  <script>
    $(document).ready(function() {
      const models = [];
      const makes = [
        <?php
        $query = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_branches ORDER BY branch_name ASC");
        while ($location = mysqli_fetch_assoc($query)) {
        ?> {
            text: '<?php echo $location["branch_name"]; ?>',
            value: '<?php echo $location["id"]; ?>'
          },
        <?php } ?>
      ];

      const modelsByMake = {
        <?php
        $query = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_branches ORDER BY branch_name ASC");
        while ($location = mysqli_fetch_assoc($query)) {
        ?>

          <?php echo $location["id"]; ?>: [
            <?php
            $query2 = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_locations WHERE branch_id='" . $location['id'] . "' AND active = 1 ORDER BY name ASC");
            while ($loc = mysqli_fetch_assoc($query2)) {
            ?> {
                text: '<?php echo $loc["name"]; ?>',
                value: '<?php echo $loc["id"]; ?>'
              },
            <?php } ?>
          ],
        <?php } ?>
      };

      const sel2 = $('#del-loc').selectize({
        options: models,
        valueField: 'value',
        labelField: 'text',
        sortField: 'value',
        searchField: ['text'],
      });

      const sel1 = $('#del-branch').selectize({
        options: makes,
        items: [{
          'text': 'طنطا',
          'value': '0'
        }],
        valueField: 'value',
        labelField: 'text',
        sortField: 'value',
        searchField: ['text'],
        onChange: (value) => {
          let options = models;
          if (value) {
            // get models for selected make
            options = options.concat(modelsByMake[value]);
          } else {
            // get all models
            $.each(modelsByMake, (i, v) => {
              options = options.concat(v);
            });
          }

          sel2[0].selectize.clear(); // clear sel2 selected items
          sel2[0].selectize.clearOptions(); // clear sel2 options

          // load options corresponding to sel1 value in sel2
          sel2[0].selectize.load((callback) => {
            callback(options);
          });

          // refresh sel2 options list
          sel2[0].selectize.refreshOptions();
        }
      });
      <?php

      $query = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_branches");
      $branch = mysqli_fetch_assoc($query);

      if (mysqli_num_rows($query) == 1) { ?>

        sel1[0].selectize.setValue('<?php echo $branch['id']; ?>')


      <?php }
      if (isset($order_info[2]) && !empty($order_info[2])) {
        $query = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_locations WHERE id='" . $order_info[2] . "'");
        $fetch = mysqli_fetch_assoc($query);

        $query = mysqli_query($GLOBALS['conn'], "SELECT * FROM food_branches WHERE id='" . $fetch['branch_id'] . "'");
        $branch = mysqli_fetch_assoc($query);
      ?>
        sel1[0].selectize.setValue('<?php echo $branch['id']; ?>')
        sel2[0].selectize.setValue('<?php echo $order_info[2]; ?>')
      <?php } ?>
    });
  </script>
</body>

</html>