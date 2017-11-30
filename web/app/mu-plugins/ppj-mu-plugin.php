<?php
namespace ppj;

function createPostTypes() {
    $name = 'position';
    $capitalizedName = ucfirst( $name );
    register_post_type( 'ppj_' . $name,
        array(
            'labels' => array(
                'name' => __( $capitalizedName . 's'),
                'singular_name' => __( $capitalizedName )
            ),
            'public' => true,
            'has_archive' => true,
        )
    );
}
add_action( 'init', __NAMESPACE__ . '\\createPostTypes' );
