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
    ?>

    <!-- Starting items main container -->
    <div class="sections main-page col-lg-4 col-md-6 col-sm-4 col-xs-12 d-flex flex-wrap align-content-center mx-auto">
        <?php
        $num = 0;
        $rows = ceil(mysqli_num_rows($getIcons) / 2);
        $everyRow = mysqli_num_rows($getIcons) / $rows;
        $counter = 0;
        if (mysqli_num_rows($getIcons) % 2 == 0 && mysqli_num_rows($getIcons) != 6) :

            for ($i = 0; $i < $rows; $i++) :
        ?>
                <div class="col-12" style="padding:0;display: flex;justify-content: center;">
                    <?php
                    for ($j = 1; $j <= $everyRow; $j++) :
                        $counter += 1;
                        switch ($icons[$counter - 1]):
                            case 'order': ?>
                                <div class="item col-lg-4 col-md-4 col-sm-4 col-4">
                                    <a href="order">
                                        <div>
                                            <?php include 'imgs/Delivery2.svg'; ?>
                                        </div>
                                    </a>
                                </div>
                            <?php break;

                            case 'offers': ?>
                                <div class="item col-lg-4 col-md-4 col-sm-4 col-4">
                                    <a href="offers">
                                        <div>
                                            <?php include 'imgs/Offer.svg'; ?>
                                        </div>
                                    </a>
                                </div>
                            <?php break;

                            case 'menu': ?>
                                <div class="item col-lg-4 col-md-4 col-sm-4 col-4">
                                    <a href="menu">
                                        <div>
                                            <?php include 'imgs/menu.svg'; ?>
                                        </div>
                                    </a>
                                </div>
                            <?php break;

                            case 'social': ?>
                                <div class="item col-lg-4 col-md-4 col-sm-4 col-4">
                                    <a href="social">
                                        <div>
                                            <?php include 'imgs/socialmedia.svg'; ?>
                                        </div>
                                    </a>
                                </div>
                            <?php break;

                            case 'safwa': ?>
                                <div class="item col-lg-4 col-md-4 col-sm-4 col-4">
                                    <a href="safwa">
                                        <div>
                                            <?php include 'imgs/safwa.svg'; ?>
                                        </div>
                                    </a>
                                </div>
                            <?php break;

                            case 'comments': ?>
                                <div class="item col-lg-4 col-md-4 col-sm-4 col-4">
                                    <a href="comments">
                                        <div>
                                            <?php include 'imgs/comments.svg'; ?>
                                        </div>
                                    </a>
                                </div>
                    <?php break;
                        endswitch;
                    endfor;
                    ?>
                </div>
            <?php endfor; ?>
            <?php
        else :
            foreach ($icons as $icon) :
                switch ($icon):
                    case 'order': ?>
                        <div class="item col-lg-4 col-md-4 col-sm-4 col-4">
                            <a href="order">
                                <div>
                                    <?php include 'imgs/Delivery2.svg'; ?>
                                </div>
                            </a>
                        </div>
                    <?php break;

                    case 'offers': ?>
                        <div class="item col-lg-4 col-md-4 col-sm-4 col-4">
                            <a href="offers">
                                <div>
                                    <?php include 'imgs/Offer.svg'; ?>
                                </div>
                            </a>
                        </div>
                    <?php break;

                    case 'menu': ?>
                        <div class="item col-lg-4 col-md-4 col-sm-4 col-4">
                            <a href="menu">
                                <div>
                                    <?php include 'imgs/menu.svg'; ?>
                                </div>
                            </a>
                        </div>
                    <?php break;

                    case 'social': ?>
                        <div class="item col-lg-4 col-md-4 col-sm-4 col-4">
                            <a href="social">
                                <div>
                                    <?php include 'imgs/socialmedia.svg'; ?>
                                </div>
                            </a>
                        </div>
                    <?php break;

                    case 'safwa': ?>
                        <div class="item col-lg-4 col-md-4 col-sm-4 col-4">
                            <a href="safwa">
                                <div>
                                    <?php include 'imgs/safwa.svg'; ?>
                                </div>
                            </a>
                        </div>
                    <?php break;

                    case 'comments': ?>
                        <div class="item col-lg-4 col-md-4 col-sm-4 col-4">
                            <a href="comments">
                                <div>
                                    <?php include 'imgs/comments.svg'; ?>
                                </div>
                            </a>
                        </div>
        <?php break;
                endswitch;
            endforeach;
        endif;
        ?>
        <?php if ($site_setting['visa_av'] == 1) : ?>
            <ul style="list-style: none;display: flex;gap: 30px;position:absolute;bottom:5px;">
                <li style="display: flex;justify-content: center;align-items: center;cursor:pointer;"><?php echo __('privacy_policy'); ?></li>
                <li style="display: flex;justify-content: center;align-items: center;cursor:pointer;"><?php echo __('refund_policy'); ?></li>
            </ul>
        <?php endif; ?>
    </div>
    <!-- End of items main container -->

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