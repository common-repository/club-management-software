<?php
add_action('wp_footer', 'swiftbook_welcome_popup', 10);
if (!function_exists('swiftbook_welcome_popup')) {

    function swiftbook_welcome_popup() {
        $sb_welcome_wizard = get_option('swiftbooks_welcome_wizard');
        $sb_welcome_wizard_delay = get_option('swiftbooks_welcome_wizard_delay');
        $sb_welcome_wizard_width = get_option('swiftbooks_welcome_wizard_width');
        $sb_welcome_wizard_height = get_option('swiftbooks_welcome_wizard_height');
        $sb_welcome_wizard_on_off = get_option('swiftbooks_welcome_wizard_on_off');

        //Return if not enabled
        if ($sb_welcome_wizard_on_off == 0)
            return;

        //don't show popoup if not login
        if (!is_user_logged_in())
            return;

        $returner = '';
        ?>

        <div style="display:none;">
            <a class="popup-with-form swift_popup_trigger" href="#sb_welcome_popup" >Inline</a>
        </div>
        <!-- This file is used to markup the public facing aspect of the plugin. -->

        <div style="display: none;position: relative;">
            <div id="sb_welcome_popup" class="sb-white-popup" style="width:<?php echo $sb_welcome_wizard_width; ?>px; height:<?php echo $sb_welcome_wizard_height; ?>px">
                <?php
                echo apply_filters('the_content', stripslashes($sb_welcome_wizard));
                ?>
                <div class='disable-wizard'>
                    <input type="checkbox" value="1" id="sb_welcome_wizard_disable" name="sb_welcome_wizard_disable"/>&nbsp;Don't show this again - I know how to use the program.
                </div>
            </div>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function() {

                if (jQuery.cookie('dont_show_welcomewizard') != '1') {
                    jQuery('.popup-with-form').magnificPopup({
                        type: 'inline',
                        preloader: false,
                        overflowY: 'scroll',
                        callbacks: {
                            close: function() {
                                jQuery.cookie('dont_show_timed', '1', {expires: null, path: '/'});
                                // jQuery.cookie('dont_show_timed', '1', {expires: 7, path: '/'});
                            }
                        },
                        // Delay in milliseconds before popup is removed
                        removalDelay: 300,
                        // Class that is added to popup wrapper and background
                        mainClass: 'mfp-fade'
                    });
                    if (jQuery.cookie('dont_show_timed') != 1) {
                        var $intrvl = <?php echo $sb_welcome_wizard_delay != "" ? $sb_welcome_wizard_delay : '1'; ?> * 1000;
                        openTimedbox($intrvl);
                    }

                }
                jQuery('#sb_welcome_wizard_disable').click(function() {
                    jQuery.cookie('dont_show_welcomewizard', '1', {expires: 365, path: '/'});
                });
            });

            function openTimedbox(interval) {
                setTimeout(function() {
                    jQuery('.swift_popup_trigger').trigger('click');
                }, interval);
            }
            jQuery("#tourvideo").on('click', function() {
                jQuery('.swift_popup_trigger').trigger('click');
            });
        </script>
        <?php
    }

}
?>