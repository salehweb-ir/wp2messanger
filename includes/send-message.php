<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include the send message functions
include_once plugin_dir_path(__FILE__) . '../includes/send-message.php';

// نمایش ویزارد
function wp2eitaa_display_wizard() {
    if (isset($_POST['submit'])) {
        // دریافت توکن و آیدی کانال از فرم ارسال شده
        $token = sanitize_text_field($_POST['token']);
        $channel_id = sanitize_text_field($_POST['channel_id']);
        $test_message = sanitize_text_field($_POST['test_message']);

        // تست اتصال به ایتا و ارسال پیام
        $send_result = wp2eitaa_send_text_message($token, $channel_id, $test_message);

        if ($send_result) {
            // ذخیره توکن و آیدی کانال در تنظیمات وردپرس
            update_option('token_eitaa_api', $token);
            update_option('eitaa_channel_id', $channel_id);

            // ایجاد برگه جدید با استفاده از شورت‌کد
            $page_title = 'Custom Form Page';
            $page_content = '[default_template]'; // استفاده از شورت‌کد برای نمایش محتوای default.php

            // چک کنید که برگه قبلاً ایجاد نشده
            $page_check = get_page_by_title($page_title);
            $page_args = array(
                'post_type'    => 'page',
                'post_title'   => $page_title,
                'post_content' => $page_content,
                'post_status'  => 'publish',
                'post_author'  => get_current_user_id(),
            );

            if (!$page_check) {
                $page_id = wp_insert_post($page_args);
            }

            // ریدایرکت به صفحه تنظیمات با پیام موفقیت
            wp_safe_redirect(admin_url('admin.php?page=wp2eitaa&success=true'));
            exit;
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>' . __('Failed to send test message.', 'wp2eitaa') . '</p></div>';
        }
    }
    ?>

    <div class="wp2eitaa-wrap">
        <h1><?php _e('WP2Eitaa Setup Wizard', 'wp2eitaa'); ?></h1>

        <form method="post" class="wp2eitaa-wizard-form">
            <h2><?php _e('Step 1: Eitaa API Information', 'wp2eitaa'); ?></h2>
            <p><?php _e('Please follow the instructions on https://eitaayar.ir/admin/api to get your API token.', 'wp2eitaa'); ?></p>
            <label for="token"><?php _e('Eitaa API Token', 'wp2eitaa'); ?></label>
            <input type="text" name="token" id="token" required>

            <label for="channel_id"><?php _e('Eitaa Channel ID', 'wp2eitaa'); ?></label>
            <input type="text" name="channel_id" id="channel_id" required>

            <h2><?php _e('Step 2: Test Connection', 'wp2eitaa'); ?></h2>
            <label for="test_message"><?php _e('Test Message', 'wp2eitaa'); ?></label>
            <input type="text" name="test_message" id="test_message" required>

            <input type="submit" name="submit" value="<?php _e('Test and Save', 'wp2eitaa'); ?>" class="wp2eitaa-button wp2eitaa-button-primary">
        </form>
    </div>

    <?php
}

wp2eitaa_display_wizard();
?>