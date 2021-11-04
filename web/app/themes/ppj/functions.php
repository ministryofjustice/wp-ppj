<?php

namespace ppj;

function enqueue_scripts()
{
    $legName = LegNav\legName();
    $root_dir = get_template_directory_uri() . '/dist';

    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Barlow:300,400,500,600,700', null, null);
    wp_enqueue_style('main', $root_dir . mix_asset("/css/{$legName}.css"), null, null);
    wp_enqueue_script('main-js', $root_dir . mix_asset('/js/main.js'), null, null, true);
    wp_enqueue_script('wistia', '//fast.wistia.com/assets/external/E-v1.js', null, null, true);

    if (is_page_template('find-a-job.php')) {
        wp_enqueue_script(
            'google-maps-js',
            'https://maps.googleapis.com/maps/api/js?key=AIzaSyDDplfBkLzNA3voskfGyExYnQ46MJ0VtpA&libraries=places',
            null,
            null,
            true
        );

        wp_register_script('find-a-job-js', $root_dir . mix_asset('/js/find-a-job.js'), ('google-maps.js'), null, true);

        $upload_dir = wp_get_upload_dir();
        wp_localize_script(
            'find-a-job-js',
            'find_job_object',
            array(
                'job_feed_url' => $upload_dir['baseurl'] . "/job-feed/"
            )
        );

        wp_enqueue_script('find-a-job-js' );
    }
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts');

function mix_asset($filename)
{
    $manifest_path = dirname(__FILE__) . '/dist/mix-manifest.json';
    $manifest = json_decode(file_get_contents($manifest_path), true);
    if (!isset($manifest[$filename])) {
        error_log("Mix asset '$filename' does not exist in manifest.");
    }
    return $manifest[$filename];
}

function registerImagesSizes()
{
    add_image_size('header-mobile', 320, 180, true);
    add_image_size('header-mobile-home', 320, 244, true);

    add_image_size('header-portrait', 768, 320, true);
    add_image_size('header-portrait-home', 768, 400, true);

    add_image_size('header-landscape', 1024, 420, true);
    add_image_size('header-landscape-home', 1024, 444, true);

    add_image_size('header-large', 1440, 560, true);

    add_image_size('header-large-home', 1440, 624, true);

    /*
     * Adding eight 16x9 image sizes, to span the entire gamut
     * from very small images for small mobile devices
     * to very large images for HiDPI screens.
     * The x suffix refers to how many times the 16 and the 9
     * have been multiplied to obtain the horizontal and vertical
     * pixel values respectively.
     */
    add_image_size('16:9x20', 320, 180, true);
    add_image_size('16:9x30', 480, 270, true);
    add_image_size('16:9x40', 640, 360, true);
    add_image_size('16:9x60', 960, 540, true);
    add_image_size('16:9x80', 1280, 720, true);
    add_image_size('16:9x100', 1600, 900, true);
    add_image_size('16:9x150', 2400, 1350, true);
    add_image_size('16:9x200', 3200, 1800, true);

    add_image_size('award', null,120, false );

}

add_action('init', __NAMESPACE__ . '\\registerImagesSizes');

/**
 * Register the navigation menu locations that this theme supports
 */
function registerNavLocations()
{
    register_nav_menus([
        'site-wide' => 'Site-wide menu',
        'prison-officer' => 'Prison Officer menu',
        'youth-custody' => 'Youth Custody menu',
    ]);
}

add_action('init', __NAMESPACE__ . '\\registerNavLocations');

/**
 * Get nav menu items for the given theme location
 *
 * This is a convenience wrapper around wp_get_nav_menu_items(),
 * so expect the same output
 *
 * @param string $location
 * @return array
 */
function navMenuItems($location)
{
    $menus = get_nav_menu_locations();
    if (!isset($menus[$location])) {
        // There's no menu set for this location. Gracefully fail.
        return [];
    } else {
        return wp_get_nav_menu_items($menus[$location]);
    }
}


/**
 * Get the current version of WP
 *
 * This is provided for external resources to resolve the current wp_version
 *
 * @return string
 */
function moj_wp_version()
{
    global $wp_version;

    return $wp_version;
}

add_action('rest_api_init', function () {
    register_rest_route('moj', '/version', array(
        'methods' => 'GET',
        'callback' => 'moj_wp_version'
    ));
});


/**
 * Load Jobs Handler files
 */

require  'jobs-handler/cpt-job-locations.php';

require 'jobs-handler/import-jobs.php';


