<?php
/**
 * @package builder_design
 */
/*
Plugin Name: BuilderPhase
Plugin URI: http:google.com?return=true
Description: BuilderPhase 
Version: 1.0.0
Author: BuilderPhase
Author URI: 
License: GPLv2 or later
*/

// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }



define('BUILDER_PLUGIN_NAME', 'builder_design');
define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('BP', dirname(__FILE__));
/** Step 2 (from text above). */
add_action( 'admin_menu', 'builder_design' );

register_activation_hook( __FILE__, 'builder_design_activate');
register_deactivation_hook( __FILE__, 'builder_design_uninstall');

	// Create tables and register plugin options to wp_options
		function builder_design_activate() {
			global $wpdb, $dbversion;
			
			include_once (dirname (__FILE__) . '/admin/main_install.php');
			builder_design_install_tables();
			$newContent1 ='[BuilderPhase]';
			$newContent2 ='[BuilderPhase_Model]';
			$newContent3 ='[BuilderPhase_build]';
			$newContent4 ='[BuilderPhase_ReadyHomes]';
			$newContent5 ='[BuilderPhase_RequestMoreInfo]';
			  $pages[] = array(
			  'post_author' => 'yogendra',
			  'post_content' => '[BuilderPhase_HomePage]',
			  'post_name' =>  'Home',
			  'post_status' => 'publish',
			  'post_title' => 'Home',
			  'post_excerpt'=>'BuilderPhaseHomePage',
			  'post_type' => 'page',
			  'comment_status' => 'closed',
			  'ping_status' => 'closed',
			  'post_parent' => 0,
			  'menu_order' => 0,
			  'to_ping' =>  '',
			  'pinged' => '',
			);
			$pages[] = array(
			  'post_author' => 'yogendra',
			  'post_content' => $newContent1,
			  'post_name' =>  'Floor Plans',
			  'post_status' => 'publish',
			  'post_title' => 'Floor Plans',
			  'post_excerpt'=>'BuilderPhase',
			  'post_type' => 'page',
			  'comment_status' =>'closed',
			  'ping_status' => 'closed',
			  'post_parent' => 0,
			  'menu_order' => 1,
			  'to_ping' =>  '',
			  'pinged' => '', 
			);
			$pages[] = array(
			  'post_author' => 'yogendra',
			  'post_content' => $newContent2,
			  'post_name' =>  'Model Homes',
			  'post_status' => 'publish',
			  'post_title' => 'Model Homes',
			  'post_excerpt'=>'BuilderPhase',
			  'post_type' => 'page',
			  'comment_status' => 'closed',
			  'ping_status' => 'closed',
			  'post_parent' => 0,
			  'menu_order' => 2,
			  'to_ping' =>  '',
			  'pinged' => '',
			);
			$pages[] = array(
			  'post_author' => 'yogendra',
			  'post_content' => $newContent3,
			  'post_name' =>  'Where We Build',
			  'post_status' => 'publish',
			  'post_title' => 'Where We Build',
			  'post_excerpt'=>'BuilderPhase',
			  'post_type' => 'page',
			  'comment_status' => 'closed',
			  'ping_status' => 'closed',
			  'post_parent' => 0,
			  'menu_order' => 3,
			  'to_ping' =>  '',
			  'pinged' => '',
			);
				$pages[] = array(
			  'post_author' => 'yogendra',
			  'post_content' => $newContent4,
			  'post_name' =>  'Move In Ready Homes',
			  'post_status' => 'publish',
			  'post_title' => 'Move In Ready Homes',
			  'post_excerpt'=>'BuilderPhase',
			  'post_type' => 'page',
			  'comment_status' => 'closed',
			  'ping_status' => 'closed',
			  'post_parent' => 0,
			  'menu_order' => 4,
			  'to_ping' =>  '',
			  'pinged' => '',
			);
			$pages[] = array(
			  'post_author' => 'yogendra',
			  'post_content' => $newContent5,
			  'post_name' =>  'Request More Info',
			  'post_status' => 'publish',
			  'post_title' => 'Request More Info',
			  'post_excerpt'=>'BuilderPhase',
			  'post_type' => 'page',
			  'comment_status' => 'closed',
			  'ping_status' => 'closed',
			  'post_parent' => 0,
			  'menu_order' => 5,
			  'to_ping' =>  '',
			  'pinged' => '',
			);
			foreach($pages as $page)
			{
				wp_insert_post($page);
			}
		}
		
		// Uninstall tables and clean plugin options for wp_options
		function builder_design_uninstall() {
			global $wpdb;
			include_once (dirname (__FILE__) . '/admin/main_uninstall.php');
			builder_design_uninstall_tables();
			$sql=$wpdb->prepare("SELECT ID,post_excerpt FROM {$wpdb->prefix}posts HAVING post_excerpt=%s",'BuilderPhase');
			
			 $pages = $wpdb->get_results($sql);
			foreach( $pages as $page)
			{
				wp_delete_post($page->ID);
			}
			/*delete Home page*/
			$homepagesql=$wpdb->prepare("SELECT ID,post_excerpt FROM {$wpdb->prefix}posts HAVING post_excerpt=%s",'BuilderPhaseHomePage');
			$homepage=$wpdb->get_results($homepagesql);
			foreach( $homepage as $hom)
			{
				wp_delete_post($hom->ID);
			}
		}	
	


