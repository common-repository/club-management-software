<?php
global $wp_roles;
$swiftbooks_autoresponder = unserialize(get_option('swiftbooks_autoresponder'));

if (isset($_POST['save_autoresponder']) && wp_verify_nonce($_POST['save_autoresponder'], 'save_autoresponder')) {
    $save_autoresponder = array();
    if (isset($_POST['swiftbooks_autoresponder']) && is_array($_POST['swiftbooks_autoresponder'])) {
        $swiftbooks_autoresponder = $_POST['swiftbooks_autoresponder'];
        foreach ($swiftbooks_autoresponder as $key => $ar) {
            $save_autoresponder[$key]['ar_user_role'] = sanitize_text_field($ar['ar_user_role']);
            $save_autoresponder[$key]['ar_type'] = sanitize_text_field($ar['ar_type']);
            $save_autoresponder[$key]['ar_list_id'] = sanitize_text_field($ar['ar_list_id']);
        }
    }
    $updateVal = serialize($save_autoresponder);
    $update1 = update_option('swiftbooks_autoresponder', $updateVal);

    if ($update1) {
        wp_redirect(admin_url("admin.php?page=swiftbook_autoresponder&tab=swiftbook-autoresponder-tab&update=1"));
    }
}


$roles = $wp_roles->get_names();
?>
<div class="wrap">
    <h2>Automation</h2>
    <hr>
    <div class="inner_content">
        <form name="FrmSBAutoresponder" id="FrmSBAutoresponder" method="post" action="">
            <div class="inner_content">
                <h2>New User Email Sequence Automation</h2>
                <p>This will automatically trigger an autoresponder / email sequence to all new users created here within wordpress.<br /> Visit <a href="http://swiftmarketing.com/public/campaigns/sequences" target="blank">http://swiftmarketing.com/public/campaigns/sequences</a> to create a sequence and/or retrieve the list ID number.</p>
            </div>
            <table class="autoresponder-table" cellpadding="5">

                <?php
                if (!empty($swiftbooks_autoresponder)) {
                    foreach ($swiftbooks_autoresponder as $index => $ar_val) {
                        ?>
                        <tr id="newrow_<?php echo $index; ?>">
                            <td width="90%">For User Type
                                <select name="swiftbooks_autoresponder[<?php echo $index; ?>][ar_user_role]" class="sb_ar_user_role">
                                    <?php
                                    foreach ($roles as $role_index => $role) {
                                        ?>
                                        <option value="<?php echo $role_index; ?>"  <?php echo ($ar_val['ar_user_role'] == $role_index) ? 'selected="selected"' : ''; ?>><?php echo $role; ?></option>
                                    <?php } ?>
                                </select>, add the new user to a
                                <select name="swiftbooks_autoresponder[<?php echo $index; ?>][ar_type]" class="swiftbook_ar_type">
                                    <option value="Global" <?php echo ($ar_val['ar_type'] == 'Global') ? 'selected="selected"' : ''; ?>>Global - Same for Everyone</option>
                                    <option value="Affiliate" <?php echo ($ar_val['ar_type'] == 'Affiliate') ? 'selected="selected"' : ''; ?>>Affiliate Specific</option>
                                </select> List ID / Auto responder Sequence
                                <label style="<?php echo ($ar_val['ar_type'] == 'Global') ? 'display: block;' : 'display: none;'; ?>">, specifically SwiftCloud / SwiftForm List ID #
                                    <input type="text" name="swiftbooks_autoresponder[<?php echo $index; ?>][ar_list_id]" class="sb_ar_list_id" value="<?php echo $ar_val['ar_list_id']; ?>" />
                                </label>
                            </td>
                            <td style="text-align: center; ">
                                <?php if ($index == 0) { ?>
                                    <img src="<?php echo plugins_url('../images/plus.png', __FILE__); ?>" class="swiftbook_plus" name="ar_row_add" alt="Add"/>
                                <?php } else { ?>
                                    <img src="<?php echo plugins_url('../images/delete.png', __FILE__); ?>" class="swiftbook_min" alt="Delete" name="ar_row_min" onclick="jQuery('#newrow_<?php echo $index; ?>').remove();"/>
                                <?php }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td width="90%">For User Type
                            <select name="swiftbooks_autoresponder[0][ar_user_role]" class="sb_ar_user_role">
                                <?php
                                foreach ($roles as $role_index => $role) {
                                    ?>
                                    <option value="<?php echo $role_index; ?>"><?php echo $role; ?></option>
                                <?php } ?>
                            </select>, add the new user to a
                            <select name="swiftbooks_autoresponder[0][ar_type]" class="swiftbook_ar_type">
                                <option value="Global">Global - Same for Everyone</option>
                                <option value="Affiliate">Affiliate Specific</option>
                            </select> List ID / Auto responder Sequence
                            <label>, specifically SwiftCloud / SwiftForm List ID #
                                <input type="text" name="swiftbooks_autoresponder[0][ar_list_id]" class="sb_ar_list_id" value="<?php echo $swiftbooks_sm_list_no; ?>" />
                            </label>
                        </td>
                        <td style="text-align: center; ">
                            <img src="<?php echo plugins_url('../images/plus.png', __FILE__); ?>" class="swiftbook_plus" name="ar_row_add" alt="Add"/>
                        </td>
                    </tr>
                <?php }
                ?>
            </table>
            <br/>
            <?php wp_nonce_field('save_autoresponder', 'save_autoresponder') ?>
            <input type="submit" class="button-primary" value="Save Settings" name="swiftbooks_submit" />
        </form>
        <!-- This div is only append row in table -->
        <div id="hidden_row" style="display: none;">
            For User Type <select name="swiftbooks_autoresponder[9999][ar_user_role]" class="sb_ar_user_role">
                <?php
                foreach ($roles as $index => $role) {
                    ?>
                    <option value="<?php echo $index; ?>"><?php echo $role; ?></option>
                <?php } ?>
            </select>, add the new user to a
            <select name="swiftbooks_autoresponder[9999][ar_type]" class="swiftbook_ar_type">
                <option value="Global">Global - Same for Everyone</option>
                <option value="Affiliate">Affiliate Specific</option>
            </select> List ID / Auto responder Sequence
            <label id="sb_list_id_label">, specifically SwiftCloud / SwiftForm List ID #
                <input type="text" name="swiftbooks_autoresponder[9999][ar_list_id]" class="sb_ar_list_id" value="<?php echo $swiftbooks_sm_list_no; ?>" />
            </label>
        </div>
    </div>
    <script type="text/javascript">
                                        jQuery(document).ready(function($) {
                                            $('.swiftbook_ar_type').on('change', function() {
                                                if ($(this).val() === 'Affiliate') {
                                                    $(this).next().hide();
                                                } else {
                                                    $(this).next().show();
                                                    //$('#sb_list_id_label').show();
                                                }
                                            });

                                            $(".swiftbook_plus").on("click", function() {
                                                var t = jQuery.now();
                                                var row = '<tr id="newrow_' + t + '"><td width="90%">' + $("#hidden_row").html() + '</td><td style="text-align: center; "><img src="<?php echo plugins_url('../images/delete.png', __FILE__); ?>" class="swiftbook_min" alt="Delete" name="ar_row_min" onclick="jQuery(\'#newrow_' + t + '\').remove();"/></td></tr>';

                                                $(".autoresponder-table tbody").append(row);
                                                $("#newrow_" + t + " .sb_ar_user_role").attr('name', 'swiftbooks_autoresponder[' + t + '][ar_user_role]')
                                                $("#newrow_" + t + " .swiftbook_ar_type").attr('name', 'swiftbooks_autoresponder[' + t + '][ar_type]')
                                                $("#newrow_" + t + " .sb_ar_list_id").attr('name', 'swiftbooks_autoresponder[' + t + '][ar_list_id]')
                                            });
                                        });
    </script>
</div>