<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include the send message functions from the includes folder
include_once plugin_dir_path(__DIR__) . '/../includes/send-message.php';

// Display the form
function wp2messenger_display_form() {
    if (isset($_POST['submit'])) {
        // Get the message from the form
        $message = sanitize_text_field($_POST['message']);

        // Get the token and channel ID from the settings
        $token = get_option('token_eitaa_api');
        $channel_id = get_option('eitaa_channel_id');

        // Send the message
        $send_result = wp2messenger_send_text_message($token, $channel_id, $message);

        if ($send_result) {
            echo '<div class="notice notice-success is-dismissible"><p>' . __('Message sent successfully.', 'wp2messenger') . '</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>' . __('Failed to send message. Please try again.', 'wp2messenger') . '</p></div>';
        }
    }
    ?>

    <div class="wp2messenger-custom-page-content">
        <h1><?php _e('Submit Your Message', 'wp2messenger'); ?></h1>
        <form method="post" class="wp2messenger-custom-form" enctype="multipart/form-data">
            <label for="wp2messenger-message"><?php _e('Your Message', 'wp2messenger'); ?></label>
            <textarea id="wp2messenger-message" name="message" required></textarea>
            <input type="submit" name="submit" value="<?php _e('Submit', 'wp2messenger'); ?>" class="wp2messenger-button wp2messenger-button-primary">
        </form>
    </div>

    <?php
}

wp2messenger_display_form();
?>