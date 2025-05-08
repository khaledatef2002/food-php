<?php include "temps/settings.php"; ?>
<!DOCTYPE html>
<html lang="<?php echo $site_setting['lang']; ?>" dir="<?php echo $site_setting['dir']; ?>">

<head>
    <?php include "temps/head.php"; ?>
    <title><?php echo $site_setting['site-title']; ?> - Social Media</title>
</head>

<body>
    <?php include "temps/header.php"; ?>
    <div class="sections social-page col-lg-8 col-lg-push-2 col-xs-12">
        <?php
        $getSocial = mysqli_query($GLOBALS['conn'], "SELECT * FROM social_media ORDER BY sort ASC");
        while ($fetchSocial = mysqli_fetch_assoc($getSocial)) {
        ?>
            <div class="row col-xs-12">
                <div class="item col-lg-2 col-md-4 col-sm-6 col-xs-6">
                    <a href="<?php echo $fetchSocial['link']; ?>" target="_blank">
                        <div>
                            <?php include $fetchSocial['img_url']; ?>
                        </div>
                    </a>
                </div>
                <?php if ($fetchSocial = mysqli_fetch_assoc($getSocial)) { ?>
                    <div class="item col-lg-2 col-md-4 col-sm-6 col-xs-6">
                        <a href="<?php echo $fetchSocial['link']; ?>" target="_blank">
                            <div>
                                <?php include $fetchSocial['img_url']; ?>
                            </div>
                        </a>
                    </div>
                <?php
                }
                ?>
            </div>
        <?php
        }
        ?>
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