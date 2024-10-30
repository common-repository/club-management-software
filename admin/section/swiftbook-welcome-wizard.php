<?php
/*
 *      Welcome Wizard
 */

function swiftbook_welcome_wizard_cb() {
    $swiftbooks_welcome_wizard = get_option('swiftbooks_welcome_wizard');
    $swiftbooks_welcome_wizard_delay = get_option('swiftbooks_welcome_wizard_delay');
    $swiftbooks_welcome_wizard_width = get_option('swiftbooks_welcome_wizard_width');
    $swiftbooks_welcome_wizard_height = get_option('swiftbooks_welcome_wizard_height');
    $swiftbooks_welcome_wizard_on_off = get_option('swiftbooks_welcome_wizard_on_off');

    if (isset($_POST['save_welcome_wizard']) && !empty($_POST['save_welcome_wizard'])) {
        $swiftbooks_welcome_wizard = wp_kses_post($_POST['sb_welcome_wizard_content']);
        $swiftbooks_welcome_wizard_delay = sanitize_text_field($_POST['swiftbooks_welcome_wizard_delay']);
        $swiftbooks_welcome_wizard_width = sanitize_text_field($_POST['swiftbooks_welcome_wizard_width']);
        $swiftbooks_welcome_wizard_height = sanitize_text_field($_POST['swiftbooks_welcome_wizard_height']);
        $swiftbooks_welcome_wizard_on_off = sanitize_text_field(!empty($_POST['swiftbooks_welcome_wizard_on_off']) ? 1 : 0);

        $update8 = update_option('swiftbooks_welcome_wizard', $swiftbooks_welcome_wizard);
        $update9 = update_option('swiftbooks_welcome_wizard_delay', $swiftbooks_welcome_wizard_delay);
        $update10 = update_option('swiftbooks_welcome_wizard_width', $swiftbooks_welcome_wizard_width);
        $update11 = update_option('swiftbooks_welcome_wizard_height', $swiftbooks_welcome_wizard_height);
        $update12 = update_option('swiftbooks_welcome_wizard_on_off', $swiftbooks_welcome_wizard_on_off);

        if ($update8 || $update9 || $update10 || $update11 || $update12) {
            wp_redirect(admin_url("admin.php?page=swiftbook_welcome_wizard&update=1"));
            die;
        }
    }
    ?>
    <div class="wrap">
        <h2>Welcome Wizard</h2>
        <hr>
        <?php
        if (isset($_GET['update']) && !empty($_GET['update']) && $_GET['update'] == 1) {
            ?>
            <div id="message" class="notice is-dismissible notice-success below-h2">
                <p>Settings saved successfully!</p>
            </div>
            <?php
        }
        ?>
        <div class="inner_content">
            <form name="FrmSBWelcomeWizard" id="FrmSBWelcomeWizard" method="post">
                <div class="inner_content">
                    <table class="form-table">
                        <tr>
                            <th><label for="popup-delay">Currently, the welcome wizard is </label></th>
                            <td>
                                <div class="toggle_container" style="width:100px; text-align:center">
                                    <?php $OnOff = ($swiftbooks_welcome_wizard_on_off == 1 ? 'checked="checked"' : ""); ?>
                                    <input type="checkbox" value="1" data-ontext="ON" data-offtext="OFF" name="swiftbooks_welcome_wizard_on_off" id="sb_ww_onoff" <?php echo $OnOff; ?>>
                                </div>
                            </td>
                        </tr>
                        <tr class="ww_hide">
                            <th><label for="popup-delay">Fire this popup after </label></th>
                            <td><input type="text" value="<?php echo $swiftbooks_welcome_wizard_delay; ?>" class="" name="swiftbooks_welcome_wizard_delay"/> seconds</td>
                        </tr>
                        <tr class="ww_hide">
                            <th><label for="popup-delay">with a width of</label></th>
                            <td><input type="text" value="<?php echo $swiftbooks_welcome_wizard_width; ?>" class="" name="swiftbooks_welcome_wizard_width"/> in pixels</td>
                        </tr>
                        <tr class="ww_hide">
                            <th><label for="popup-delay">and height</label></th>
                            <td><input type="text" value="<?php echo $swiftbooks_welcome_wizard_height ?>" class="" name="swiftbooks_welcome_wizard_height"/> in pixels.</td>
                        </tr>
                    </table>
                    <!--<input style="display:none;" type="radio" class="" name="sb_welcome_wizard" value="html_content" />-->
                    <div class="ww_hide">
                        <?php
                        $settings = array('media_buttons' => true, 'quicktags' => true, 'textarea_name' => 'sb_welcome_wizard_content',);
                        wp_editor(stripslashes($swiftbooks_welcome_wizard), 'sb_welcome_wizard_id', $settings)
                        ?>
                    </div>
                </div>
                <br/>
                <?php wp_nonce_field('save_welcome_wizard', 'save_welcome_wizard'); ?>
                <input type="submit" class="button-primary" value="Save Settings" name="swiftbooks_submit_welcome" />
            </form>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                if (jQuery('#sb_ww_onoff:checkbox').is(':checked')) {
                    jQuery('.ww_hide').show();
                }
                jQuery('#sb_ww_onoff:checkbox').rcSwitcher({
                    width: 80,
                }).on({
                    'turnon.rcSwitcher': function(e, dataObj) {
                        // to do on turning on a switch
                        jQuery('.ww_hide').show();
                    },
                    'turnoff.rcSwitcher': function(e, dataObj) {
                        // to do on turning off a switch
                        jQuery(".ww_hide").hide();
                    }
                });
            });
        </script>
    </div>
    <?php
}
?>