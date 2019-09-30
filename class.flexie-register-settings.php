<?php
/**
 * Register the configuration settings for the plugin.
 *
 * Validate Flexie Subdomain and Flexie API Key
 * by making a request to the User mapped to this API Key. 
 * If no result is found, settings will not save. 
 *
 * @link       https://flexie.io/
 * @since      1.0.0
 * @package    Flexie-CRM
 * @author     Flexie CRM
 */

class Flexie_Register_Settings {

	/**
	 * Initialise plugin admin menu
	 *
	 */
	public function register_settings(){	
		  
		add_action( 'admin_menu', array($this, 'flexie_woocommerce_integration_menu'), 10, 1 );
	}

	/**
	 * Create plugin menu and register options
	 *
	 */
	public function flexie_woocommerce_integration_menu(){
		$menu_icon_svg = "data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHN0eWxlPSJoZWlnaHQ6IDIwO3dpZHRoOiAyMHB4OyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDE1NiAxNTYiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDE1NiAxNTYiIHhtbDpzcGFjZT0icHJlc2VydmUiPiAgICA8cGF0aCBmaWxsPSIjMDAwMDAwIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTcuMDkxLDBoMTQyLjE4NkMxNTMuMDc2LDAsMTU2LDMuMTIzLDE1Niw2LjkyMVY3MEg4NC43MDRINjkuNTM5ICAgIEg2OHY4Nkg3LjA5MUMzLjI5MywxNTYsMCwxNTIuOTA1LDAsMTQ5LjEwN1Y2LjkyMUMwLDMuMTIzLDMuMjkzLDAsNy4wOTEsMEw3LjA5MSwweiI+PC9wYXRoPiAgICA8cGF0aCBmaWxsPSIjMDAwMDAwIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTg1LDg3djY5aDY0LjI3N2MzLjc5OSwwLDYuNzIzLTMuMDk1LDYuNzIzLTYuODkzVjg3SDg1eiBNMTQ2LDEyMSAgICBIOTB2LTE3aDU2VjEyMXoiPjwvcGF0aD48L3N2Zz4=";

		// Create Flexie CRM menu
		add_menu_page( 'Flexie WooCommerce Tracking', 'Flexie Tracking', 'administrator', __FILE__, array($this,'flexie_settings_menu') , $menu_icon_svg);
		// Init plugin settings
		add_action( 'admin_init', array($this, 'register_flexie_wc_settings') );   

	}
	
	/**
	 * Initialise the Register Options method
	 *
	 */
	public function register_flexie_wc_settings() {
		$this->register_options();
	}
	
	/**
	 * Register menu components.
	 *
	 */
	public function flexie_settings_menu(){
		include( plugin_dir_path( __FILE__ ).'flexie-wc-menu.php' );
	}
	
	/**
	 * Register plugin configuration settings.
	 *
	 */
	public function register_options (){  
		register_setting( 'flexie-crm-settings', 'flexie_track_product' );
		register_setting( 'flexie-crm-settings', 'flexie_track_cart' );
		register_setting( 'flexie-crm-settings', 'flexie_track_order' );
		register_setting( 'flexie-crm-settings', 'flexie_track_pagehit' );
		register_setting( 'flexie-crm-settings', 'flexie_subdomain' );
		register_setting( 'flexie-crm-settings', 'flexie_api_key', array($this, 'credential_validation') );	
	}
	
	/**
	 * Validate Subdomain and API Key configured in the plugin settings.
	 *
	 */
	public function credential_validation( $apiKey ){
		$url = 'https://'. $_POST['flexie_subdomain'] .'.flexie.io/api/users/self?apikey=' . $apiKey . '';
		$response = wp_remote_get( $url );
		$flexieUser = json_decode( wp_remote_retrieve_body( $response ), true );
		
		if( $flexieUser["user"]["id"] != null ){
			return $apiKey;
		} else {
			delete_option( 'flexie_subdomain' );
			delete_option( 'flexie_track_product' );
			delete_option( 'flexie_track_cart' );
			delete_option( 'flexie_track_order' );  
			delete_option( 'flexie_track_pagehit' );      
			
			add_settings_error(
				'flexie_subdomain',
				'error-check',
				__( 'Your Subdomain or API Key are not correct! Please check your credentials!', 'FlexiCRM' ),
				'error'
			);			
			return;
		}
	}
}

