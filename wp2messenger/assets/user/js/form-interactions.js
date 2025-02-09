// JavaScript for wp2messenger custom form interactions

document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('custom-form');
    var messageField = document.getElementById('message');

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        // Get the user message value
        var message = messageField.value;

        // Validate the message value
        if (message.trim() === '') {
            alert(wp2messengerTranslations.please_enter_message);
            return;
        }

        // Send the message to the server using Fetch API
        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(new FormData(form)).toString()
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(wp2messengerTranslations.message_sent_success);
                form.reset(); // Reset the form
            } else {
                alert(wp2messengerTranslations.message_send_failed);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(wp2messengerTranslations.server_error);
        });
    });
});
