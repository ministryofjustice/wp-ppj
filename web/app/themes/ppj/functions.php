<?php
namespace ppj;

function enqueue_scripts()
{

    $root_dir = get_template_directory_uri();
    wp_enqueue_style('main-css', $root_dir . mix_asset('/dest/css/main.css'), null, false);
    wp_enqueue_script('main-js', $root_dir . mix_asset('/dest/js/main.js'), null, false, true);
    wp_enqueue_script('wistia-e-v1', 'https://fast.wistia.com/assets/external/E-v1.js', null, false, false);
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

