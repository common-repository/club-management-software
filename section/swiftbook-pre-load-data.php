<?php

/*
 *      With plugin active insert following data
 */
if (!function_exists('swiftbook_pre_load_data')) {

    function swiftbook_pre_load_data() {
        global $wpdb;
        /* Email Template table */
        $charset_collate = $wpdb->get_charset_collate();
        $table_email_template = $wpdb->prefix . 'sb_email_template';
        $create_table = "CREATE TABLE IF NOT EXISTS `$table_email_template` (
                        `et_id` int(11) NOT NULL AUTO_INCREMENT,
                        `et_name` varchar(100) NOT NULL,
                        `et_replace_keyword` TEXT NOT NULL,
                        `et_subject` varchar(255) NOT NULL,
                        `et_content` LONGTEXT NOT NULL,
                         PRIMARY KEY (`et_id`)
                     ) $charset_collate ;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($create_table);

        /**
         *      Email template default data
         */
        global $wpdb;
        $sitename = get_bloginfo('name');

        /** signup email content * */
        $subject = 'Welcome to {site_title}';
        $body = '';
        $body.= 'Hello,';
        $body.= '<br/><br/>Welcome to {site_title}';
        $body.= "<br/><br/>You've successfully registered with us.";
        $body.= "<br/>Here is your Username & Password";
        $body.= "<br/>Username: {user_name}";
        $body.= "<br/>Password: {user_password}";
        $body.= "<br /><br />Thanks,<br />{site_title}";
        $signupMailBody = addslashes($body);

        $insert_data = "INSERT INTO `" . $wpdb->prefix . 'sb_email_template' . "` (`et_id`,`et_name`, `et_replace_keyword`, `et_subject`, `et_content`) VALUES
                    (1,'Signup welcome email', 'user_name,user_email,user_password,site_title', '$subject', '$signupMailBody');";
        $wpdb->query($insert_data);

        /** forgot password email content * */
        $fp_subject = 'Reset password request';
        $fp_body = '';
        $fp_body.= 'Hello,';
        $fp_body.= '<br/><br/>We have received your reset password request.';
        $fp_body.= '<br/>To reset your password visit the following address, otherwise just ignore this email and nothing will happen.<br/>';
        $fp_body.= '<br/><a href="{reset_password_link}">Click here</a> to reset password.';
        $fp_body.= "<br /><br />Thanks,<br />{site_title}";

        $fp_insert_data = "INSERT INTO `" . $wpdb->prefix . 'sb_email_template' . "` (`et_id`,`et_name`, `et_replace_keyword`, `et_subject`, `et_content`) VALUES
                    (2,'Reset password email', 'reset_password_link,site_title', '$fp_subject', '$fp_body');";
        $wpdb->query($fp_insert_data);

        /* update options */
        //membership level toggle
        update_option('swiftbooks_membership_free_paid', '1');
        update_option('swiftbooks_grace_period','3');
    }

}

/*
 *      After user permission insert following data
 */
if (!function_exists('swiftbook_initial_data')) {

    function swiftbook_initial_data() {
        /**
         *   Insert pages: Login / Signup / Lost Password / Reset password
         *      option: Subscription Management Page login/lost password/reset password option
         *
         */
        $pages_array = array(
            "signup" => array("title" => "Signup", "content" => "[swift_membership_signup]", "option" => ""),
            "change-password" => array("title" => "Change Password", "content" => "[swift_membership_change_password]", "option" => ""),
            "login" => array("title" => "Login", "content" => "[swift_membership_login]", "option" => "swiftbooks_login_page"),
            "lost-password" => array("title" => "Lost Password", "content" => "[swift_membership_lost_password]", "option" => "swiftbooks_forgot_password_page"),
            "reset-password" => array("title" => "Reset Password", "content" => "[swift_membership_reset_password]", "option" => "swiftbooks_reset_password_page"),
            "home-logged-in" => array("title" => "Home Logged IN", "content" => "<h2>Coming soon...</h2>", "option" => "swiftbooks_logged_in_homepage"),
            "home-logged-out" => array("title" => "Home Logged OUT", "content" => "<h2>Coming soon...</h2>", "option" => "swiftbooks_logged_out_homepage"),
            "expired-page" => array("title" => "Expired Page", "content" => "<h2>Coming soon...</h2>", "option" => "swiftbooks_expired_page"),
            "banned-page" => array("title" => "Banned Page", "content" => "<h2>Coming soon...</h2>", "option" => "swiftbooks_banned_page"),
        );
        $swiftbooks_pages_id = '';
        foreach ($pages_array as $key => $page) {
            $page_data = array(
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_title' => $page['title'],
                'post_content' => $page['content'],
                'comment_status' => 'closed'
            );
            $page_id = wp_insert_post($page_data);
            if (!empty($page['option'])) {
                update_option($page['option'], $page_id);
            }
            $swiftbooks_pages_id .= $page_id . ",";
        }

        if (!empty($swiftbooks_pages_id)) {
            update_option('swiftbooks_pages', rtrim($swiftbooks_pages_id, ","));
        }
    }

}
?>
