<?php
namespace com\kwipped\approve\wordpress\plugin;

function dd2($item){
	error_log(print_r($item,true));
}

//***********************************************************************
//* The following functions will load all needed WORDPRESS settings, etc.
//***********************************************************************
function load_scripts() {
	$options = get_option(APPROVE_WORDPRESS_PLUGIN_PREFIX.'_options');
	$approve_id = "";
	$loader_url = APPROVE_WORDPRESS_PLUGIN_DEFAULT_LOADER_URL;
	$approve_url = APPROVE_WORDPRESS_PLUGIN_DEFAULT_APPROVE_URL;
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

	$data =[
		"ajax_url" => admin_url("admin-ajax.php"),
		"approve_id"=>$approve_id,
		"loader_url"=>$loader_url,
		"approve_url"=>$approve_url
	];
	wp_enqueue_script('approve_wordpress_plugin', plugin_dir_url(__FILE__) . 'approve_wordpress_plugin.js', array('jquery'),CURRENT_APPROVE_PLUGIN_VERSION);
	wp_localize_script( 'approve_wordpress_plugin', 'php_vars', $data );
}

function render_plugin_settings_page() {
	?>
	<h2>APPROVE WordPress Plugin Settings</h2>
	<form action="options.php" method="post">
			<?php 
			settings_fields(APPROVE_WORDPRESS_PLUGIN_PREFIX.'_options' );
			do_settings_sections(APPROVE_WORDPRESS_PLUGIN_PREFIX ); ?>
			<input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
	</form>
	<?php
}

function settings_section_text() {
	echo '<p>Here you can set all the options for using the APPROVE WordPress Plugin. Retreive your APPROVE information by logging into KWIPPED.</p>';
}

function add_approve_id() {
	$options = get_option(APPROVE_WORDPRESS_PLUGIN_PREFIX.'_options');
	echo "<input id='approve_plugin_setting_api_key' name='".APPROVE_WORDPRESS_PLUGIN_PREFIX."_options[approve_id]' type='text' value='".
		esc_attr($options['approve_id'])."' style='width:100%;'/>";
}

function add_loader_url() {
	$options = get_option(APPROVE_WORDPRESS_PLUGIN_PREFIX.'_options');
	echo "<input id='approve_plugin_setting_loader_url' name='".APPROVE_WORDPRESS_PLUGIN_PREFIX."_options[loader_url]' type='text' value='".
		esc_attr($options['loader_url'])."' style='width:100%;'/>";
}

function add_approve_url() {
	$options = get_option(APPROVE_WORDPRESS_PLUGIN_PREFIX.'_options');
	echo "<input id='approve_plugin_setting_approve_url' name='".APPROVE_WORDPRESS_PLUGIN_PREFIX."_options[approve_url]' type='text' value='".
		esc_attr($options['approve_url'])."' style='width:100%;'/>";
}


?>