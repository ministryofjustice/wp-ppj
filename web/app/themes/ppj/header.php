<?php
    $htmlTitle = get_field('html_title');
    $htmlMetaDescription = get_field('html_meta_description');

    $bodyClasses = [];
    if ($legName  = ppj\getLegNameFromPath()) $bodyClasses[] = $legName;
    if ($postSlug = $post->post_name)         $bodyClasses[] = 'post-slug--' . $postSlug;
    if (ppj\isLegHome())                      $bodyClasses[] = 'leg-home';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?= $htmlTitle ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?= $htmlMetaDescription ?>" />
    <?php wp_head(); ?>
    <?php if (constant('ENVIRONMENT') == 'staging' || constant('ENVIRONMENT') == 'production'): ?>
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-WQ92V8W');
        </script>
    <?php endif; ?>
</head>

<body <?php body_class($bodyClasses); ?>>



