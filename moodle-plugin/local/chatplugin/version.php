<?php
// Plugin version and Moodle version compatibility.
defined('MOODLE_INTERNAL') || die();

$plugin->component = 'local_chatplugin';  // Full plugin name.
$plugin->version = 2023092100;            // Plugin version (date in YYYYMMDDXX format).
$plugin->requires = 2020061500;           // Requires this Moodle version (Moodle 3.9).
$plugin->maturity = MATURITY_STABLE;      // This is a stable version.
$plugin->release = 'v1.0';                // Plugin release version.
