<?php
namespace ppj;

function enqueue_scripts() {

    $root_dir = get_template_directory_uri();
    wp_enqueue_style( 'main-css', $root_dir . mix_asset('/dest/css/main.css'), null, false);
    wp_enqueue_script( 'main-js', $root_dir . mix_asset('/dest/js/main.js'), null, false, true );
    wp_enqueue_script( 'wistia-e-v1', 'https://fast.wistia.com/assets/external/E-v1.js', null, false, false );

}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );

function mix_asset($filename) {
    $manifest_path = dirname(__FILE__) . '/mix-manifest.json';
    $manifest = json_decode(file_get_contents($manifest_path), true);
    if (!isset($manifest[$filename])) {
        error_log("Mix asset '$filename' does not exist in manifest.");
    }
    return $manifest[$filename];
}

function template($data, $slug, $name='') {
    global $ppj_template_data;
    $ppj_template_data = $data;

    ob_start();
    get_template_part($slug, $name);
    $output = ob_get_contents();
    ob_end_clean();

    $ppj_template_data = null;
    return $output;
}

function partial($data, $slug, $name='') {
    return template($data, 'partials/' . $slug, $name);
}


function dump($var) {
    echo "<pre>" . print_r($var, true) . "</pre>";
}

function renderPageBlockData($acf) {
    $output = '';
    if (isset($acf) && is_array($acf)) {
        foreach($acf as $fieldGroup) {
            if (isset($fieldGroup['show']) && $fieldGroup['show'] == '') {
                continue;
            } else {
                $blockType = $fieldGroup['acf_fc_layout'];

                //error_log('renderPageBlockData ' . $blockType);
                switch($blockType) {
                    case 'call_to_action':
                        $output .= partial($fieldGroup, 'callToAction');
                        break;

                    case 'text_block':
                        $output .= partial($fieldGroup, 'textBlock');
                        break;

                    case 'text_image_block':
                        $output .= partial($fieldGroup, 'textImageBlock');
                        break;

                    case 'search':
                        $output .= partial($fieldGroup, 'search');
                        break;

                    case 'ordered_list':
                        $output .= partial($fieldGroup, 'orderedList');
                        break;

                    case 'accordion':
                        $output .= partial($fieldGroup, 'accordion');
                        break;

                    case 'role_intro':
                        $output .= partial($fieldGroup, 'roleIntro');
                        break;

                    case 'tabs':
                        $output .= partial($fieldGroup, 'tabs');
                        break;

                    case 'video':
                        $output .= partial($fieldGroup, 'videoPlayer');
                        break;

                    default:
                        error_log('renderPageBlockData unrecognized block type ' . $blockType);
                }
            }
        }
    }
    return $output;
}

function stopAutoInsertionOfPTags() {
    remove_filter('the_content', 'wpautop');
    remove_filter ('acf_the_content', 'wpautop');
}
add_action('acf/init', __NAMESPACE__ . '\\stopAutoInsertionOfPTags', 15);

function stopEmojicons() {
    // https://wordpress.stackexchange.com/questions/185577/disable-emojicons-introduced-with-wp-4-2
    // all actions related to emojis
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

    // filter to remove TinyMCE emojis
    add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}

add_action('acf/init', __NAMESPACE__ . '\\stopEmojicons', 15);

?>
