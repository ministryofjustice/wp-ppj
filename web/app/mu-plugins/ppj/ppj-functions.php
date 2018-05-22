<?php
namespace ppj;
require_once 'LegNav.php';

function template($data, $slug, $name = '')
{
    global $ppj_template_data;
    $ppj_template_data = $data;

    ob_start();
    get_template_part($slug, $name);
    $output = ob_get_contents();
    ob_end_clean();

    $ppj_template_data = null;
    return $output;
}

function partial($data, $slug, $name = '')
{
    return template($data, 'partials/' . $slug, $name);
}

function dump($var)
{
    echo "<pre>" . print_r($var, true) . "</pre>";
}

function renderPageBlockData($acf)
{
    $output = '';
    if (isset($acf) && is_array($acf)) {
        foreach ($acf as $fieldGroup) {
            if (isset($fieldGroup['show']) && $fieldGroup['show'] == '') {
                continue;
            } else {
                $blockType = $fieldGroup['acf_fc_layout'];

                switch ($blockType) {

                    case 'text_block':
                        $output .= partial($fieldGroup, 'textBlock');
                        break;

                    case 'video':
                        $output .= partial($fieldGroup, 'videoPlayer');
                        break;

                    case 'image_block':
                        $output .= partial($fieldGroup, 'imageBlock');
                        break;

                    case 'navigation_block':
                        $output .= partial($fieldGroup, 'navigationBlock');
                        break;

                    case 'landing_page':
                        $output .= partial($fieldGroup, 'landingPage');

                    default:
                        error_log('renderPageBlockData unrecognized block type ' . $blockType);
                }
            }
        }
    }
    return $output;
}

function my_acf_admin_head() {
    ?>
    <style type="text/css">
        /*        .acf-flexible-content {
					background-color: black;
				}*/

        .acf-flexible-content .layout .acf-fc-layout-handle {
            /*background-color: #00B8E4;*/
            background-color: #202428;
            color: #eee;
        }

        .acf-repeater.-row > table > tbody > tr > td,
        .acf-repeater.-block > table > tbody > tr > td {
            border-top: 2px solid #202428;
        }

        .acf-repeater .acf-row-handle {
            vertical-align: top !important;
            padding-top: 16px;
        }

        .acf-repeater .acf-row-handle span {
            font-size: 20px;
            font-weight: bold;
            color: #202428;
        }

        .imageUpload img {
            width: 75px;
        }

        .acf-repeater .acf-row-handle .acf-icon.-minus {
            top: 30px;
        }

    </style>
    <?php
}

add_action('acf/input/admin_head', __NAMESPACE__ . '\\my_acf_admin_head');

function disable_emojicons_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}

function stopEmojicons()
{
    // https://wordpress.stackexchange.com/questions/185577/disable-emojicons-introduced-with-wp-4-2
    // all actions related to emojis
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');

    // filter to remove TinyMCE emojis
    add_filter('tiny_mce_plugins', __NAMESPACE__ . '\\disable_emojicons_tinymce');
}
add_action('acf/init', __NAMESPACE__ . '\\stopEmojicons', 15);

function videoPlayer($attrs)
{
    $a = shortcode_atts(array(
        'host' => 'youtube',
        'id' => '',
        'cover-image-id' => null,
    ), $attrs);

    return partial($a, 'videoPlayer');
}
add_shortcode('video-player', __NAMESPACE__ . '\\videoPlayer');

/**
 * Adjust URL parameters used for embedded YouTube iframe players
 * to 'unbrand' it:
 *   - don't show related videos at the end
 *   - hide annotations
 *   - don't show the video title & uploader info
 *
 * @param string $iframe The YouTube iframe HTML
 * @return string Adjusted iframe HTML
 */
function youtubeEmbedParams($iframe) {
    // If this isn't a YouTube iframe, do nothing
    if (stripos($iframe, 'youtube.com') === false || stripos($iframe, ' src=') === false) {
        return $iframe;
    }

    preg_match('/src="(.+?)"/', $iframe, $matches);
    $embed_url = $matches[1];

    // YouTube Embed parameters are documented here:
    // https://developers.google.com/youtube/player_parameters#Parameters
    $params = [
        'rel' => 0,
        'showinfo' => 0,
        'iv_load_policy' => 3,
    ];

    $new_url = add_query_arg($params, $embed_url);
    return str_replace($embed_url, $new_url, $iframe);
}

/**
 * Filter the oEmbed HTML used for YouTube videos
 * This will adjust the embed iframe URL parameters and wrap it in a responsive div.
 *
 * @param string $html HTML markup for the oEmbed
 * @param string $url URL of the content being embedded
 *
 * @return string Adjusted HTML markup
 */
function filterYoutubeOembed($html, $url) {
    if (preg_match('/https?:\/\/((www\.)?youtube\.com|youtu\.be)\//', $url)) {
        $html = youtubeEmbedParams($html);
        $html = '<div class="embed-responsive embed-responsive-16by9">' . $html . '</div>';
    }
    return $html;
}
add_filter('embed_oembed_html', __NAMESPACE__ . '\\filterYoutubeOembed', 10, 2);

