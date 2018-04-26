<?php

global $ppj_template_data;
$td = $ppj_template_data;

/**
 * @param $imageData
 * @param $breakpoint
 * @param $home
 * @param bool $width
 *
 * @return string representing one width specific clause of the srcset attribute
 */
function createSrcsetLine($imageData, $breakpoint, $home, $width=false) {
    $imageName = 'header-' . $breakpoint . $home;
    $srcset = $imageData['sizes'][$imageName];
    if ($width) {
        $srcset .= ' ' . $imageData['sizes']['header-' . $breakpoint . $home . '-width'] . 'w, ';
    }
    return $srcset;
}

/**
 * @param $headerStyle
 * @param $headerImageData
 * @param null $headerImageMobileData
 *
 * The following function will return a complete
 * HTML <img> srcset attribute value
 * based on the available image data
 *
 * @return string complete HTML <img> srcset attribute value
 */
function createSrcset($headerStyle, $headerImageData, $headerImageMobileData=null) {
    $breakpointWidths = [
        'mobile' => 320,
        'portrait' => 768,
        'landscape' => 1024,
        'large' => 1440
    ];

    $breakpointNames = array_keys($breakpointWidths);

    $home = ($headerStyle == 'home') ? '-home' : '';

    $srcset = '';
    if ($headerImageMobileData) {
        foreach(array_slice($breakpointNames, 0, 2) as $bp) {
            $srcset .= createSrcsetLine($headerImageMobileData, $bp, $home, true);
        }
        $breakpointNames = array_slice($breakpointNames, 2);
    }

    foreach ($breakpointNames as $bp) {
        $srcset .= createSrcsetLine($headerImageData, $bp, $home, true);
    }

    return $srcset;
}

$headerImageAttr    = '';
$headerStyle        = 'dark';
$headerImageAltText = '';

$headerImageData       = $td['imageData'];
$headerImageMobileData = $td['mobileImageData'];

if ( $headerImageData ) {
    $headerStyle = (ppj\onLegHome()) ? 'home' : '';
    $headerImageAttr = json_encode($headerImageData);
    $headerImageUrl = $headerImageData['url'];
    $srcset = createSrcset($headerStyle, $headerImageData, $headerImageMobileData);
    if (isset($headerImageData['alt'])) $headerImageAltText = $headerImageData['alt'];
}
?>

<div class="header__img-container">
    <img class="header__image"
         src="<?= $headerImageUrl ?>"
         srcset="<?= $srcset ?>"
         alt="<?= $headerImageAltText ?>"
         onload="window.ppj.handleImageLoaded()"
    />
</div>
