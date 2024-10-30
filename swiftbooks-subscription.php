<?php

/*
 *   Plugin Name: SwiftBooks Subscription
 *   Plugin URL: http://kb.SwiftCloud.me/wordpress-plugin
 *   Description: This will automatically trigger an autoresponder / email sequence to all new users created here within wordpress. Visit http://swiftmarketing.com/public/campaigns/sequences to create a sequence and/or retrieve the list ID number
 *   Version: 1.0
 *   Author: Roger Vaughn, Tejas Hapani
 *   Author URI: http://SwiftCloud.me/
 *   Text Domain: swiftbook
 */

define('SWIFTBOOKSUBS_VERSION', '1.0');
define('SWIFTBOOKSUBS__MINIMUM_WP_VERSION', '4.5');
define('SWIFTBOOKSUBS__PLUGIN_URL', plugin_dir_url(__FILE__));
define('SWIFTBOOKSUBS__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SWIFTBOOKSUBS_PLUGIN_PREFIX', 'swiftbook_');

register_activation_hook(__FILE__, 'swiftbook_activation');
if (!function_exists('swiftbook_activation')) {

    function swiftbook_activation() {
        if (version_compare($GLOBALS['wp_version'], SWIFTBOOKSUBS__MINIMUM_WP_VERSION, '<')) {
            add_action('admin_notices', create_function('', "
        echo '<div class=\"error\"><p>" . sprintf(esc_html__('SwiftBook Subscription %s requires WordPress %s or higher.', 'swiftcloud'), SWIFTBOOKSUBS_VERSION, SWIFTBOOKSUBS__MINIMUM_WP_VERSION) . "</p></div>'; "));

            add_action('admin_init', 'swiftbook_deactivate_self');

            function swiftbook_deactivate_self() {
                if (isset($_GET["activate"]))
                    unset($_GET["activate"]);
                deactivate_plugins(plugin_basename(__FILE__));
            }

            return;
        }
        update_option('swiftbook_subscription_version', SWIFTBOOKSUBS_VERSION);

        swiftbook_pre_load_data();
    }

}


/**
 *  Update checking
 */
add_action('plugins_loaded', 'swiftbook_update_check');
if (!function_exists('swiftbook_update_check')) {

    function swiftbook_update_check() {
        if (get_option("swiftbook_subscription_version") != SWIFTBOOKSUBS_VERSION) {
            swiftbook_activation();
        }
    }

}


add_action('upgrader_process_complete', 'swiftbook_update_process');
if (!function_exists('swiftbook_update_process')) {

    function swiftbook_update_process($upgrader_object, $options = "") {
        $current_plugin_path_name = plugin_basename(__FILE__);

        if (isset($options) && !empty($options) && $options['action'] == 'update' && $options['type'] == 'plugin' && $options['bulk'] == false && $options['bulk'] == false) {
            foreach ($options['packages'] as $each_plugin) {
                if ($each_plugin == $current_plugin_path_name) {
                    swiftbook_activation();
                    swiftbook_initial_data();
                }
            }
        }
    }

}

/**
 *      Deactive plugin
 */
register_deactivation_hook(__FILE__, 'swiftbook_deactive_plugin');
if (!function_exists('swiftbook_deactive_plugin')) {

    function swiftbook_deactive_plugin() {

    }

}

/**
 *      Uninstall plugin
 *      Remove Tabel sb_email_template
 */
register_uninstall_hook(__FILE__, 'swiftbook_uninstall_callback');
if (!function_exists('swiftbook_uninstall_callback')) {

    function swiftbook_uninstall_callback() {
        delete_option('swiftbook_subscription_version');
        delete_option("swiftbook_notice");

        global $wpdb;
        $table_email_template = $wpdb->prefix . 'sb_email_template';
        $wpdb->query("DROP TABLE IF EXISTS $table_email_template");
        delete_option("swiftbook_subscription_version");

        // delete pages
        $pages = get_option('swiftbooks_pages');
        if ($pages) {
            $pages = explode(",", $pages);
            foreach ($pages as $pid) {
                wp_delete_post($pid, true);
            }
        }
        delete_option("swiftbooks_pages");
    }

}

/*
 *      Register login logout navigations.
 */

add_action('init', 'swiftbook_register_loginlogout_menu');
if (!function_exists('swiftbook_register_loginlogout_menu')) {

    function swiftbook_register_loginlogout_menu() {
        register_nav_menus(array('loggin-menu' => __('Main Nav Logged In Users', 'swiftbooks-subscription'), 'loggout-menu' => __('Main Nav Logged OUT', 'swiftbooks-subscription'), 'myaccount' => __('Side Nav Logged In Users', 'swiftbooks-subscription'),
        ));
    }

}

add_action('wp_enqueue_scripts', 'swiftbook_enqueue_scripts_styles');
if (!function_exists('swiftbook_enqueue_scripts_styles')) {

    function swiftbook_enqueue_scripts_styles() {
        wp_enqueue_script('swift-popup-js', plugins_url('/js/jquery.magnific-popup.min.js', __FILE__), array('jquery'), '', true);
        wp_enqueue_script('swift-cookie-js', plugins_url('/js/jquery.cookie.js', __FILE__), array('jquery', 'swift-popup-js'), '', true);
        wp_enqueue_style('swift-popup-css', plugins_url('css/magnific-popup.css', __FILE__), '', '', '');
        wp_enqueue_style('swift-popup-custom', plugins_url('css/sb_public.css', __FILE__), '', '', '');
    }

}

//Load admin modules
include'admin/swiftbook-admin.php';

include 'section/swiftbook-pre-load-data.php';
include 'section/swiftbook-function.php';
include 'section/swiftbook-shortcodes.php';
include 'section/swiftbook-welcompopup.php';
include 'section/swiftbook-listener.php';
include 'section/swiftbook-login.php';
include 'section/swiftbook-signup.php';
include 'section/swiftbook-lost-password.php';
include 'section/swiftbook-reset-password.php';
include 'section/swiftbook-change-password.php';
include 'section/swift-form-error-popup.php';

/*
 *      Signup/Login process
 */
add_action('init', 'swiftbook_init_cb');
if (!function_exists('swiftbook_init_cb')) {

    function swiftbook_init_cb() {
        //signup
        if (isset($_POST['swift_membership_signup_nonce']) && wp_verify_nonce($_POST['swift_membership_signup_nonce'], 'swift_membership_signup_nonce')) {

            $referer_url = sanitize_text_field($_POST['_wp_http_referer']);
            $userEmail = sanitize_email($_POST['sbs_signup_email']);
            $userName = sanitize_email($_POST['sbs_signup_email']);
            $userPwd = trim($_POST['sbs_signup_password']);

            if (!empty($userName) && !empty($userEmail) && !empty($userPwd)) {
                $user_id = username_exists($userName);
                if (!$user_id) {
                    if (email_exists($userEmail) == false) {
                        $new_user_id = wp_create_user($userName, $userPwd, $userEmail);

                        // Send welcome email to user
                        swiftbook_signup_mail($userEmail, $userName, $userPwd);

                        $_SESSION['sbsSignupMsg'] = '<p class="sucsses"> You have successfully signup.</p>';
                        wp_redirect($referer_url);
                        exit;
                    } else {
                        $_SESSION['sbsSignupMsg'] = '<p class="error"><strong>ERROR</strong>: Email already exists.</p>';
                    }
                } else {
                    $_SESSION['sbsSignupMsg'] = '<p class="error"><strong>ERROR</strong>: Username already exists.</p>';
                }
            } else {
                $_SESSION['sbsSignupMsg'] = '<p class="error"><strong>ERROR</strong>: All fields are required.</p>';
            }
        }

        //login
        if (isset($_POST['swift_membership_login_nonce']) && wp_verify_nonce($_POST['swift_membership_login_nonce'], 'swift_membership_login_nonce')) {

            $username = sanitize_text_field($_POST['sbs_login_username']);
            $pwd = trim($_POST['sbs_login_password']);
            $referer_url = ($_POST['_wp_http_referer']);

            if (!empty($username) && !empty($pwd)) {
                $user_meta = get_user_by('login', $username);
                if (!$user_meta) {
                    $user_meta = get_user_by('email', $username);
                }

                if ($user_meta) {
                    $creds = array();
                    $creds['user_login'] = $username;
                    $creds['user_password'] = $pwd;
                    $creds['remember'] = true;
                    $user = wp_signon($creds, false);

                    if (is_wp_error($user)) {
                        if ($user->get_error_code() === 'invalid_username') {
                            $err = '<p class="error"><strong>ERROR</strong>: Invalid Email.</p>';
                        } else if ($user->get_error_code() === 'incorrect_password') {
                            $err = '<p class="error"><strong>ERROR</strong>: The password you entered for the email <b>' . $username . '</b> is incorrect.</p>';
                        } else if ($user->get_error_message()) {
                            $err = $user->get_error_message();
                        }
                        $_SESSION['sbsLoginMsg'] = $err;
                        wp_redirect($referer_url);
                        exit;
                    } else {
                        if (in_array('subscriber', $user->roles)) {
                            $affiliate_id = get_user_meta($user->ID, 'affiliate_from', true);
                            if (!empty($affiliate_id)) {
                                setcookie('agent_id', $affiliate_id, strtotime('+1 hour'), "/", $domain);
                            }
                        } else {
                            if (in_array('affiliate_jv_partner', $user->roles)) {
                                setcookie('agent_id', $user->ID, strtotime('+1 hour'), "/", $domain);
                            }
                        }
                    }

                    /* login redirect start */
                    $logged_in_homepage = "";
                    $swiftbooks_logged_in_url = get_option('swiftbooks_logged_in_homepage');

                    /* check membership and get level homepage if any */
                    $swiftbook_membership_levels = get_option('swiftbook_membership_levels');
                    if (isset($swiftbook_membership_levels) && !empty($swiftbook_membership_levels)) {
                        $lvl = 0;
                        $swift_book_memberships = array(
                            'platinum',
                            'gold',
                            'silver',
                            'bronze',
                            'copper'
                        );
                        foreach ($swiftbook_membership_levels['name'] as $membership_level) {
                            if (in_array('swiftbook_membership_' . $swift_book_memberships[$lvl], $user->roles)) {
                                $logged_in_homepage = (isset($swiftbook_membership_levels['homepage'][$lvl]) && !empty($swiftbook_membership_levels['homepage'][$lvl])) ? get_permalink($swiftbook_membership_levels['homepage'][$lvl]) : "";
                            }
                            $lvl++;
                        }
                    }
                    if (empty($logged_in_homepage) && $swiftbooks_logged_in_url) {
                        $logged_in_homepage = get_permalink($swiftbooks_logged_in_url);
                    }
                    $logged_in_homepage = (empty($logged_in_homepage)) ? home_url() : $logged_in_homepage;
                    wp_redirect($logged_in_homepage);
                    exit;
                } else {
                    $err = '<p class="error"><strong>ERROR</strong>: User does not exists.</p>';
                    $_SESSION['sbsLoginMsg'] = $err;
                }
            } else {
                $err = '<p class="error"><strong>ERROR</strong>: Username or/and Password is empty.</p>';
                $_SESSION['sbsLoginMsg'] = $err;
            }
        }

        //forgot password
        if (isset($_POST['swift_membership_lostpwd_nonce']) && wp_verify_nonce($_POST['swift_membership_lostpwd_nonce'], 'swift_membership_lostpwd_nonce')) {
            global $wpdb;
            global $table_user;
            $table_user = $wpdb->prefix . "users";
            $lostPwdEmail = sanitize_email($_POST['sbs_lost_pwd_email']);

            if (!empty($lostPwdEmail)) {
                if (username_exists($lostPwdEmail)) {
                    $userData = get_user_by('login', $lostPwdEmail);
                } else if (email_exists($lostPwdEmail)) {
                    $userData = get_user_by('email', $lostPwdEmail);
                }

                if (!empty($userData->ID)) {
                    $lostPwdkey = md5(time());
                    $query = $wpdb->update(
                            $table_user, array('user_activation_key' => $lostPwdkey), array('ID' => $userData->ID), array('%s'), array('%d'));

                    // Reset password email
                    $reset_pwd_page_id = get_option('swiftbooks_reset_password_page');
                    $reset_pwd_page = get_page_link($reset_pwd_page_id);
                    $reset_pwd_link = $reset_pwd_page . "?act=rpwd&xid=" . base64_encode($userData->ID) . "&pwdkey=" . $lostPwdkey;

                    $to = $userData->user_email;
                    swiftbook_reset_password_mail($reset_pwd_link, $to);

                    $_SESSION['sbsLostPwdMsg'] = '<p class="sucsses">Email has been sent,Please check in mail.</p>';
                    wp_redirect(home_url() . "/login");
                    exit;
                } else {
                    $_SESSION['sbsLostPwdMsg'] = '<p class="error"><strong>ERROR</strong>:  Invalid e-mail.</p>';
                }
            } else {
                $_SESSION['sbsLostPwdMsg'] = '<p class="error"><strong>ERROR</strong>: Enter a username or e-mail address.</p>';
            }
        }

//reset password
        if (isset($_POST['swift_membership_resetpwd_nonce']) && wp_verify_nonce($_POST['swift_membership_resetpwd_nonce'], 'swift_membership_resetpwd_nonce')) {
            $pwd = trim($_POST['sbs_new_pwd']);
            $retypePwd = trim($_POST['sbs_retype_pwd']);
            $userID = sanitize_text_field($_POST['sbs_userid']);

            if (!empty($pwd) && !empty($retypePwd)) {
                if ($pwd == $retypePwd) {
                    wp_set_password($pwd, $userID);

                    $_SESSION['sbsResetPwdMsg'] = '<p class="sucsses">Your password has been reset successfully.</p>';
                    $page_id = get_option("swiftbooks_reset_password_page");
                    if ($page_id) {
                        wp_redirect(get_permalink($page_id));
                        exit;
                    } else {
                        wp_redirect(home_url());
                        exit;
                    }
                } else {
                    $_SESSION['sbsResetPwdMsg'] = '<p class="error"><strong>ERROR</strong>: Passwords do not match.</p>';
                }
            } else {
                $_SESSION['sbsResetPwdMsg'] = '<p class="error"><strong>ERROR</strong>: All fields are required.</p>';
            }
        }

        //change password
        if (isset($_POST['swift_membership_change_password_nonce']) && wp_verify_nonce($_POST['swift_membership_change_password_nonce'], 'swift_membership_change_password_nonce')) {
            $pwd = trim($_POST['sbs_new_password']);
            $pwd1 = trim($_POST['sbs_retype_password']);

            $userId = get_current_user_id();

            if ($pwd != "" && $pwd1 != "") {
                if ($pwd == $pwd1) {
                    wp_set_password($pwd, $userId);

                    $_SESSION['sbsChangePwdMsg'] = '<p class="sucsses">Your password has been changed.</p>';
                    $login_page = get_option('swiftbooks_login_page');
                    ($login_page) ? wp_redirect(get_permalink($login_page)) :
                                    wp_redirect(home_url());
                    die;
                } else {
                    $_SESSION['sbsChangePwdMsg'] = '<p class="error">The passwords does not match.</p>';
                }
            } else {
                $_SESSION['sbsChangePwdMsg'] = '<p class="error">The passwords are empty.</p>';
            }
        }
    }

}


/**
 *      Remove agent cookie on logout
 */
add_action('wp_logout', 'swiftbook_logout_clear_affiliate');
if (!function_exists('swiftbook_logout_clear_affiliate')) {

    function swiftbook_logout_clear_affiliate() {
        if (isset($_COOKIE['agent_id']) && !empty($_COOKIE['agent_id'])) {
            setcookie('agent_id', null, -1, "/");
        }
    }

}
?>