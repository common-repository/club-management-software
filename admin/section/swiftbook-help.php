<?php
/*
 *      Subscription Management Page
 */
if (!function_exists('swiftbook_help_cb')) {

    function swiftbook_help_cb() {
        ?>
        <div class="wrap">
            <h2>Help / Setup</h2>
            <hr/>
            <div class="inner_content">
                <h3>1. The code to setup the nav (logged in vs logged out)</h3>
                <p><strong><?php _e('How to use:', 'swiftbook'); ?></strong></p>
                <ol class="sb_setup_nav">
                    <li><?php _e('Visit <a href="http://swiftbooks.com/?utm_source=native_ads&utm_medium=wordpress_plugin&utm_term=wordpress_plugin_installed&utm_campaign=wordpress_plugin" target="_blank">SwiftBooks.com</a>, signup or login as needed, then go to Settings >> Subscriptions. Setup your subscription details; you\'ll manage all your subscribers including the financials, payment history, etc. within SwiftBooks, and all subscribers will have a SwiftBooks account (free) to manage upgrades, cancellation, etc. Take note of the subscription number(s).', 'swiftbook'); ?></li>
                    <li><?php _e('Add <input type="text" readonly="readonly" class="justSelect" onclick="this.select();" value="[SwiftBooks Subscription Home Redirector]" style="width: 300px;"/> to your homepage. It will not display anything, but will redirect logged-in-users to the homepage you have selected.', 'swiftbook'); ?></li>
                    <li><?php _e('Add <input type="text" readonly="readonly" class="justSelect" onclick="this.select();" value="[SwiftBooks Logged-in-only]"/> to any page you want to protect. It will redirect visitors to your chosen page selected above.', 'swiftbook'); ?></li>
                    <li><?php _e('To split the Navigation Menu into 2 separate logged-out and logged-in menus, add the following code into your theme: <input type="text" readonly="readonly" class="justSelect" value="[login_logout_nav]"/>', 'swiftbook'); ?></li>
                    <li><?php _e('Tip: You can also greet your members by name using <input type="text" onclick="this.select();" readonly="readonly" class="justSelect" value="[swiftbooks firstname]"/> or in the theme you can add <code>&lt;?php echo do_shortcode("[swiftbooks firstname]"); ?&gt;</code>', 'swiftbook'); ?></li>
                </ol>
                <ul class="sb_setup_nav">
                    <li><?php _e('- Create two menus.', 'swiftbook'); ?></li>
                    <li><?php _e('- For the Loggedin menu, selct <b>Logged in Menu</b> under<b> Menu Settings</b>.', 'swiftbook'); ?></li>
                    <li><?php _e('- For the Loggout menu, select <b>Logged out Menu</b> under<b> Menu Settings</b>.', 'swiftbook'); ?></li>
                    <li><?php _e('- Add shortcode <input type="text" readonly="readonly" onclick="this.select();" class="justSelect" value="[login_logout_nav]"/> where you want to display menu.', 'swiftbook'); ?></li>
                    <li><?php _e('<b>Note: </b> Change the css as per your requirement.', 'swiftbook'); ?></li>
                </ul>
                <hr/>
            </div>
            <div class="inner_content">
                <h3><?php _e('2. How to re-trigger the Welcome Wizard?', 'swiftbook'); ?></h3>
                <ul>
                    <li><?php _e('- Copy following tag and Paste where you want to re-trigger Welcome Wizard.', 'swiftbook'); ?></li>
                    <li><?php _e('&lt;a href="javascript:void(0)" id="tourvideo"&gt; Tour Video&lt;/a&gt;', 'swiftbook'); ?></li>
                    <li><?php _e('This will automatically open popup with the content you set on backend, when clicked.', 'swiftbook'); ?></li>
                </ul>
                <hr/>
            </div>
            <div class="inner_content">
                <h3><?php _e('3. How to setup registration form?', 'swiftbook'); ?></h3>
                <ul>
                    <li><?php _e('Add following shortcode  in page or post, This shortcode display signup form.', 'swiftbook'); ?></li>
                    <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_membership_signup paid_through_date=\'+1 month\']" style="width: 30em"/>', 'swiftbook'); ?></li>
                    <li><?php _e('  - paid_through_date : Optional; Add user\'s validity(paid through date) value like +7 days, +2 months, +1 year; Default validity : +1 month', 'swiftbook'); ?></li>
                </ul>
                <p><?php _e('<b>Note: </b> When you active plugin, It will auto generate a page named "Signup" with this shortcode.', 'swiftbook'); ?></p>
                <hr/>
            </div>
            <div class="inner_content">
                <h3><?php _e('4. How to setup login form?', 'swiftbook'); ?></h3>
                <ul>
                    <li><?php _e('Add following shortcode  in page or post, This shortcode display login form.', 'swiftbook'); ?></li>
                    <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_membership_login]" style="width: 30em"/>', 'swiftbook'); ?></li>
                </ul>
                <p><?php _e('<b>Note: </b> When you active plugin, It will auto generate a page named "Login" with this shortcode.', 'swiftbook'); ?></p>
                <hr/>
            </div>
            <div class="inner_content">
                <h3><?php _e('5. How to setup lost password form?', 'swiftbook'); ?></h3>
                <ul>
                    <li><?php _e('Add following shortcode  in page or post, This shortcode display lost password form.', 'swiftbook'); ?></li>
                    <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_membership_lost_password]" style="width: 30em"/>', 'swiftbook'); ?></li>
                </ul>
                <p><?php _e('<b>Note: </b> When you active plugin, It will auto generate a page named "Lost Password" with this shortcode.', 'swiftbook'); ?></p>
                <hr/>
            </div>
            <div class="inner_content">
                <h3><?php _e('6. How to setup reset password form?', 'swiftbook'); ?></h3>
                <ul>
                    <li><?php _e('Add following shortcode  in page, This shortcode display reset password form.', 'swiftbook'); ?></li>
                    <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_membership_reset_password]" style="width: 30em"/>', 'swiftbook'); ?></li>
                </ul>
                <p><?php _e('<b>Note: </b> When you active plugin, It will auto generate a page named "Reset Password" with this shortcode.', 'swiftbook'); ?></p>
                <hr/>
            </div>
            <div class="inner_content">
                <h3><?php _e('7. How to setup change password form?', 'swiftbook'); ?></h3>
                <ul>
                    <li><?php _e('Add following shortcode  in page, This shortcode display change password form.', 'swiftbook'); ?></li>
                    <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swift_membership_change_password]" style="width: 30em"/>', 'swiftbook'); ?></li>
                </ul>
                <p><?php _e('<b>Note: </b> When you active plugin, It will auto generate a page named "Change Password" with this shortcode.', 'swiftbook'); ?></p>
            </div>
            <div class="inner_content">
                <h3><?php _e('8. Shortcode: [swiftbook_logout] ', 'swiftbook'); ?></h3>
                <ul>
                    <li><?php _e('Logout the login user', 'swiftbook'); ?></li>
                    <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swiftbook_logout]" style="width: 30em"/>', 'swiftbook'); ?></li>
                </ul><hr/>
            </div>
            <div class="inner_content">
                <h3><?php _e('9. Shortcode: [swiftbook_firstname] ', 'swiftbook'); ?></h3>
                <ul>
                    <li><?php _e('Display login user\'s first name', 'swiftbook'); ?></li>
                    <li><?php _e('<input type="text" readonly="readonly" class="regular-text" onclick="this.select();" value="[swiftbook_firstname]" style="width: 30em"/>', 'swiftbook'); ?></li>
                </ul>
            </div>
        </div>
        <?php
    }

}
?>