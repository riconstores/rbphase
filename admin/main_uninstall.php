<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }



// Uninstall all settings and tables called via Setup and register_unstall hook

function builder_design_uninstall_tables() {
	global $wpdb;
	
	// first remove all tables
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bd_builders");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bd_corporations");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bd_divison");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bd_plan");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bd_plan_images");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bd_specification");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bd_specification_images");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bd_specification_options");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bd_riconllc");
	// then remove all options
	delete_option( 'builder_design_options' );
	delete_option( 'builder_design_db_version' );

}

?>