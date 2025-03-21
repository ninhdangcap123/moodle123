<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings->add(new admin_setting_configtext(
        'local_myplugin/example_setting', 
        get_string('example_setting', 'local_myplugin'), 
        get_string('example_setting_desc', 'local_myplugin'), 
        '', 
        PARAM_TEXT
    ));
}
