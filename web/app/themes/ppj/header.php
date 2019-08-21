<?php

use ppj\LegNav;

$htmlTitle = get_field('html_title');
$htmlMetaDescription = get_field('html_meta_description');

$bodyClasses = [];
if ($legName = LegNav\legName()) {
    $bodyClasses[] = $legName;
}
if ($postSlug = $post->post_name) {
    $bodyClasses[] = 'post-slug--' . $postSlug;
}
if (LegNav\onLegHome()) {
    $bodyClasses[] = 'leg-home';
}
if (LegNav\onLeg()) {
    $bodyClasses[] = 'leg';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?= $htmlTitle ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content="<?= $htmlMetaDescription ?>"/>

    <!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l !== 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-N2SCJMC');</script>
    <!-- End Google Tag Manager -->

    <?php wp_head(); ?>
    <script><?php // code that must be available before the HTML has finished loading. ?>
        window.ppj = {};

        window.ppj.handleImageLoaded = function () {
            document.getElementsByClassName('header__image')[0].classList.add('header__image--loaded');
        };
    </script>
    <?php if (constant('ENVIRONMENT') == 'staging' || constant('ENVIRONMENT') == 'production'): ?>
        <script>
            (function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start':
                        new Date().getTime(), event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s), dl = l !== 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', 'GTM-WQ92V8W');
        </script>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
</head>

<body <?php body_class($bodyClasses); ?>>
<nav aria-label="Skip Links"><a href="#maincontent" id="skip-link">Skip to main content</a></nav>
<!-- Google Tag Manager (noscript) -->
<noscript>
If this message is displayed, you do not have JavaScript enabled. If you cannot enable JavaScript, telephone us on 0845 010 3508 to request the information you require in an alternative format.
</noscript>
<!-- End Google Tag Manager (noscript) -->
