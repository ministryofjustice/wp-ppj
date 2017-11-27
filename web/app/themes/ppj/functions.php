<?php

$ppj_model = null;

class ppj {

    public static function enqueue_scripts() {

        $root_dir = get_template_directory_uri() . '/dest/';
        wp_enqueue_style( 'main-css', $root_dir . 'css/main.css' , null, '1.0');
        wp_enqueue_script( 'main-js', $root_dir . 'js/main.js'   , null, '1.0', true );
    }

    public static function template($templateModel, $slug, $name='') {
        global $ppj_model;
        $ppj_model = $templateModel;

        ob_start();
        get_template_part($slug, $name);
        $output = ob_get_contents();
        ob_end_clean();

        $ppj_model = null;
        return $output;
    }

    public static function partial($templateModel, $slug, $name='') {
        return self::template($templateModel, 'partials/' . $slug, $name);
    }


    public static function dump($var) {
        echo "<pre>" . print_r($var, true) . "</pre>";
    }

    public static function controller($acf) {
        $output = '';
        foreach($acf as $fieldGroup) {
            switch( $fieldGroup['block_type']) {
                case 'call to action':
                    $output .= ppj::partial($fieldGroup, 'callToAction');
                    break;

                case 'text block':
                    $output .= ppj::partial($fieldGroup, 'textBlock');
                    break;

                case 'text image block':
                    $output .= ppj::partial($fieldGroup, 'textImageBlock');
                    break;
            }
        }
        return $output;
    }

}

add_action( 'wp_enqueue_scripts', 'ppj::enqueue_scripts' );
?>