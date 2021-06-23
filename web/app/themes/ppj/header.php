<?php

    use ppj\LegNav;

    $htmlTitle = get_field('html_title');
    $htmlMetaDescription = get_field('html_meta_description');

    $bodyClasses = [];
    if ($legName = LegNav\legName()) {
        $bodyClasses[] = $legName;
    }
    if ($post != NULL) {
        if ($postSlug = $post->post_name) {
            $bodyClasses[] = 'post-slug--' . $postSlug;
        }
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
        <?php wp_head(); ?>
        <script><?php // code that must be available before the HTML has finished loading. ?>
            window.ppj = {};

            window.ppj.handleImageLoaded = function () {
                document.getElementsByClassName('header__image')[0].classList.add('header__image--loaded');
            };
        </script>
        <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    </head>

    <body <?php body_class($bodyClasses); ?>>

    <?php

    if ( ! function_exists( 'wp_body_open' ) ) {
        /**
         * Open the body tag, pull in any hooked triggers.
         **/
        function wp_body_open() {
            do_action( 'wp_body_open' );
        }
    }
    wp_body_open();

    ?>

    <div class="ccfw-background-grey-overlay"></div>
    <?php do_action('after_body_open_tag'); ?>

<?php require_once( get_template_directory() . '/page-header-two.php'); ?>
