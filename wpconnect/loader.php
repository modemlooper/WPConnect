<?php

/**
Plugin Name: WPConnect
Plugin URI:  http://taptappress.com
Description: Allows users to log into your site with a WordPress.com user acount.
Author:      modemlooper
Author URI:  http://taptappress.com
Version:     0.1
Text Domain: wpconnect
Domain Path: /languages/
License:     GPLv2 or later (license.txt)
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) )
    exit;
    
	session_start();

if ( !class_exists( 'WPConnect' ) ) {

    class WPConnect {


    	public function __construct() {

	    	$this->constants();
	    	$this->setup_globals();
	    	$this->includes();
    	}


        /**
         * constants function.
         *
         * @access private
         * @return void
         */
        private function constants() {
        
	        global $wpconnect_options;
	
			$wpconnect_options = get_option('wpconnect_plugin_options');
			
			$client_id = !empty( $wpconnect_options['wpconnect_client_id'] ) ? $wpconnect_options['wpconnect_client_id'] : '' ;
			$client_secret = !empty( $wpconnect_options['wpconnect_client_secret'] ) ? $wpconnect_options['wpconnect_client_secret'] : '' ;
			$redirect_url = !empty( $wpconnect_options['wpconnect_redirect_url'] ) ? $wpconnect_options['wpconnect_redirect_url'] : '' ;

			define ( 'CLIENT_ID', $client_id );
			define ( 'CLIENT_SECRET', $client_secret );
			define ( 'REDIRECT_URL', $redirect_url );
			define ( 'REQUEST_TOKEN_URL', 'https://public-api.wordpress.com/oauth2/token' );
			define ( 'AUTHENTICATE_URL', 'https://public-api.wordpress.com/oauth2/authenticate' );

            // Path and URL
            if ( !defined( 'WPCONNECT_PLUGIN_DIR' ) )
                define( 'WPCONNECT_PLUGIN_DIR', trailingslashit( WP_PLUGIN_DIR . '/wpconnect' ) );

            if ( !defined( 'WPCONNECT_PLUGIN_URL' ) ) {
                $plugin_url = plugin_dir_url( __FILE__ );

                // If we're using https, update the protocol. Workaround for WP13941, WP15928, WP19037.
                if ( is_ssl() )
                    $plugin_url = str_replace( 'http://', 'https://', $plugin_url );

                define( 'WPCONNECT_PLUGIN_URL', $plugin_url );
            }

        }


        /**
         * setup_globals function.
         *
         * @access private
         * @return void
         */
        private function setup_globals() {

            /** Paths *************************************************************/

            // TapTapPress root directory
            $this->file       = __FILE__;
            $this->basename   = plugin_basename( $this->file );
            $this->plugin_dir = WPCONNECT_PLUGIN_DIR;
            $this->plugin_url = WPCONNECT_PLUGIN_URL;


        }


        /**
         * includes function.
         *
         * @access private
         * @return void
         */
        private function includes() {

			if (file_exists(ABSPATH . 'wp-includes/pluggable.php')) {
				require_once(ABSPATH . 'wp-includes/pluggable.php');
			}

			require( $this->plugin_dir . 'includes/wpconnect-core.php' );
			require( $this->plugin_dir . 'includes/wpconnect-admin.php' );
			require( $this->plugin_dir . 'includes/wpconnect-button.php' );
       
        }


    }

	$ttp = new WPConnect();

}
