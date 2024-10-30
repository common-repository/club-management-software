<?php

/*
 *      Shortcode: [swift_membership_change_password]
 *      no parameter
 */
add_shortcode('swift_membership_change_password', 'swiftbook_change_password_shortcode_cb');
if (!function_exists('swiftbook_change_password_shortcode_cb')) {

    function swiftbook_change_password_shortcode_cb() {
        if (!is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }

        $OP = "";
        $OP.='<div class="sbs_change_password">';
        // Message/Error Section

        if (isset($_SESSION['sbsChangePwdMsg']) && !empty($_SESSION['sbsChangePwdMsg'])) {
            $OP.='<div class="swiftbook_notification">' . $_SESSION["sbsChangePwdMsg"] . '</div>';
            unset($_SESSION['sbsChangePwdMsg']);
        }
        //Form HTML
        $OP.='<form id="FrmMemberChangePwd" class="sbs_form" name="FrmMemberChangePwd" method="POST">
                <div class="sb-input-group">
                    <label for="sbs_new_password">New Password</label>
                    <input type="password" name="sbs_new_password" id="sbs_new_password" required="required" placeholder="New Password"/>
                </div>
                <div class="sb-input-group">
                    <label for="sbs_retype_password">Re-type Password</label>
                    <input type="password" name="sbs_retype_password" id="sbs_retype_password" required="required" placeholder="Retype Password" />
                </div>
                <div class="sb-input-group">
                    ' . wp_nonce_field("swift_membership_change_password_nonce", "swift_membership_change_password_nonce") . '
                    <button type="submit" name="sbs_changepwdsubmit" id="sbs_changepwdsubmit" value="Change Password">Change Password</button>
                </div>
            </form>';
        $OP.='</div>';

        return $OP;
    }

}
?>