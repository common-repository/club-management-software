<?php
/*
 * Plugin file. This file should ideally be used to work with the
 * administrative side of the WordPress site.
 */

/**
 *  On plugin activation notice */
// check is plugin active
if (version_compare($GLOBALS['wp_version'], SWIFTBOOKSUBS__MINIMUM_WP_VERSION, '>=')) {
    add_action('admin_notices', 'swiftbook_admin_notice');
}

if (!function_exists('swiftbook_admin_notice')) {

    function swiftbook_admin_notice() {
        if (!get_option('swiftbook_notice') && !get_option('swiftbooks_pages')) {
            ?>
            <div class="notice notice-success is-dismissible swiftbook-notice">
                <p><b>SwiftCloud Subscription Plugin</b></p>
                <form method="post">
                    <p class="sr-notice-msg"><?php _e('Want to auto-create the following pages to quickly get you set up? ', 'swiftbooks-subscription'); ?></p>
                    <ul>
                        <li>Signup</li>
                        <li>Login</li>
                        <li>Lost Password</li>
                        <li>Reset Password</li>
                        <li>Change Password</li>
                        <li>Home Logged IN</li>
                        <li>Home Logged OUT</li>
                        <li>Expired Page</li>
                        <li>Banned Page</li>
                    </ul>
                    <?php wp_nonce_field('swiftbook_autogen_pages', 'swiftbook_autogen_pages'); ?>
                    <button type="submit" value="yes" name="swiftbooks_autogen_yes" class="button button-green"><i class="fa fa-check"></i> Yes</button>  <button type="submit" name="swiftbooks_autogen_no" value="no" class="button button-default button-red"><i class="fa fa-ban"></i> No</button>
                </form>
            </div>
            <?php
        }
    }

}

// Add the options page and menu item.
add_action('admin_menu', 'swiftbooks_subscription_menu');
if (!function_exists('swiftbooks_subscription_menu')) {

    function swiftbooks_subscription_menu() {
        $icon_url = plugins_url('/images/swiftcloud.png', __FILE__);
        $menu_capability = 'manage_options';
        $parent_menu_slug = 'swiftbooks_subscription';

        add_menu_page('SwiftCloud Membership', 'SwiftCloud Membership', $menu_capability, $parent_menu_slug, 'swiftbook_settings_cb', $icon_url);
        add_submenu_page($parent_menu_slug, "Settings", "Settings", $menu_capability, $parent_menu_slug, null);
        add_submenu_page($parent_menu_slug, "Messages / Automation", "Messages / Automation", $menu_capability, "swiftbook_autoresponder", 'swiftbook_autoresponder_cb');
        add_submenu_page($parent_menu_slug, "Welcome Wizard", "Welcome Wizard", $menu_capability, "swiftbook_welcome_wizard", 'swiftbook_welcome_wizard_cb');
        add_submenu_page($parent_menu_slug, "Help / Setup", "Help / Setup", $menu_capability, 'swiftbook_help', 'swiftbook_help_cb');
        add_submenu_page($parent_menu_slug, "Updates & Tips", "Updates & Tips", $menu_capability, 'swiftbook_dashboard', 'swiftbook_dashboard_callback');
        add_submenu_page(null, "Add Email Templates", "Menu Title", 'manage_options', "swiftbook_add_email_template", 'swiftbook_add_email_template_cb');
    }

}


