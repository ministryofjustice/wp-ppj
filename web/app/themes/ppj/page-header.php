<?php

get_header();

$header_text           = get_field( 'header_text' );
$header_subtext        = get_field( 'header_subtext' );
$headerImageData       = get_field( 'header_image' );
$headerImageMobileData = get_field( 'header_image_mobile' );

if ( $headerImageData ) {
    if (is_front_page()) {
        $headerStyle = 'home';
    } else {
        $headerStyle = '';
    }
} else {
    $headerStyle     = 'dark';
}

$headerClass = '';
$headerClass .= ($headerStyle == 'dark') ?  'header--style-dark' : '';

?>
<div id="site-container">
    <div class="page-container">
        <div class="page-container__overlay"
             onclick="window.ppj.closeNavMenu()"
        ></div>
        <div class="header <?= $headerClass ?>">
            <?= ppj\partial(null, 'headerNavigationMenu') ?>

            <?php if ($headerImageData || $header_text): ?>
                <div class="header__hero">
                    <div class="header__img-ratio">
                        <?php if ($headerImageData): ?>
                            <?= ppj\partial(
                                    [
                                        'imageData' => $headerImageData,
                                        'mobileImageData' => $headerImageMobileData
                                    ],
                                'headerHeroImage')
                            ?>
                        <?php elseif( \ppj\LegNav\onLeg() ): ?>
                            <div class="header__hero-image-placeholder"></div>
                        <?php endif; ?>
                        <?php if ($header_text): ?>
                            <div class="header__overlay">
                                <div class="l-full">
                                    <div class="header__text-container">
                                        <?php if ($header_text): ?>
                                            <div class="header__text">
                                                <h1><?= $header_text ?></h1>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($header_subtext): ?>
                                            <div class="header__subtext">
                                                <?= $header_subtext ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

