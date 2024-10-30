<?php
/*
 *      Message /Autoresponder page
 */
if (!function_exists('swiftbook_autoresponder_cb')) {

    function swiftbook_autoresponder_cb() {
        ?>
        <div class="wrap">
            <h2 class="help-setup-title">Messages / Automation</h2><hr>
            <?php if (isset($_GET['update']) && !empty($_GET['update']) && $_GET['update'] == 1) { ?>
                <div id="message" class="notice notice-success is-dismissible below-h2">
                    <p>Setting updated successfully.</p>
                </div>
                <?php
            }
            ?>
            <div class="inner_content">
                <h2 class="nav-tab-wrapper" id="sw-setting-tabs">
                    <a class="nav-tab custom-tab <?php echo (!isset($_GET['tab']) || $_GET['tab'] == "swiftbook-email-templates-tab") ? 'nav-tab-active' : ''; ?>" id="swiftbook-email-templates" href="#swiftbook-email-templates-tab">Email Templates</a>
                    <a class="nav-tab custom-tab <?php echo ($_GET['tab'] == "swiftbook-autoresponder-tab") ? 'nav-tab-active' : ''; ?>" id="swiftbook-autoresponder" href="#swiftbook-autoresponder-tab">Automation</a>
                </h2>
                <div class="tabwrapper">
                    <div id="swiftbook-email-templates-tab" class="panel <?php echo (!isset($_GET['tab']) || $_GET['tab'] == "swiftbook-email-templates-tab") ? 'active' : ''; ?>">
                        <?php include 'swiftbook-email-templates.php'; ?>
                    </div>
                    <div id="swiftbook-autoresponder-tab" class="panel <?php echo ($_GET['tab'] == "swiftbook-autoresponder-tab") ? 'active' : ''; ?>">
                        <?php include 'swiftbook-autoresponder.php'; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}
?>