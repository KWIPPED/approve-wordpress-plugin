<?php
	/*
	Plugin Name: APPROVE WordPress Plugin
	Plugin URI: http://kwipped.com
	description:May be used by APPROVE clients to create the necessary link to connect into the Approve cart from wordpress.
	Version: 2.0.0
	Author: Wellington Souza
	Author URI: http://kwipped.com
	License: GPL2
	*/

	//Required so I can use the deactivate_plugin function.
	include_once(ABSPATH.'wp-admin/includes/plugin.php');

	class ApproveWordPressPlugin{
		public static $version = "2.0.0";
		public static $prefix = "approve_wordpress_plugin";
		public static $options = "approve_wordpress_plugin_options";
		public static $default_loader_url = "https://api.kwipped.com/approve/plugin/1.0/approve_plugin_loader.php";
		public static $default_approve_url = "https://www.kwipped.com";

		public function __construct(){
			/**
			 * Provides update functionality
			 */
			// require 'plugin-update-checker-4.9/plugin-update-checker.php';
			// $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
			// 	'https://github.com/KWIPPED/approve-wordpress-devtools-plugin/',
			// 	__FILE__,
			// 	'approve-wordpress-devtools-plugin'
			// );

			//Registers the function that WP will call to load javascript and CSS.
			add_action( 'wp_enqueue_scripts', [$this,'load_scripts']);

			//If the user deactivates the wordpress plugin, we will make sure that other plugins that
			//depend on it are also deactivated.
			register_deactivation_hook( __FILE__,  function(){
				if(is_plugin_active( 'approve-woocommerce-integration-plugin/approve-woocommerce-integration-plugin.php' )){
					add_action('update_option_active_plugins', function(){
						deactivate_plugins('approve-woocommerce-integration-plugin/approve-woocommerce-integration-plugin.php',true);
					});
				}
			});

			//*****************************************************
			//* Plugin settings
			//*****************************************************
			add_action( 'admin_menu', function() {
				add_options_page( 'APPROVE WordPress Plugin Settings', 'APPROVE WordPress Plugin', 'manage_options', 
					self::$prefix, [$this,'render_plugin_settings_page']);
			});

			add_action( 'admin_init', function() {
				register_setting(self::$options,self::$options);
				add_settings_section( 'api_settings', 'API Settings',[$this,'settings_section_text'],self::$prefix);

				add_settings_field( 'approve_id', 'APPROVE id',[$this,'add_approve_id'],self::$prefix, 'api_settings' );
				add_settings_field( 'loader_url', 'Loader URL',[$this,'add_loader_url'],self::$prefix, 'api_settings' );
				add_settings_field( 'approve_url', 'APPROVE URL',[$this,'add_approve_url'],self::$prefix, 'api_settings' );
			});
		}

		/**
		 * Returns the settings for the plugin. The static variables on top of this class contain the default values
		 * for the settings. When the user sets them in WP, we will use those values. This function will be called outside
		 * this plugin by other plugins that require it. Specifically, the woocommerce integration plugin (as of 6.2020)
		 */
		public static function get_settings(){
			$options = get_option(self::$options);
			$approve_id = "";
			$loader_url = self::$default_loader_url;
			$approve_url = self::$default_approve_url;
			if(!empty($options)){
				if(isset($options['approve_id']) && !empty($options['approve_id'])){
					$approve_id  =$options['approve_id'];
				}
				if(isset($options['loader_url']) && !empty($options['loader_url'])){
					$loader_url  =$options['loader_url'];
				}
				if(isset($options['approve_url']) && !empty($options['approve_url'])){
					$approve_url  =$options['approve_url'];
				}
			}
			return(object)[
				"approve_id"=>$approve_id,
				"loader_url"=>$loader_url,
				"approve_url"=>$approve_url
			];
		}

		/**
		 * Utiliarian function. Will be called by WP to load javascript/css
		 */
		public function load_scripts() {
			$settings = self::get_settings();
			$data =[
				"ajax_url" => admin_url("admin-ajax.php"),
				"approve_id"=>$settings->approve_id,
				"loader_url"=>$settings->loader_url,
				"approve_url"=>$settings->approve_url
			];
			wp_enqueue_script('approve_wordpress_plugin', plugin_dir_url(__FILE__) . 
				'approve_wordpress_plugin.js', array('jquery'),self::$version);
			wp_localize_script( 'approve_wordpress_plugin', 'php_vars', $data );
		}

		/**
		 * Utiliarian function. Will be called by WP to compose the setting page/menu
		 */
		public function render_plugin_settings_page() {
			?>
			<h2>APPROVE WordPress Plugin Settings</h2>
			<form action="options.php" method="post">
					<?php 
					settings_fields(self::$options);
					do_settings_sections(self::$prefix); ?>
					<input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
			</form>
			<?php
		}

		/**
		 * Utiliarian function. Will be called by WP to compose the setting page/menu
		 */
		public function settings_section_text() {
			echo '<p>Here you can set all the options for using the APPROVE WordPress Plugin. Retreive your APPROVE information by logging into KWIPPED.</p>';
		}
		
		/**
		 * Utiliarian function. Will be called by WP to compose the setting page/menu
		 */
		public function add_approve_id() {
			$options = get_option(self::$options);
			echo "<input id='approve_plugin_setting_api_key' name='".(self::$options)."[approve_id]' type='text' value='".
				esc_attr($options['approve_id'])."' style='width:100%;'/>";
		}
		
		/**
		 * Utiliarian function. Will be called by WP to compose the setting page/menu
		 */
		public function add_loader_url() {
			$options = get_option(self::$options);
			echo "<input id='approve_plugin_setting_loader_url' name='".(self::$options)."_options[loader_url]' type='text' value='".
				esc_attr($options['loader_url'])."' style='width:100%;'/>";
		}
		
		/**
		 * Utiliarian function. Will be called by WP to compose the setting page/menu
		 */
		public function add_approve_url() {
			$options = get_option(self::$options);
			echo "<input id='approve_plugin_setting_approve_url' name='".(self::$options)."_options[approve_url]' type='text' value='".
				esc_attr($options['approve_url'])."' style='width:100%;'/>";
		}

		public function dd2($item){
			error_log(print_r($item,true));
		}
	}

	//Start your engines.
	$approve_wordpress_plugin = new ApproveWordPressPlugin();
?>
