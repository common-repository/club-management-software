<?php

/**
 *      Set "nofollow" meta tag if "Who can see this?" == member
 */
add_action("wp_head", "swiftbook_set_meta_tag");
if (!function_exists('swiftbook_set_meta_tag')) {

    function swiftbook_set_meta_tag() {
        global $post;
echo get_the_ID();
        $page_restriction = get_post_meta($post->ID, 'swiftbooks_page_restriction', true);
        if (!empty($page_restriction)) {
            echo '<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">';
        }
    }

}

/**
 *      Get email template
 *      @param int $template_id pass template id
 *      @return mixed Email subject, Email body
 */
if (!function_exists('swiftbook_get_email_template')) {

    function swiftbook_get_email_template($template_id) {
        global $wpdb;
        global $table_email_template;
        $table_email_template = $wpdb->prefix . 'sb_email_template';

        $template = $wpdb->get_row("SELECT * FROM `$table_email_template` WHERE `et_id`=" . $template_id);

        return $template;
    }

}


/**
 *      Send welcome email when user signup
 *      @param string $userName Optional | User name
 *      @param string $userEmail Required | User Email
 *      @param mixed $userPwd Optional | User Password
 */
if (!function_exists('swiftbook_signup_mail')) {

    function swiftbook_signup_mail($userName = "", $userEmail = "", $userPwd = "") {
        $template = swiftbook_get_email_template(1);

        $sitename = get_bloginfo('name');
        $body = "";
        $to = $userEmail;

        $email_content = stripslashes($template->et_content);
        $replace = explode(",", $template->et_replace_keyword);

        $email_content = str_replace("{" . $replace[0] . "}", $userName, $email_content);
        $email_content = str_replace("{" . $replace[1] . "}", $userEmail, $email_content);
        $email_content = str_replace("{" . $replace[2] . "}", $userPwd, $email_content);
        $email_content = str_replace("{" . $replace[3] . "}", $sitename, $email_content);
        $subject = str_replace("{" . $replace[3] . "}", $sitename, $template->et_subject);

        $body.=nl2br($email_content);

        $headers = array("Content-Type: text/html; charset=UTF-8", "From: " . $sitename . " <" . get_bloginfo('admin_email') . ">");

        wp_mail($to, $subject, $body, $headers);
    }

}

/**
 *      Send reset password email
 *      @param string $resetPasswordLink Required | Reset password link
 *      @param string $userName Required | User name
 */
if (!function_exists('swiftbook_reset_password_mail')) {

    function swiftbook_reset_password_mail($resetPasswordLink, $userEmail = "") {
        $template = swiftbook_get_email_template(2);
        $sitename = get_bloginfo('name');

        $body = "";
        $to = $userEmail;


        $email_content = stripslashes($template->et_content);
        $replace = explode(",", $template->et_replace_keyword);
        $email_content = str_replace("{" . $replace[0] . "}", $resetPasswordLink, $email_content);
        $email_content = str_replace("{" . $replace[1] . "}", $sitename, $email_content);
        $subject = str_replace("{" . $replace[1] . "}", $sitename, $template->et_subject);

        $body.=nl2br($email_content);

        $headers = array("Content-Type: text/html; charset=UTF-8", "From: " . $sitename . " <" . get_bloginfo('admin_email') . ">");
        wp_mail($to, $subject, $body, $headers);
    }

}
?>
