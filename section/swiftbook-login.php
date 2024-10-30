<?php

/*
 *      Shortcode: [swift_membership_login]
 *      no parameter
 */
add_shortcode('swift_membership_login', 'swiftbook_login_shortcode_cb');
if (!function_exists('swiftbook_login_shortcode_cb')) {

    function swiftbook_login_shortcode_cb() {
        if (is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }
        $swiftbooks_forgot_password_page = get_option('swiftbooks_forgot_password_page');
        $logOP = "";
        $logOP.='<div class="sbs_login">';
        // Message/Error Section

        if (isset($_SESSION['sbsLoginMsg']) && !empty($_SESSION['sbsLoginMsg'])) {
            $logOP.='<div class="swiftbook_notification">' . $_SESSION["sbsLoginMsg"] . '</div>';
            unset($_SESSION['sbsLoginMsg']);
        }
        if (isset($_SESSION['sbsLostPwdMsg']) && !empty($_SESSION['sbsLostPwdMsg'])) {
            $logOP.='<div class="swiftbook_notification">' . $_SESSION["sbsLostPwdMsg"] . '</div>';
            unset($_SESSION['sbsLostPwdMsg']);
        }
        if (isset($_SESSION['sbsChangePwdMsg']) && !empty($_SESSION['sbsChangePwdMsg'])) {
            $logOP.='<div class="swiftbook_notification">' . $_SESSION["sbsChangePwdMsg"] . '</div>';
            unset($_SESSION['sbsChangePwdMsg']);
        }

        //Form HTML
        $logOP.='<form id="FrmMemberLogin" class="sbs_form" name="FrmMemberLogin" method="POST">
                <div class="sb-input-group">
                    <label for="sbs_loginusername">Enter Email</label>
                    <input type="text" name="sbs_login_username" id="sbs_loginusername" required="required" placeholder="Enter Your Email"/>
                </div>
                <div class="sb-input-group">
                    <label for="sbs_loginpassword">Enter Password</label>
                    <input type="password" name="sbs_login_password" id="sbs_loginpassword" required="required" placeholder="Enter Your Password" />
                </div>
                <div class="sb-input-group">
                    ' . wp_nonce_field("swift_membership_login_nonce", "swift_membership_login_nonce") . '
                    <button type="submit" name="sbs_loginsubmit" id="sbs_loginsubmit" value="Login">Login</button>
                </div>
                <div class="sb-input-group sb-lostpwd-link-block">
                    <a class="sb-lost-pwd-link" href="' . get_permalink($swiftbooks_forgot_password_page) . '">Lost Password?</a>
                </div>
            </form>';
        $logOP.='</div>';

        return $logOP;
    }

}

/**
 *  Login extra validation
 *  check paid through date and user expiration
 */
add_filter('wp_authenticate_user', 'swiftbook_login_authentication', 30, 3);
if (!function_exists('swiftbook_login_authentication')) {

    function swiftbook_login_authentication($user, $password) {
        if ($user->roles[0] == 'subscriber') {
            $swiftbooks_expired_user_url = get_option('swiftbooks_expired_page');
            $swiftbooks_banned_page_url = get_option('swiftbooks_banned_page');
            $swiftbooks_grace_period = get_option('swiftbooks_grace_period');

            $paidThroughtDate = get_user_meta($user->ID, "swiftcloud_sm_paidthroughdate", true);

            if ($paidThroughtDate == 'life_time') {
                return $user;
            }

            if ($paidThroughtDate == '00/00/0000') {
                //banned user
                if ($swiftbooks_banned_page_url) {
                    wp_redirect(get_permalink($swiftbooks_banned_page_url));
                    exit;
                } else {
                    return new WP_Error('banned', __("<strong>ERROR</strong>: Your account has been banned", "swiftbook"));
                }
            } else {
                $date = strtotime(date("n/d/Y", strtotime($paidThroughtDate)) . " +" . $swiftbooks_grace_period . " day");
                $newPaidThroughtDate = date('n/d/Y', $date);
                if (strtotime($newPaidThroughtDate) >= strtotime(date('n/j/Y'))) {
                    return $user;
                } else {
                    //expired user
                    if ($swiftbooks_expired_user_url) {
                        wp_redirect(get_permalink($swiftbooks_expired_user_url));
                        exit;
                    } else {
                        return new WP_Error('expired', __("<strong>ERROR</strong>: Your account has been expired.", "swiftbook"));
                    }
                }
            }
        } else {
            return $user;
        }
    }

}
?>