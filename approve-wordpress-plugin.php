<?php
	require('ApproveWordPressPlugin.php');
	require('ApproveWordPressPluginUtil.php');
	use com\kwipped\approve\wordpress\plugin\ApproveWordPressPlugin;
	/*
	Plugin Name: APPROVE Wordpress Plugin
	Plugin URI: http://kwipped.com
	description:May be used by APPROVE clients to create the necessary link to connect into the Approve cart from wordpress.
	Version: 2.0.0
	Author: Wellington Souza
	Author URI: http://kwipped.com
	License: GPL2
	*/
	define('CURRENT_APPROVE_PLUGIN_VERSION',"2.0.0");
	define('APPROVE_WORDPRESS_PLUGIN_PREFIX',"approve_wordpress_plugin");
	define('APPROVE_WORDPRESS_PLUGIN_DEFAULT_LOADER_URL',"https://api.kwipped.com/approve/plugin/1.0/approve_plugin_loader.php");
	define('APPROVE_WORDPRESS_PLUGIN_DEFAULT_APPROVE_URL',"https://www.kwipped.com");

	/**
	 * Provides update functionality
	 */
	// require 'plugin-update-checker-4.9/plugin-update-checker.php';
	// $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	// 	'https://github.com/KWIPPED/approve-wordpress-devtools-plugin/',
	// 	__FILE__,
	// 	'approve-wordpress-devtools-plugin'
	// );

	//Get scripts loaded at the right time.
	add_action( 'wp_enqueue_scripts', 'com\kwipped\approve\wordpress\plugin\load_scripts' );

	//*****************************************************
	//* Plugin settings
	//*****************************************************
	add_action( 'admin_menu', function() {
		add_options_page( 'APPROVE WordPress Plugin Settings', 'APPROVE WordPress Plugin', 'manage_options', 
			APPROVE_WORDPRESS_PLUGIN_PREFIX, 'com\kwipped\approve\wordpress\plugin\render_plugin_settings_page' );
	});

	add_action( 'admin_init', function() {
		register_setting( APPROVE_WORDPRESS_PLUGIN_PREFIX.'_options', APPROVE_WORDPRESS_PLUGIN_PREFIX.'_options');
		add_settings_section( 'api_settings', 'API Settings', 
			'com\kwipped\approve\wordpress\plugin\settings_section_text', APPROVE_WORDPRESS_PLUGIN_PREFIX );

		add_settings_field( 'approve_id', 'APPROVE id', 
			'com\kwipped\approve\wordpress\plugin\add_approve_id', APPROVE_WORDPRESS_PLUGIN_PREFIX, 'api_settings' );
		add_settings_field( 'loader_url', 'Loader URL', 
			'com\kwipped\approve\wordpress\plugin\add_loader_url', APPROVE_WORDPRESS_PLUGIN_PREFIX, 'api_settings' );
		add_settings_field( 'approve_url', 'APPROVE URL', 
			'com\kwipped\approve\wordpress\plugin\add_approve_url', APPROVE_WORDPRESS_PLUGIN_PREFIX, 'api_settings' );
	});
?>
