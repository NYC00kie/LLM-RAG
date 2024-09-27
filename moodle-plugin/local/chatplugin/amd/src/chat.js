window.addEventListener('load', function () {

    try {
        $('#send-message').click(function() {
            var message = $('#message-input').val();
            var useridfrom = M.cfg.userid;  // Logged-in user ID
            var useridto = "-1" ;  // Recipient ID

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
            var msgClass = (message.useridfrom == M.cfg.userid) ? 'you' : 'other';
            var msgHtml = '<div class="message ' + msgClass + '"><b>' + (msgClass == 'you' ? 'You' : 'Other') + ':</b> ' + message.message + '</div>';
            $('#chat-messages').append(msgHtml);
            $('#message-input').val('');  // Clear the input
        },
        error: function() {
            alert('Error sending message.');
        }
    });
}

function loadMessages() {
    $.ajax({
        url: M.cfg.wwwroot + '/local/chatplugin/load_messages.php',
        method: 'GET',
        data: {
            useridfrom: M.cfg.userid,  // Current user ID
            useridto: "-1"  // Recipient ID
        },
        success: function(response) {
            $('#chat-messages').html('');  // Clear the chat messages
            response.forEach(function(message) {
                var msgClass = (message.useridfrom == M.cfg.userid) ? 'you' : 'other';
                var msgHtml = '<div class="message ' + msgClass + '"><b>' + (msgClass == 'you' ? 'You' : 'Other') + ':</b> ' + message.message + '</div>';
                $('#chat-messages').append(msgHtml);
            });
            $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);  // Scroll to the bottom
        },
        error: function() {
            console.log('Error loading messages');
        }
    });
}

// Load messages initially
loadMessages();

// Set an interval to refresh the messages every 5 seconds
setInterval(loadMessages, 5000);