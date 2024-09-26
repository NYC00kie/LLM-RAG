<?php
require_once('../../config.php');
require_login();

$PAGE->set_url(new moodle_url('/local/chatplugin/index.php'));
$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/local/chatplugin/amd/src/jquery.min.js'));
$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/local/chatplugin/amd/src/chat.js'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Chat Plugin');
$PAGE->set_heading('Global Chat');

// Fetch a list of users in the course (replace with your course ID).
$courseid = 1; // Change this to your course ID or use dynamic ID.
$context = context_course::instance($courseid);
$users = get_enrolled_users($context);

// Prepare the users array for the Mustache template.
$users_array = [];
foreach ($users as $user) {
    $users_array[] = [
        'id' => $user->id,
        'name' => fullname($user),
    ];
}

// Include the chat template and JavaScript.
echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_chatplugin/chat', ['users' => $users_array, 'current_user' => $USER->id]);
echo $OUTPUT->footer();
?>
