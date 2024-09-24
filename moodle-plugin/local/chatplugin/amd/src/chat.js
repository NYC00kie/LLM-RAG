define(['jquery'], function($) {

    function sendMessage(useridfrom, useridto, message) {
        $.ajax({
            url: M.cfg.wwwroot + '/local/chatplugin/send.php',
            method: 'POST',
            data: {
                useridfrom: useridfrom,
                useridto: useridto,
                message: message
            },
            success: function(response) {
                $('#chat-messages').append('<div>' + message + '</div>');
                $('#message-input').val('');  // Clear input
            },
            error: function() {
                alert('Error sending message.');
            }
        });
    }

    $('#send-message').click(function() {
        var message = $('#message-input').val();
        var useridfrom = M.cfg.userid;  // Logged-in user
        var useridto = $('#recipient-id').val();  // Chat partner

        if (message.length > 0) {
            sendMessage(useridfrom, useridto, message);
        }
    });
});