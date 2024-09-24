<?php
require_once('../../config.php');
require_login();

$PAGE->set_url(new moodle_url('/local/chatplugin/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Chat Plugin');
$PAGE->set_heading('Global Chat');

// Include the chat template and JavaScript.
echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_chatplugin/chat', []);
echo $OUTPUT->footer();
?>
