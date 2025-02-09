<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Function to send a text message to Eitaa
function wp2messenger_send_text_message($token, $channel_id, $message) {
    $url = "https://eitaayar.ir/api/sendMessage";
    
    $post_fields = array(
        'token' => $token,
        'chat_id' => $channel_id,
        'text' => $message,
        'parse_mode' => 'HTML'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Log the API response
    if (function_exists('error_log')) {
        error_log('Eitaa API Response: ' . $response);
    }

    curl_close($ch);

    if ($http_code == 200) {
        return true;
    } else {
        return false;
    }
}
?>