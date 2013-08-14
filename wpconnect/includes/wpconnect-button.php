<?php
session_start();

function buddyconnect_login_form_button() {

	if ( !is_user_logged_in() ) {
	$wpcc_state = md5( mt_rand() );
	
	$_SESSION[ 'wpcc_state' ] = $wpcc_state;
	 
	$params = array(
	  'response_type' => 'code',
	  'client_id' => CLIENT_ID,
	  'state' => $wpcc_state,
	  'redirect_uri' => REDIRECT_URL
	);
	 
	$url_to = AUTHENTICATE_URL .'?'. http_build_query( $params );
	 
	echo '<p style="margin-bottom:15px;"><a href="' . $url_to . '"><img src="//s0.wp.com/i/wpcc-button.png" width="231" /></a></p>';
	}
	
}
$wpconnect_options = get_option('wpconnect_plugin_options');
if ( $wpconnect_options['wpconnect_client_id'] ) {
	add_action( 'login_form', 'buddyconnect_login_form_button' );
}