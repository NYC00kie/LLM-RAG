<?php
defined('MOODLE_INTERNAL') || die();

/*function local_chatplugin_send_message($useridfrom, $useridto, $message) {
    global $DB;

    $record = new stdClass();
    $record->useridfrom = $useridfrom;
    $record->useridto = $useridto;
    $record->message = $message;
    $record->timecreated = time();

    return $DB->insert_record('local_chatplugin', $record);
}

function local_chatplugin_get_messages($useridfrom, $useridto) {
    global $DB;

    // Fetch the last 20 messages between these two users.
    $sql = "SELECT * FROM {local_chatplugin} 
            WHERE (useridfrom = :useridfrom AND useridto = :useridto)
            OR (useridfrom = :useridto AND useridto = :useridfrom)
            ORDER BY timecreated DESC LIMIT 20";

    return $DB->get_records_sql($sql, ['useridfrom' => $useridfrom, 'useridto' => $useridto]);
}*/
