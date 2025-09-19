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
                            <a data-bs-toggle="modal" data-bs-target="#myModal">
                                <div>
                                    <?php include 'imgs/newhot.svg'; ?>
                                </div>
                            </a>
                        </div>
                        <?php
                    break;
                    case 'whatsapp': ?>
                        <div class="item <?php echo $class; ?>">
                            <a data-bs-toggle="modal" data-bs-target="#myModal">
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