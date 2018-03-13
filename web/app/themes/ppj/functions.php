<?php
namespace ppj;

function enqueue_scripts()
{

    $root_dir = get_template_directory_uri();
    wp_enqueue_style('main-css', $root_dir . mix_asset('/dest/css/main.css'), null, false);
    wp_enqueue_script('main-js', $root_dir . mix_asset('/dest/js/main.js'), null, false, true);
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts');

function mix_asset($filename)
{
    $manifest_path = dirname(__FILE__) . '/mix-manifest.json';
    $manifest = json_decode(file_get_contents($manifest_path), true);
    if (!isset($manifest[$filename])) {
        error_log("Mix asset '$filename' does not exist in manifest.");
    }
    return $manifest[$filename];
}

function registerImagesSizes() {
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
