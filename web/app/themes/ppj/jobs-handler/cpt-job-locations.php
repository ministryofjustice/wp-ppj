<?php
// Register Job Location Post Type
function ppj_register_job_location_post_type()
{

    $labels = array(
        'name' => _x('Job Locations', 'Post Type General Name', 'ppj'),
        'singular_name' => _x('Job Location', 'Post Type Singular Name', 'ppj'),
        'menu_name' => __('Job Locations', 'ppj'),
        'name_admin_bar' => __('Job Location', 'ppj'),
        'archives' => __('Job Location Archives', 'ppj'),
        'attributes' => __('Job Location Attributes', 'ppj'),
        'parent_item_colon' => __('Parent Job Location:', 'ppj'),
        'all_items' => __('All Job Locations', 'ppj'),
        'add_new_item' => __('Add New Job Location', 'ppj'),
        'add_new' => __('Add New', 'ppj'),
        'new_item' => __('New Job Location', 'ppj'),
        'edit_item' => __('Edit Job Location', 'ppj'),
        'update_item' => __('Update Job Location', 'ppj'),
        'view_item' => __('View Job Location', 'ppj'),
        'view_items' => __('View Job Locations', 'ppj'),
        'search_items' => __('Search Job Locations', 'ppj'),
        'not_found' => __('Not found', 'ppj'),
        'not_found_in_trash' => __('Not found in Trash', 'ppj'),
        'featured_image' => __('Featured Image', 'ppj'),
        'set_featured_image' => __('Set featured image', 'ppj'),
        'remove_featured_image' => __('Remove featured image', 'ppj'),
        'use_featured_image' => __('Use as featured image', 'ppj'),
        'insert_into_item' => __('Insert into job_location', 'ppj'),
        'uploaded_to_this_item' => __('Uploaded to this Job Location', 'ppj'),
        'items_list' => __('Job Locations list', 'ppj'),
        'items_list_navigation' => __('Job Locations list navigation', 'ppj'),
        'filter_items_list' => __('Filter Job Locations list', 'ppj'),
    );
    $args = array(
        'label' => __('Job Location', 'ppj'),
        'description' => __('Contains details of Job Location', 'ppj'),
        'labels' => $labels,
        'supports' => array('title'),
        'taxonomies' => array(),
        'hierarchical' => false,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-location',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => false,
        'show_in_rest' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'capability_type' => 'page',
        'rewrite' => array(
            'slug' => 'job_locations',
            'with_front' => false
        ),
    );

    register_post_type('job_location', $args);


}

add_action('init', 'ppj_register_job_location_post_type', 0);

function ppj_save_job_location( $post_id ) {

    $post = get_post($post_id);

    if($post->post_type == "job_location" && $post->post_status == "publish") {
        $location_args = array(
            'post_type' => 'job_location',
            'posts_per_page' => -1
        );
        $job_locations = get_posts($location_args);

        $job_loc_array = [];

        if(count($job_locations) > 0){
            foreach ($job_locations as $job_location){

                $location_name = $job_location->post_title;
                $location_type = get_field('location_type', $job_location->ID );
                $location_town = get_field('location_town', $job_location->ID );
                $location_lat = get_field('location_latitude', $job_location->ID );
                $location_lng = get_field('location_longitude', $job_location->ID );

                $location_name_variations = get_field('location_name_variations', $job_location->ID );

                $loc_variations = array();

                if(!empty($location_name_variations)){

                    foreach ($location_name_variations as $variation){

                        if(array_key_exists("variation_name", $variation) == true && !empty($variation['variation_name'])){
                            $loc_variations[] = $variation['variation_name'];
                        }

                    }
                }

                $job_loc_array[] = array(
                    'name' => $location_name,
                    'town' => $location_town,
                    'lat' => $location_lat,
                    'lng' => $location_lng,
                    'type' => $location_type,
                    'name_variations' => $loc_variations
                );
            }

            if(count($job_loc_array) > 0){
                $upload_dir = wp_get_upload_dir();
                $locations_file = $upload_dir['basedir'] . "/job-feed/temp-locations.json";

                //Write location json file
                $fp = fopen($locations_file, 'w');
                fwrite($fp, json_encode($job_loc_array));
                fclose($fp);
            }
        }
    }
};

// on acf save post so fires after meta fields are saved
add_action( "acf/save_post", 'ppj_save_job_location', 10, 3 );
