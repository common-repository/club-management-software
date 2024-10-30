<?php

/*
 *      Shortcode : paid_through_date
 *      Display paid-through-date of current user, using [paid_through_date] shortcode.
 */
add_shortcode('paid_through_date', 'swiftbook_shortcode_paid_through_date');
if (!function_exists('swiftbook_shortcode_paid_through_date')) {

    function swiftbook_shortcode_paid_through_date() {
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $paidThroughDate = $current_user->swiftcloud_sm_paidthroughdate;
            $paidThroughDate == "" ? $paidThroughDate = "-/-/-" : $paidThroughDate;
        }
        return $paidThroughDate;
    }

}


/*
 *      Shortcode : [login_logout_nav]
 *          - Display diffrent menu on login and logout
 */

add_shortcode('login_logout_nav', 'swiftbook_shortcode_login_logout_nav');

if (!function_exists('swiftbook_shortcode_login_logout_nav')) {

    function swiftbook_shortcode_login_logout_nav() {
        ob_start();
        $menu_locations = get_theme_mod('nav_menu_locations');
        if (isset($menu_locations) && !empty($menu_locations)) {
            $menuid = is_user_logged_in() ? "loggin-menu" : "loggout-menu";
            $header_menu_arr = array();
            $header_menu = wp_get_nav_menu_items($menu_locations[$menuid]);
            foreach ($header_menu as $hk => $hd) {
                if ($hd->menu_item_parent == 0) {
                    $header_menu_arr[$hd->menu_item_parent][] = $hd;
                } else {
                    $header_menu_arr[$hd->menu_item_parent][$hk][] = $hd;
                }
            }

            if (!empty($header_menu_arr[0])) {
                echo '<ul id="drop-nav">';
                foreach ($header_menu_arr[0] as $h) {
                    $active_cls = '';
                    if (array_key_exists($h->ID, $header_menu_arr)) {
                        $child_menu_arr = array();
                        foreach ($header_menu_arr[$h->ID] as $h2) {
                            $child_menu_arr[] = $h2[0]->object_id;
                        }
                        $curr_ID = get_the_ID();
                        $active_cls = (in_array($curr_ID, $child_menu_arr)) ? "active" : "";
                    }

                    $arrow = (array_key_exists($h->ID, $header_menu_arr) ? "<b class='caret'></b>" : "");
                    echo '<li><a href="' . $h->url . '">' . $h->title . $arrow . ' </a>';
                    if (array_key_exists($h->ID, $header_menu_arr)) {
                        echo '<ul>';
                        foreach ($header_menu_arr[$h->ID] as $h2) {
                            echo '<li><a href="' . $h2[0]->url . '">' . $h2[0]->title . '</a></li>';
                        }
                        echo '</ul>';
                    }
                    echo '</li>';
                }
                echo '</ul>';
            }
        }
        return ob_get_clean();
    }

}

/*
 *  [swiftbook_buynow product="12345" user_id="12" button_text='Add to cart']
 *      product="12345"
 *      user_id='1'
 *      button_text='Add to cart'
 */
add_shortcode('swiftbook_buynow', 'swiftbook_shortcode_buynow');
if (!function_exists('swiftbook_shortcode_buynow')) {

    function swiftbook_shortcode_buynow($buynow_atts) {
        $op = "";
        $a = shortcode_atts(
                array(
            'product' => '',
            'user_id' => '',
            'button_text' => '',
                ), $buynow_atts);
        extract($a);

        if (empty($product) && empty($user_id)) {
            $op = '<h4 style="color:#EE1616;">Please add product, user_id in shortcode to show this button.</h4>';
            return $o;
        }
        if (empty($product)) {
            $op = '<h4 style="color:#EE1616;">Please add product in shortcode to show this button.</h4>';
            return $op;
        }
        if (empty($user_id)) {
            $op = '<h4 style="color:#EE1616;">Please add user_id in shortcode to show this button.</h4>';
            return $op;
        }

        $swiftbooks_sm_user_id = get_option('$swiftbooks_user_id');

        $user_id = !empty($user_id) ? $user_id : $swiftbooks_sm_user_id;
        $button_text = !empty($button_text) ? $button_text : 'Buy Now';
        $op = '<a class="sb_btn_buynow" href="https://swiftshop.com/checkout/' . $user_id . '/' . $product . '" >' . $button_text . '</a>';
        return $op;
    }

}

/**
 *      Shortcode: [swiftbook_firstname]
 *      Show login user first name
 *      @return string user's first name
 */
add_shortcode('swiftbook_firstname', 'swiftbook_shortcode_firstname');
if (!function_exists('swiftbook_shortcode_firstname')) {

    function swiftbook_shortcode_firstname() {
        $output = "";
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $user_name = (!empty($current_user->user_firstname)) ? $current_user->user_firstname : $current_user->display_name;
            $output = "Welcome " . ucfirst($user_name);
        }
        return $output;
    }

}

/**
 *      Shortcode: [swiftbook_logout]
 *      @return html logout link with a tag
 */
add_shortcode('swiftbook_logout', 'swiftbook_shortcode_logout');
if (!function_exists('swiftbook_shortcode_logout')) {

    function swiftbook_shortcode_logout() {
        $output = "";
        if (is_user_logged_in()) {
            $output.= '<a href="' . wp_logout_url(home_url()) . '">Logoff</a>';
        }
        return $output;
    }

}