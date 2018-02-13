<?php
$htmlTitle = get_field('html_title');
$htmlMetaDescription = get_field('html_meta_description');
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
    <?php if (constant('ENVIRONMENT') == 'development'): ?>
        <script>// in developement</script>
    <?php endif; ?>
    <?php if (constant('ENVIRONMENT') == 'staging'): ?>
        <script>// in staging</script>
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-WQ92V8W');
        </script>
    <?php endif; ?>
    <?php if (constant('ENVIRONMENT') == 'production'): ?>
        <script>// in production</script>
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-WQ92V8W');
        </script>
    <?php endif; ?>
</head>

<body  <?php body_class(); ?>>



