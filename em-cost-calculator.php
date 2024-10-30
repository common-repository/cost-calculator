<?php
/*
Plugin Name:	EM Cost Calculator
Plugin URI:		https://e-motahar.com/cost-calculator-wordpress-plugin/
Description: 	Calculator for setting the price & visitors will calculate the total cost.
Author: 		Motahar Hossain
Version:		2.3.1
Author URI: 	www.e-motohar.com
License:      	GNU GENERAL PUBLIC LICENSE Version 3,
License URI: 	https://www.gnu.org/licenses/gpl-3.0.txt
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/******************************
// Data Process
*******************************/

global $em_cost_calc_db_version;
$em_cost_calc_db_version = '1.0';

function em_cost_calc_db_install() {
	global $wpdb;
	global $em_cost_calc_db_version;

	$em_cost_calc_table_name = $wpdb->prefix . 'emotahar_cost_calc';
	
	$em_cost_calc_charset_collate = $wpdb->get_charset_collate();

	$em_cost_calc_sql = "CREATE TABLE $em_cost_calc_table_name (
		ID int NOT NULL AUTO_INCREMENT,
	    service_1 int(10),
	    service_2 int(10),
	    service_3 int(10),
	    service_4 int(10),
	    service_5 int(10),
	    service_6 int(10),
	    service_7 int(10),
	    service_8 int(10),
	    service_9 int(10),
	    service_10 int(10),
	    em_admin_email varchar(50),
	    PRIMARY KEY (ID)
	) $em_cost_calc_charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $em_cost_calc_sql );

	add_option( 'em_cost_calc_db_version', $em_cost_calc_db_version );
}

// Data insert
function em_cost_calc_em_install_data() {
	global $wpdb;
	
	$em_cost_calc_defaul_prices = array(
		'service_1' => 100,
		'service_2' => 150,
		'service_3' => 240,
		'service_4' => 170,
		'service_5' => 210,
		'service_6' => 190,
		'service_7' => 110,
		'service_8' => 180,
		'service_9' => 220,
		'service_10' => 100,
		'em_admin_email' => 'motahar211@gmail.com',
	);
	
	$em_cost_calc_table_name = $wpdb->prefix . 'emotahar_cost_calc';
	$wpdb->insert( $em_cost_calc_table_name, $em_cost_calc_defaul_prices );
}

$em_cost_calc_installed_ver = get_option( "em_cost_calc_db_version" );

if ( $em_cost_calc_installed_ver != $em_cost_calc_db_version ) {

	$em_cost_calc_table_name = $wpdb->prefix . 'emotahar_cost_calc';

	$em_cost_calc_sql = "CREATE TABLE $em_cost_calc_table_name (
		ID int NOT NULL AUTO_INCREMENT,
	    service_1 int(10),
	    service_2 int(10),
	    service_3 int(10),
	    service_4 int(10),
	    service_5 int(10),
	    service_6 int(10),
	    service_7 int(10),
	    service_8 int(10),
	    service_9 int(10),
	    service_10 int(10),
	    em_admin_email varchar(50),
	    PRIMARY KEY (ID)
	);";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $em_cost_calc_sql );

	update_option( "em_cost_calc_db_version", $em_cost_calc_db_version );
}
register_activation_hook( __FILE__, 'em_cost_calc_db_install' );
register_activation_hook( __FILE__, 'em_cost_calc_em_install_data' );

function em_cost_calc_update_db_check() {
    global $em_cost_calc_db_version;
    if ( get_site_option( 'em_cost_calc_db_version' ) != $em_cost_calc_db_version ) {
        em_cost_calc_db_install();
    }
}

add_action( 'plugins_loaded', 'em_cost_calc_update_db_check' );// Only after the activation of the plugin it will create the database table (see codex)


/********************************************************************************************
 Create table for all orders details created from customers
*********************************************************************************************/

// create a table for customer. When they click on order they will receive a default email
	global $emcc_order_details_db_version;
	$emcc_order_details_db_version = '1.0';

	function emcc_order_details_db_install() {
		global $wpdb;
		global $emcc_order_details_db_version;

		$emcc_order_details_table_name = $wpdb->prefix . 'emcc_order_details';
		
		$emcc_order_details_charset_collate = $wpdb->get_charset_collate();

		$emcc_order_details_sql = "CREATE TABLE $emcc_order_details_table_name (
			ID int NOT NULL AUTO_INCREMENT,
			Order_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			customer_name varchar(50),
			customer_email varchar(150),
			customer_address text,
			customer_query text,
			service_1_costs text,
			service_2_costs text,
			service_3_costs text,
			service_4_costs text,
			service_5_costs text,
			service_6_costs text,
			service_7_costs text,
			total_cost text,
			PRIMARY KEY (ID)
		) $emcc_order_details_charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $emcc_order_details_sql );

		add_option( 'emcc_order_details_db_version', $emcc_order_details_db_version );
	}

	// Data insert
	function emcc_order_details_install_data() {
		global $wpdb;
		
		$emcc_order_details_default_fields = array(
			'customer_name' => 'Motahar',
			'customer_email' => 'Custommers email address',
			'customer_address' => 'Here will be the address.',
			'customer_query' => 'Does not have any query.',
			'service_1_costs' => 123,
			'service_2_costs' => 123,
			'service_3_costs' => 123,
			'service_4_costs' => 123,
			'service_5_costs' => 123,
			'service_6_costs' => 123,
			'service_7_costs' => 123,
			'total_cost' => 615,
		);
		
		$emcc_order_details_table_name = $wpdb->prefix . 'emcc_order_details';
		$wpdb->insert( $emcc_order_details_table_name, $emcc_order_details_default_fields );
	};

	$emcc_order_details_installed_ver = get_option( "emcc_order_details_db_version" );

	if ( $emcc_order_details_installed_ver != $emcc_order_details_db_version ) {

		$emcc_order_details_table_name = $wpdb->prefix . 'emcc_order_details';

		$emcc_order_details_sql = "CREATE TABLE $emcc_order_details_table_name (
			ID int NOT NULL AUTO_INCREMENT,
			Order_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			customer_name varchar(50),
			customer_email varchar(150),
			customer_address text,
			customer_query text,
			service_1_costs text,
			service_2_costs text,
			service_3_costs text,
			service_4_costs text,
			service_5_costs text,
			service_6_costs text,
			service_7_costs text,
			total_cost text,
			PRIMARY KEY (ID)
		);
		";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $emcc_order_details_sql );

		update_option( "emcc_order_details_db_version", $emcc_order_details_db_version );
		
	}
	register_activation_hook( __FILE__, 'emcc_order_details_db_install' );
	register_activation_hook( __FILE__, 'emcc_order_details_install_data' );

	function emcc_order_details_update_db_check() {
		global $emcc_order_details_db_version;
		if ( get_site_option( 'emcc_order_details_db_version' ) != $emcc_order_details_db_version ) {
			emcc_order_details_db_install();
		}
	}

	add_action( 'plugins_loaded', 'emcc_order_details_update_db_check' );// Only after the activation of the plugin it will create the database table (see codex)
	
	



