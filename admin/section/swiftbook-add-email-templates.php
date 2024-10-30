<?php
/*
 *      Add email template
 */
if (!function_exists('swiftbook_add_email_template_cb')) {

    function swiftbook_add_email_template_cb() {
        global $wpdb;
        global $table_emailtemplate;
        $table_emailtemplate = $wpdb->prefix . 'sb_email_template';

        if (isset($_POST['save_sb_email_template']) && wp_verify_nonce($_POST['save_sb_email_template'], 'save_sb_email_template')) {
            $email_subject = sanitize_text_field($_POST['sb_email_template_subject']);
            $email_body = wp_kses_post($_POST['sb_email_template_body']);
            $template_id = sanitize_text_field($_POST['sb_email_template_id']);

            if (!empty($email_subject) && !empty($email_body) && !empty($template_id)) {
                $update = $wpdb->query($wpdb->prepare("UPDATE $table_emailtemplate SET `et_subject`='%s',`et_content`='%s' WHERE `et_id`=%d", $email_subject, $email_body, $template_id));
                if ($update) {
                    wp_redirect(admin_url("admin.php?page=swiftbook_autoresponder&update=1&tab=swiftbook-email-templates-tab"));
                    exit;
                }
            }
        }

        if (!isset($_GET['id']) && empty($_GET['id'])) {
            return;
        }

        $template = $wpdb->get_row("SELECT * FROM `$table_emailtemplate` WHERE `et_id`=" . $_GET['id']);
        ?>
        <div class="wrap">
            <h2>Edit Email Template</h2>
            <hr>

            <div class="inner_content email-template">
                <div class="content-left pull-left">
                    <form name="FrmEmailTemplate" id="FrmEmailTemplate" method="post">
                        <table class="form-table">
                            <tr>
                                <th><label for="sb_email_template_subject">Email Subject:</label></th>
                                <td>
                                    <input type="text" class="regular-text" value="<?php echo $template->et_subject; ?>" name="sb_email_template_subject" id="sb_email_template_subject"/>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="sb_email_template_body">Email Body:</label></th>
                                <td>
                                    <?php
                                    $template_body = stripslashes($template->et_content);
                                    $settings = array('media_buttons' => false, 'quicktags' => true, 'textarea_name' => 'sb_email_template_body',);
                                    wp_editor($template_body, 'sb_email_template_body', $settings)
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <td>
                                    <input type="hidden" value="<?php echo $template->et_id; ?>" name="sb_email_template_id" id="sb_email_template_id"/>
                                    <?php wp_nonce_field('save_sb_email_template', 'save_sb_email_template'); ?>
                                    <input type="submit" class="button-primary" value="Update" name="btn_save_sb_email_template" />
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="content-right pull-right">
                    <div class="replace-variable">
                        <div class="variable-list">
                            <?php
                            if ($template->et_replace_keyword) {
                                $replace = explode(",", $template->et_replace_keyword);
                                $keyword = '';
                                foreach ($replace as $r) {
                                    $keyword.="<li>" . ucfirst(str_replace("_", " ", $r)) . " = {" . $r . "}</li>";
                                }
                                ?>
                                <h4><?php _e('Replace following', 'swift-cloud'); ?></h4>
                                <ul>
                                    <?php echo $keyword; ?>
                                </ul>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}
?>