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

$curl_handle=curl_init();
curl_setopt($curl_handle,CURLOPT_URL,'https://api64.ipify.org/?format=txt');
curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
$buffer = curl_exec($curl_handle);
curl_close($curl_handle);
if (empty($buffer)){
    http_response_code(503);
    echo "Nothing returned from url.<p>";
}
else{
    
    $answer = array("useridfrom"=> -1, "useridto"=>$useridfrom, "message"=>$buffer);

    $record = new stdClass();
    $record->useridfrom = -1;
    $record->useridto = $useridfrom;
    $record->message = $buffer;
    $record->timecreated = time();

    $DB->insert_record('local_chatplugin', $record);

    echo json_encode(["succes" => "succes", "message" => $message]);
}