/******************************
 Create table for default email for users
*******************************/

// create a table for customer. When they click on order they will receive a default email
	global $emcc_order_mail_db_version;
	$emcc_order_mail_db_version = '1.0';

	function emcc_order_mail_db_install() {
		global $wpdb;
		global $emcc_order_mail_db_version;

		$emcc_order_mail_table_name = $wpdb->prefix . 'emcc_default_mail_user';
		
		$emcc_order_mail_charset_collate = $wpdb->get_charset_collate();

		$emcc_order_mail_sql = "CREATE TABLE $emcc_order_mail_table_name (
			ID int NOT NULL AUTO_INCREMENT,
			time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			emcc_order_mail_admin varchar(50),
			default_mail_subject varchar(150),
			default_mail_body text,
			default_mail_sender_name text,
			default_mail_extra_field_1 text,
			default_mail_extra_field_2 text,
			default_mail_extra_field_3 int(10),
			default_mail_extra_field_4 int(10),
			PRIMARY KEY (ID)
		) $emcc_order_mail_charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $emcc_order_mail_sql );

		add_option( 'emcc_order_mail_db_version', $emcc_order_mail_db_version );
	}

	// Data insert
	function emcc_order_mail_install_data() {
		global $wpdb;
		
		$emcc_order_mail_default_fields = array(
			//'time' => create_function('now()', 'echo now;'),
			'emcc_order_mail_admin' => 'motahar211@gmail.com',
			'default_mail_subject' => 'Mail from EM Cost Calculator',
			'default_mail_body' => 'Write message for your customer.',
			'default_mail_sender_name' => 'Write the name & email address whom will see the customers as sender. e.g. From: Mail Name <myname@example.com>',
			'default_mail_extra_field_1' => 123,
			'default_mail_extra_field_2' => 123,
			'default_mail_extra_field_3' => 123,
			'default_mail_extra_field_4' => 123,
		);
		
		$emcc_order_mail_table_name = $wpdb->prefix . 'emcc_default_mail_user';
		$wpdb->insert( $emcc_order_mail_table_name, $emcc_order_mail_default_fields );
	};

	$emcc_order_mail_installed_ver = get_option( "emcc_order_mail_db_version" );

	if ( $emcc_order_mail_installed_ver != $emcc_order_mail_db_version ) {

		$emcc_order_mail_table_name = $wpdb->prefix . 'emcc_default_mail_user';

		$emcc_order_mail_sql = "CREATE TABLE $emcc_order_mail_table_name (
			ID int NOT NULL AUTO_INCREMENT,
			emcc_order_mail_admin varchar(50),
			default_mail_subject varchar(150),
			default_mail_body text,
			default_mail_sender_name varchar(100),
			default_mail_extra_field_1 varchar(255),
			default_mail_extra_field_2 varchar(255),
			default_mail_extra_field_3 int(10),
			default_mail_extra_field_4 int(10),
			PRIMARY KEY (ID)
		);
		";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $emcc_order_mail_sql );

		update_option( "emcc_order_mail_db_version", $emcc_order_mail_db_version );
		
	}
	register_activation_hook( __FILE__, 'emcc_order_mail_db_install' );
	register_activation_hook( __FILE__, 'emcc_order_mail_install_data' );

	function emcc_order_mail_update_db_check() {
		global $emcc_order_mail_db_version;
		if ( get_site_option( 'emcc_order_mail_db_version' ) != $emcc_order_mail_db_version ) {
			emcc_order_mail_db_install();
		}
	}

	add_action( 'plugins_loaded', 'emcc_order_mail_update_db_check' );// Only after the activation of the plugin it will create the database table (see codex)
	
	

/******************************
	Cost Calculator Widget
*******************************/
include_once plugin_dir_path( __FILE__ ) . 'em-cost-calculator-widget.php';

add_action( 'widgets_init', function(){
	register_widget( 'em_cost_calculator' );
});


/******************************
	For admin control dashboard
*******************************/
include_once plugin_dir_path( __FILE__ ) . 'em-cost-calc-admin-page.php';

/******************************
	Add Custom template
*******************************/
include_once plugin_dir_path( __FILE__ ) . 'em-add-custom-template.php';


/// Check error on my_loggg.txt on plugin fokder
register_activation_hook( __FILE__, 'my_activation_func' );

function my_activation_func() {
    file_put_contents( __DIR__ . '/my_loggg.txt', ob_get_contents() );
}