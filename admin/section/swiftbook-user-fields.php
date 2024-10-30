<?php
add_action('show_user_profile', 'swift_sub_manager_user_profile_fields');
add_action('edit_user_profile', 'swift_sub_manager_user_profile_fields');
add_action('user_new_form', 'swift_sub_manager_user_profile_add_fields');

add_action('personal_options_update', 'swift_sub_manager_update_user_profile_fields');
add_action('edit_user_profile_update', 'swift_sub_manager_update_user_profile_fields');
add_action('user_register', 'swift_sub_manager_insert_user_profile_fields');

/*
 *  Add fields 
 *     Product ID number
 *     Paid Through Date
 *     Inventory
 *     Redirect New Users After Signup To
 *     Member Phone
 *     Member Comment
 *  in User Profile
 */
if (!function_exists('swift_sub_manager_user_profile_fields')) {

    function swift_sub_manager_user_profile_fields($user) {
        if (current_user_can('edit_user')) {
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style('jquery-style', plugins_url('../css/jquery-ui-1.10.4.custom.css', __FILE__), '', '', '');
        }
        ?>
        <table class="form-table">
            <tr>
                <th><label for="swiftcloud_sm_productidnumber"><?php _e('Product ID number'); ?></label></th>
                <td>
                    <input type="text" name="swiftcloud_sm_productidnumber" id="swiftcloud_sm_productidnumber" value="<?php echo esc_attr(get_the_author_meta('swiftcloud_sm_productidnumber', $user->ID)); ?>" class="regular-text" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <th><label for="swiftcloud_sm_paidthroughdate"><?php _e('Paid Through Date'); ?></label></th>
                <td>
                    <?php if (current_user_can('edit_user')) { ?>
                        <script type="text/javascript">
                            jQuery(document).ready(function() {
                                if (jQuery("#swiftcloud_sm_paidthroughdate").val() == 'life_time') {
                                    jQuery("#sbs_life_time").attr("checked", "checked");
                                    checkLifeTime();
                                }
                                if (!jQuery("#sbs_life_time").is(":checked")) {
                                    jQuery('#swiftcloud_sm_paidthroughdate').datepicker({
                                        dateFormat: 'm/d/yy'
                                    });
                                }

                                jQuery("#sbs_life_time").on('click', function() {
                                    checkLifeTime();
                                });
                            });
                            function checkLifeTime() {
                                if (jQuery("#sbs_life_time").is(":checked")) {
                                    jQuery('#swiftcloud_sm_paidthroughdate').datepicker("destroy");
                                    jQuery("#swiftcloud_sm_paidthroughdate").val('life_time');
                                    jQuery('#swiftcloud_sm_paidthroughdate').attr('readonly', 'readonly');
                                } else {
                                    jQuery("#swiftcloud_sm_paidthroughdate").val('');
                                    jQuery('#swiftcloud_sm_paidthroughdate').removeAttr('readonly');
                                    jQuery('#swiftcloud_sm_paidthroughdate').datepicker({
                                        dateFormat: 'm/d/yy'
                                    });
                                }
                            }
                        </script>
                    <?php } ?>
                    <input type="text" name="swiftcloud_sm_paidthroughdate" id="swiftcloud_sm_paidthroughdate" value="<?php echo esc_attr(get_the_author_meta('swiftcloud_sm_paidthroughdate', $user->ID)); ?>" class="regular-text" <?php echo (!current_user_can('edit_user') ? 'disabled="disabled"' : ''); ?>  />
                    <?php if (current_user_can('edit_user')) { ?>
                        <label for="sbs_life_time"><input type="checkbox" value="life_time" name="sbs_life_time" id="sbs_life_time"/>Life Time</label>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th><label for="swiftcloud_sm_inventory"><?php _e('Inventory'); ?></label></th>
                <td>
                    <?php
                    global $wpdb;
                    global $table_voucher;
                    $table_voucher = $wpdb->prefix . "swiftvouchers";
                    $UserInventory = $wpdb->get_var("SELECT COUNT(*) FROM " . $table_voucher . " WHERE `affiliate_id`=" . $user->ID . " AND `status`=0 AND `send_status`=0 ");
                    ?>
                    <input type="text" name="swiftcloud_sm_inventory" id="swiftcloud_sm_inventory" value="<?php echo $UserInventory; ?>" class="regular-text" readonly />
                </td>
            </tr>
            <tr>
                <th><label for="swiftcloud_sm_signup_redirect"><?php _e('Redirect New Users After Signup To'); ?></label></th>
                <td>
                    <?php
                    $args = array(
                        'sort_order' => 'ASC',
                        'sort_column' => 'post_title',
                        'hierarchical' => 1,
                        'exclude' => '',
                        'include' => '',
                        'meta_key' => '',
                        'meta_value' => '',
                        'authors' => '',
                        'child_of' => 0,
                        'parent' => -1,
                        'exclude_tree' => '',
                        'number' => '',
                        'offset' => 0,
                        'post_type' => 'page',
                        'post_status' => 'publish'
                    );
                    $get_pages = get_pages($args);
                    $redirect_page = get_the_author_meta('swiftcloud_sm_signup_redirect', $user->ID);
                    ?>
                    <select name="swiftcloud_sm_signup_redirect" id="swiftcloud_sm_signup_redirect">
                        <option value="">Select after signup page</option>
                        <?php
                        if ($get_pages) {
                            foreach ($get_pages as $page) {
                                ?>
                                <option <?php selected($redirect_page, $page->ID); ?> value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="swiftcloud_sm_member_phone"><?php _e('Phone<br/><small>(from membership)</small>'); ?></label></th>
                <td>
                    <input type="text" name="swiftcloud_sm_member_phone" id="swiftcloud_sm_member_phone" value="<?php echo esc_attr(get_the_author_meta('swiftcloud_sm_member_phone', $user->ID)); ?>" class="regular-text"  />
                </td>
            </tr>
            <tr>
                <th><label for="swiftcloud_sm_member_comment"><?php _e('Comment<br/><small>(from membership)</small>'); ?></label></th>
                <td>
                    <input type="text" name="swiftcloud_sm_member_comment" id="swiftcloud_sm_member_comment" value="<?php echo esc_attr(get_the_author_meta('swiftcloud_sm_member_comment', $user->ID)); ?>" class="regular-text"  />
                </td>
            </tr>
        </table>
        <?php
    }

}


if (!function_exists('swift_sub_manager_user_profile_add_fields')) {

    function swift_sub_manager_user_profile_add_fields() {
        ?>
        <table class="form-table">
            <tr>
                <th><label for="swiftcloud_sm_paidthroughdate"><?php _e('Paid Through Date'); ?></label></th>
                <td>
                    <select name="swiftcloud_sm_paidthroughdate" id="swiftcloud_sm_paidthroughdate">
                        <option value="7 days">7 days</option>
                        <option value="30 days">30 days</option>
                        <option value="90 days">90 days</option>
                        <option value="180 days">180 days</option>
                        <option value="1 year">1 year</option>
                        <option value="2 years">2 years</option>
                        <option value="3 years">3 years</option>
                        <option value="life_time" selected="selected">Lifetime</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="swiftcloud_sm_signup_redirect"><?php _e('Redirect New Users After Signup To'); ?></label></th>
                <td>
                    <?php
                    $args = array(
                        'name' => 'swiftcloud_sm_signup_redirect',
                        'id' => 'swiftcloud_sm_signup_redirect',
                        'show_option_none' => 'Select after signup page',
                    );
                    wp_dropdown_pages($args);
                    ?>
                </td>
            </tr>
            <tr>
                <th><label for="swiftcloud_sm_member_phone"><?php _e('Phone<br/><small>(for member)</small>'); ?></label></th>
                <td>
                    <input type="text" name="swiftcloud_sm_member_phone" id="swiftcloud_sm_member_phone" value="<?php echo esc_attr(get_the_author_meta('swiftcloud_sm_member_phone', $user->ID)); ?>" class="regular-text"  />
                </td>
            </tr>
            <tr>
                <th><label for="swiftcloud_sm_member_comment"><?php _e('Comment<br/><small>(for member)</small>'); ?></label></th>
                <td>
                    <input type="text" name="swiftcloud_sm_member_comment" id="swiftcloud_sm_member_comment" value="<?php echo esc_attr(get_the_author_meta('swiftcloud_sm_member_comment', $user->ID)); ?>" class="regular-text"  />
                </td>
            </tr>
        </table>
        <?php
    }

}

if (!function_exists('swift_sub_manager_insert_user_profile_fields')) {

    function swift_sub_manager_insert_user_profile_fields($user_id) {
        if (current_user_can('edit_user', $user_id)) {
            $input_date = sanitize_text_field($_POST['swiftcloud_sm_paidthroughdate']);
            if ($input_date != 'life_time') {
                $dt = date('n/j/Y');
                $cdate = strtotime($dt);
                $new_date = strtotime('+ ' . $input_date, $cdate);
                $newPaidThroughDate = date('n/j/Y', $new_date);
            } else {
                $newPaidThroughDate = $input_date;
            }

            update_user_meta($user_id, 'swiftcloud_sm_paidthroughdate', $newPaidThroughDate);
            update_user_meta($user_id, 'swiftcloud_sm_signup_redirect', sanitize_text_field($_POST['swiftcloud_sm_signup_redirect']));
            update_user_meta($user_id, 'swiftcloud_sm_member_phone', sanitize_text_field($_POST['swiftcloud_sm_member_phone']));
            update_user_meta($user_id, 'swiftcloud_sm_member_comment', sanitize_text_field($_POST['swiftcloud_sm_member_comment']));
        }
    }

}

if (!function_exists('swift_sub_manager_update_user_profile_fields')) {

    function swift_sub_manager_update_user_profile_fields($user_id) {
        if (current_user_can('edit_user', $user_id)) {
            update_user_meta($user_id, 'swiftcloud_sm_paidthroughdate', sanitize_text_field($_POST['swiftcloud_sm_paidthroughdate']));
            update_user_meta($user_id, 'swiftcloud_sm_signup_redirect', sanitize_text_field($_POST['swiftcloud_sm_signup_redirect']));
            update_user_meta($user_id, 'swiftcloud_sm_member_phone', sanitize_text_field($_POST['swiftcloud_sm_member_phone']));
            update_user_meta($user_id, 'swiftcloud_sm_member_comment', sanitize_text_field($_POST['swiftcloud_sm_member_comment']));
        }
    }

}
?>