<?php

// ?action=newUser&email=test@testing.com&pdate=20/9/2016&pid=414
// ?action=paidThroughDateUpdated&email=test@testing.com&pdate=20/9/2016
// ?action=paidThroughDateBatchUpdate&pdate=20/9/2017&emails=
// ?action=banUser&email=test@testing.com


add_action('init', 'swiftbook_listener_callback');
if (!function_exists('swiftbook_listener_callback')) {

    function swiftbook_listener_callback() {

        if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
            $emailsArr = (!empty($_REQUEST['emails']) ? explode(",", $_REQUEST['emails']) : ''); // array("test@testing.com", "test12@test.com", "test2@mailinator.com");
            $action = (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) ? sanitize_text_field($_REQUEST['action']) : "";
            $userName = $userEmail = (isset($_REQUEST['email']) && !empty($_REQUEST['email'])) ? sanitize_email($_REQUEST['email']) : "";
            $paidThroughDate = (isset($_REQUEST['pdate']) && !empty($_REQUEST['pdate'])) ? sanitize_text_field($_REQUEST['pdate']) : "";
            $productID = (isset($_REQUEST['pid']) && !empty($_REQUEST['pid'])) ? sanitize_text_field($_REQUEST['pid']) : "";

            switch ($action) {
                case 'newUser': {
                        if (empty($paidThroughDate)) {
                            $dt = date('n/j/Y');
                            $cdate = strtotime($dt);
                            $new_date = strtotime('+1 month', $cdate);
                            $paidThroughDate = date('n/j/Y', $new_date);
                        }

                        if (!empty($userName) && !empty($userEmail)) {
                            $user_id = username_exists($userName);
                            $userPwd = wp_generate_password(10);
                            $msg = "User already exists";
                            if (!$user_id) {
                                if (email_exists($userEmail) == false) {
                                    $new_user_id = wp_create_user($userName, $userPwd, $userEmail);
                                    if ($new_user_id) {
                                        update_user_meta($new_user_id, 'show_admin_bar_front', 'false');
                                        update_user_meta($new_user_id, 'swiftcloud_sm_paidthroughdate', $paidThroughDate);
                                        update_user_meta($new_user_id, 'swiftcloud_sm_productidnumber', $productID);
                                        update_user_meta($new_user_id, 'remote_user', 'true');
                                        if (isset($_COOKIE['agent_id']) && !empty($_COOKIE['agent_id'])) {
                                            update_user_meta($new_user_id, 'affiliate_from', $_COOKIE['agent_id']);
                                        }

                                        // Send welcome email to user
                                        swiftbook_signup_mail($userEmail, $userName, $userPwd);
                                        $msg = "success";
                                    }
                                }
                            }
                        }
                        echo $msg;
                        break;
                    }
                case 'paidThroughDateUpdated': {
                        $msg = "invalid";
                        if (!empty($paidThroughDate) && !empty($userEmail)) {
                            $user_meta = get_user_by('email', $userEmail);
                            if ($user_meta) {
                                update_user_meta($user_meta->ID, "swiftcloud_sm_paidthroughdate", $paidThroughDate);
                                $msg = "success";
                            }
                        }
                        echo $msg;
                        break;
                    }
                case 'paidThroughDateBatchUpdate': {
                        $msg = "invalid";
                        if (!empty($paidThroughDate) && !empty($emailsArr) && is_array($emailsArr)) {
                            foreach ($emailsArr as $e) {
                                $e = sanitize_email($e);
                                $user_meta = get_user_by('email', $e);
                                if ($user_meta) {
                                    update_user_meta($user_meta->ID, "swiftcloud_sm_paidthroughdate", $paidThroughDate);
                                    $msg = "success";
                                }
                            }
                        }
                        echo $msg;
                        break;
                    }
                case 'banUser': {
                        $msg = "invalid";
                        if (!empty($paidThroughDate) && !empty($userEmail)) {
                            $user_meta = get_user_by('email', $userEmail);
                            if ($user_meta) {
                                update_user_meta($user_meta->ID, "swiftcloud_sm_paidthroughdate", '00/00/0000');
                                $msg = "success";
                            }
                        }
                        echo $msg;
                        break;
                    }
            }
        }
    }

}
?>