<?php
namespace ppj;

function enqueue_scripts()
{
    $legName  = getLegNameFromPath();
    $root_dir = get_template_directory_uri() . '/dest';

    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Barlow:300,400,500,600,700', null, null );
    wp_enqueue_style( 'main', $root_dir . mix_asset( "/css/{$legName}.css" ), null, null );
    wp_enqueue_script( 'main-js', $root_dir . mix_asset( '/js/main.js' ), null, null, true );
    wp_enqueue_script( 'wistia', '//fast.wistia.com/assets/external/E-v1.js', null, null, true );

    if (is_page_template('find-a-job.php')) {
        wp_enqueue_script( 'google-maps-js','https://maps.googleapis.com/maps/api/js?key=AIzaSyDDplfBkLzNA3voskfGyExYnQ46MJ0VtpA', null, null, true );
        wp_enqueue_script( 'find-a-job-js', $root_dir . mix_asset('/js/find-a-job.js'), ('google-maps.js'), null, true );
    }
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts');

function mix_asset($filename)
{
    $manifest_path = dirname(__FILE__) . '/dest/mix-manifest.json';
    $manifest = json_decode(file_get_contents($manifest_path), true);
    if (!isset($manifest[$filename])) {
        error_log("Mix asset '$filename' does not exist in manifest.");
    }
    return $manifest[$filename];
}

function registerImagesSizes()
{
    add_image_size( 'header-mobile',      320, 180, true );
    add_image_size( 'header-mobile-home', 320, 244, true );

    add_image_size( 'header-portrait',      768, 320, true );
    add_image_size( 'header-portrait-home', 768, 400, true );

    add_image_size( 'header-landscape',      1024, 420, true );
    add_image_size( 'header-landscape-home', 1024, 444, true );

    add_image_size( 'header-large',      1440, 560, true );
    add_image_size( 'header-large-home', 1440, 624, true );
}
add_action('init', __NAMESPACE__ . '\\registerImagesSizes');
