<?php

/*
 *      Shortcode: [swift_membership_lost_password]
 *      no parameter
 */
add_shortcode('swift_membership_lost_password', 'swiftbook_membership_lost_password_cb');
if (!function_exists('swiftbook_membership_lost_password_cb')) {

    function swiftbook_membership_lost_password_cb() {
        if (is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }
        $lpOP = "";
        $lpOP.='<div class="sbs_lostpwd">';
        // Message/Error Section
        if (isset($_SESSION['sbsLostPwdMsg']) && !empty($_SESSION['sbsLostPwdMsg'])) {
            $lpOP.='<div class="swiftbook_notification">' . $_SESSION["sbsLostPwdMsg"] . '</div>';
            unset($_SESSION['sbsLostPwdMsg']);
        }
        //Form HTML
        $lpOP.='<form method="POST" name="FrmMemberLostpwd" class="sbs_form" id="FrmMemberLostpwd">
                <div class="sb-input-group">
                    <label for="sbs_lost_pwd_email">Enter E-mail:</label>
                    <input type="email" name="sbs_lost_pwd_email" id="sbs_lost_pwd_email" required="required" placeholder="Enter Your Email"/>
                </div>
                <div class="sb-input-group">
                    ' . wp_nonce_field("swift_membership_lostpwd_nonce", "swift_membership_lostpwd_nonce") . '
                    <button type="submit" name="sbs_lostpwdsubmit" id="sbs_lostpwdsubmit" value="Get New Password">Get New Password</button>
                </div>
             </form>';
        $lpOP.='</div>';

        return $lpOP;
    }

}
?>