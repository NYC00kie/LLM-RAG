<?php
require_once(__DIR__ . '/../../config.php');
require_login();

$useridfrom = required_param('useridfrom', PARAM_INT);
$useridto = required_param('useridto', PARAM_INT);
$message = required_param('message', PARAM_TEXT);

// Sanitize and validate input
if (!$useridfrom || !$useridto || empty($message)) {
    throw new moodle_exception('Invalid parameters');
}

// Here you would insert the message into a database table (you would need to create one).
// This is an example of inserting into a `chat_messages` table.

$record = new stdClass();
$record->useridfrom = $useridfrom;
$record->useridto = $useridto;
$record->message = $message;
$record->timecreated = time();

// Assuming you have created a table `local_chatplugin_messages` to store the messages.
$DB->insert_record('local_chatplugin', $record);

// Optionally, send a JSON response back to the frontend.
echo json_encode(['status' => 'success', 'message' => $message]);