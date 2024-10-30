<?php

/*
 *      Shortcode: [swift_membership_reset_password]
 *      no parameter
 */
add_shortcode('swift_membership_reset_password', 'swiftbook_membership_reset_password_cb');
if (!function_exists('swiftbook_membership_reset_password_cb')) {

    function swiftbook_membership_reset_password_cb() {
        if (is_user_logged_in() || (!isset($_REQUEST['act']) && empty($_REQUEST['act']) && $_REQUEST['act'] != 'rpwd' && !isset($_REQUEST['pwdkey']) && empty($_REQUEST['pwdkey']))) {
            wp_redirect(home_url());
            exit;
        }
        $lpOP = "";

        $pwdKey = $_REQUEST['pwdkey'];
        $userID = base64_decode($_REQUEST['xid']);

        //get user activation key
        global $wpdb;
        $tab_user = $wpdb->prefix . 'users';
        $resetPwdUser = $wpdb->get_row("SELECT * FROM " . $tab_user . " WHERE ID =" . $userID);

        $lpOP.='<div class="sbs_resetpwd">';

        // Message/Error Section
        if (isset($_SESSION['sbsResetPwdMsg']) && !empty($_SESSION['sbsResetPwdMsg'])) {
            $lpOP.='<div class="swiftbook_notification">' . $_SESSION["sbsResetPwdMsg"] . '</div>';
            unset($_SESSION['sbsResetPwdMsg']);
        }


        if ($pwdKey != $resetPwdUser->user_activation_key) {
            $lpOP.='<div class="swiftbook_notification"><p class="error">Sorry, that key does not appear to be valid.</p></div>';
        } else {
            //Form HTML
            $lpOP.='<form method="POST" name="FrmMemberRestePwd" class="sbs_form" id="FrmMemberRestePwd">
                <div class="sb-input-group">
                    <label for="sbs_new_pwd">New Password:</label>
                    <input type="password" placeholder="New Password" name="sbs_new_pwd" id="sbs_new_pwd" class="" required="required" />
                </div>
                <div class="sb-input-group">
                    <label for="sbs_retype_pwd">Retype Password:</label>
                    <input type="password" placeholder="Retype Password" name="sbs_retype_pwd" id="sbs_retype_pwd" required="required" />
                </div>
                <div class="sb-input-group">
                    <input type="hidden" name="sbs_userid" value="' . $resetPwdUser->ID . '"/>
                    ' . wp_nonce_field("swift_membership_resetpwd_nonce", "swift_membership_resetpwd_nonce") . '
                    <button type="submit" name="sbs_lostpwdsubmit" id="sbs_lostpwdsubmit" value="Get New Password">Get New Password</button>
                </div>
             </form>';
        }
        $lpOP.='</div>';

        return $lpOP;
    }

}
?>