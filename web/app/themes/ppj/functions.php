<?php
namespace ppj;

function enqueue_scripts() {

    $root_dir = get_template_directory_uri() . '/dest/';
    wp_enqueue_style( 'main-css', $root_dir . 'css/main.css' , null, '1.0');
    wp_enqueue_script( 'main-js', $root_dir . 'js/main.js'   , null, '1.0', true );
    wp_enqueue_script( 'wistia-e-v1', 'https://fast.wistia.com/assets/external/E-v1.js'   , null, '1.0', false );

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

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );


?>