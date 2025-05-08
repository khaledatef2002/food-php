<meta charset="UTF-8">
<link rel="icon" type="image/x-icon" href="<?php echo $site_setting['site-logo']; ?>">
<meta name="theme-color" content="<?php echo $colors_settings['header']; ?>" />
<meta name="keywords" content="<?php echo $site_setting['keywords']; ?>">
<meta name="description" content="<?php echo $site_setting['description']; ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta property="og:image" content="<?php echo $site_setting['og:image']; ?>" />
<meta name="author" content="Khaled Atef Mohamed ElGazzar">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="libs/bootstrap/css/bootstrap<?php echo ($site_setting['dir'] == 'rtl') ? '.rtl' : ''; ?>.min.css">
<link rel="stylesheet" href="libs//sweetalert2/sweet.css">
<link rel="stylesheet" href="css/main.css?id=250">
<?php if ($site_setting['dir'] == 'rtl'): ?>
<link rel="stylesheet" href="css/main.rtl.css">
<?php endif; ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;900&family=Noto+Naskh+Arabic&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Cairo', sans-serif;
        font-family: 'Noto Naskh Arabic', serif;
    }
</style>
<style>
    :root {
        --icon-border-color: <?php echo $colors_settings['icon_border']; ?>;
        --icon-color: <?php echo $colors_settings['icon']; ?>;
        --icon-back-color: <?php echo $colors_settings['icon_back']; ?>;
        --header: <?php echo $colors_settings['header']; ?>;
        --footer: <?php echo $colors_settings['footer']; ?>;
        --button-back: <?php echo $colors_settings['button_back']; ?>;
        --button-color: <?php echo $colors_settings['button_color']; ?>;
        --cat-header-back: <?php echo $colors_settings['cat_header_back']; ?>;
        --cat-header-color: <?php echo $colors_settings['cat_header_color']; ?>;
        --cat-header-active-back: <?php echo $colors_settings['cat_header_active_back']; ?>;
        --cat-header-active-color: <?php echo $colors_settings['cat_header_active_color']; ?>;
        --order-footer-back: <?php echo $colors_settings['order_footer_back']; ?>;
        --order-footer-color: <?php echo $colors_settings['order_footer_color']; ?>;
        --order-footer-n-back: <?php echo $colors_settings['order_footer_n_back']; ?>;
        --order-footer-n-color: <?php echo $colors_settings['order_footer_n_color']; ?>;
        --footer-color: <?php echo $colors_settings['footer_color']; ?>;
        --radio-border: <?php echo $colors_settings['radio_border']; ?>;
        --radio-back: <?php echo $colors_settings['radio_back']; ?>;
        --radio-color: <?php echo $colors_settings['radio_color']; ?>;
        --text: <?php echo $colors_settings['text']; ?>;
        --modal-header-back: <?php echo $colors_settings['modal_header_back']; ?>;
        --modal-header-color: <?php echo $colors_settings['modal_header_color']; ?>;
    }
</style>