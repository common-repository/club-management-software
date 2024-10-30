<?php

/*
 *      Shortcode: [swift_membership_signup paid_through_date=""]
 *      paid_through_date: add user validity(paid through date) value like +7 days, +2 months, +1 year; Default validity: +1 month
 */
add_shortcode('swift_membership_signup', 'swiftbook_membership_signup_cb');
if (!function_exists('swiftbook_membership_signup_cb')) {

    function swiftbook_membership_signup_cb($atts) {
        if (is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }
        $regOP = "";
        $a = shortcode_atts(
                array(
            'paid_through_date' => ''
                ), $atts);
        extract($a);

        // Form HTML

        $paid_through_date = !empty($a['paid_through_date']) ? $a['paid_through_date'] : '+1 month';
        $regOP .= '<div class="sbs_signup">';
        // Message/Error section
        if (isset($_SESSION['sbsSignupMsg']) && !empty($_SESSION['sbsSignupMsg'])) {
            $regOP.='<div class="swiftbook_notification">' . $_SESSION['sbsSignupMsg'] . '</div>';
            unset($_SESSION['sbsSignupMsg']);
        }

        $regOP.='<form action="" id="FrmMemberSignup" class="sbs_form" name="FrmMemberSignup" method="POST">
                <div class="sb-input-group">
                    <label for="sbs_signupemail">Enter Email</label>
                    <input type="email" name="sbs_signup_email" id="sbs_signupemail" required="required" />
                </div>
                <div class="sb-input-group">
                    <label for="sbs_signuppassword">Enter Password</label>
                    <input type="password" name="sbs_signup_password" id="sbs_signuppassword" class="" required="required"/>
                </div>
                <div class="sb-input-group">
                    <input type="hidden" name="swiftcloud_sm_paidthroughdate" value="' . $paid_through_date . '" />
                    ' . wp_nonce_field("swift_membership_signup_nonce", "swift_membership_signup_nonce") . '
                    <button type="submit" name="sbs_signupsubmit" id="sbs_signupsubmit" value="Signup" >Signup</button>
                </div>
            </form>';
        $regOP.='</div>';

        return $regOP;
    }

}


/**
 *      saving additional user meta
 */
add_action('user_register', 'swiftbook_signup_addtional_meta', 10, 1);
if (!function_exists('swiftbook_signup_addtional_meta')) {

    function swiftbook_signup_addtional_meta($user_id) {
        $user_meta = get_userdata($user_id);
        if (!in_array("affiliate_jv_partner", $user_meta->roles)) {
            $paid_through_date = !empty($_POST['swiftcloud_sm_paidthroughdate']) ? sanitize_text_field($_POST['swiftcloud_sm_paidthroughdate']) : '+1 month';

            $dt = date('n/j/Y');
            $cdate = strtotime($dt);
            $new_date = strtotime($paid_through_date, $cdate);
            $new_paid_through_date = date('n/j/Y', $new_date);

            update_user_meta($user_id, 'show_admin_bar_front', 'false');
            update_user_meta($user_id, 'swiftcloud_sm_paidthroughdate', $new_paid_through_date);
        }
    }

}
?>