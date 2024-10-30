<!--Membership Levels Content -->
<?php
$swiftbooks_membership_free_paid = get_option('swiftbooks_membership_free_paid');
$swiftbooks_manage_membership = get_option('swiftbooks_manage_membership');
$swiftbook_number_of_levels = get_option('swiftbook_number_of_levels');
if (!$swiftbook_number_of_levels)
    $swiftbook_number_of_levels = 5;
$swiftbook_membership_levels = get_option('swiftbook_membership_levels');

if (isset($_POST['save_sb_membership_levels']) && wp_verify_nonce($_POST['save_sb_membership_levels'], 'save_sb_membership_levels')) {

    $swiftbook_membership_toggle = sanitize_text_field($_POST['swiftbooks_membership_free_paid']);
    $swiftbooks_membership_free_paid = (isset($swiftbook_membership_toggle) && !empty($swiftbook_membership_toggle)) ? 1 : 0;
    $swiftbooks_manage_membership = sanitize_text_field($_POST['swiftbook_manage_membership']);
    $swiftbook_number_of_levels = sanitize_text_field($_POST['swiftbook_number_of_levels']);
    $swiftbook_membership_levels = ($_POST['swiftbook_membership_levels']);

    if (!empty($swiftbook_membership_levels)) {
        remove_role('swiftbook_membership_platinum');
        remove_role('swiftbook_membership_gold');
        remove_role('swiftbook_membership_silver');
        remove_role('swiftbook_membership_bronze');
        remove_role('swiftbook_membership_copper');

        $lvl = 0;
        $swift_book_memberships = array(
            'platinum',
            'gold',
            'silver',
            'bronze',
            'copper'
        );
        foreach ($swiftbook_membership_levels['name'] as $membership_level) {
            add_role('swiftbook_membership_' . $swift_book_memberships[$lvl], $membership_level, array('read' => true));
            $lvl++;
        }
    }

    $update2 = update_option('swiftbooks_membership_free_paid', $swiftbooks_membership_free_paid);
    $update3 = update_option('swiftbooks_manage_membership', $swiftbooks_manage_membership);
    $update4 = update_option('swiftbook_number_of_levels', $swiftbook_number_of_levels);
    $update5 = update_option('swiftbook_membership_levels', $swiftbook_membership_levels);

    if ($update2 || $update3 || $update4 || $update5) {
        wp_redirect(admin_url("admin.php?page=swiftbooks_subscription&tab=swiftbook-membership-levels&update=1"));
        die;
    }
}
?>
<div class="inner_content">
    <form name="FrmSBMemberShipLevel" id="FrmSBMemberShipLevel" method="post" action="">
        <table class="form-table">
            <tr>
                <th><label for="swiftbooks_membership_free_paid">Membership is currently</label></th>
                <td>
                    <div class="toggle_container" style="width:100px; text-align:center">
                        <?php $OnOff = ($swiftbooks_membership_free_paid == 1 ? 'checked="checked"' : ""); ?>
                        <input type="checkbox" value="1" data-ontext="PAID" data-offtext="FREE" name="swiftbooks_membership_free_paid" id="sb_membership" <?php echo $OnOff; ?>>
                    </div>
                </td>
            </tr>
        </table>
        <table class="form-table sb_membership_content" style="<?php echo ((get_option('swiftbooks_membership_free_paid') == 0) ? 'display: none;' : 'display: block;'); ?>">
            <tr>
                <th><label for="swiftbook_manage_membership">Manage Memberships</label></th>
                <td>
                    <select name="swiftbook_manage_membership" id="swiftbook_manage_membership">
                        <option value="">- Select Membership -</option>
                        <option <?php selected($swiftbooks_manage_membership, "swiftcloud") ?> value="swiftcloud">SwiftCloud</option>
                        <option <?php selected($swiftbooks_manage_membership, "duvisio") ?> value="duvisio">Duvisio</option>
                        <option <?php selected($swiftbooks_manage_membership, "locally") ?> value="locally">Locally</option>
                        <option <?php selected($swiftbooks_manage_membership, "other_api") ?> value="other_api">Other API</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="swiftbook_number_of_levels">Number of Levels</label></th>
                <td>
                    <select name="swiftbook_number_of_levels" id="swiftbook_number_of_levels">
                        <option value="1" <?php selected($swiftbook_number_of_levels, "1") ?>>1</option>
                        <option value="2" <?php selected($swiftbook_number_of_levels, "2") ?>>2</option>
                        <option value="3" <?php selected($swiftbook_number_of_levels, "3") ?>>3</option>
                        <option value="4" <?php selected($swiftbook_number_of_levels, "4") ?>>4</option>
                        <option value="5" <?php selected($swiftbook_number_of_levels, "5") ?>>5</option>
                    </select>
                </td>
            </tr>
        </table>
        <table class="widefat fixed striped" id='tbl_membership'>
            <thead>
                <tr>
                    <th width="30%">Membership Name / Label</th>
                    <th width="30%">Product ID # <span class="dashicons dashicons-editor-help ttip" title="Product ID Number as seen in Membership Manager (SwiftCloud product ID, Duvisio Product ID, etc.) - use a comma and space for multiple like 123, 456"></span></th>
                    <th width="40%">Level Home Page <span class="dashicons dashicons-editor-help ttip" title="Logged-in users will default to this page based on their membership level"></span></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $swift_book_memberships = array(
                    'platinum' => 'Platinum',
                    'gold' => 'Gold',
                    'silver' => 'Silver',
                    'bronze' => 'Bronze',
                    'copper' => 'Copper'
                );

                $sb_membership_key = 0;
                foreach ($swift_book_memberships as $membership_key => $membership):
                    $inactive_membership = $disabled_membership = "";
                    if ($sb_membership_key >= $swiftbook_number_of_levels) {
                        $inactive_membership = "display:none;";
                        $disabled_membership = "disabled='disabled'";
                    }
                    $membership_name = (isset($swiftbook_membership_levels['name'][$sb_membership_key]) && !empty($swiftbook_membership_levels['name'][$sb_membership_key])) ? $swiftbook_membership_levels['name'][$sb_membership_key] : $membership;
                    $membership_prod_id = (isset($swiftbook_membership_levels['product_id'][$sb_membership_key]) && !empty($swiftbook_membership_levels['product_id'][$sb_membership_key])) ? $swiftbook_membership_levels['product_id'][$sb_membership_key] : "";
                    $membership_level_homepage = (isset($swiftbook_membership_levels['homepage'][$sb_membership_key]) && !empty($swiftbook_membership_levels['homepage'][$sb_membership_key])) ? $swiftbook_membership_levels['homepage'][$sb_membership_key] : "";
                    ?>
                    <tr style="<?php echo $inactive_membership; ?>">
                        <td><input type="text" name="swiftbook_membership_levels[name][]" id="swiftbook_membership_name_<?php echo $membership_key ?>" value="<?php echo $membership_name; ?>" class="regular-text" <?php echo $disabled_membership; ?> /></td>
                        <td><input type="text" name="swiftbook_membership_levels[product_id][]" id="swiftbook_membership_product_id_<?php echo $membership_key ?>" value="<?php echo $membership_prod_id; ?>" class="regular-text" <?php echo $disabled_membership; ?> /></td>
                        <td>
                            <select name="swiftbook_membership_levels[homepage][]" id="swiftbook_membership_level_homepage_<?php echo $membership_key ?>" class="regular-text" <?php echo $disabled_membership; ?>>
                                <option value="0">--Select Page--</option>
                                <?php
                                if ($pages) {
                                    foreach ($pages as $page) {
                                        ?>
                                        <option <?php selected($membership_level_homepage, $page->ID); ?> value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <?php $sb_membership_key++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <br/>
        <?php wp_nonce_field('save_sb_membership_levels', 'save_sb_membership_levels') ?>
        <input type="submit" class="button-primary" value="Save Settings" name="swiftbooks_submit_subs" />
    </form>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('#sb_membership:checkbox').rcSwitcher({
                width: 80,
            }).on({
                'turnon.rcSwitcher': function(e, dataObj) {
                    // to do on turning on a switch
                    jQuery('.sb_membership_content').show();
                },
                'turnoff.rcSwitcher': function(e, dataObj) {
                    // to do on turning off a switch
                    jQuery('.sb_membership_content').hide();

                }
            });

            jQuery("#swiftbook_number_of_levels").on("change", function() {
                jQuery("#tbl_membership").find('.regular-text').attr('disabled', 'disabled');
                jQuery("#tbl_membership tbody").find('tr').hide();
                var m;
                for (m = 1; m <= jQuery(this).val(); m++) {
                    jQuery("#tbl_membership tbody").find('tr').eq(m - 1).show();
                    jQuery("#tbl_membership tbody").find('tr').eq(m - 1).find('.regular-text').removeAttr('disabled');
                }
            });
        });
    </script>
</div>
