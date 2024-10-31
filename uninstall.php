<?php
// if uninstall.php is not called by WordPress
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

global $wpdb;
$sql[] = "DROP TABLE  `".$wpdb->prefix."sellsy_version`";
$sql[] = "DROP TABLE  `".$wpdb->prefix."sellsy_setting`";
$sql[] = "DROP TABLE  `".$wpdb->prefix."sellsy_ticket`";
$sql[] = "DROP TABLE  `".$wpdb->prefix."sellsy_ticket_form`";
$sql[] = "DROP TABLE  `".$wpdb->prefix."sellsy_contact`";
$sql[] = "DROP TABLE  `".$wpdb->prefix."sellsy_contact_form`";
$sql[] = "DROP TABLE  `".$wpdb->prefix."sellsy_error`";
if (isset($sql) && !empty($sql)) {
	foreach( $sql as $v ){ $wpdb->query($v); }
}

//$sql = "
//DROP TABLE  ".SELLSY_PREFIXE_BDD."version
//DROP TABLE  ".SELLSY_PREFIXE_BDD."setting
//DROP TABLE  ".SELLSY_PREFIXE_BDD."ticket
//DROP TABLE  ".SELLSY_PREFIXE_BDD."ticket_form
//DROP TABLE  ".SELLSY_PREFIXE_BDD."contact
//DROP TABLE  ".SELLSY_PREFIXE_BDD."contact_form
//DROP TABLE  ".SELLSY_PREFIXE_BDD."error
//";
//require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
//dbDelta( $sql );
