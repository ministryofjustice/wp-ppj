<?php

get_header();

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

if ( $headerImageMobileData = get_field( 'header_image_mobile' ) ) {
    $headerImageMobileAttr = json_encode($headerImageMobileData);
} else {
    $headerImageMobileAttr = '';
}

// menu data
$navMenuItems         = wp_get_nav_menu_items( 'Main menu' );
$filteredNavMenuItems = [];
foreach ( $navMenuItems as $item ) {
    $same = ( isset($post) && $post->ID == $item->object_id );

    $filteredNavMenuItems[] = [
        'title'    => $item->title,
        'url'      => $item->url,
        'selected' => $same
    ];
}
$mainMenuJSON = json_encode( $filteredNavMenuItems );
?>
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
