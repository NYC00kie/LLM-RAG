<?php
require_once('../../config.php');
require_login();

// Get parameters
$useridfrom = required_param('useridfrom', PARAM_INT);
$useridto = required_param('useridto', PARAM_INT);

global $DB;

// Fetch the messages between the two users
$sql = "SELECT * FROM {local_chatplugin}
        WHERE (useridfrom = :useridfrom AND useridto = :useridto)
           OR (useridfrom = :useridto2 AND useridto = :useridfrom2)
        ORDER BY timecreated ASC";

$params = [
    'useridfrom' => $useridfrom,
    'useridto' => $useridto,
    'useridfrom2' => $useridfrom,
    'useridto2' => $useridto,
];

$messages = $DB->get_records_sql($sql, $params,$limitfrom = 0, $limitnum = 0);

// Return messages as JSON
header('Content-Type: application/json');
echo json_encode(array_values($messages));  // Array values ensure a clean JSON array