<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

/* step 2*/
global $wpdb, $table_prefix;

$table_name = $table_prefix . 'basic';

$q = "DROP TABLE $table_name";

$wpdb->query($q);