function shortcodeQuote($attrs)
{
    $a = shortcode_atts(array(
        'quote' => '',
        'quote-source' => '',
        'origin' => 'top-right',
        'style' => 'strong',
        'no-border' => '',
        'position' => 'left'
    ), $attrs);

    return partial($a, 'shortcode-quote');
}
add_shortcode('ppj-quote', __NAMESPACE__ . '\\shortcodeQuote');

function shortcodeCurrentYear($attrs)
{
    return date("Y");
}
add_shortcode('ppj-current-year', __NAMESPACE__ . '\\shortcodeCurrentYear');

function inlineSVG($svgFileName) {
    $templateDirectory = get_template_directory();
    $fullSVGPath = $templateDirectory . '/dest/img/svg/' . $svgFileName . '.svg';
    include($fullSVGPath);
}

function initializeAcfSettingsPage() {
    if ( function_exists( 'acf_add_options_page' ) ) {

        acf_add_options_page( array(
            'page_title' => 'PPJ Settings',
            'menu_title' => 'PPJ Settings',
            'menu_slug'  => 'ppj-general-settings',
            'capability' => 'edit_posts',
            'redirect'   => false
        ));

        acf_add_options_sub_page( array(
            'page_title'  => 'PPJ Footer Settings',
            'menu_title'  => 'Footer',
            'parent_slug' => 'ppj-general-settings',
        ));

    }
}
add_action('acf/init', __NAMESPACE__ . '\\initializeAcfSettingsPage', 15);

/**
 * This functions ensures that the ACF json file will now be saved
 * in a theme agnostic location, allowing ACF structures to be shared between
 * themes which may be beneficial in a multi-site scenario.
 *
 * https://www.advancedcustomfields.com/resources/local-json/
 */
function acf_json_save_point( $path )
{
    // update path
    $path = WPMU_PLUGIN_DIR .  '/ppj/acf-json';

    return $path;
}
add_filter('acf/settings/save_json', __NAMESPACE__ . '\\acf_json_save_point');

/**
 * ACF schema now loaded from /mu-plugins/ppj/acf-json
 *
 * See acf_json_save_point for rationale.
 */
function acf_json_load_point( $paths )
{
    // remove original path
    unset($paths[0]);

    // append path
    $paths[] = WPMU_PLUGIN_DIR . '/ppj/acf-json';

    return $paths;
}
add_filter('acf/settings/load_json', __NAMESPACE__ . '\\acf_json_load_point');

function renderSurveyMonkeySnippet() {
    echo partial([], 'surveyMonkeySnippet');
}
add_action('wp_footer', __NAMESPACE__ . '\\renderSurveyMonkeySnippet');

/**
 * Disable WordPress' visual editor until we know it's safe to use
 */
add_filter('user_can_richedit', '__return_false');

/**
 * function to determine if the supplied url
 * links to an internal destination or not
 *
 * @param $url
 *
 * @return bool
 */
function isInternalLink($url) {
    $components = parse_url($url);
    $siteComponents = parse_url(get_site_url(null, null, null));

    return ($components['host'] == $siteComponents['host']);
}

/**
 * Utility function to mark the menu item corresponding
 * to the current post/page
 *
 * @param $menuItems array of WP Menu items
 *
 * @return array of modified WP Menu items
 */
function markCurrentlySelectedMenuItem($menuItems) {
    global $post;

    $modifiedMenuItems = [];
    if ( isset($menuItems) && !!$menuItems ) {
        foreach ( $menuItems as $item ) {
            $same = ( isset( $post ) && $post->ID == $item->object_id );

            $modifiedMenuItems[] = [
                'title'    => $item->title,
                'url'      => $item->url,
                'selected' => $same
            ];
        }
    }
    return $modifiedMenuItems;
}

/**
 * Utility function to mark the menu item corresponding
 * to an ancestor of the currently viewed page/post
 *
 * @param $menuItems of WP Menu items
 *
 * @return array of modfied Menu items
 */
function markCurrentlySelectedAncestorMenuItem($menuItems) {
    $modifiedMenuItems = [];
    $homePath = LegNav\legHomeUrl();

    if ( isset($menuItems) && !!$menuItems ) {
        foreach ( $menuItems as $item ) {
            $selected = ( $item->url == $homePath );

            $modifiedMenuItems[] = [
                'title'    => $item->title,
                'url'      => $item->url,
                'selected' => $selected
            ];
        }
    }
    return $modifiedMenuItems;
}

/**
 * @param $str
 *
 * Simple helper function to remove unicode characters using regex matching.
 *
 * @return mixed
 */
function removeUnicodeCharacters($str) {
    return preg_replace('/[^\x{00}-\x{7F}]/u', '', $str);
}

/**
 * @param $value
 * @param $post_id
 * @param $field
 *
 * Perform modifications on ACF values upon save
 *
 * @return mixed
 */
function processAcfValues( $value, $post_id, $field  ) {

    if (gettype($value) == 'string') {
        $value = removeUnicodeCharacters($value);
    }

    return $value;
}

add_filter('acf/update_value',  __NAMESPACE__ . '\\processAcfValues', 10, 3);
