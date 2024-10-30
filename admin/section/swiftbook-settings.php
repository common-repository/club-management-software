<?php
/*
 *      Subscription Management Page
 */
if (!function_exists('swiftbook_settings_cb')) {

    function swiftbook_settings_cb() {
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
        $pages = get_pages($args);
        ?>
        <div class="wrap">
            <h2>Settings</h2>
            <hr>
            <?php if (isset($_GET['update']) && !empty($_GET['update']) && $_GET['update'] == 1) { ?>
                <div id="message" class="notice notice-success is-dismissible below-h2">
                    <p>Setting updated successfully.</p>
                </div>
                <?php
            }
            ?>
            <div class="inner_content">
                <h2 class="nav-tab-wrapper" id="swiftbook-setting-tabs">
                    <a class="nav-tab custom-tab <?php echo (!isset($_GET['tab']) || $_GET['tab'] == "swiftbook-access-management") ? 'nav-tab-active' : ''; ?>" id="swiftbook-access-management-tab" href="#swiftbook-access-management">Access Management</a>
                    <a class="nav-tab custom-tab <?php echo ($_GET['tab'] == "swiftbook-membership-levels") ? 'nav-tab-active' : ''; ?>" id="swiftbook-membership-levels-tab" href="#swiftbook-membership-levels">Membership Levels</a>
                </h2>
                <div class="tabwrapper">
                    <div id="swiftbook-access-management" class="panel <?php echo (!isset($_GET['tab']) || $_GET['tab'] == "swiftbook-access-management") ? 'active' : ''; ?>">
                        <?php include 'swiftbook-access-management.php'; ?>
                    </div>
                    <div id="swiftbook-membership-levels" class="panel <?php echo ($_GET['tab'] == "swiftbook-membership-levels") ? 'active' : ''; ?>">
                        <?php include 'swiftbook-membership-levels.php'; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}
?>