<?php
namespace com\kwipped\approve\wordpress\devtools;
//****************************************************************************************
//* You should not modify the code below. It assures the correct format needed by Approve.
//*****************************************************************************************


function dd2($item){
	error_log(print_r($item,true));
}

//***********************************************************************
//* The following functions will load all needed WORDPRESS settings, etc.
//***********************************************************************
function load_scripts() {
	global $current_version;
	$data =[
		"ajax_url" => admin_url("admin-ajax.php")
	];
	wp_enqueue_script('approve_global', plugin_dir_url(__FILE__) . 'global.js', array('jquery'),CURRENT_APPROVE_DEVTOOLS_VERSION);
	wp_localize_script( 'approve_global', 'php_vars', $data );
}

function render_plugin_settings_page() {
	?>
	<h2>APPROVE Devtools Plugin Settings</h2>
	<form action="options.php" method="post">
			<?php 
			settings_fields( PLUGIN_PREFIX.'_options' );
			do_settings_sections( PLUGIN_PREFIX ); ?>
			<input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
	</form>
	<?php
}

function settings_section_text() {
	echo '<p>Here you can set all the options for using the APPROVE Devtools Plugin. Retreive your APPROVE information by logging into KWIPPED.</p>';
}

function add_approve_id() {
	$options = get_option( PLUGIN_PREFIX.'_options');
	echo "<input id='approve_plugin_setting_api_key' name='".PLUGIN_PREFIX."_options[approve_id]' type='text' value='".
		esc_attr($options['approve_id'])."' style='width:100%;'/>";
}

?>	