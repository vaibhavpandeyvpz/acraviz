<?php

return array(
    'alerts' => array(
        'applications' => array(
            'add_exists' => 'An application already exists with this title or package name',
            'add_failure' => 'Unable to add application to database',
            'add_success' => 'Application has been added to database'
        ),
        'login' => array(
            'logout' => 'You are now logged out'
        ),
        'settings' => array(
            'update_failure' => 'Failed to update your new settings',
            'update_success' => 'Your new settings have been updated'
        )
    ),
    'app' => 'ACRAViz',
    'buttons'=> array(
        'add' => 'Add',
        'delete' => 'Delete',
        'hide' => 'Hide',
        'show' => 'Show',
        'deselect_all' => 'Deselect All',
        'login' => 'Login',
        'refresh' => 'Refresh',
        'select_all' => 'Select All',
        'update' => 'Update',
        'view_all_reports' => 'View All Reports'
    ),
    'errors' => array(
        'settings' => array(
            'password_mismatch' => 'Repeat password must match with new password above'
        )
    ),
    'fields' => array(
        'package_name' => array(
            'label' => 'Package Name',
            'placeholder' => 'com.example.app'
        ),
        'password' => array(
            'label' => 'Password',
            'placeholder' => 'this#is^tough$as~hell'
        ),
        'password_new' => 'New Password',
        'password_repeat' => 'Repeat Password',
        'remember_me' => 'Remember me',
        'search' => 'Search exception or application ...',
        'title' => array(
            'label' => 'Label / Title',
            'placeholder' => 'Example Application'
        ),
        'token' => array(
            'label' => 'Token'
        ),
        'username' => array(
            'label' => 'Username',
            'placeholder' => 'hotdog'
        )
    ),
    'headings'=> array(
        'add_application' => 'Add Application',
        'device_info' => 'Device Information',
        'version_info' => 'Version Information',
        'misc_info' => 'Miscellaneous Information',
        'most_crashing' => 'Most Crashing Applications',
        'most_reported' => 'Most Reported Exceptions'
    ),
    'messages' => array(
        'login' => array(
            'reset' => 'If you forgot your login password, you can reset it by logging in to your server via SSH & running below command:'
        )
    ),
    'nav'=> array(
        'applications' => 'Applications',
        'dashboard' => 'Dashboard',
        'reports' => 'Reports',
        'settings' => 'Settings',
        'logout' => 'Logout'
    ),
    'report'=> array(
        'android_version' => 'Android Version',
        'app_version_code' => 'Application Version (Code)',
        'app_version_name' => 'Application Version (Name)',
        'brand' => 'Brand',
        'build' => 'Build',
        'build_config' => 'Build Config',
        'custom_data' => 'Custom Data',
        'crash_configuration' => 'Crash Configuration',
        'crash_datetime' => 'Crash Date/Time',
        'device_features' => 'Device Features',
        'display' => 'Display',
        'dumpsys_meminfo' => 'Dumpsys MemInfo',
        'environment' => 'Environment',
        'file_path' => 'File Path',
        'initial_configuration' => 'Initial Configuration',
        'logcat' => 'Logcat',
        'phone_model' => 'Phone Model',
        'product' => 'Product',
        'settings_global' => 'Global Settings',
        'settings_secure' => 'Secure Settings ',
        'settings_system' => 'System Settings ',
        'shared_preferences' => 'Shared Preferences',
        'stack_trace' => 'Stack Trace',
        'start_datetime' => 'Start Date/Time'
    ),
    'table'=> array(
        'added_on' => 'Added On',
        'application' => 'Application',
        'crash_count' => 'Crash Count',
        'device' => 'Device',
        'exception' => 'Exception',
        'package' => 'Package',
        'last_crashed_on' => 'Last Crashed On',
        'last_reported_on' => 'Last Reported On',
        'no_crashes' => 'No crashes reported',
        'report_count' => 'Report Count',
        'reported_on' => 'Reported On',
        'hidden' => 'Hidden',
        'token' => 'Token'
    ),
    'text'=> array(
        'hi' => 'Hi! %user%'
    ),
    'titles'=> array(
        'applications' => 'Applications',
        'dashboard' => 'Dashboard',
        'login' => 'Login',
        'report' => 'Report #%id%',
        'reports' => 'Reports',
        'settings' => 'Settings'
    )
);
