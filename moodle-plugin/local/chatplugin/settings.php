<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_chatplugin', get_string('pluginname', 'local_chatplugin'));

    $settings->add(new admin_setting_configcheckbox('local_chatplugin/enablechat',
        get_string('enablechat', 'local_chatplugin'),
        get_string('enablechat_desc', 'local_chatplugin'),
        1));

    $ADMIN->add('localplugins', $settings);
}