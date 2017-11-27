<?php

$carousel = get_field('carousel');
$header_text = get_field('header_text');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title>Prison & Probation Jobs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- <link rel="stylesheet" type="text/css" href="/wp/wp-content/main.css">-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDplfBkLzNA3voskfGyExYnQ46MJ0VtpA"></script>

    <!-- <script src="main.js"></script>-->
    <?php wp_head(); ?>
</head>
<body  <?php body_class(); ?>>
<div id="site-container">
    <?php

    $carouselImages = [];
    foreach ($carousel as $slide) {
        array_push($carouselImages, $slide['image']['url']);
    }

    $carouselImagesAttr = implode(',', $carouselImages);

    ?>
<page-container>
    <page-header carousel-images="<?= $carouselImagesAttr ?>">
        <?php echo $header_text; ?>
    </page-header>

