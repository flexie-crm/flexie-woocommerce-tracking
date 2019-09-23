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
	public function Register_Settings(){
		add_action( 'admin_menu', array($this, 'flexie_woocommerce_integration_menu'), 10, 1 );
	}
	
	/**
	 * Create plugin menu and register options
	 *
	 */
	public function flexie_woocommerce_integration_menu(){
		// Create Flexie CRM menu
		add_menu_page( 'Flexie WooCommerce Tracking', 'Flexie Tracking', 'administrator', __FILE__, array($this,'flexie_settings_menu') , plugins_url('/images/icon.png', __FILE__) );
		// Init plugin settings
		add_action( 'admin_init', array($this, 'register_flexie_wc_settings') );   

	}
	
	/**
	 * Initialise the Register Options method
	 *
	 */
	public function register_flexie_wc_settings() {
		$this->Register_Options();
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
	public function Register_Options (){  
		register_setting( 'flexie-crm-settings', 'flexie_track_products' );
		register_setting( 'flexie-crm-settings', 'flexie_track_cart' );
		register_setting( 'flexie-crm-settings', 'flexie_track_order' );
		register_setting( 'flexie-crm-settings', 'flexie_subdomain' );
		register_setting( 'flexie-crm-settings', 'flexie_api_key', array($this, 'Credential_Validation') );	
	}
	
	/**
	 * Validate Subdomain and API Key configured in the plugin settings.
	 *
	 */
	public function Credential_Validation( $apiKey ){
		$url = 'https://'. $_POST['flexie_subdomain'] .'.flexie.io/api/users/self?apikey=' . $apiKey . '';
		$response = wp_remote_get( $url );
		$flexieUser = json_decode( wp_remote_retrieve_body( $response ), true );
		
		if( $flexieUser["user"]["id"] != null ){
			return $apiKey;
		} else {
			delete_option('flexie_subdomain');
			delete_option('flexie_track_products');
			delete_option('flexie_track_cart');
			delete_option('flexie_track_order');        
			
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

