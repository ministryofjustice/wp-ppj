<?php
global $post;

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php echo get_bloginfo('name'); ?> - <?php the_title(); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link href="https://fonts.googleapis.com/css?family=Barlow:300,400,500,600,700" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDplfBkLzNA3voskfGyExYnQ46MJ0VtpA"></script>
    <?php wp_head(); ?>
</head>

<body  <?php body_class(); ?>>
<div id="site-container">
    <page-container>
        <page-header :menu-data='<?= $mainMenuJSON ?>'
                     header-image='<?= $headerImageAttr ?>'
                     header-style="<?= $headerStyle ?>"
                     header-subtext="<?= $header_subtext; ?>"
        >
            <?= $header_text; ?>
        </page-header>

