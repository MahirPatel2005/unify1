<?php
$favicon_url = get_favicon_url();
$apple_touch_icon_url = get_apple_touch_icon_url();
?>
<link rel="manifest" href="<?php echo get_uri('pwa/app_manifest'); ?>">
<meta id="theme-color-meta-tag" name="theme-color" content="<?php echo get_setting("pwa_theme_color") ? get_setting("pwa_theme_color") : "#1c2026"; ?>">

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="fairsketch">
<?php $favicon_192_url = get_file_uri("assets/images/favicon-192.png"); ?>
<link rel="icon" href="<?php echo $favicon_url; ?>" type="image/png" sizes="32x32" />
<link rel="icon" href="<?php echo $favicon_192_url; ?>" type="image/png" sizes="192x192" />
<link rel="shortcut icon" href="<?php echo $favicon_url; ?>" type="image/png" />
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $favicon_192_url; ?>" />
<link rel="apple-touch-icon-precomposed" href="<?php echo $favicon_192_url; ?>" />

<title>
    <?php
    $router = service('router');
    $controller_name = strtolower(get_actual_controller_name($router));
    $title = get_setting('app_title');
    if (strpos(app_lang($controller_name), '.') === false) {
        $title = app_lang($controller_name) . " | " . $title;
    }
    echo $title;
    ?>
</title>