add_action('admin_enqueue_scripts', 'swiftbook_enqueue_cssjs');
if (!function_exists('swiftbook_enqueue_cssjs')) {

    function swiftbook_enqueue_cssjs() {
        wp_enqueue_style('swift-toggle-style', plugins_url('/css/swiftbook_rcswitcher.css', __FILE__), '', '', '');
        wp_enqueue_style('swift-membership-admin', plugins_url('/css/swiftbook_admin.css', __FILE__), '', '', '');

        wp_enqueue_script('swift-toggle', plugins_url('/js/swiftbook_rcswitcher.js', __FILE__), array('jquery'), '', true);
        wp_enqueue_script('swift-membership-custom', plugins_url('/js/swiftbook_admin.js', __FILE__), array('jquery'), '', true);
        wp_localize_script('swift-membership-custom', 'swiftbook_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }

}

include 'section/swift_dashboard.php';
include 'section/swiftbook-settings.php';
include 'section/swiftbook-message-autoresponder.php';
include 'section/swiftbook-welcome-wizard.php';
include 'section/swiftbook-add-email-templates.php';
include 'section/swiftbook-user-fields.php';
include 'section/swiftbook-help.php';

/* init action */
add_action("init", "swiftbook_form_submit");
if (!function_exists('swiftbook_form_submit')) {

    function swiftbook_form_submit() {
        /* on plugin active auto generate pages and options */
        if (isset($_POST['swiftbook_autogen_pages']) && wp_verify_nonce($_POST['swiftbook_autogen_pages'], 'swiftbook_autogen_pages')) {
            if ($_POST['swiftbooks_autogen_yes'] == 'yes') {
                swiftbook_initial_data();
            }
            update_option('swiftbook_notice', true);
        }
    }

}

/* Dismiss notice callback */
add_action('wp_ajax_swiftbook_dismiss_notice', 'swiftbook_dismiss_notice_callback');
add_action('wp_ajax_nopriv_swiftbook_dismiss_notice', 'swiftbook_dismiss_notice_callback');

if (!function_exists('swiftbook_dismiss_notice_callback')) {

    function swiftbook_dismiss_notice_callback() {
        update_option('swiftbook_notice', true);
    }

}

/*
 *      Add custom css to header
 */
add_action('wp_head', 'swiftbook_custom_css_callback');
if (!function_exists('swiftbook_custom_css_callback')) {

    function swiftbook_custom_css_callback() {
        $swiftbook_custom_css = get_option('swiftbooks_custom_css');
        $output = "<style type='text/css'>" . $swiftbook_custom_css . "</style>";
        echo $output;
    }

}

/**
 *      Add Toggle into Public Box in all posts/pages.
 *      Page/Post restriction.
 */
add_action('post_submitbox_misc_actions', 'swiftbook_add_public_member_toggle_action');
if (!function_exists('swiftbook_add_public_member_toggle_action')) {

    function swiftbook_add_public_member_toggle_action($post) {
        wp_enqueue_style('sb-toggle-css', plugins_url('swiftbooks-subscription/admin/css/swiftbook_rcswitcher.min.css'), '', '', '');
        wp_enqueue_script('sb-toggle-js', plugins_url('swiftbooks-subscription/admin/js/swiftbook_rcswitcher.min.js'), array('jquery'), '', true);

        global $post;
        global $wp_roles;
        $roles = $wp_roles->get_names();

        $value = get_post_meta($post->ID, 'swiftbooks_page_restriction', true);
        $display = ($value == 1) ? "display:block" : 'display:none';
        $selected_value = get_post_meta($post->ID, 'swiftbook_page_restricted_roles', true);
        ?>
        <div class="misc-pub-section public-member">
            <strong>Who can see this?</strong>&nbsp;
            <input type="checkbox" value="1" data-ontext="Members" data-offtext="Public" name="swiftbook_show_public_or_member" id="show-public-member" class="show-public-member" <?php echo ($value == 1 ? 'checked="checked"' : ""); ?>>
            <div class="restricted_roles_div" style="<?php echo $display; ?>">
                <?php
                foreach ($roles as $key => $role) {
                    if ($role !== "Administrator") {
                        if (!empty($selected_value)) {
                            $checked = in_array($key, $selected_value) ? 'checked="checked"' : '';
                        }
                        ?>
                        <label for="swiftbook_role_<?php echo $key; ?>"><input type="checkbox" id="swiftbook_role_<?php echo $key; ?>" name="swiftbook_page_restricted_roles[]" <?php echo $checked; ?> class="sb-page-restricted-roles-checkbox" value="<?php echo $key; ?>" /><?php echo $role; ?></label><br/>
                            <?php
                        }
                    }
                    ?>
            </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                jQuery('.show-public-member:checkbox').rcSwitcher({
                    width: 90,
                    blobOffset: 0
                }).on({
                    'turnon.rcSwitcher': function(e, dataObj) {
                        jQuery('.restricted_roles_div').fadeIn();
                        jQuery('.sb-page-restricted-roles-checkbox').prop('checked', true);
                    },
                    'turnoff.rcSwitcher': function(e, dataObj) {
                        jQuery('.restricted_roles_div').hide();
                        jQuery('.sb-page-restricted-roles-checkbox').prop('checked', false);
                    }
                });
            });
        </script>
        <?php
    }

}

add_action('save_post', 'swiftbook_save_postdata');
if (!function_exists('swiftbook_save_postdata')) {

    function swiftbook_save_postdata($postid) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return false;
        if (!current_user_can('edit_page', $postid))
            return false;
        if (empty($postid))
            return false;

        if (!empty($_POST['swiftbook_show_public_or_member'])) {
            update_post_meta($postid, 'swiftbooks_page_restriction', $_POST['swiftbook_show_public_or_member']);
        } else {
            update_post_meta($postid, 'swiftbooks_page_restriction', 0);
        }

        if (!empty($_POST['swiftbook_page_restricted_roles'])) {
            update_post_meta($postid, 'swiftbook_page_restricted_roles', $_POST['swiftbook_page_restricted_roles']);
        } else {
            update_post_meta($postid, 'swiftbook_page_restricted_roles', '');
        }
    }

}


add_action('pre_get_posts', 'swiftbook_check_page_restriction');
if (!function_exists('swiftbook_check_page_restriction')) {

    function swiftbook_check_page_restriction() {
        $current_user = get_userdata(get_current_user_id());
        if (!current_user_can('administrator')) {
            if (is_page() || is_single()) {
                global $post;
                $page_view = get_post_meta($post->ID, 'swiftbooks_page_restriction', true);
                if ($page_view) {
                    if (!is_user_logged_in()) {
                        $swiftbooks_logged_out_homepage = get_option('swiftbooks_logged_out_homepage');
                        if ($swiftbooks_logged_out_homepage) {
                            wp_redirect(get_permalink($swiftbooks_logged_out_homepage));
                        }
                    } else {
                        $swiftbooks_banned_page = get_option('swiftbooks_banned_page');
                        $restricted_roles = get_post_meta($post->ID, 'swiftbook_page_restricted_roles', true);
                        foreach ($current_user->roles as $key => $role) {
                            if (!in_array($role, $restricted_roles)) {
                                wp_redirect(get_permalink($swiftbooks_banned_page));
                            }
                        }
                    }
                }//page view
            }
        }
    }

}
?>