<?php include "temps/settings.php"; ?>
<!DOCTYPE html>
<html lang="<?php echo $site_setting['lang']; ?>" dir="<?php echo $site_setting['dir']; ?>">

<head>
    <?php include "temps/head.php"; ?>
    <title><?php echo $site_setting['site-title']; ?> - HomePage</title>
</head>

<body>
    <!-- includeing main header and starting DB connection -->
    <?php include "temps/header.php"; ?>
    <?php
    include "includes/functions.php";
    add_visit('index');
    ?>
    <!-- Starting main page header Carousel -->
    <?php
    $carousel_query = mysqli_query($GLOBALS['conn'], "SELECT * FROM main_page_header ORDER BY sort ASC");
    $carousel_items_nums = mysqli_num_rows($carousel_query);
    ?>
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
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
            <?php
            }
            ?>
        </div>
    </div>
    <!-- End of main page header Carousel -->

    <?php
    $getIcons = mysqli_query($GLOBALS['conn'], "SELECT * FROM main_page_icons WHERE icon_active = '1'");
    $icons = [];
    while ($icon = mysqli_fetch_assoc($getIcons)) :
        $icons[] = $icon['icon_name'];
    endwhile;
    $class = "col-lg-4 col-md-6 col-sm-6 col-6";
    if(count($icons) == 2){
        $class = "col-lg-5 col-md-6 col-sm-6 col-6";
    }
    ?>

    <!-- Starting items main container -->
    <div class="sections main-page col-lg-4 col-md-6 col-sm-4 col-xs-12 d-flex flex-wrap align-content-center mx-auto <?php echo count($icons) >= 3 ? 'gap-2' : ''; ?>">
        <?php
            $order = 0;
            foreach ($icons as $icon) {
                $order++;

                if(($order == 1 || $order == 3) && count($icons) >= 3){
                    echo "<div class='w-100 d-flex justify-content-evenly'>";
                }

                switch ($icon):
                    case 'order': ?>
                        <div class="item <?php echo $class; ?>">
                            <a href="order-online">
                                <div>
                                    <?php include 'imgs/wb-esite.svg'; ?>
                                </div>
                            </a>
                        </div>
                    <?php break;
                    case 'menu': ?>
                        <div class="item <?php echo $class; ?>">
                            <a href="menu">
                                <div>
                                    <?php include 'imgs/menu.svg'; ?>
                                </div>
                            </a>
                        </div>
                    <?php break;
                    case 'phone': ?>
                        <div class="item <?php echo $class; ?>">
                            <a data-bs-toggle="offcanvas" data-bs-target="#phoneOffCanvas" role="button">
                                <div>
                                    <?php include 'imgs/newhot.svg'; ?>
                                </div>
                            </a>
                        </div>
                        <?php
                    break;
                    case 'whatsapp': ?>
                        <div class="item <?php echo $class; ?>">
                            <a data-bs-toggle="offcanvas" data-bs-target="#whatsappOffCanvas" role="button">
                                <div>
                                    <?php include 'imgs/whatsapp.svg'; ?>
                                </div>
                            </a>
                        </div>
                        <?php
                    break;

                    case 'social': ?>
                        <div class="item <?php echo $class; ?>">
                            <a href="social">
                                <div>
                                    <?php include 'imgs/socialmedia.svg'; ?>
                                </div>
                            </a>
                        </div>
                    <?php break;
                endswitch;

                if(($order == 2 || $order == count($icons)) && count($icons) >= 3){
                    echo "</div>";
                }
            }
        ?>
    </div>
    <!-- End of items main container -->
    <?php if ($is_visa_available): ?>
        <ul id="faq" class="list-unstyled d-flex justify-content-center align-items-center gap-4 mb-0" style="flex-wrap: wrap;">
            <li class="fw-bold" style="cursor:pointer;">
                <a href="/privacy" class="text-dark text-decoration-none"><?php echo __('privacy_policy'); ?></a>
            </li>
            <li class="fw-bold" style="cursor:pointer;">
                <a href="/refund" class="text-dark text-decoration-none"><?php echo __('refund_policy'); ?></a>
            </li>
            <li class="fw-bold" style="cursor:pointer;">
                <a href="/terms" class="text-dark text-decoration-none">الشروط والاحكام</a>
            </li>
        </ul>
    <?php endif; ?>



    <div class="offcanvas offcanvas-start" tabindex="-1" id="phoneOffCanvas" aria-labelledby="phoneOffCanvasLabel" style="width: 300px;">
        <div class="offcanvas-header" style="background: var(--modal-header-back); color: var(--modal-header-color); padding: 10px;">
            <h5 class="offcanvas-title fw-bold fs-5" id="phoneOffCanvasLabel">
                <?php echo __('contact_phones'); ?>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" style="filter: invert(1);"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="list-unstyled d-flex flex-column gap-3">
                <?php
                $get_phones = mysqli_query($GLOBALS['conn'], "SELECT * FROM order_page WHERE type = 'phone'");
                while ($fetch = mysqli_fetch_assoc($get_phones)) {
                ?>
                    <a class="text-decoration-none text-white" href="tel:<?php echo $fetch['value']; ?>">
                        <li class="rounded d-flex align-items-center gap-2" style="background: var(--button-back);color: var(--button-color);">
                            <span class="d-flex align-items-center ps-3 pe-1">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width: 25px;fill: var(--button-color);"  viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M376 32C504.1 32 608 135.9 608 264C608 277.3 597.3 288 584 288C570.7 288 560 277.3 560 264C560 162.4 477.6 80 376 80C362.7 80 352 69.3 352 56C352 42.7 362.7 32 376 32zM384 224C401.7 224 416 238.3 416 256C416 273.7 401.7 288 384 288C366.3 288 352 273.7 352 256C352 238.3 366.3 224 384 224zM352 152C352 138.7 362.7 128 376 128C451.1 128 512 188.9 512 264C512 277.3 501.3 288 488 288C474.7 288 464 277.3 464 264C464 215.4 424.6 176 376 176C362.7 176 352 165.3 352 152zM176.1 65.4C195.8 60 216.4 70.1 224.2 88.9L264.7 186.2C271.6 202.7 266.8 221.8 252.9 233.2L208.8 269.3C241.3 340.9 297.8 399.3 368.1 434.2L406.7 387C418 373.1 437.1 368.4 453.7 375.2L551 415.8C569.8 423.6 579.9 444.2 574.5 463.9L573 469.4C555.4 534.1 492.9 589.3 416.6 573.2C241.6 536.1 103.9 398.4 66.8 223.4C50.7 147.1 105.9 84.6 170.5 66.9L176 65.4z"/></svg>
                            </span>
                            <p class="pt-2 pb-1 mb-0"><?php echo $fetch['value']; ?></p>
                        </li>
                    </a>
                <?php
                }
                ?>
            </ul>
        </div>
        <div class="offcanvas-footer">
            <button class="btn btn-light w-100" style="background-color: #e1e1e1 !important;" type="button" data-bs-dismiss="offcanvas">إغلاق</button>
        </div>
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="whatsappOffCanvas" aria-labelledby="whatsappOffCanvasLabel" style="width: 300px;">
        <div class="offcanvas-header" style="background: var(--modal-header-back); color: var(--modal-header-color); padding: 10px;">
            <h5 class="offcanvas-title fw-bold fs-5" id="whatsappOffCanvasLabel">
                أرقام الواتساب
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" style="filter: invert(1);"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="list-unstyled d-flex flex-column gap-3">
                <?php
                $get_phones = mysqli_query($GLOBALS['conn'], "SELECT * FROM order_page WHERE type = 'whatsapp'");
                while ($fetch = mysqli_fetch_assoc($get_phones)) {
                ?>
                    <a class="text-decoration-none text-white" href="tel:<?php echo $fetch['value']; ?>">
                        <li class="rounded d-flex align-items-center gap-2" style="background: var(--button-back);color: var(--button-color);">
                            <span class="d-flex align-items-center ps-3 pe-1">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width: 25px;fill: var(--button-color);"viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M476.9 161.1C435 119.1 379.2 96 319.9 96C197.5 96 97.9 195.6 97.9 318C97.9 357.1 108.1 395.3 127.5 429L96 544L213.7 513.1C246.1 530.8 282.6 540.1 319.8 540.1L319.9 540.1C442.2 540.1 544 440.5 544 318.1C544 258.8 518.8 203.1 476.9 161.1zM319.9 502.7C286.7 502.7 254.2 493.8 225.9 477L219.2 473L149.4 491.3L168 423.2L163.6 416.2C145.1 386.8 135.4 352.9 135.4 318C135.4 216.3 218.2 133.5 320 133.5C369.3 133.5 415.6 152.7 450.4 187.6C485.2 222.5 506.6 268.8 506.5 318.1C506.5 419.9 421.6 502.7 319.9 502.7zM421.1 364.5C415.6 361.7 388.3 348.3 383.2 346.5C378.1 344.6 374.4 343.7 370.7 349.3C367 354.9 356.4 367.3 353.1 371.1C349.9 374.8 346.6 375.3 341.1 372.5C308.5 356.2 287.1 343.4 265.6 306.5C259.9 296.7 271.3 297.4 281.9 276.2C283.7 272.5 282.8 269.3 281.4 266.5C280 263.7 268.9 236.4 264.3 225.3C259.8 214.5 255.2 216 251.8 215.8C248.6 215.6 244.9 215.6 241.2 215.6C237.5 215.6 231.5 217 226.4 222.5C221.3 228.1 207 241.5 207 268.8C207 296.1 226.9 322.5 229.6 326.2C232.4 329.9 268.7 385.9 324.4 410C359.6 425.2 373.4 426.5 391 423.9C401.7 422.3 423.8 410.5 428.4 397.5C433 384.5 433 373.4 431.6 371.1C430.3 368.6 426.6 367.2 421.1 364.5z"/></svg>
                            </span>
                            <p class="pt-2 pb-1 mb-0"><?php echo $fetch['value']; ?></p>
                        </li>
                    </a>
                <?php
                }
                ?>
            </ul>
        </div>
        <div class="offcanvas-footer">
            <button class="btn btn-light w-100" style="background-color: #e1e1e1 !important;" type="button" data-bs-dismiss="offcanvas">إغلاق</button>
        </div>
    </div>


    <!-- Footer -->
    <div class="footer" style="    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;">
        <?php include 'temps/footer.php'; ?>
    </div>
    <?php include 'temps/jslibs.php'; ?>
</body>

</html>