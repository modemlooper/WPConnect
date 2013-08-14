<?php
if ( isset( $_GET[ 'code' ] ) && !is_user_logged_in() ) {

	if ( false == isset( $_GET[ 'state' ] ) )
		die( 'Warning! State variable missing after authentication' );

	if ( $_GET[ 'state' ] != $_SESSION[ 'wpcc_state' ] )
		die( 'Warning! State mismatch. Authentication attempt may have been compromised.' );
	
	$curl = curl_init( REQUEST_TOKEN_URL );
	curl_setopt( $curl, CURLOPT_POST, true );
	curl_setopt( $curl, CURLOPT_POSTFIELDS, array(
		'client_id' => CLIENT_ID,
		'redirect_uri' => REDIRECT_URL,
		'client_secret' => CLIENT_SECRET,
		'code' => $_GET[ 'code' ], // The code from the previous request
		'grant_type' => 'authorization_code'
	) );
	
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
	$auth = curl_exec( $curl );
	$secret = json_decode( $auth );

	$access_token = $secret->access_token;
	 
	$curl = curl_init( "https://public-api.wordpress.com/rest/v1/me/" );
	curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Authorization: Bearer ' . $access_token ) );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
	$me = json_decode( curl_exec( $curl ) );
	
	if ( $me->verified != true )
	die( 'You have not verified your WordPress.com account.' );
	
	$user_data = get_user_by( 'login', $me->username );
	
	if ( $user_data ) {
		$user_id = $user_data->ID;
		$user_email = $user_data->user_email;
		$user_name = $me->username;
		
		wpconnect_log_in_user( $user_id, $user_name );
		
	} else {
	
		wpconnect_create_user( $me->username, $user_name );
		
	}

}


function wpconnect_log_in_user( $user_id, $user_login ) {

	wp_set_auth_cookie( $user_id, true );
	wp_set_current_user( $user_id, $user_login );
	do_action( 'wp_login', $user_login, get_userdata( $user_id ) );
	
}


function wpconnect_create_user( $user_name, $user_email ) {

	if ( email_exists( $user_email ) == false ) {
	
		$random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
		$user_id = wp_create_user( $user_name, $random_password, $user_email );
		
		wpconnect_log_in_user( $user_id, $user_name );
		
	} else {
	
		$random_password = __('User already exists.  Password inherited.');
	}
	
}
