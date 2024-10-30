<?php
/*
 *      Email template listing
 */


global $wpdb;
global $table_email_template;
$table_email_template = $wpdb->prefix . 'sb_email_template';

$templates = $wpdb->get_results("SELECT * FROM `$table_email_template`");
?>
<div class="wrap">
    <h2>Email Templates</h2>
    <hr>
    <div class="inner_content">
        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th><b>Template Name</b></th>
                    <th><b>Email Subject</b></th>
                    <th><b>Action</b></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($templates)) {
                    foreach ($templates as $t) {
                        ?>
                        <tr>
                            <td><?php echo $t->et_name; ?></td>
                            <td><?php echo $t->et_subject; ?></td>
                            <td><a class="edit" href="<?php echo admin_url("admin.php?page=swiftbook_add_email_template&id=$t->et_id"); ?>"><span class="dashicons dashicons-edit"></span></a></td>
                        </tr>
                        <?php
                    };
                } else {
                    ?>
                    <tr>
                        <td colspan="3"><h3><center>No template found!</center></h3></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>