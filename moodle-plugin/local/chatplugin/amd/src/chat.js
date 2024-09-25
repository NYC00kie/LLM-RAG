window.addEventListener('load', function () {
    alert("It's loaded!")

    try {
        $('#send-message').click(function() {
            var message = $('#message-input').val();
            var useridfrom = M.cfg.userid;  // Logged-in user ID
            var useridto = $('#recipient-id').val();  // Recipient ID

            if (message.length > 0) {
                sendMessage(useridfrom, useridto, message);
            }
        });
    }
    catch(err) {
        alert(err.message)
        location.reload()
    }

})



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
            $('#message-input').val('');  // Clear the input
        },
        error: function() {
            alert('Error sending message.');
        }
    });
}
