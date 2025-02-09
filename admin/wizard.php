<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
// Include the send message functions
include_once plugin_dir_path(__FILE__) . '../includes/send-message.php';
// Display the wizard
function wp2messenger_display_wizard() {
    if (isset($_POST['submit'])) {
        // Get the token and channel ID from the submitted form
        $token = sanitize_text_field($_POST['token']);
        $channel_id = sanitize_text_field($_POST['channel_id']);
        $test_message = sanitize_text_field($_POST['test_message']);
        // Test connection to Eitaa and send message
        $send_result = wp2messenger_send_text_message($token, $channel_id, $test_message);
        if ($send_result) {
            // Save the token and channel ID in WordPress options
            update_option('token_eitaa_api', $token);
            update_option('eitaa_channel_id', $channel_id);
            // Create a new page using the shortcode
            $page_title = __('Anonymous message to Eitaa', 'wp2messenger');
            $page_content = '[default_template]'; // Use shortcode to display the content of default.php
            // Check if the page already exists
            $page_check = get_page_by_title($page_title);
            $page_args = array(
                'post_type'    => 'page',
                'post_name'    => 'anonymous-message',
                'post_title'   => $page_title,
                'post_content' => $page_content,
                'post_status'  => 'publish',
                'post_author'  => get_current_user_id(),
            );
            if (!$page_check) {
                $page_id = wp_insert_post($page_args);
            }
            // Redirect to the settings page with a success message
            wp_safe_redirect(admin_url('admin.php?page=wp2messenger&success=true'));
            exit;
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>' . __('Failed to send test message.', 'wp2messenger') . '</p></div>';
        }
    }
    ?>
    <div class="wp2messenger-wrap">
        <h1><?php _e('wp2messenger Setup Wizard', 'wp2messenger'); ?></h1>
        <form method="post" class="wp2messenger-wizard-form">
            <h2><?php _e('Step 1: Eitaa API Information', 'wp2messenger'); ?></h2>
            <p><?php _e('Please follow the instructions on <a href="https://eitaayar.ir/admin/api" target="_blank">eitaa API documentation</a> to get your API token.', 'wp2messenger'); ?></p>
            <label for="token"><?php _e('Eitaa API Token', 'wp2messenger'); ?></label>
            <input type="text" name="token" id="token" required>
            <label for="channel_id"><?php _e('Eitaa Channel ID', 'wp2messenger'); ?></label>
            <input type="text" name="channel_id" id="channel_id" required>
            <h2><?php _e('Step 2: Test Connection', 'wp2messenger'); ?></h2>
            <label for="test_message"><?php _e('Test Message', 'wp2messenger'); ?></label>
            <input type="text" name="test_message" id="test_message" required>
            <input type="submit" name="submit" value="<?php _e('Test and Save', 'wp2messenger'); ?>" class="wp2messenger-button wp2messenger-button-primary">
        </form>
    </div>
    <?php
}
wp2messenger_display_wizard();
?>
