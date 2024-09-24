<?php
require_once('../../config.php');
require_login();

$useridfrom = required_param('useridfrom', PARAM_INT);
$useridto = required_param('useridto', PARAM_INT);
$message = required_param('message', PARAM_TEXT);

if (local_chatplugin_send_message($useridfrom, $useridto, $message)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
