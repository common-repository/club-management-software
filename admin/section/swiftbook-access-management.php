<!--Access Management Content -->
<?php
//access management options
$swiftbooks_logged_in_homepage = get_option('swiftbooks_logged_in_homepage');
$swiftbooks_logged_out_homepage = get_option('swiftbooks_logged_out_homepage');
$swiftbooks_expired_page = get_option('swiftbooks_expired_page');
$swiftbooks_banned_page = get_option('swiftbooks_banned_page');
$swiftbooks_login_page = get_option('swiftbooks_login_page');
$swiftbooks_reset_password_page = get_option('swiftbooks_reset_password_page');
$swiftbooks_forgot_password_page = get_option('swiftbooks_forgot_password_page');
$swiftbooks_grace_period = get_option('swiftbooks_grace_period');


if (isset($_POST['save_sb_access_management']) && wp_verify_nonce($_POST['save_sb_access_management'], 'save_sb_access_management')) {

    $swiftbooks_logged_in_homepage = sanitize_text_field($_POST['swiftbooks_logged_in_homepage']);
    $swiftbooks_logged_out_homepage = sanitize_text_field($_POST['swiftbooks_logged_out_homepage']);
    $swiftbooks_expired_page = sanitize_text_field($_POST['swiftbooks_expired_page']);
    $swiftbooks_banned_page = sanitize_text_field($_POST['swiftbooks_banned_page']);
    $swiftbooks_login_page = sanitize_text_field($_POST['swiftbooks_login_page']);
    $swiftbooks_reset_password_page = sanitize_text_field($_POST['swiftbooks_reset_password_page']);
    $swiftbooks_forgot_password_page = sanitize_text_field($_POST['swiftbooks_forgot_password_page']);
    $swiftbooks_grace_period = sanitize_text_field($_POST['swiftbooks_grace_period']);

    $update1 = update_option('swiftbooks_logged_in_homepage', $swiftbooks_logged_in_homepage);
    $update2 = update_option('swiftbooks_logged_out_homepage', $swiftbooks_logged_out_homepage);
    $update3 = update_option('swiftbooks_expired_page', $swiftbooks_expired_page);
    $update4 = update_option('swiftbooks_banned_page', $swiftbooks_banned_page);
    $update5 = update_option('swiftbooks_login_page', $swiftbooks_login_page);
    $update6 = update_option('swiftbooks_reset_password_page', $swiftbooks_reset_password_page);
    $update7 = update_option('swiftbooks_forgot_password_page', $swiftbooks_forgot_password_page);
    $update8 = update_option('swiftbooks_grace_period', $swiftbooks_grace_period);

    if ($update1 || $update2 || $update3 || $update4 || $update5 || $update6 || $update7 || $update8) {
        wp_redirect(admin_url("admin.php?page=swiftbooks_subscription&tab=swiftbook-access-management&update=1"));
        die;
    }
}
?>
<div class="inner_content">
    <form name="FrmSBAccessManagement" id="FrmSBAccessManagement" method="post" action="">
        <ol>
            <li>
                <strong>Logged-in Home Page:</strong>
                Use
                <select name="swiftbooks_logged_in_homepage" id="swiftbooks_logged_in_homepage">
                    <option value="0">--Select Page--</option>
                    <?php
                    if ($pages) {
                        foreach ($pages as $page) {
                            ?>
                            <option <?php selected($swiftbooks_logged_in_homepage, $page->ID); ?> value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
                as the Logged-in Home Page.
            </li>
            <li>
                <strong>Access Restriction:</strong>
                If a user is not logged in, but tries to access a protected page, redirect them to
                <select name="swiftbooks_logged_out_homepage" id="swiftbooks_logged_out_homepage">
                    <option value="0">--Select Page--</option>
                    <?php
                    if ($pages) {
                        foreach ($pages as $page) {
                            ?>
                            <option <?php selected($swiftbooks_logged_out_homepage, $page->ID); ?> value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
                , i.e. usually a signup page about your subscription benefits.
            </li>
            <li>
                <strong>Expired page:</strong> If a user's access has expired, but they have not been banned, redirect them to
                <select name="swiftbooks_expired_page" id="swiftbooks_expired_page">
                    <option value="0">--Select Page--</option>
                    <?php
                    if ($pages) {
                        foreach ($pages as $page) {
                            ?>
                            <option <?php selected($swiftbooks_expired_page, $page->ID); ?> value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>, i.e. a renewal page.
            </li>
            <li>
                <strong>Grace Period:</strong> If a user's billing was not updated (card expired, declined, etc.) allow a grace period of
                <select name="swiftbooks_grace_period" id="swiftbooks_grace_period" style="width: 50px;">
                    <option value="0" <?php selected($swiftbooks_grace_period, 0); ?>>0</option>
                    <option value="1" <?php selected($swiftbooks_grace_period, 1); ?>>1</option>
                    <option value="2" <?php selected($swiftbooks_grace_period, 2); ?>>2</option>
                    <option value="3" <?php selected($swiftbooks_grace_period, 3); ?>>3</option>
                    <option value="4" <?php selected($swiftbooks_grace_period, 4); ?>>4</option>
                    <option value="5" <?php selected($swiftbooks_grace_period, 5); ?>>5</option>
                </select> days to attempt rebill.
            </li>
            <li>
                <strong>Banned page:</strong>  If a user has been banned, or charged back a credit card, and is blocked from signup or banned from access, redirect them to
                <select name="swiftbooks_banned_page" id="swiftbooks_banned_page">
                    <option value="0">--Select Page--</option>
                    <?php
                    if ($pages) {
                        foreach ($pages as $page) {
                            ?>
                            <option <?php selected($swiftbooks_banned_page, $page->ID); ?> value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </li>
            <li>
                <strong>Login page:</strong>  select login page
                <select name="swiftbooks_login_page" id="swiftbooks_login_page">
                    <option value="0">--Select Page--</option>
                    <?php
                    if ($pages) {
                        foreach ($pages as $page) {
                            ?>
                            <option <?php selected($swiftbooks_login_page, $page->ID); ?> value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </li>
            <li>
                <strong>Reset password page:</strong>  select reset password page
                <select name="swiftbooks_reset_password_page" id="swiftbooks_reset_password_page">
                    <option value="0">--Select Page--</option>
                    <?php
                    if ($pages) {
                        foreach ($pages as $page) {
                            ?>
                            <option <?php selected($swiftbooks_reset_password_page, $page->ID); ?> value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </li>
            <li>
                <strong>Forgot password page:</strong>  select forgot password page
                <select name="swiftbooks_forgot_password_page" id="swiftbooks_forgot_password_page">
                    <option value="0">--Select Page--</option>
                    <?php
                    if ($pages) {
                        foreach ($pages as $page) {
                            ?>
                            <option <?php selected($swiftbooks_forgot_password_page, $page->ID); ?> value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </li>
        </ol>
        <?php wp_nonce_field('save_sb_access_management', 'save_sb_access_management') ?>
        <input type="submit" class="button-primary" value="Save Settings" name="swiftbooks_submit_subs" />
    </form>
</div>
