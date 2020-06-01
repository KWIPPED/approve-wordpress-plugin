<?php
	use com\kwipped\approve\wordpress\devtools\Approve;
	/*
	Plugin Name: APPROVE Devtools Plugin
	Plugin URI: http://kwipped.com
	description:May be used by APPROVE clients to create the necessary link to connect into the Approve cart from wordpress.
	Version: 1.0.0
	Author: Wellington Souza
	Author URI: http://kwipped.com
	License: GPL2
	*/
	$current_version = "1.0.0";

	/**
	 * Provides update functionality
	 */
	require 'plugin-update-checker-4.9/plugin-update-checker.php';
	$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
		'https://github.com/KWIPPED/approve-wordpress-devtools-plugin/',
		__FILE__,
		'approve-wordpress-devtools-plugin'
	);


	//Include needed script and pass it some variables.
	function load_approve_devtools_scripts() {
		$data =[
			"ajax_url" => admin_url("admin-ajax.php")
		];
		wp_enqueue_script('approve_global', plugin_dir_url(__FILE__) . 'global.js', array('jquery'),$current_version);
		wp_localize_script( 'approve_global', 'php_vars', $data );
	}

	//Get scripts loaded at the right time.
	add_action( 'wp_enqueue_scripts', 'load_approve_devtools_scripts' );

	//*****************************************************
	//* Plugin settings : APPROVE Woocommerce Plugin (awcp)
	//*****************************************************
	add_action( 'admin_menu', function() {
		add_options_page( 'APPROVE Devtools Plugin Page', 'APPROVE Devtools Plugin', 'manage_options', 'approve-devtools-plugin', 'awdp_render_plugin_settings_page' );
	});

	function awdp_render_plugin_settings_page() {
		?>
		<h2>APPROVE Devtools Plugin Settings</h2>
		<form action="options.php" method="post">
			 <?php 
			 	settings_fields( 'awdp_options' );
				do_settings_sections( 'approve-devtools-plugin' ); ?>
			 <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
		</form>
		<?php
  }

  add_action( 'admin_init', function() {
		register_setting( 'awdp_options', 'awdp_options');
		add_settings_section( 'api_settings', 'API Settings', 'awdp_section_text', 'approve-devtools-plugin' );
		add_settings_field( 'approve_devtools_id', 'APPROVE id', 'approve_id', 'approve-devtools-plugin', 'api_settings' );
	});
	
	function awdp_section_text() {
		echo '<p>Here you can set all the options for using the APPROVE Devtools Plugin. Retreive your APPROVE information by logging into KWIPPED.</p>';
  }

  function approve_devtools_id() {
		$options = get_option( 'awcp_options');
		echo "<input id='approve_plugin_setting_api_key' name='awdp_options[approve_id]' type='text' value='".esc_attr( $options['approve_id'])."' style='width:100%;'/>";
	}

	//*****************************
	//* Plugin settings END
	//*****************************

	//Will retrieve woocart and return approve rates based on that
	add_action("wp_ajax_get_approve_information", 'Approve.get_approve_information' );
	add_action("wp_ajax_nopriv_get_approve_information", "Approve.get_approve_information");

	//Will use information passed in data dn return approve rates base on that
	add_action("wp_ajax_get_approve_teaser", 'Approve.get_approve_teaser' );
	add_action("wp_ajax_nopriv_get_approve_teaser", "Approve.get_approve_teaser");

	//Will use information passed in data to return a teaser.
	add_action("wp_ajax_get_approve_teaser_custom", 'Approve.get_approve_teaser_custom' );
	add_action("wp_ajax_nopriv_get_approve_teaser_custom", "Approve.get_approve_teaser_custom");

	//Will use information passed in data to return a teaser.
	add_action("wp_ajax_get_static_button_action", 'Approve.get_static_button_action' );
	add_action("wp_ajax_nopriv_get_static_button_action", "Approve.get_static_button_action");

	// function get_approve_information() {
	// 	$approve = new Approve();

	// 	//*****************************************************
	// 	//** YOUR CODE GOES IN HERE
	// 	//* For each item in your cart call the following function:
	// 	//* $approve->add(model,price,quantity,type)
	// 	//* If you need information about the specific meaning of each of these fields please visit 
	// 	//* https://kwipped.com/someplacewhereinformationlives
	// 	//*****************************************************
	// 	global $woocommerce;
	// 	$items = $woocommerce->cart->get_cart();
	// 	foreach($items as $item => $values) { 
	// 		//print_r($values); die();
	// 		$approve->add($values['data']->get_name(),get_post_meta($values['product_id'] , '_price', true),$values['quantity'],"new_product");
	// 	}
	// 	$shipping = $woocommerce->cart->get_shipping_total();
	// 	if(!empty($shipping) && $shipping>0) $approve->add("Shipping",$shipping,1,"shipping");
	// 	//***************************
	// 	//* End of your code
	// 	//***************************

	// 	wp_send_json($approve->get_approve_information());
	// 	wp_die(); // this is required to terminate immediately and return a proper response
	// }

	// function get_approve_teaser() {
	// 	$approve = new Approve();
	// 	$approve->add($_POST['data']['model'],$_POST['data']['price'],1,"new_product");
	// 	//***************************
	// 	//* End of your code
	// 	//***************************

	// 	wp_send_json($approve->get_approve_information());
	// 	wp_die(); // this is required to terminate immediately and return a proper response
	// }

	// /**
	//  * Returns a teaser rate for a specific value fed into this function.
	//  */
	// function get_approve_teaser_custom() {
	// 	$approve = new Approve();
	// 	$value = $_POST['data']['value'];
	// 	wp_send_json($approve->get_teaser($value));
	// 	wp_die(); // this is required to terminate immediately and return a proper response
	// }

	// function get_static_button_action() {
	// 	$approve = new Approve();
	// 	$approve->add($_POST['data']['model'],$_POST['data']['price'],$_POST['data']['qty'],$_POST['data']['item_type']);
	// 	wp_send_json($approve->get_approve_information());
	// 	wp_die(); // this is required to terminate immediately and return a proper response
	// }

//Dump to log.
// function dd2($item){
// 	error_log(print_r($item,true));
// }
?>