global $wpdb;
/** Step 1. */
function builder_design()
{
	global $_registered_pages;
	add_menu_page( 'Builder Design', 'BuilderPhase', 'manage_options', 'builder_design', 'builder_design_options' );
	add_submenu_page( 'builder_design', 'Corporation Listing', 'Corporation', 'manage_options', 'corporation', 'corporation_function');
	add_submenu_page( 'builder_design', 'Divisions', 'Divisions', 'manage_options', 'edit_builders', 'edit_function');
	add_submenu_page( 'builder_design', 'Subdivision Listing', 'Subdivisions', 'manage_options', 'div_listing', 'div_listing_function');
	add_submenu_page( 'builder_design', 'Plan Listing', 'Plans', 'manage_options', 'list_plans', 'plans_list_function');
	add_submenu_page( 'builder_design', 'Specification Listing', 'Specs ', 'manage_options', 'specifications', 'spec_list_function');
	$code_pages = array('edit/edit_corporation.php',
	'edit/edit_builder.php','edit/edit_division.php',
	'edit/edit_plan.php','edit/edit_specification.php',
	'edit/edit_specification_option.php',
	'edit/specification_option_list.php',
	'add/add_corporation.php',
	'add/add_builder.php',
	'add/add_division.php',
	'add/add_plan.php',
	'add/add_specification.php',
	'add/add_specification_option.php',
	
	);
	foreach($code_pages as $code_page) {
		$hookname = get_plugin_page_hookname(BUILDER_PLUGIN_NAME.'/admin/'.$code_page, '' );
		$_registered_pages[$hookname] = true;
	}

}


function builder_design_options() {
global $title;
include_once (dirname (__FILE__) . '/admin/Import.php');


}
function edit_function()
{
	global $title;
			include_once (dirname (__FILE__) . '/admin/test_form.php');
}

function corporation_function()
{
	global $title;
			include_once (dirname (__FILE__) . '/admin/corporation.php');
}
function add_function()
{
	global $title;
			include_once (dirname (__FILE__) . '/admin/add_builders.php');
}
function plans_list_function()
{
	global $title;
			include_once (dirname (__FILE__) . '/admin/plans.php');
}
function spec_list_function()
{
	global $title;
			include_once (dirname (__FILE__) . '/admin/specifications.php');
}

function div_listing_function()
{
	global $title;
	include_once (dirname (__FILE__) . '/admin/div_listing.php');
}





//[foobar]
function riconllc_shortcode( $atts ){
	
	include_once (dirname (__FILE__) . '/frontend/show.php');
	
}
function riconllc_model( $atts ){
	
	include_once (dirname (__FILE__) . '/frontend/model.php');
	
}
function riconllc_Build( $atts ){
	
	include_once (dirname (__FILE__) . '/frontend/build/build.php');
	
}
function riconllc_ReadyHomes( $atts ){
	
	include_once (dirname (__FILE__) . '/frontend/move_in_readyhomes.php');
	
}
function RiconLLC_RiconHomePage( $atts ){
	
	include_once (dirname (__FILE__) . '/frontend/Homepage.php');
	
}
function BuilderPhase_RequestMoreInfo( $atts ){
	
	include_once (dirname (__FILE__) . '/frontend/RequestMoreInfo.php');
	
}
add_shortcode('BuilderPhase', 'riconllc_shortcode');
add_shortcode('BuilderPhase_Model', 'riconllc_model');
add_shortcode('BuilderPhase_build', 'riconllc_Build');
add_shortcode('BuilderPhase_ReadyHomes', 'riconllc_ReadyHomes');
add_shortcode('BuilderPhase_HomePage', 'RiconLLC_RiconHomePage');
add_shortcode('BuilderPhase_RequestMoreInfo', 'BuilderPhase_RequestMoreInfo');


if ( ! wp_next_scheduled( 'updateXML' ) ) {
  wp_schedule_event( time(), 'daily', 'updateXML' );
}

add_action( 'updateXML', 'my_task_function' );

function my_task_function()
{
	/*function for cron job*/
	include_once (dirname (__FILE__) . '/admin/updatexmlfile.php');

	/***Creating Object to update Database*/
	$object=new updateDBfromXML();
	$url=BP.DS.'xmlimport'.DS.'test.xml';
	$data=json_decode(json_encode((array) simplexml_load_file($url)), 1);
	 $object->SaveXMLData($data);
	/**/
}
/***Add Css and script*****/
function my_scripts_method() {
    wp_enqueue_script('custom-script',plugins_url( '/frontend/js/jquery-1.9.0.min.js', __FILE__ ), array( 'jquery' ));
	wp_enqueue_script('custom-script1',plugins_url( '/frontend/js/jquery.nivo.slider.js', __FILE__ ), array( 'jquery' ));
	wp_enqueue_script('custom-script2',plugins_url( '/frontend/js/jquery.prettyPhoto.js', __FILE__ ), array( 'jquery' ));
	wp_enqueue_script('custom-script3',plugins_url( '/frontend/js/jquery.validate.min.js', __FILE__ ), array( 'jquery' ));
	wp_enqueue_script('custom-script4',plugins_url( '/frontend/js/builderphase.js', __FILE__ ), array( 'jquery' ));
	
	wp_register_style( 'custom-style1', plugins_url( '/frontend/css/builderphase.css', __FILE__ ), array(), '20120208', 'all' );  
	wp_register_style( 'custom-style2', plugins_url( 'frontend/css/nivo-slider.css', __FILE__ ), array(), '20120208', 'all' ); 
	wp_register_style( 'custom-style3', plugins_url( 'frontend/css/prettyPhoto.css', __FILE__ ), array(), '20120208', 'all' ); 
	wp_enqueue_style( 'custom-style1' );  
	wp_enqueue_style( 'custom-style2' );
	wp_enqueue_style( 'custom-style3' );
	
}
add_action( 'wp_enqueue_scripts', 'my_scripts_method' );


