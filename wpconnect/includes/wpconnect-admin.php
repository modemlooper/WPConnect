<?php
global $wpconnect_options;
$wpconnect_options = get_option('wpconnect_plugin_options');

function wpconnect_settings_menu() {
	add_options_page('WPConnect', 'WPConnect', 'administrator', 'wpconnect', 'wpconnect_plugin_options_page' );
}
add_action('admin_menu', 'wpconnect_settings_menu');
add_action('network_admin_menu', 'wpconnect_settings_menu');


function wpconnect_plugin_options_page() {
?>

	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>WordPress Connect</h2>
		<form action="options.php" method="post">
		<?php settings_fields('wpconnect_plugin_options'); ?>
		<?php do_settings_sections(__FILE__); ?>

		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings'); ?>" />
		</p>
		</form>
	</div>

<?php
}


function wpconnect_plugin_admin_init() {

	register_setting( 'wpconnect_plugin_options', 'wpconnect_plugin_options', 'wpconnect_plugin_options_validate' );
	add_settings_section('wpconnect_section_general', 'OAuth Settings', 'wpconnect_section_general', __FILE__);

	add_settings_field('wpconnect_client_id', 'Client ID', 'wpconnect_setting_client_id', __FILE__, 'wpconnect_section_general');
	add_settings_field('wpconnect_client_secret', 'Client Secret', 'wpconnect_setting_client_secret', __FILE__, 'wpconnect_section_general');
	add_settings_field('wpconnect_redirect_url', 'Redirect URL', 'wpconnect_setting_redirect_url', __FILE__, 'wpconnect_section_general');


}
add_action('admin_init', 'wpconnect_plugin_admin_init');


function wpconnect_section_general() {
	_e('Please enter your WordPress.com application OAuth settings. You can access  your settings or create an app at <a href="https://developer.wordpress.com/apps/">WordPress.com</a>', 'wpconnect');
}


//connect settings fields
function wpconnect_setting_client_id() {
	global $wpconnect_options;

	$key = !empty( $wpconnect_options['wpconnect_client_id'] ) ? $wpconnect_options['wpconnect_client_id'] : '' ;
	
	echo "<input id='wpconnect_client_id' name='wpconnect_plugin_options[wpconnect_client_id]' size='40' type='text' value='$key' />  ";

}

function wpconnect_setting_client_secret() {
	global $wpconnect_options;

	$key = !empty( $wpconnect_options['wpconnect_client_secret'] ) ? $wpconnect_options['wpconnect_client_secret'] : '' ;

	echo "<input id='wpconnect_client_secret' name='wpconnect_plugin_options[wpconnect_client_secret]' size='40' type='text' value='$key' />  ";

}

function wpconnect_setting_redirect_url() {
	global $wpconnect_options;

	$key = !empty( $wpconnect_options['wpconnect_redirect_url'] ) ? $wpconnect_options['wpconnect_redirect_url'] : '' ;

	echo "<input id='wpconnect_redirect_url' name='wpconnect_plugin_options[wpconnect_redirect_url]' size='40' type='text' value='$key' />  ";

}


function wpconnect_plugin_options_validate($input) {

	return $input; // return validated input

}