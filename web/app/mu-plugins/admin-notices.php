<?php
/**
 * Created by PhpStorm.
 * User: damienwilson
 * Date: 2019-02-19
 * Time: 11:11
 */
if (WP_ENV !== 'production' && is_admin()) {
    add_action('init', '__admin_notice_after_init');

    /**
     * Output a notification to inform the user that any content entered will not be used
     * @return null
     */
    function __admin_notice_after_init()
    {
        if (!get_transient('no-persistent-edit-notice-dismissed-' . wp_get_current_user()->user_login)) {
            add_action('admin_notices', 'no_persistent_edit_notice');
        }

        function no_persistent_edit_notice()
        {
            $pt = get_current_screen()->post_type;
            $pb = get_current_screen()->parent_base;
            if ($pb !== 'edit' && ($pt !== 'post' && $pt !== 'page')) {
                return;
            }

            ?>
            <div class="notice warning no-persistent-edit-notice is-dismissible">
                <p><?php
                    _e(
                        'Content entered here may not be copied to the production site.<br>For clarification, please contact Tactical Products.',
                        'my-text-domain'
                    ); ?></p>
            </div>
            <script>
                jQuery(document).on('click', '.no-persistent-edit-notice .notice-dismiss', function () {
                    jQuery.ajax({
                        url: ajaxurl,
                        data: {
                            action: 'no_persistent_edit_dismissed'
                        }
                    });
                });
            </script>

            <?php
        }
    }
}

add_action('wp_ajax_no_persistent_edit_dismissed', 'wp_ajax_no_persistent_edit_dismissed_handler');

/**
 * When a user clicks the dismiss button on the no-persistent-edit notification, set a transient
 * @return bool
 */
function wp_ajax_no_persistent_edit_dismissed_handler()
{
    set_transient(
        'no-persistent-edit-notice-dismissed-' . wp_get_current_user()->user_login,
        true,
        3 * DAY_IN_SECONDS
    );

    return true;
}
