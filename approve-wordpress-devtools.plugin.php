<?php
	require('Approve.php');
	require('ApproveUtil.php');
	use com\kwipped\approve\wordpress\devtools\Approve;
	use com\kwipped\approve\wordpress\devtools\ApproveUtil;
	/*
	Plugin Name: APPROVE Devtools Plugin
	Plugin URI: http://kwipped.com
	description:May be used by APPROVE clients to create the necessary link to connect into the Approve cart from wordpress.
	Version: 1.0.0
	Author: Wellington Souza
	Author URI: http://kwipped.com
	License: GPL2
	*/
	define('CURRENT_APPROVE_DEVTOOLS_VERSION',"1.0.0");
	define('PLUGIN_PREFIX',"approve_wordpress_devtools");

	/**
	 * Provides update functionality
	 */
	require 'plugin-update-checker-4.9/plugin-update-checker.php';
	$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
		'https://github.com/KWIPPED/approve-wordpress-devtools-plugin/',
		__FILE__,
		'approve-wordpress-devtools-plugin'
	);

	//Get scripts loaded at the right time.
	add_action( 'wp_enqueue_scripts', 'com\kwipped\approve\wordpress\devtools\load_scripts' );

	//*****************************************************
	//* Plugin settings
	//*****************************************************
	add_action( 'admin_menu', function() {
		add_options_page( 'APPROVE Devtools Plugin Settings', 'APPROVE Devtools Plugin', 'manage_options', 
			PLUGIN_PREFIX, 'com\kwipped\approve\wordpress\devtools\render_plugin_settings_page' );
	});

  add_action( 'admin_init', function() {
		register_setting( PLUGIN_PREFIX.'_options', PLUGIN_PREFIX.'_options');
		add_settings_section( 'api_settings', 'API Settings', 
			'com\kwipped\approve\wordpress\devtools\settings_section_text', PLUGIN_PREFIX );
		add_settings_field( 'approve_id', 'APPROVE id', 
			'com\kwipped\approve\wordpress\devtools\add_approve_id', PLUGIN_PREFIX, 'api_settings' );
	});

	//*****************************
	//* Plugin settings END
	//*****************************

	//Will retrieve woocart and return approve rates based on that
	add_action("wp_ajax_get_approve_information", '\com\kwipped\approve\wordpress\devtools\Approve::ajax_get_approve_information' );
	add_action("wp_ajax_nopriv_get_approve_information", "\com\kwipped\approve\wordpress\devtools\Approve::ajax_get_approve_information");

	//Will use information passed in data dn return approve rates base on that
	add_action("wp_ajax_get_approve_teaser", '\com\kwipped\approve\wordpress\devtools\Approve::ajax_get_teaser' );
	add_action("wp_ajax_nopriv_get_approve_teaser", "\com\kwipped\approve\wordpress\devtools\Approve::ajax_get_teaser");


	// //Will use information passed in data to return a teaser.
	add_action("wp_ajax_get_button_action", '\com\kwipped\approve\wordpress\devtools\Approve::ajax_get_button_action' );
	add_action("wp_ajax_nopriv_get_button_action", "\com\kwipped\approve\wordpress\devtools\Approve::ajax_get_button_action");
?>
