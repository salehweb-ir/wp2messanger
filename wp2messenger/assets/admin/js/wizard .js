jQuery(document).ready(function($) {
    // Hide success message after a few seconds
    $('.notice-success').delay(5000).slideUp(400);

    // Handle form submission
    $('.wp2messenger-wizard-form').on('submit', function(e) {
        e.preventDefault();

        // Get form values
        var token = $('#token').val();
        var channelId = $('#channel_id').val();
        var testMessage = $('#test_message').val();

        // Validate form values
        if (!token || !channelId || !testMessage) {
            alert(wp2messengerTranslations.please_enter_message);
            return;
        }

        // Send data to server
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'wp2messenger_test_connection',
                token: token,
                channel_id: channelId,
                test_message: testMessage
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect_url;
                } else {
                    alert(wp2messengerTranslations.message_send_failed);
                }
            },
            error: function() {
                alert(wp2messengerTranslations.server_error);
            }
        });
    });
});
