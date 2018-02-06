<?php
global $post;

$htmlTitle = get_field('html_title');
$htmlMetaDescription = get_field('html_meta_description');

// header text
$header_text    = get_field( 'header_text' );
$header_subtext = get_field( 'header_subtext' );

if ( $headerImageData = get_field( 'header_image' ) ) {
    if (is_front_page()) {
        $headerStyle = 'home';
    } else {
        $headerStyle = '';
    }
    $headerImageAttr = json_encode($headerImageData);

} else {
    $headerImageAttr = '';
    $headerStyle        = 'dark';
}

if ( $headerImageMobileData = get_field( 'header_image_mobile' ) ) {
    $headerImageMobileAttr = json_encode($headerImageMobileData);
} else {
    $headerImageMobileAttr = '';
}

// menu data
$navMenuItems         = wp_get_nav_menu_items( 'Main menu' );
$filteredNavMenuItems = [];
foreach ( $navMenuItems as $item ) {
    $same                   = ( $post->ID == $item->object_id );
    $filteredNavMenuItems[] = [
        'title'    => $item->title,
        'url'      => $item->url,
        'selected' => $same
    ];
}
$mainMenuJSON = json_encode( $filteredNavMenuItems );

$googleAnalyticsPropertyId = constant("GOOGLE_ANALYTICS_PROPERTY_ID");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?= $htmlTitle ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="<?= $htmlMetaDescription ?>" />
    <link href="https://fonts.googleapis.com/css?family=Barlow:300,400,500,600,700" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDplfBkLzNA3voskfGyExYnQ46MJ0VtpA"></script>
    <?php wp_head(); ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= $googleAnalyticsPropertyId ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= $googleAnalyticsPropertyId ?>');
    </script>
</head>

<body  <?php body_class(); ?>>
<div id="site-container">
    <page-container>
        <page-header :menu-data='<?= $mainMenuJSON ?>'
                     header-image='<?= $headerImageAttr ?>'
                     header-image-mobile='<?= $headerImageMobileAttr ?>'
                     header-style="<?= $headerStyle ?>"
                     header-subtext="<?= $header_subtext; ?>"
        >
            <?= $header_text; ?>
        </page-header>

